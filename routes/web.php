<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\CounsellorController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Models\ChatMessage;
use App\Models\InboxNotification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
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


    Route::get('/booking', function () {
        $user = request()->user();
        $role = $user?->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);

        $counsellors = [
            'Dr. Aina',
            'Mr. Hakim',
            'Ms. Farah',
            'Dr. Daniel',
        ];

        return view('booking', [
            'user' => $user,
            'role' => $role,
            'counsellors' => $counsellors,
        ]);
    })->name('booking.index');

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
