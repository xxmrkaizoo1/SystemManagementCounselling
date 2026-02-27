<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class AuthController extends Controller
{
    private const DEFAULT_PROFILE_PIC = '/images/default-profile.svg';
    public function showLogin(): View
    {
        return view('login');
    }

    public function showSignup(): View
    {
        return view('signup');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['student', 'teacher', 'counsellor'])],
            'years' => ['nullable', 'string', 'max:50'],
            'programme' => ['nullable', 'string', 'max:50'],
            'profile_pic' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if ($validated['role'] === 'student') {
            $request->validate([
                'years' => ['required', 'string', 'max:50'],
                'programme' => ['required', 'string', 'max:50'],
            ]);
        }
        $profilePicPath = $this->storeProfilePicture($request->file('profile_pic'));

        $user = User::create([
            'name' => $validated['full_name'],
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'years' => $validated['role'] === 'student' ? ($validated['years'] ?? null) : null,
            'programme' => $validated['role'] === 'student' ? ($validated['programme'] ?? null) : null,
            'profile_pic' => $profilePicPath,
        ]);

        $role = Role::firstOrCreate(
            ['name' => $validated['role']],
            ['description' => ucfirst($validated['role']) . ' role']
        );

        $user->roles()->attach($role->id, ['assigned_at' => now()]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('status', 'Account created successfully.');
    }

    public function updateProfilePicture(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'profile_pic' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $user->profile_pic = $this->storeProfilePicture($validated['profile_pic']);
        $user->save();

        return back()->with('status', 'Profile picture updated successfully.');
    }
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

       private function storeProfilePicture(?UploadedFile $file): string
    {
        if (! $file) {
            return self::DEFAULT_PROFILE_PIC;
        }

        $uploadDir = public_path('uploads/profile_pics');

        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $file->move($uploadDir, $filename);

        return '/uploads/profile_pics/'.$filename;
    }
}
