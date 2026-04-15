<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\CounsellorController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Models\BookingRequest;
use App\Models\ChatMessage;
use App\Models\InboxNotification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    return view('index');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register'])->name('signup.store');

    Route::get('/signup/otp', [AuthController::class, 'showOtpForm'])->name('signup.otp.form');
    Route::post('/signup/otp/verify', [AuthController::class, 'verifySignupOtp'])->name('signup.otp.verify');
    Route::post('/signup/otp/resend', [AuthController::class, 'resendSignupOtp'])->name('signup.otp.resend');
});

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

        $counsellors = [
            ['name' => 'Dr. Aina', 'available' => true, 'next_slot' => '10:30 AM'],
            ['name' => 'Mr. Hakim', 'available' => false, 'next_slot' => '2:00 PM'],
            ['name' => 'Ms. Farah', 'available' => true, 'next_slot' => '11:15 AM'],
            ['name' => 'Dr. Daniel', 'available' => false, 'next_slot' => '3:30 PM'],
        ];

        return view('home', [
            'user' => $user,
            'role' => $role,
            'announcements' => $announcements,
            'counsellors' => $counsellors,
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
                    'topic' => $booking->note,
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

        $counsellorNames = array_values(array_filter([
            $user->full_name,
            $user->name,
        ]));
        $normalizedCounsellorNames = array_values(array_unique(array_map(
            static fn(string $name): string => mb_strtolower(trim($name)),
            $counsellorNames
        )));

        $pendingRequests = BookingRequest::query()
            ->with('user:id,name,full_name')
            ->whereIn(DB::raw('LOWER(TRIM(counsellor_name))'), $normalizedCounsellorNames)
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
                    'topic' => $booking->note,
                    'status' => $booking->status,
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

        $counsellorNames = array_values(array_filter([
            $user?->full_name,
            $user?->name,
        ]));
        $normalizedCounsellorNames = array_values(array_unique(array_map(
            static fn(string $name): string => mb_strtolower(trim($name)),
            $counsellorNames
        )));
        abort_unless(
            in_array(mb_strtolower(trim($bookingRequest->counsellor_name)), $normalizedCounsellorNames, true),
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

        $counsellorNames = array_values(array_filter([
            $user->full_name,
            $user->name,
        ]));

        $statusLabel = static fn(string $status): string => match ($status) {
            'approved' => 'Approved',
            'completed' => 'Completed',
            default => 'Booked',
        };

        $sessions = BookingRequest::query()
            ->with('user:id,name,full_name')
                        ->whereIn(DB::raw('LOWER(TRIM(counsellor_name))'), $normalizedCounsellorNames)
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

    Route::post('/booking', function (Request $request) {
        $user = $request->user();
        $role = $user?->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);

        $validated = $request->validate([
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'string', 'max:50'],
            'counsellor_name' => ['required', 'string', 'max:255'],
            'note' => ['required', 'string', 'max:500'],
        ]);

        $isValidCounsellor = User::query()
            ->whereHas('roles', static fn($query) => $query->where('name', 'counsellor'))
            ->where(static function ($query) use ($validated): void {
                $query
                    ->where('name', $validated['counsellor_name'])
                    ->orWhere('full_name', $validated['counsellor_name']);
            })
            ->exists();

        if (! $isValidCounsellor) {
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

        BookingRequest::create([
            'user_id' => $user->id,
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'counsellor_name' => $validated['counsellor_name'],
            'note' => $validated['note'],
            'status' => 'pending',
        ]);

        $user->inboxNotifications()->create([
            'title' => 'Booking request sent',
            'message' => 'Your counselling request for ' . $validated['booking_date'] . ' (' . $validated['booking_time'] . ') with ' . $validated['counsellor_name'] . ' has been submitted.',
        ]);

        return response()->json([
            'message' => 'Booking request submitted.',
        ]);
    })->name('booking.store');


    Route::get('/inbox', function () {
        $user = request()->user();
        $role = $user?->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);

        return view('inbox', [
            'user' => $user,
            'role' => $role,
            'notifications' => $user->inboxNotifications()->latest()->get(),
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
