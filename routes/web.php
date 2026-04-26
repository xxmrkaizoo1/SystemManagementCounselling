<?php

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

    Route::get('/signup/otp', [AuthController::class, 'showOtpForm'])->name('signup.otp.form');
    Route::post('/signup/otp/verify', [AuthController::class, 'verifySignupOtp'])->name('signup.otp.verify');
    Route::post('/signup/otp/resend', [AuthController::class, 'resendSignupOtp'])->name('signup.otp.resend');
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

        $now = now();
        $currentMinutes = ((int) $now->format('H')) * 60 + ((int) $now->format('i'));
        $nextSlotLabel = $now->copy()->addHour()->startOfHour()->format('g:i A');

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
            ->map(static fn(string $name): array => [
                'name' => $name,
                'available' => !array_key_exists($name, $occupiedLookup),
                'next_slot' => $nextSlotLabel,
            ])
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
            $entriesQuery->where('no_matriks', 'like', '%' . $searchTerm . '%');
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
    })->name('admin.users.no-matriks');


    Route::post('/admin/no-matriks-users/{managedUser?}', function (Request $request) {
        $authUser = $request->user();
        $authRole = $authUser?->roles()->value('name');

        abort_unless($authRole === 'admin', 403);

        $validated = $request->validate([
            'no_matriks' => ['nullable', 'string'],
            'no_matriks_file' => ['nullable', 'file', 'max:5120', 'mimes:txt,csv,jpg,jpeg,png,webp'],
        ]);

        $rawInput = trim((string) ($validated['no_matriks'] ?? ''));
        $uploadedFile = $request->file('no_matriks_file');

        $normalizeLikelyOcrMistakes = static function (string $value): string {
            $normalized = strtoupper($value);

            // Common OCR confusion in mixed alpha-numeric IDs
            $normalized = preg_replace('/(?<=\d)[OQ](?=\d)/', '0', $normalized) ?? $normalized;
            $normalized = preg_replace('/(?<=\d)[IL](?=\d)/', '1', $normalized) ?? $normalized;
            $normalized = preg_replace('/(?<=[A-Z])[0](?=[A-Z])/', 'O', $normalized) ?? $normalized;

            return $normalized;
        };
        $resolveTesseractBinary = static function (): ?string {
            $candidates = array_values(array_filter([
                config('services.tesseract.binary'),
                env('TESSERACT_BINARY'),
                'tesseract',
                'C:\\Program Files\\Tesseract-OCR\\tesseract.exe',
                'C:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe',
            ], static fn($value): bool => is_string($value) && trim($value) !== ''));

            foreach ($candidates as $candidate) {
                $candidate = trim($candidate);

                if (
                    str_contains($candidate, DIRECTORY_SEPARATOR)
                    || str_contains($candidate, '\\')
                    || str_contains($candidate, '/')
                ) {
                    if (is_file($candidate)) {
                        return $candidate;
                    }

                    continue;
                }

                $lookupCommand = strtoupper(substr(PHP_OS_FAMILY, 0, 3)) === 'WIN'
                    ? "where {$candidate} 2>NUL"
                    : "command -v {$candidate} 2>/dev/null";
                $resolvedPath = trim((string) shell_exec($lookupCommand));
                if ($resolvedPath !== '') {
                    $firstResolvedLine = trim((string) strtok($resolvedPath, "\r\n"));
                    if ($firstResolvedLine !== '') {
                        return $firstResolvedLine;
                    }
                }
            }

            return null;
        };

        $extractMatriksFromText = static function (string $text) use ($normalizeLikelyOcrMistakes): array {
            $upperText = strtoupper($text);
            return collect(preg_split('/\R+/', $upperText))
                ->flatMap(static function (?string $line): array {
                    if ($line === null) {
                        return [];
                    }

                    preg_match_all('/[A-Z0-9]{5,}/', $line, $lineMatches);
                    return $lineMatches[0] ?? [];
                })
                ->map(static fn(string $value): string => preg_replace('/[^A-Z0-9]/', '', $value) ?? '')
                ->map($normalizeLikelyOcrMistakes)
                ->filter(static fn(string $value): bool => $value !== '')
                ->filter(static fn(string $value): bool => strlen($value) >= 6 && strlen($value) <= 50)
                ->filter(static fn(string $value): bool => preg_match('/[A-Z]/', $value) === 1 && preg_match('/\d/', $value) === 1)
                ->unique()
                ->values()
                ->all();
        };

        if ($uploadedFile) {
            $mimeType = (string) ($uploadedFile->getMimeType() ?? '');
            $isImage = str_starts_with($mimeType, 'image/');

            if ($isImage) {


                $tesseractBinary = $resolveTesseractBinary();
                if ($tesseractBinary === null) {
                    return redirect()
                        ->route('admin.users.no-matriks')
                        ->withErrors(['no_matriks_file' => 'Image OCR is unavailable because Tesseract is not configured on the server. Please set TESSERACT_BINARY or install Tesseract.']);
                }

                $sourcePath = $uploadedFile->getRealPath();
                $escapedSource = escapeshellarg((string) $sourcePath);
                $escapedTesseractBinary = escapeshellarg($tesseractBinary);
                $outputBase = tempnam(sys_get_temp_dir(), 'matriks_ocr_');

                if ($outputBase === false) {
                    return redirect()
                        ->route('admin.users.no-matriks')
                        ->withErrors(['no_matriks_file' => 'Unable to process the uploaded image right now.']);
                }

                @unlink($outputBase);
                $escapedOutputBase = escapeshellarg($outputBase);
                $command = "tesseract {$escapedSource} {$escapedOutputBase} -l eng --psm 6 2>&1";
                exec($command, $shellOutput, $exitCode);

                $ocrCommands = [
                    "{$escapedTesseractBinary} {$escapedSource} {$escapedOutputBase} -l eng --oem 1 --psm 6 -c tessedit_char_whitelist=ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 2>&1",
                    "{$escapedTesseractBinary} {$escapedSource} {$escapedOutputBase} -l eng --oem 1 --psm 7 -c tessedit_char_whitelist=ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 2>&1",
                    "{$escapedTesseractBinary} {$escapedSource} {$escapedOutputBase} -l eng --oem 1 --psm 11 -c tessedit_char_whitelist=ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 2>&1",
                    "{$escapedTesseractBinary} {$escapedSource} {$escapedOutputBase} -l eng --oem 1 --psm 12 -c tessedit_char_whitelist=ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 2>&1",
                ];

                $ocrTexts = [];
                foreach ($ocrCommands as $command) {
                    $shellOutput = [];
                    $exitCode = 1;
                    exec($command, $shellOutput, $exitCode);
                    if ($exitCode === 0) {
                        $ocrTexts[] = (string) @file_get_contents($outputBase . '.txt');
                    }
                }

                @unlink($outputBase . '.txt');

                if (empty($ocrTexts)) {
                    return redirect()
                        ->route('admin.users.no-matriks')
                        ->withErrors(['no_matriks_file' => 'Image OCR failed. Please upload a TXT/CSV file or paste the list manually.']);
                }

                $ocrMerged = implode("\n", $ocrTexts);
                $ocrCandidates = $extractMatriksFromText($ocrMerged);
                $ocrInputToMerge = !empty($ocrCandidates) ? implode("\n", $ocrCandidates) : $ocrMerged;
                $rawInput = trim($rawInput . "\n" . $ocrInputToMerge);
            } else {
                $fileText = @file_get_contents($uploadedFile->getRealPath());
                $rawInput = trim($rawInput . "\n" . (string) $fileText);
            }
        }

        $entries = collect(preg_split('/[\r\n,;]+/', $rawInput))
            ->map(static fn(?string $value): string => trim((string) $value))
            ->filter()
            ->unique()
            ->values();

        if ($entries->isEmpty()) {
            return redirect()
                ->route('admin.users.no-matriks')
                ->withErrors(['no_matriks' => 'Please enter at least one no_matriks value.']);
        }

        $tooLong = $entries->first(static fn(string $value): bool => mb_strlen($value) > 50);
        if ($tooLong) {
            return redirect()
                ->route('admin.users.no-matriks')
                ->withErrors(['no_matriks' => 'Each no_matriks value must be 50 characters or fewer.']);
        }

        $existing = NoMatriksEntry::query()
            ->whereIn('no_matriks', $entries->all())
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
            $entries->map(static fn(string $value): array => [
                'no_matriks' => $value,
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
            ->with('user:id,name,full_name')
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
                    'student' => $booking->user?->full_name ?: $booking->user?->name ?: 'Pelajar',
                    'request_date' => (string) $booking->booking_date,
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
            ->with('user:id,name,full_name')
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
            ->with('user:id,name,full_name')
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
                    s
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

        return view('booking', [
            'user' => $user,
            'role' => $role,
            'counsellors' => $counsellors,
            'bookingSlots' => $bookingSlots,
        ]);
    })->name('booking.index');
    Route::get('/booking-history', function (Request $request) {
        $user = $request->user();
        $role = $user?->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);

        $filters = $request->validate([
            'status' => ['nullable', 'in:all,pending,approved,rejected,completed'],
        ]);

        $selectedStatus = $filters['status'] ?? 'all';

        $statusLabel = static fn(string $status): string => match ($status) {
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'completed' => 'Completed',
            default => ucfirst($status),
        };

        $statusBadgeClass = static fn(string $status): string => match ($status) {
            'approved' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'rejected' => 'border-rose-200 bg-rose-50 text-rose-700',
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
        $selectedCounsellor->inboxNotifications()->create([
            'title' => 'New counselling request',
            'message' => ($user->full_name ?: $user->name ?: 'A student') . ' submitted a counselling request for ' . $validated['booking_date'] . ' (' . $validated['booking_time'] . ').',
        ]);

        return response()->json([
            'message' => 'Booking request submitted.',
        ]);
    })->name('booking.store');


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


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';
