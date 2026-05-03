<?php

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Models\NoMatriksEntry;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\CounsellorController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Models\BookingRequest;
use App\Models\ChatMessage;
use App\Models\InboxNotification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $user = request()->user();

    if ($user) {
        $role = $user->roles()->value('name');

        if (in_array($role, ['student', 'teacher'], true)) {
            return redirect()->route('home.session');
        }
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($role === 'counsellor') {
            return redirect()->route('counsellor.dashboard');
        }
    }

    $today = now();
    $weekdayIso = (int) $today->dayOfWeekIso; // 1 (Mon) ... 7 (Sun)

    $hourlySlotCount = match (true) {
        $weekdayIso >= 1 && $weekdayIso <= 4 => 9, // 8:00-17:00
        $weekdayIso === 5 => 4, // 8:00-12:00
        default => 0, // weekend closed
    };

    $counsellorCount = User::query()
        ->whereHas('roles', static fn($query) => $query->where('name', 'counsellor'))
        ->count();

    $totalTodaySlots = $hourlySlotCount * $counsellorCount;

    $bookedTodaySlots = BookingRequest::query()
        ->whereDate('booking_date', $today->toDateString())
        ->whereIn('status', ['pending', 'approved'])
        ->count();

    $openTodaySlots = max($totalTodaySlots - $bookedTodaySlots, 0);
    $todayCalendarStatus = $totalTodaySlots === 0
        ? 'Closed'
        : ($openTodaySlots > 0 ? 'Open' : 'Full');

    $supportStatus = $counsellorCount === 0
        ? 'Offline'
        : ($openTodaySlots > 0 ? 'Live' : 'Busy');
    $counsellorNames = User::query()
        ->whereHas('roles', static fn($query) => $query->where('name', 'counsellor'))
        ->orderBy('full_name')
        ->orderBy('name')
        ->get()
        ->map(static fn(User $counsellor): string => trim((string) ($counsellor->full_name ?: $counsellor->name)))
        ->filter()
        ->unique()
        ->values()
        ->all();

    $occupiedCounsellors = BookingRequest::query()
        ->whereDate('booking_date', $today->toDateString())
        ->whereIn('status', ['pending', 'approved'])
        ->pluck('counsellor_name')
        ->map(static fn(?string $name): string => trim((string) $name))
        ->filter()
        ->unique()
        ->values()
        ->all();

    $occupiedCounsellorLookup = array_flip($occupiedCounsellors);
    $landingCounsellors = collect($counsellorNames)
        ->map(static fn(string $name): array => [
            'name' => $name,
            'status' => array_key_exists($name, $occupiedCounsellorLookup) ? 'Busy' : 'Available',
        ])
        ->values()
        ->all();
    return view('index', [
        'liveCalendarStatus' => $todayCalendarStatus,
        'liveOpenSlots' => $openTodaySlots,
        'liveSupportStatus' => $supportStatus,
        'landingCounsellors' => $landingCounsellors,
    ]);
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');


    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register'])->name('signup.store');
    Route::get('/signup/no-matriks', [AuthController::class, 'lookupNoMatriks'])->name('signup.no-matriks.lookup');

    Route::get('/signup/otp', [AuthController::class, 'showOtpForm'])->name('signup.otp.form');
    Route::post('/signup/otp/verify', [AuthController::class, 'verifySignupOtp'])->name('signup.otp.verify');
    Route::post('/signup/otp/resend', [AuthController::class, 'resendSignupOtp'])->name('signup.otp.resend');

    Route::get('/counsellor/login-otp', [AuthController::class, 'showCounsellorLoginOtpForm'])->name('counsellor.login.otp.form');
    Route::post('/counsellor/login-otp/verify', [AuthController::class, 'verifyCounsellorLoginOtp'])->name('counsellor.login.otp.verify');
    Route::post('/counsellor/login-otp/resend', [AuthController::class, 'resendCounsellorLoginOtp'])->name('counsellor.login.otp.resend');
});

$autoRejectExpiredBookings = static function (): void {
    $now = now();

    $pendingBookings = BookingRequest::query()
        ->with('user:id,name,full_name,email')
        ->where('status', 'pending')
        ->whereDate('booking_date', '<=', $now->toDateString())
        ->get();

    $pendingBookings
        ->filter(static function (BookingRequest $booking) use ($now): bool {
            $bookingDate = (string) $booking->booking_date;
            $rawTimeRange = trim((string) $booking->booking_time);
            $timeRangeParts = preg_split('/\s*-\s*/', $rawTimeRange);
            $endTime = trim((string) ($timeRangeParts[1] ?? ''));
            $startTime = trim((string) ($timeRangeParts[0] ?? ''));
            $comparisonTime = $endTime !== '' ? $endTime : $startTime;

            if ($comparisonTime === '') {
                return false;
            }

            try {
                $bookingCutOff = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    "{$bookingDate} {$comparisonTime}",
                    config('app.timezone')
                );
            } catch (\Throwable) {
                return false;
            }

            return $bookingCutOff->lessThanOrEqualTo($now);
        })
        ->each(static function (BookingRequest $booking): void {
            $booking->update([
                'status' => 'rejected',
            ]);

            $recipient = $booking->user;
            if (! $recipient) {
                return;
            }

            $notifyMessage = 'Your booking on ' . $booking->booking_date . ' (' . $booking->booking_time . ') with '
                . $booking->counsellor_name . ' was auto-rejected because the session time has passed without approval.';

            $recipient->inboxNotifications()->create([
                'title' => 'Booking auto-rejected',
                'message' => $notifyMessage,
            ]);

            if (! empty($recipient->email)) {
                Mail::raw($notifyMessage, static function ($message) use ($recipient): void {
                    $message->to($recipient->email)
                        ->subject('CollegeCare Booking Auto-Rejected');
                });
            }
        });
};

Route::matched(static function () use ($autoRejectExpiredBookings): void {
    if (auth()->check()) {
        $autoRejectExpiredBookings();
    }
});
$autoRejectExpiredBookings();

Route::middleware('auth')->group(function () {
    Route::get('/admin', function () {
        $role = request()->user()?->roles()->value('name');

        abort_unless($role === 'admin', 403);

        return view('admin.overview');
    })->name('admin.overview');

    Route::get('/home-session', function () {
        $user = request()->user();
        $role = $user?->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);

        $announcements = [
            'Counselling slots for this week are now open. Book early to secure your preferred time.',
            'Need to change time? Use Booking History to reschedule your active appointment.',
            'Check your inbox regularly for OTP and reminder notifications before your session.',
        ];

        $counsellorNames = User::query()
            ->whereHas('roles', static fn($query) => $query->where('name', 'counsellor'))
            ->orderBy('full_name')
            ->orderBy('name')
            ->get()
            ->map(static fn(User $counsellor) => $counsellor->full_name ?: $counsellor->name)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $bookingSlots = BookingRequest::query()
            ->whereIn('status', ['pending', 'approved'])
            ->get(['booking_date', 'booking_time', 'counsellor_name', 'status'])
            ->map(static fn(BookingRequest $booking): array => [
                'date' => (string) $booking->booking_date,
                'time' => $booking->booking_time,
                'counsellor' => $booking->counsellor_name,
                'status' => $booking->status,
            ])
            ->all();

        $userActiveBookings = BookingRequest::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->get(['id', 'booking_date', 'booking_time', 'counsellor_name', 'status'])
            ->map(static fn(BookingRequest $booking): array => [
                'id' => $booking->id,
                'date' => (string) $booking->booking_date,
                'time' => $booking->booking_time,
                'counsellor' => $booking->counsellor_name,
                'status' => $booking->status,
            ])
            ->all();

        $now = now();
        $isWeekend = $now->isWeekend();
        $currentMinutes = ((int) $now->format('H')) * 60 + ((int) $now->format('i'));
        $buildDailySlots = static function (Carbon $date): array {
            $weekdayIso = (int) $date->dayOfWeekIso;

            $startHour = 9;
            $endHour = match (true) {
                $weekdayIso >= 1 && $weekdayIso <= 4 => 17,
                $weekdayIso === 5 => 12,
                default => 9,
            };

            if ($weekdayIso >= 6) {
                return [];
            }

            $slots = [];
            for ($hour = $startHour; $hour < $endHour; $hour++) {
                $slots[] = sprintf('%02d:00 - %02d:00', $hour, $hour + 1);
            }

            return $slots;
        };

        $occupiedSlots = BookingRequest::query()
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('booking_date', '>=', $now->toDateString())
            ->get(['booking_date', 'booking_time', 'counsellor_name'])
            ->map(static fn(BookingRequest $booking): array => [
                'date' => (string) $booking->booking_date,
                'time' => trim((string) $booking->booking_time),
                'counsellor' => trim((string) $booking->counsellor_name),
            ])
            ->all();

        $occupiedSlotLookup = [];
        foreach ($occupiedSlots as $slot) {
            if ($slot['date'] === '' || $slot['time'] === '' || $slot['counsellor'] === '') {
                continue;
            }

            $occupiedSlotLookup[$slot['counsellor']][$slot['date']][$slot['time']] = true;
        }

        $findNextAvailableSlot = static function (string $counsellorName) use ($now, $buildDailySlots, $occupiedSlotLookup): string {
            $today = $now->copy()->startOfDay();

            for ($dayOffset = 0; $dayOffset <= 60; $dayOffset++) {
                $date = $today->copy()->addDays($dayOffset);
                $slots = $buildDailySlots($date);

                foreach ($slots as $slot) {
                    [$startTime] = array_map('trim', explode('-', $slot));
                    $slotStart = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $startTime);

                    if ($slotStart->lessThanOrEqualTo($now)) {
                        continue;
                    }

                    if (isset($occupiedSlotLookup[$counsellorName][$date->toDateString()][$slot])) {
                        continue;
                    }

                    return $slotStart->isSameDay($now)
                        ? $slotStart->format('g:i A')
                        : $slotStart->format('D, M j g:i A');
                }
            }

            return 'No upcoming slot';
        };

        $occupiedNow = BookingRequest::query()
            ->whereDate('booking_date', $now->toDateString())
            ->whereIn('status', ['pending', 'approved'])
            ->get(['booking_time', 'counsellor_name'])
            ->filter(static function (BookingRequest $booking) use ($currentMinutes): bool {
                if (!preg_match('/^(\d{2}:\d{2})\s*-\s*(\d{2}:\d{2})$/', (string) $booking->booking_time, $matches)) {
                    return false;
                }

                [$startHour, $startMinute] = array_map('intval', explode(':', $matches[1]));
                [$endHour, $endMinute] = array_map('intval', explode(':', $matches[2]));

                $startMinutes = ($startHour * 60) + $startMinute;
                $endMinutes = ($endHour * 60) + $endMinute;

                return $currentMinutes >= $startMinutes && $currentMinutes < $endMinutes;
            })
            ->pluck('counsellor_name')
            ->map(static fn(?string $name): string => trim((string) $name))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $occupiedLookup = array_flip($occupiedNow);
        $counsellors = collect($counsellorNames)
            ->map(static function (string $name) use ($occupiedLookup, $isWeekend, $findNextAvailableSlot): array {
                if ($isWeekend) {
                    return [
                        'name' => $name,
                        'available' => false,
                        'status_label' => 'Unavailable',
                        'next_slot' => $findNextAvailableSlot($name),
                    ];
                }

                $available = !array_key_exists($name, $occupiedLookup);

                return [
                    'name' => $name,
                    'available' => $available,
                    'status_label' => $available ? 'Available' : 'In Session',
                    'next_slot' => $findNextAvailableSlot($name),
                ];
            })
            ->values()
            ->all();

        return view('home', [
            'user' => $user,
            'role' => $role,
            'announcements' => $announcements,
            'counsellors' => $counsellors,
            'counsellorNames' => $counsellorNames,
            'bookingSlots' => $bookingSlots,
            'currentTimeLabel' => $now->format('g:i A'),
            'userActiveBookings' => $userActiveBookings,
        ]);
    })->name('home.session');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');

    Route::get('/admin', function () {
        $user = request()->user();
        $role = $user?->roles()->value('name');

        abort_unless($role === 'admin', 403);

        $userCountsByRole = User::query()
            ->leftJoin('user_role', 'users.id', '=', 'user_role.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'user_role.role_id')
            ->selectRaw("COALESCE(roles.name, 'unassigned') as role_name")
            ->selectRaw('COUNT(DISTINCT users.id) as total')
            ->groupBy('role_name')
            ->orderByDesc('total')
            ->pluck('total', 'role_name');

        $stats = [
            'total_users' => User::count(),
            'total_roles' => Role::count(),
            'total_messages' => ChatMessage::count(),
            'total_notifications' => InboxNotification::count(),
            'total_bookings' => BookingRequest::count(),
            'pending_bookings' => BookingRequest::where('status', 'pending')->count(),
        ];

        return view('admin', [
            'user' => $user,
            'stats' => $stats,
            'userCountsByRole' => $userCountsByRole,
            'recentUsers' => User::latest()->take(8)->get(),
            'recentNotifications' => InboxNotification::query()
                ->with('user:id,name,email')
                ->latest()
                ->take(6)
                ->get(),
        ]);
    })->name('admin.dashboard');

    Route::get('/admin/manage-accounts', function () {
        $user = request()->user();
        $role = $user?->roles()->value('name');

        abort_unless($role === 'admin', 403);

        $managedUsers = User::query()
            ->leftJoin('user_role', 'users.id', '=', 'user_role.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'user_role.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.phone', 'users.created_at')
            ->selectRaw("COALESCE(roles.name, 'unassigned') as role_name")
            ->orderByDesc('users.created_at')
            ->take(20)
            ->get();

        return view('admin.manage-accounts', [
            'user' => $user,
            'managedUsers' => $managedUsers,
        ]);
    })->name('admin.accounts.manage');


    Route::get('/admin/student-statistics', function () {
        $user = request()->user();
        $role = $user?->roles()->value('name');

        abort_unless($role === 'admin', 403);

        $statusCounts = BookingRequest::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusTotals = [
            'all' => (int) $statusCounts->sum(),
            'pending' => (int) ($statusCounts['pending'] ?? 0),
            'approved' => (int) ($statusCounts['approved'] ?? 0),
            'rejected' => (int) ($statusCounts['rejected'] ?? 0),
            'completed' => (int) ($statusCounts['completed'] ?? 0),
        ];

        $topicStats = BookingRequest::query()
            ->selectRaw("COALESCE(NULLIF(TRIM(REPLACE(topic, '[EMERGENCY] ', '')), ''), 'General support') as topic_category")
            ->selectRaw('COUNT(*) as total_bookings')
            ->selectRaw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_total")
            ->selectRaw("SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_total")
            ->selectRaw("SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_total")
            ->selectRaw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_total")
            ->groupBy('topic_category')
            ->orderByDesc('total_bookings')
            ->get()
            ->map(static function ($row): array {
                return [
                    'topic' => (string) $row->topic_category,
                    'total' => (int) $row->total_bookings,
                    'pending' => (int) $row->pending_total,
                    'approved' => (int) $row->approved_total,
                    'rejected' => (int) $row->rejected_total,
                    'completed' => (int) $row->completed_total,
                ];
            });

        $studentStats = BookingRequest::query()
            ->join('users', 'users.id', '=', 'booking_requests.user_id')
            ->select('users.id', 'users.name', 'users.full_name', 'users.email')
            ->selectRaw('COUNT(booking_requests.id) as total_bookings')
            ->selectRaw("SUM(CASE WHEN booking_requests.status = 'pending' THEN 1 ELSE 0 END) as active_pending")
            ->selectRaw("SUM(CASE WHEN booking_requests.status = 'approved' THEN 1 ELSE 0 END) as active_approved")
            ->groupBy('users.id', 'users.name', 'users.full_name', 'users.email')
            ->orderByDesc('total_bookings')
            ->limit(10)
            ->get()
            ->map(static function ($row): array {
                return [
                    'student' => (string) ($row->full_name ?: $row->name),
                    'email' => (string) $row->email,
                    'total' => (int) $row->total_bookings,
                    'active_pending' => (int) $row->active_pending,
                    'active_approved' => (int) $row->active_approved,
                ];
            });

        return view('admin.student-statistic-page', [
            'user' => $user,
            'statusTotals' => $statusTotals,
            'topicStats' => $topicStats,
            'studentStats' => $studentStats,
        ]);
    })->name('admin.student-statistics');


    Route::get('/admin/no-matriks-users', function (Request $request) {
        $user = request()->user();
        $role = $user?->roles()->value('name');


        abort_unless($role === 'admin', 403);

        $searchTerm = trim((string) $request->query('search', ''));
        $statusFilter = (string) $request->query('status', 'all');
        if (!in_array($statusFilter, ['all', 'used', 'unused'], true)) {
            $statusFilter = 'all';
        }

        $usedNoMatriksUsers = User::query()
            ->whereNotNull('no_matriks')
            ->where('no_matriks', '!=', '')
            ->select('id', 'name', 'email', 'phone', 'no_matriks', 'created_at')
            ->get()
            ->keyBy('no_matriks');



        $entriesQuery = NoMatriksEntry::query()
            ->latest();

        if ($searchTerm !== '') {
            $entriesQuery->where(function ($query) use ($searchTerm) {
                $query->where('no_matriks', 'like', '%' . $searchTerm . '%')
                    ->orWhere('label_name', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($statusFilter === 'used') {
            $entriesQuery->whereIn('no_matriks', $usedNoMatriksUsers->keys()->all());
        } elseif ($statusFilter === 'unused') {
            $entriesQuery->whereNotIn('no_matriks', $usedNoMatriksUsers->keys()->all());
        }

        $entriesForView = $entriesQuery
            ->get()
            ->map(static function (NoMatriksEntry $entry) use ($usedNoMatriksUsers): NoMatriksEntry {
                $matchedUser = $usedNoMatriksUsers->get($entry->no_matriks);
                $entry->is_used = $matchedUser !== null;
                $entry->used_by_user = $matchedUser;
                return $entry;
            });

        return view('admin.no-matriks-users', [
            'user' => $user,
            'matriksEntries' => $entriesForView,
            'filters' => [
                'search' => $searchTerm,
                'status' => $statusFilter,
            ],
            'totalEntriesCount' => NoMatriksEntry::query()->count(),
        ]);
    })->name('admin.no-matriks-users');
    Route::get('/admin/users/no-matriks', static function () {
        return redirect()->route('admin.no-matriks-users');
    })->name('admin.users.no-matriks');



    Route::post('/admin/no-matriks-users/{managedUser?}', function (Request $request) {
        $authUser = $request->user();
        $authRole = $authUser?->roles()->value('name');

        abort_unless($authRole === 'admin', 403);

        $validated = $request->validate([
            'no_matriks' => ['nullable', 'string'],
            'no_matriks_file' => ['nullable', 'file', 'max:5120', 'mimes:txt,csv'],
        ]);

        $rawInput = trim((string) ($validated['no_matriks'] ?? ''));
        $uploadedFile = $request->file('no_matriks_file');

        if ($uploadedFile) {
            $fileText = @file_get_contents($uploadedFile->getRealPath());
            $rawInput = trim($rawInput . "\n" . (string) $fileText);
        }

        $isLikelyMatriks = static function (string $value): bool {
            $trimmed = trim($value);
            return preg_match('/^[A-Z0-9]{5,50}$/i', $trimmed) === 1
                && preg_match('/[A-Z]/i', $trimmed) === 1
                && preg_match('/\d/', $trimmed) === 1;
        };


        $parsedEntries = collect(preg_split('/\R+/', $rawInput))
            ->flatMap(static function (?string $rawLine) use ($isLikelyMatriks): array {
                $line = trim((string) $rawLine);
                if ($line === '') {
                    return [];
                }

                if (str_contains($line, '|')) {
                    [$first, $second] = array_pad(explode('|', $line, 2), 2, '');
                    $first = trim($first);
                    $second = trim($second);

                    if ($isLikelyMatriks($second) && ! $isLikelyMatriks($first)) {
                        $matriks = $second;
                        $name = $first;
                    } else {
                        $matriks = $first;
                        $name = $second;
                    }
                    return [[
                        'no_matriks' => trim($matriks),
                        'label_name' => trim($name),
                    ]];
                }

                if (str_contains($line, ',')) {
                    $parts = array_values(array_filter(array_map('trim', explode(',', $line)), static fn(string $value): bool => $value !== ''));
                    $allLookLikeMatriks = ! empty($parts)
                        && collect($parts)->every(static fn(string $value): bool => $isLikelyMatriks($value));

                    if ($allLookLikeMatriks) {
                        return array_map(static fn(string $value): array => [
                            'no_matriks' => $value,
                            'label_name' => '',
                        ], $parts);
                    }

                    $matriksIndex = collect($parts)->search(static fn(string $value): bool => $isLikelyMatriks($value));
                    $matriks = '';
                    $name = '';

                    if ($matriksIndex !== false) {
                        $matriks = $parts[$matriksIndex];
                        $name = $matriksIndex > 0
                            ? $parts[$matriksIndex - 1]
                            : trim(implode(', ', array_slice($parts, 1)));
                    } else {
                        $matriks = $parts[0] ?? '';
                        $name = trim(implode(', ', array_slice($parts, 1)));
                    }
                    return [[
                        'no_matriks' => $matriks,
                        'label_name' => $name,
                    ]];
                }

                if (str_contains($line, ';')) {
                    return array_map(static fn(string $value): array => [
                        'no_matriks' => trim($value),
                        'label_name' => '',
                    ], array_values(array_filter(array_map('trim', explode(';', $line)), static fn(string $value): bool => $value !== '')));
                }

                return [[
                    'no_matriks' => $line,
                    'label_name' => '',
                ]];
            })
            ->map(static fn(array $entry): array => [
                'no_matriks' => strtoupper(trim((string) ($entry['no_matriks'] ?? ''))),
                'label_name' => trim((string) ($entry['label_name'] ?? '')),
            ])
            ->filter(static fn(array $entry): bool => $entry['no_matriks'] !== '')
            ->unique('no_matriks')
            ->values();

        if ($parsedEntries->isEmpty()) {
            return redirect()
                ->route('admin.users.no-matriks')
                ->withErrors(['no_matriks' => 'Please enter at least one no_matriks value.']);
        }

        $tooLong = $parsedEntries->first(static fn(array $entry): bool => mb_strlen($entry['no_matriks']) > 50);
        if ($tooLong !== null) {
            return redirect()
                ->route('admin.users.no-matriks')
                ->withErrors(['no_matriks' => 'Each no_matriks value must be 50 characters or fewer.']);
        }
        $tooLongLabel = $parsedEntries->first(static fn(array $entry): bool => mb_strlen($entry['label_name']) > 120);
        if ($tooLongLabel !== null) {
            return redirect()
                ->route('admin.users.no-matriks')
                ->withErrors(['no_matriks' => 'Each name label must be 120 characters or fewer.']);
        }
        $existing = NoMatriksEntry::query()
            ->whereIn('no_matriks', $parsedEntries->pluck('no_matriks')->all())
            ->pluck('no_matriks')
            ->all();

        if (!empty($existing)) {
            $duplicatePreview = collect($existing)->take(5)->implode(', ');
            $duplicateSuffix = count($existing) > 5 ? ' ...' : '';

            return redirect()
                ->route('admin.users.no-matriks')
                ->withInput()
                ->withErrors([
                    'no_matriks' => 'Cannot save because some no_matriks already exist: ' . $duplicatePreview . $duplicateSuffix,
                ])
                ->with('error_popup', 'Duplicate no_matriks detected. Please remove existing numbers and try again.');
        }

        $now = now();
        NoMatriksEntry::query()->insert(
            $parsedEntries->map(static fn(array $entry): array => [
                'no_matriks' => $entry['no_matriks'],
                'label_name' => $entry['label_name'] !== '' ? $entry['label_name'] : null,
                'created_by' => $authUser?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all()
        );

        return redirect()
            ->route('admin.users.no-matriks')
            ->with('status', 'Nombor matriks berjaya disimpan.');
    })->name('admin.users.no-matriks.store');

    Route::delete('/admin/no-matriks-users', function (Request $request) {
        $authUser = $request->user();
        $authRole = $authUser?->roles()->value('name');

        abort_unless($authRole === 'admin', 403);

        $validated = $request->validate([
            'entry_ids' => ['required', 'array', 'min:1'],
            'entry_ids.*' => ['integer', 'exists:no_matriks_entries,id'],
        ]);

        $deletedCount = NoMatriksEntry::query()
            ->whereIn('id', $validated['entry_ids'])
            ->delete();

        return redirect()
            ->route('admin.users.no-matriks')
            ->with('status', $deletedCount > 0
                ? "Berjaya padam {$deletedCount} no_matriks."
                : 'Tiada no_matriks dipadam.');
    })->name('admin.users.no-matriks.bulk-destroy');



    Route::patch('/admin/manage-accounts/{managedUser}', function (Request $request, User $managedUser) {
        $authUser = $request->user();
        $authRole = $authUser?->roles()->value('name');

        abort_unless($authRole === 'admin', 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $managedUser->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'in:student,teacher,counsellor,admin,unassigned'],
        ]);

        $managedUser->name = $validated['name'];
        $managedUser->email = $validated['email'];
        $managedUser->phone = $validated['phone'] ?: null;
        $managedUser->save();

        if ($validated['role'] === 'unassigned') {
            $managedUser->roles()->detach();
        } else {
            $roleModel = Role::firstOrCreate(
                ['name' => $validated['role']],
                ['description' => ucfirst($validated['role']) . ' user']
            );

            $managedUser->roles()->sync([
                $roleModel->id => ['assigned_at' => now()],
            ]);
        }

        return redirect()
            ->route('admin.accounts.manage')
            ->with('status', 'Akaun pengguna berjaya dikemas kini.');
    })->name('admin.accounts.update');

    Route::delete('/admin/manage-accounts/{managedUser}', function (Request $request, User $managedUser) {
        $authUser = $request->user();
        $authRole = $authUser?->roles()->value('name');

        abort_unless($authRole === 'admin', 403);

        if ($authUser && $authUser->id === $managedUser->id) {
            return redirect()
                ->route('admin.accounts.manage')
                ->with('status', 'Anda tidak boleh padam akaun admin sendiri.');
        }

        $managedUser->roles()->detach();
        $managedUser->delete();

        return redirect()
            ->route('admin.accounts.manage')
            ->with('status', 'Akaun pengguna berjaya dipadam.');
    })->name('admin.accounts.delete');

    Route::get('/counsellor', function () {
        return redirect()->route('counsellor.dashboard');
    })->name('counsellor.index');

    Route::get('/counsellor/dashboard', function () {
        $user = request()->user();
        $role = $user?->roles()->value('name');

        abort_unless($role === 'counsellor', 403);

        $counsellorNames = array_values(array_filter([
            $user->full_name,
            $user->name,
        ]));
        $normalizedCounsellorNames = array_values(array_unique(array_map(
            static fn(string $name): string => mb_strtolower(trim($name)),
            $counsellorNames
        )));

        $bookingsQuery = BookingRequest::query()
            ->with(['user:id,name,full_name,email', 'user.roles:id,name'])
            ->whereIn(DB::raw('LOWER(TRIM(counsellor_name))'), $normalizedCounsellorNames)
            ->latest('booking_date')
            ->latest('booking_time');

        $bookings = $bookingsQuery->get();

        $statusLabel = static fn(string $status): string => match ($status) {
            'approved' => 'Diluluskan',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            default => 'Menunggu',
        };

        $applications = $bookings
            ->whereIn('status', ['pending', 'approved'])
            ->values()
            ->map(static function (BookingRequest $booking) use ($statusLabel): array {
                return [
                    'booking_request_id' => $booking->id,
                    'student_id' => $booking->user?->id,
                    'student' => $booking->user?->full_name ?: $booking->user?->name ?: 'Pelajar',
                    'student_email' => $booking->user?->email,
                    'student_phone' => data_get($booking, 'user.phone') ?: data_get($booking, 'user.mobile') ?: data_get($booking, 'user.phone_number'),
                    'requester_role' => (
                        $booking->user?->roles
                        ?->pluck('name')
                        ->map(static fn(string $name): string => mb_strtolower($name))
                        ->first(static fn(string $name): bool => in_array($name, ['student', 'teacher'], true))
                    ) === 'teacher'
                        ? 'Lecturer'
                        : 'Student',
                    'request_date' => (string) $booking->booking_date,
                    'request_time' => (string) $booking->booking_time,
                    'topic' => $booking->topic ?: 'General support',
                    'status' => $statusLabel($booking->status),
                ];
            })
            ->all();

        $scheduleSlots = $bookings
            ->whereIn('status', ['approved', 'completed'])
            ->sortBy(['booking_date', 'booking_time'])
            ->values()
            ->take(12)
            ->map(static function (BookingRequest $booking): array {
                $bookingDate = Carbon::parse($booking->booking_date);

                return [
                    'time' => $booking->booking_time,
                    'date' => $bookingDate->translatedFormat('l, j M'),
                    'slot_status' => $booking->status === 'completed' ? 'Selesai' : 'Ditempah',
                ];
            })
            ->all();

        $sessionRecords = $bookings
            ->where('status', 'completed')
            ->values()
            ->take(10)
            ->map(static function (BookingRequest $booking): array {
                return [
                    'student' => $booking->user?->full_name ?: $booking->user?->name ?: 'Pelajar',
                    'date' => (string) $booking->booking_date,
                    'notes' => $booking->note,
                ];
            })
            ->all();

        return view('counsellor', [
            'user' => $user,
            'applications' => $applications,
            'scheduleSlots' => $scheduleSlots,
            'sessionRecords' => $sessionRecords,
        ]);
    })->name('counsellor.dashboard');

    Route::get('/counsellor/pending-requests', function () {
        $user = request()->user();
        $role = $user?->roles()->value('name');

        abort_unless($role === 'counsellor', 403);

        $normalizeCounsellorName = static fn(?string $name): string => preg_replace('/\s+/', '', mb_strtolower(trim((string) $name)));
        $counsellorNames = array_values(array_filter([
            $user->full_name,
            $user->name,
        ]));
        $normalizedCounsellorNames = array_values(array_unique(array_map(
            $normalizeCounsellorName,
            $counsellorNames
        )));

        $pendingRequests = BookingRequest::query()
            ->with(['user:id,name,full_name,email', 'user.roles:id,name'])
            ->whereIn(DB::raw("LOWER(REPLACE(TRIM(counsellor_name), ' ', ''))"), $normalizedCounsellorNames)
            ->where('status', 'pending')
            ->latest('booking_date')
            ->latest('booking_time')
            ->get()
            ->map(static function (BookingRequest $booking): array {
                return [
                    'id' => $booking->id,
                    'student' => $booking->user?->full_name ?: $booking->user?->name ?: 'Pelajar',
                    'date' => (string) $booking->booking_date,
                    'time' => $booking->booking_time,
                    'topic' => $booking->topic ?: 'General support',
                    'status' => $booking->status,
                    'notes' => $booking->note,
                ];
            })
            ->all();

        return view('counsellor-pending-requests', [
            'user' => $user,
            'pendingRequests' => $pendingRequests,
        ]);
    })->name('counsellor.pending-requests');

    Route::patch('/counsellor/booking-requests/{bookingRequest}/status', function (Request $request, BookingRequest $bookingRequest) {
        $user = $request->user();
        $role = $user?->roles()->value('name');

        abort_unless($role === 'counsellor', 403);

        $normalizeCounsellorName = static fn(?string $name): string => preg_replace('/\s+/', '', mb_strtolower(trim((string) $name)));
        $counsellorNames = array_values(array_filter([
            $user?->full_name,
            $user?->name,
        ]));
        $normalizedCounsellorNames = array_values(array_unique(array_map(
            $normalizeCounsellorName,
            $counsellorNames
        )));
        abort_unless(
            in_array($normalizeCounsellorName($bookingRequest->counsellor_name), $normalizedCounsellorNames, true),
            403
        );

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected,completed'],
        ]);

        if ($bookingRequest->status === 'rejected' && $validated['status'] !== 'rejected') {
            return back()->with('status', 'Rejected request cannot be changed.');
        }

        if ($bookingRequest->status === 'completed' && $validated['status'] !== 'completed') {
            return back()->with('status', 'Completed session cannot be changed.');
        }

        if ($validated['status'] === 'completed' && $bookingRequest->status !== 'approved') {
            return back()->with('status', 'Only approved sessions can be marked as completed.');
        }

        $bookingRequest->update([
            'status' => $validated['status'],
        ]);

        $statusMessage = match ($validated['status']) {
            'approved' => 'approved',
            'rejected' => 'rejected',
            'completed' => 'completed',
            default => $validated['status'],
        };

        $bookingRequest->user?->inboxNotifications()->create([
            'title' => 'Booking status updated',
            'message' => 'Your counselling session on ' . $bookingRequest->booking_date . ' at ' . $bookingRequest->booking_time . ' with ' . $bookingRequest->counsellor_name . ' was ' . $statusMessage . '.',
        ]);

        return back()->with('status', 'Booking status updated successfully.');
    })->name('counsellor.booking-request.status');


    Route::post('/counsellor/booking-requests/{bookingRequest}/reminder', function (Request $request, BookingRequest $bookingRequest) {
        $user = $request->user();
        $role = $user?->roles()->value('name');

        abort_unless($role === 'counsellor', 403);

        $normalizeCounsellorName = static fn(?string $name): string => preg_replace('/\s+/', '', mb_strtolower(trim((string) $name)));
        $counsellorNames = array_values(array_filter([
            $user?->full_name,
            $user?->name,
        ]));
        $normalizedCounsellorNames = array_values(array_unique(array_map(
            $normalizeCounsellorName,
            $counsellorNames
        )));

        abort_unless(
            in_array($normalizeCounsellorName($bookingRequest->counsellor_name), $normalizedCounsellorNames, true),
            403
        );

        $validated = $request->validate([
            'reminder_message' => ['nullable', 'string', 'max:1000'],
        ]);

        $recipient = $bookingRequest->user;
        if (! $recipient) {
            return back()->with('status', 'Reminder could not be sent because the student account is missing.');
        }

        $defaultMessage = 'Reminder: your counselling request on '
            . $bookingRequest->booking_date
            . ' (' . $bookingRequest->booking_time . ') with '
            . $bookingRequest->counsellor_name
            . ' still needs your attention.';
        $reminderMessage = trim((string) ($validated['reminder_message'] ?? ''));
        if ($reminderMessage === '') {
            $reminderMessage = $defaultMessage;
        }

        $recipient->inboxNotifications()->create([
            'title' => 'Counselling reminder',
            'message' => $reminderMessage,
        ]);
        ChatMessage::create([
            'sender_id' => $user->id,
            'receiver_id' => $recipient->id,
            'message' => $reminderMessage,
        ]);
        if (! empty($recipient->email)) {
            Mail::raw($reminderMessage, static function ($message) use ($recipient): void {
                $message->to($recipient->email)
                    ->subject('CollegeCare Counselling Reminder');
            });
        }

        return back()->with('status', 'Reminder sent to student inbox and email.');
    })->name('counsellor.booking-request.reminder');


    Route::get('/counsellor/statistics', function () {
        $user = request()->user();
        $role = $user?->roles()->value('name');

        abort_unless($role === 'counsellor', 403);

        $normalizeCounsellorName = static fn(?string $name): string => preg_replace('/\s+/', '', mb_strtolower(trim((string) $name)));
        $counsellorNames = array_values(array_filter([
            $user->full_name,
            $user->name,
        ]));
        $normalizedCounsellorNames = array_values(array_unique(array_map(
            $normalizeCounsellorName,
            $counsellorNames
        )));

        $bookings = BookingRequest::query()
            ->with(['user:id,name,full_name'])
            ->whereIn(DB::raw("LOWER(REPLACE(TRIM(counsellor_name), ' ', ''))"), $normalizedCounsellorNames)
            ->get();

        $topStudents = $bookings
            ->groupBy(static fn(BookingRequest $booking): string => $booking->user?->full_name ?: $booking->user?->name ?: 'Unknown Student')
            ->map(static fn($items, string $student): array => [
                'student' => $student,
                'total' => $items->count(),
            ])
            ->sortByDesc('total')
            ->values()
            ->take(10)
            ->all();


        $chartBookings = $bookings
            ->map(static fn(BookingRequest $booking): array => [
                'date' => (string) $booking->booking_date,
                'topic' => trim((string) ($booking->topic ?: 'General support')),
                'is_emergency' => str_contains(mb_strtolower((string) $booking->topic), 'emergency'),
            ])
            ->values()
            ->all();

        $topTopics = $bookings
            ->groupBy(static fn(BookingRequest $booking): string => trim((string) ($booking->topic ?: 'General support')))
            ->map(static fn($items, string $topic): array => [
                'topic' => $topic,
                'total' => $items->count(),
            ])
            ->sortByDesc('total')
            ->values()
            ->take(10)
            ->all();

        $emergencyBookings = $bookings
            ->filter(static fn(BookingRequest $booking): bool => str_contains(mb_strtolower((string) $booking->topic), 'emergency'))
            ->values();

        $emergencyTopStudents = $emergencyBookings
            ->groupBy(static fn(BookingRequest $booking): string => $booking->user?->full_name ?: $booking->user?->name ?: 'Unknown Student')
            ->map(static fn($items, string $student): array => [
                'student' => $student,
                'total' => $items->count(),
            ])
            ->sortByDesc('total')
            ->values()
            ->take(10)
            ->all();

        $emergencyTopTopics = $emergencyBookings
            ->groupBy(static fn(BookingRequest $booking): string => trim((string) ($booking->topic ?: 'General support')))
            ->map(static fn($items, string $topic): array => [
                'topic' => $topic,
                'total' => $items->count(),
            ])
            ->sortByDesc('total')
            ->values()
            ->take(10)
            ->all();

        return view('counsellor-statistics', [
            'user' => $user,
            'topStudents' => $topStudents,
            'topTopics' => $topTopics,
            'totalBookings' => $bookings->count(),
            'emergencyBookingsCount' => $emergencyBookings->count(),
            'emergencyTopStudents' => $emergencyTopStudents,
            'emergencyTopTopics' => $emergencyTopTopics,
            'chartBookings' => $chartBookings,
        ]);
    })->name('counsellor.statistics');

    Route::get('/counsellor/session-status-list', function () {
        $user = request()->user();
        $role = $user?->roles()->value('name');

        abort_unless($role === 'counsellor', 403);

        $normalizeCounsellorName = static fn(?string $name): string => preg_replace('/\s+/', '', mb_strtolower(trim((string) $name)));
        $counsellorNames = array_values(array_filter([
            $user->full_name,
            $user->name,
        ]));
        $normalizedCounsellorNames = array_values(array_unique(array_map(
            $normalizeCounsellorName,
            $counsellorNames
        )));

        $statusLabel = static fn(string $status): string => match ($status) {
            'approved' => 'Approved',
            'completed' => 'Completed',
            default => 'Booked',
        };

        $sessions = BookingRequest::query()
            ->with(['user:id,name,full_name,email', 'user.roles:id,name'])
            ->whereIn(DB::raw("LOWER(REPLACE(TRIM(counsellor_name), ' ', ''))"), $normalizedCounsellorNames)
            ->whereIn('status', ['approved', 'completed'])
            ->latest('booking_date')
            ->latest('booking_time')
            ->get()
            ->map(static function (BookingRequest $booking) use ($statusLabel): array {

                return [
                    'id' => $booking->id,
                    'student' => $booking->user?->full_name ?: $booking->user?->name ?: 'Pelajar',
                    'date' => (string) $booking->booking_date,
                    'time' => $booking->booking_time,
                    'status' => $statusLabel($booking->status),
                    'status_value' => $booking->status,
                    'topic' => $booking->note,
                    'topic' => $booking->topic ?: 'General support',

                ];
            })
            ->all();

        return view('counsellor-session-status-list', [
            'user' => $user,
            'sessions' => $sessions,
        ]);
    })->name('counsellor.session-status-list');


    Route::get('/booking', function () {
        $user = request()->user();
        $role = $user?->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);

        $counsellors = User::query()
            ->whereHas('roles', static fn($query) => $query->where('name', 'counsellor'))
            ->orderBy('full_name')
            ->orderBy('name')
            ->get()
            ->map(static fn(User $counsellor) => $counsellor->full_name ?: $counsellor->name)
            ->filter()
            ->unique()
            ->values()
            ->all();
        $bookingSlots = BookingRequest::query()
            ->whereIn('status', ['pending', 'approved'])
            ->get(['booking_date', 'booking_time', 'counsellor_name', 'status'])
            ->map(static fn(BookingRequest $booking): array => [
                'date' => (string) $booking->booking_date,
                'time' => $booking->booking_time,
                'counsellor' => $booking->counsellor_name,
                'status' => $booking->status,
            ])
            ->all();
        $userActiveBookings = BookingRequest::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->get(['id', 'booking_date', 'booking_time', 'counsellor_name', 'status'])
            ->map(static fn(BookingRequest $booking): array => [
                'id' => $booking->id,
                'date' => (string) $booking->booking_date,
                'time' => $booking->booking_time,
                'counsellor' => $booking->counsellor_name,
                'status' => $booking->status,
            ])
            ->all();
        return view('booking', [
            'user' => $user,
            'role' => $role,
            'counsellors' => $counsellors,
            'bookingSlots' => $bookingSlots,
            'userActiveBookings' => $userActiveBookings,

        ]);
    })->name('booking.index');
    Route::get('/booking-history', function (Request $request) {
        $user = $request->user();
        $role = $user?->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);

        $filters = $request->validate([
            'status' => ['nullable', 'in:all,pending,approved,rejected,cancelled,completed'],
        ]);

        $selectedStatus = $filters['status'] ?? 'all';

        $statusLabel = static fn(string $status): string => match ($status) {
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
            default => ucfirst($status),
        };

        $statusBadgeClass = static fn(string $status): string => match ($status) {
            'approved' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'rejected' => 'border-rose-200 bg-rose-50 text-rose-700',
            'cancelled' => 'border-rose-200 bg-rose-50 text-rose-700',
            'completed' => 'border-slate-300 bg-slate-100 text-slate-700',
            default => 'border-amber-200 bg-amber-50 text-amber-700',
        };

        $bookingsQuery = BookingRequest::query()
            ->where('user_id', $user->id);

        if ($selectedStatus !== 'all') {
            $bookingsQuery->where('status', $selectedStatus);
        }

        $bookings = $bookingsQuery
            ->latest('booking_date')
            ->latest('booking_time')
            ->get()
            ->map(static function (BookingRequest $booking) use ($statusLabel, $statusBadgeClass): array {
                return [
                    'id' => $booking->id,
                    'date' => (string) $booking->booking_date,
                    'time' => $booking->booking_time,
                    'counsellor' => $booking->counsellor_name,
                    'note' => $booking->note,
                    'status' => $booking->status,
                    'status_label' => $statusLabel($booking->status),
                    'status_badge_class' => $statusBadgeClass($booking->status),
                ];
            })
            ->all();

        $bookingStats = BookingRequest::query()
            ->where('user_id', $user->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('booking-history', [
            'user' => $user,
            'role' => $role,
            'bookings' => $bookings,
            'selectedStatus' => $selectedStatus,
            'bookingStats' => [
                'all' => (int) $bookingStats->sum(),
                'pending' => (int) ($bookingStats['pending'] ?? 0),
                'approved' => (int) ($bookingStats['approved'] ?? 0),
                'rejected' => (int) ($bookingStats['rejected'] ?? 0),
                'cancelled' => (int) ($bookingStats['cancelled'] ?? 0),
                'completed' => (int) ($bookingStats['completed'] ?? 0),
            ],
        ]);
    })->name('booking.history');


    Route::post('/booking', function (Request $request) {
        $user = $request->user();
        $role = $user?->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);

        $validated = $request->validate([
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'string', 'max:50'],
            'counsellor_name' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:120'],
            'reason_other' => ['nullable', 'string', 'max:120'],
            'reason_other' => ['nullable', 'string', 'max:120'],
            'note' => ['required', 'string', 'max:700'],
        ]);

        if (
            $validated['reason'] === 'Lain-lain'
            && blank($validated['reason_other'] ?? null)
        ) {
            return response()->json([
                'message' => 'Please provide a reason detail when selecting "Lain-lain".',
            ], 422);
        }

        $normalizeCounsellorName = static fn(?string $name): string => preg_replace('/\s+/', '', mb_strtolower(trim((string) $name)));
        $normalizedRequestedCounsellor = $normalizeCounsellorName($validated['counsellor_name']);

        $selectedCounsellor = User::query()
            ->whereHas('roles', static fn($query) => $query->where('name', 'counsellor'))
            ->get(['id', 'name', 'full_name'])
            ->first(static function (User $counsellor) use ($normalizeCounsellorName, $normalizedRequestedCounsellor): bool {
                return in_array($normalizedRequestedCounsellor, [
                    $normalizeCounsellorName($counsellor->name),
                    $normalizeCounsellorName($counsellor->full_name),
                ], true);
            });

        if (! $selectedCounsellor) {
            return response()->json([
                'message' => 'Selected counsellor is not available.',
            ], 422);
        }

        $noteUppercase = mb_strtoupper((string) ($validated['note'] ?? ''));
        $isEmergencyRequest = $request->boolean('is_emergency')
            || str_contains($noteUppercase, '[EMERGENCY]');
        $activeBookingCount = BookingRequest::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->count();
        $maxActiveBookingCount = $isEmergencyRequest ? 2 : 1;

        if ($activeBookingCount >= $maxActiveBookingCount) {
            $limitMessage = $isEmergencyRequest
                ? 'Emergency booking dibenarkan maksimum 2 booking aktif sahaja.'
                : 'Anda hanya boleh ada 1 booking aktif sahaja. Guna pilihan emergency jika benar-benar perlu.';

            return response()->json([
                'message' => $limitMessage,
            ], 422);
        }

        if ($isEmergencyRequest) {
            $hasEmergencyBookingOnDate = BookingRequest::query()
                ->where('user_id', $user->id)
                ->whereDate('booking_date', $validated['booking_date'])
                ->whereIn('status', ['pending', 'approved'])
                ->where(static function ($query) {
                    $query
                        ->where('topic', 'like', '[EMERGENCY]%')
                        ->orWhere('note', 'like', '[EMERGENCY]%');
                })
                ->exists();

            if ($hasEmergencyBookingOnDate) {
                return response()->json([
                    'message' => 'Emergency booking hanya dibenarkan 1 kali sehari untuk setiap pengguna.',
                ], 422);
            }
        }
        $existingSlot = BookingRequest::query()
            ->whereDate('booking_date', $validated['booking_date'])
            ->where('booking_time', $validated['booking_time'])
            ->where('counsellor_name', $validated['counsellor_name'])
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existingSlot) {
            return response()->json([
                'message' => 'This slot is no longer available. Please choose another slot.',
            ], 422);
        }

        $bookingTopic = $validated['reason'] === 'Lain-lain'
            ? ($validated['reason_other'] ?? 'General support')
            : $validated['reason'];
        $bookingTopic = $isEmergencyRequest ? '[EMERGENCY] ' . $bookingTopic : $bookingTopic;

        BookingRequest::create([
            'user_id' => $user->id,
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'counsellor_name' => $validated['counsellor_name'],
            'topic' => $bookingTopic,
            'note' => $validated['note'],
            'status' => 'pending',
        ]);

        $user->inboxNotifications()->create([
            'title' => 'Booking request sent',
            'message' => 'Your counselling request for ' . $validated['booking_date'] . ' (' . $validated['booking_time'] . ') with ' . $validated['counsellor_name'] . ' has been submitted.',
        ]);
        $counsellorNotificationTitle = $isEmergencyRequest
            ? 'Emergency counselling request'
            : 'New counselling request';
        $counsellorNotificationMessage = ($user->full_name ?: $user->name ?: 'A student') . ' submitted '
            . ($isEmergencyRequest ? 'an EMERGENCY ' : 'a ')
            . 'counselling request for ' . $validated['booking_date'] . ' (' . $validated['booking_time'] . ').';
        $selectedCounsellor->inboxNotifications()->create([
            'title' => $counsellorNotificationTitle,
            'message' => $counsellorNotificationMessage,
        ]);

        return response()->json([
            'message' => 'Booking request submitted.',
        ]);
    })->name('booking.store');
    Route::delete('/booking/{bookingRequest}', function (Request $request, BookingRequest $bookingRequest) {
        $user = $request->user();
        $role = $user?->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);
        abort_unless((int) $bookingRequest->user_id === (int) $user?->id, 403);

        if (! in_array($bookingRequest->status, ['pending', 'approved'], true)) {
            return response()->json([
                'message' => 'Only pending or approved bookings can be cancelled.',
            ], 422);
        }

        $bookingRequest->update([
            'status' => 'cancelled',
        ]);

        $user->inboxNotifications()->create([
            'title' => 'Booking cancelled',
            'message' => 'Your counselling booking for ' . $bookingRequest->booking_date . ' (' . $bookingRequest->booking_time . ') with ' . $bookingRequest->counsellor_name . ' has been cancelled. Reason: ' . $cancelReason . '.',



        ]);

        $normalizedCounsellorName = preg_replace('/\s+/', '', mb_strtolower(trim((string) $bookingRequest->counsellor_name)));
        $matchedCounsellor = User::query()
            ->whereHas('roles', static fn($query) => $query->where('name', 'counsellor'))
            ->get(['id', 'name', 'full_name'])
            ->first(static function (User $counsellor) use ($normalizedCounsellorName): bool {
                $candidates = [
                    preg_replace('/\s+/', '', mb_strtolower(trim((string) $counsellor->name))),
                    preg_replace('/\s+/', '', mb_strtolower(trim((string) $counsellor->full_name))),
                ];

                return in_array($normalizedCounsellorName, $candidates, true);
            });

        $matchedCounsellor?->inboxNotifications()->create([
            'title' => 'Booking cancelled by user',
            'message' => ($user->full_name ?: $user->name ?: 'A user') . ' cancelled the counselling booking on '
                . $bookingRequest->booking_date . ' (' . $bookingRequest->booking_time . '). Reason: ' . $cancelReason . '.',
        ]);

        return response()->json([
            'message' => 'Booking cancelled successfully.',
        ]);
    })->name('booking.cancel');

    Route::get('/inbox', function (Request $request) {
        $user = $request->user();
        $role = $user?->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);

        $filters = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'time_from' => ['nullable', 'date_format:H:i'],
            'time_to' => ['nullable', 'date_format:H:i'],
        ]);

        $notificationsQuery = $user->inboxNotifications()->latest();

        if (!empty($filters['date_from'])) {
            $notificationsQuery->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $notificationsQuery->whereDate('created_at', '<=', $filters['date_to']);
        }
        if (!empty($filters['time_from'])) {
            $notificationsQuery->whereTime('created_at', '>=', $filters['time_from']);
        }
        if (!empty($filters['time_to'])) {
            $notificationsQuery->whereTime('created_at', '<=', $filters['time_to']);
        }

        return view('inbox', [
            'user' => $user,
            'role' => $role,
            'notifications' => $notificationsQuery->get(),
            'filters' => $filters,
        ]);
    })->name('inbox');

    Route::delete('/inbox/bulk-delete', function (Request $request) {
        $user = $request->user();
        $role = $user?->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);

        $validated = $request->validate([
            'notification_ids' => ['required', 'array', 'min:1'],
            'notification_ids.*' => ['integer'],
        ]);

        $deletedCount = $user->inboxNotifications()
            ->whereIn('id', $validated['notification_ids'])
            ->delete();

        return back()->with('status', $deletedCount > 0
            ? $deletedCount . ' notification' . ($deletedCount === 1 ? '' : 's') . ' deleted.'
            : 'No notifications were deleted.');
    })->name('inbox.notification.bulk-delete');


    Route::delete('/inbox/{notification}', function (InboxNotification $notification) {
        $user = request()->user();
        $role = $user?->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);
        abort_unless((int) $notification->user_id === (int) $user?->id, 403);

        $notification->delete();

        return back()->with('status', 'Notification deleted.');
    })->name('inbox.notification.delete');

    Route::get('/phone-otp', [AuthController::class, 'showPhoneOtpForm'])->name('phone.otp.form');
    Route::post('/phone-otp/verify', [AuthController::class, 'verifyPhoneOtp'])->name('phone.otp.verify');
    Route::post('/phone-otp/resend', [AuthController::class, 'resendPhoneOtp'])->name('phone.otp.resend');



    Route::get('/edit-profile', [ProfileController::class, 'showEditProfile'])
        ->name('profile.edit');

    Route::post('/edit-profile', [ProfileController::class, 'updateProfileInfo'])
        ->name('profile.update');


    Route::post('/profile/picture', [AuthController::class, 'updateProfilePicture'])
        ->name('profile.picture.update');


    Route::get('/admin/counsellors/create', [CounsellorController::class, 'create'])
        ->name('admin.counsellor.create');

    Route::get('/admin/signup-counsellor', [CounsellorController::class, 'create'])
        ->name('admin.counsellor.signup');

    Route::post('/admin/counsellors', [CounsellorController::class, 'store'])
        ->name('admin.counsellor.store');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
