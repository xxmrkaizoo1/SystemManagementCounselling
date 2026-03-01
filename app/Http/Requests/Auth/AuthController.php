<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class AuthController extends Controller
{
    private const DEFAULT_PROFILE_PIC = '/images/default-profile.svg';
    private const OTP_TTL_MINUTES = 10;
    private const SESSION_PENDING_SIGNUP = 'signup.pending';
    private const SESSION_PENDING_EMAIL = 'signup.otp_email';
    private const SESSION_PENDING_PHONE_OTP_USER_ID = 'login.phone_otp_user_id';

    public function showLogin(): View
    {
        return view('login');
    }

    public function showSignup(): View
    {
        return view('signup');
    }

    public function showOtpForm(Request $request): View|RedirectResponse
    {
        $pendingSignup = $request->session()->get(self::SESSION_PENDING_SIGNUP);

        if (! $pendingSignup) {
            return redirect()->route('signup')->withErrors([
                'email' => 'Please complete the signup form first.',
            ]);
        }

        return view('signup-otp', [
            'pendingEmail' => $request->session()->get(self::SESSION_PENDING_EMAIL),
            'profilePic' => $pendingSignup['profile_pic'] ?? self::DEFAULT_PROFILE_PIC,
        ]);
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
        /** @var User $user */
        $user = Auth::user();

        if (! $user->phone_verified_at) {
            Auth::logout();

            return $this->startPhoneOtpVerification($request, $user);
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

        $profilePicPath = $this->storeTemporaryProfilePicture($request->file('profile_pic'));
        $otp = random_int(100000, 999999);

        $pendingSignup = [
            'name' => $validated['full_name'],
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'years' => $validated['role'] === 'student' ? ($validated['years'] ?? null) : null,
            'programme' => $validated['role'] === 'student' ? ($validated['programme'] ?? null) : null,
            'profile_pic' => $profilePicPath,
        ];

        $request->session()->put(self::SESSION_PENDING_SIGNUP, $pendingSignup);
        $request->session()->put(self::SESSION_PENDING_EMAIL, $validated['email']);

        Cache::put(
            $this->otpCacheKey($validated['email']),
            ['otp' => $otp],
            now()->addMinutes(self::OTP_TTL_MINUTES)
        );

        try {
            $this->sendSignupOtpEmail($validated['email'], $otp);
        } catch (Throwable $exception) {
            $this->clearPendingSignup($request, $validated['email']);

            report($exception);

            return back()
                ->withErrors(['email' => 'Unable to send OTP email right now. Please check mail settings and try again.'])
                ->withInput($request->except(['password', 'password_confirmation', 'profile_pic']));
        }
        return redirect()->route('signup.otp.form')->with('status', 'OTP sent to your email. Please verify to complete signup.');
    }

    public function verifySignupOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'integer', 'digits:6'],
        ]);

        $pendingSignup = $request->session()->get(self::SESSION_PENDING_SIGNUP);
        $pendingEmail = $request->session()->get(self::SESSION_PENDING_EMAIL);

        if (! $pendingSignup || ! $pendingEmail) {
            return redirect()->route('signup')->withErrors([
                'email' => 'Your signup session expired. Please register again.',
            ]);
        }

        if (User::where('email', $pendingEmail)->exists()) {
            $this->clearPendingSignup($request, $pendingEmail);

            return redirect()->route('signup')->withErrors([
                'email' => 'This email is already registered. Please use another email.',
            ]);
        }

        $cachedOtp = Cache::get($this->otpCacheKey($pendingEmail));

        if (! $cachedOtp) {
            return back()->withErrors([
                'otp' => 'OTP expired. Please resend a new code.',
            ]);
        }

        if ((int) $cachedOtp['otp'] !== (int) $request->integer('otp')) {
            return back()->withErrors([
                'otp' => 'Invalid OTP code. Please try again.',
            ])->withInput();
        }

        $profilePicPath = $this->promoteTemporaryProfilePicture($pendingSignup['profile_pic'] ?? null);

        $user = User::create([
            'name' => $pendingSignup['name'],
            'full_name' => $pendingSignup['full_name'],
            'phone' => $pendingSignup['phone'],
            'email' => $pendingSignup['email'],
            'password' => $pendingSignup['password'],
            'years' => $pendingSignup['years'],
            'programme' => $pendingSignup['programme'],
            'profile_pic' => $profilePicPath,
        ]);

        $role = Role::firstOrCreate(
            ['name' => $pendingSignup['role']],
            ['description' => ucfirst($pendingSignup['role']) . ' role']
        );

        $user->roles()->attach($role->id, ['assigned_at' => now()]);

        $this->clearPendingSignup($request, $pendingEmail);

        return redirect()->route('login')->with('status', 'Account created successfully. Please sign in and verify your phone via SMS OTP.');
    }


    public function showPhoneOtpForm(Request $request): View|RedirectResponse
    {
        $userId = $request->session()->get(self::SESSION_PENDING_PHONE_OTP_USER_ID);

        if (! $userId) {
            return redirect()->route('login')->withErrors([
                'email' => 'Please sign in first to continue phone verification.',
            ]);
        }

        $user = User::find($userId);

        if (! $user) {
            $request->session()->forget(self::SESSION_PENDING_PHONE_OTP_USER_ID);

            return redirect()->route('signup')->withErrors([
                'email' => 'Account not found. Please register again.',
            ]);
        }

        if (! Cache::has($this->phoneOtpCacheKey((int) $user->id))) {
            $this->deleteUnverifiedPhoneAccount($user, $request);

            return redirect()->route('signup')->withErrors([
                'email' => 'Phone OTP expired. Your unverified account has been deleted. Please sign up again.',
            ]);
        }

        return view('phone-otp', [
            'maskedPhone' => $this->maskPhone($user->phone),
        ]);
    }

    public function verifyPhoneOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'integer', 'digits:6'],
        ]);

        $userId = (int) $request->session()->get(self::SESSION_PENDING_PHONE_OTP_USER_ID);
        $user = User::find($userId);

        if (! $user) {
            $request->session()->forget(self::SESSION_PENDING_PHONE_OTP_USER_ID);

            return redirect()->route('signup')->withErrors([
                'email' => 'Account not found. Please register again.',
            ]);
        }

        $cachedOtp = Cache::get($this->phoneOtpCacheKey((int) $user->id));

        if (! $cachedOtp) {
            $this->deleteUnverifiedPhoneAccount($user, $request);

            return redirect()->route('signup')->withErrors([
                'email' => 'Phone OTP expired. Your unverified account has been deleted. Please sign up again.',
            ]);
        }

        if ((int) ($cachedOtp['otp'] ?? 0) !== (int) $request->integer('otp')) {
            return back()->withErrors([
                'otp' => 'Invalid SMS OTP code. Please try again.',
            ])->withInput();
        }

        $user->phone_verified_at = now();
        $user->save();

        Cache::forget($this->phoneOtpCacheKey((int) $user->id));
        $request->session()->forget(self::SESSION_PENDING_PHONE_OTP_USER_ID);






        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('status', 'Phone verified successfully. Welcome back!');
    }

    public function resendPhoneOtp(Request $request): RedirectResponse
    {
        $userId = (int) $request->session()->get(self::SESSION_PENDING_PHONE_OTP_USER_ID);
        $user = User::find($userId);

        if (! $user) {
            $request->session()->forget(self::SESSION_PENDING_PHONE_OTP_USER_ID);

            return redirect()->route('signup')->withErrors([
                'email' => 'Account not found. Please register again.',
            ]);
        }

        return $this->startPhoneOtpVerification($request, $user, true);
    }

    public function resendSignupOtp(Request $request): RedirectResponse
    {
        $pendingEmail = $request->session()->get(self::SESSION_PENDING_EMAIL);
        $pendingSignup = $request->session()->get(self::SESSION_PENDING_SIGNUP);

        if (! $pendingEmail || ! $pendingSignup) {
            return redirect()->route('signup')->withErrors([
                'email' => 'Signup session expired. Please register again.',
            ]);
        }

        $otp = random_int(100000, 999999);

        Cache::put(
            $this->otpCacheKey($pendingEmail),
            ['otp' => $otp],
            now()->addMinutes(self::OTP_TTL_MINUTES)
        );

        try {
            $this->sendSignupOtpEmail($pendingEmail, $otp);
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'otp' => 'Unable to resend OTP right now. Please try again in a moment.',
            ]);
        }
        return back()->with('status', 'A new OTP has been sent to your email.');
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

    public function showEditProfile(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $role = $user->roles()->value('name');

        return view('edit-profile', [
            'user' => $user,
            'role' => $role,
        ]);
    }

    public function updateProfileInfo(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $role = $user->roles()->value('name');
        $isStudent = $role === 'student';

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'years' => [Rule::requiredIf($isStudent), 'nullable', 'string', 'max:50'],
            'programme' => [Rule::requiredIf($isStudent), 'nullable', 'string', 'max:50'],
        ]);

        $user->full_name = $validated['full_name'];
        $user->name = $validated['full_name'];
        $user->phone = $validated['phone'];
        $user->years = $isStudent ? ($validated['years'] ?? null) : null;
        $user->programme = $isStudent ? ($validated['programme'] ?? null) : null;

        $user->save();

        return back()->with('status', 'Profile updated successfully.');
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

        $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
        $file->move($uploadDir, $filename);

        return '/uploads/profile_pics/' . $filename;
    }

    private function storeTemporaryProfilePicture(?UploadedFile $file): string
    {
        if (! $file) {
            return self::DEFAULT_PROFILE_PIC;
        }

        $tempUploadDir = public_path('uploads/profile_pics/temp');

        if (! is_dir($tempUploadDir)) {
            mkdir($tempUploadDir, 0755, true);
        }

        $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
        $file->move($tempUploadDir, $filename);

        return '/uploads/profile_pics/temp/' . $filename;
    }

    private function promoteTemporaryProfilePicture(?string $path): string
    {
        if (! $path || $path === self::DEFAULT_PROFILE_PIC) {
            return self::DEFAULT_PROFILE_PIC;
        }

        if (! str_starts_with($path, '/uploads/profile_pics/temp/')) {
            return $path;
        }

        $source = public_path(ltrim($path, '/'));

        if (! file_exists($source)) {
            return self::DEFAULT_PROFILE_PIC;
        }

        $finalUploadDir = public_path('uploads/profile_pics');

        if (! is_dir($finalUploadDir)) {
            mkdir($finalUploadDir, 0755, true);
        }

        $extension = pathinfo($source, PATHINFO_EXTENSION);
        $newFilename = Str::uuid()->toString() . ($extension ? '.' . $extension : '');
        $destination = $finalUploadDir . DIRECTORY_SEPARATOR . $newFilename;

        rename($source, $destination);

        return '/uploads/profile_pics/' . $newFilename;
    }

    private function sendSignupOtpEmail(string $email, int $otp): void
    {
        Mail::mailer(config('mail.default', 'failover'))->raw(
            "Your CollegeCare OTP code is: {$otp}. This code expires in " . self::OTP_TTL_MINUTES . ' minutes.',
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('CollegeCare Signup OTP Verification');
            }
        );
    }
    private function startPhoneOtpVerification(Request $request, User $user, bool $isResend = false): RedirectResponse
    {
        $otp = random_int(100000, 999999);

        Cache::put(
            $this->phoneOtpCacheKey((int) $user->id),
            ['otp' => $otp],
            now()->addMinutes(self::OTP_TTL_MINUTES)
        );

        try {
            $this->sendPhoneOtpSms($user->phone, $otp);
        } catch (Throwable $exception) {
            report($exception);
            $this->deleteUnverifiedPhoneAccount($user, $request);

            return redirect()->route('signup')->withErrors([
                'phone' => 'Unable to send SMS OTP. Your unverified account has been deleted. Please register again.',
            ]);
        }

        $request->session()->put(self::SESSION_PENDING_PHONE_OTP_USER_ID, (int) $user->id);

        $message = $isResend
            ? 'A new SMS OTP has been sent to your phone.'
            : 'SMS OTP sent to your phone. Verify to activate your account.';

        return redirect()->route('phone.otp.form')->with('status', $message);
    }

    private function sendPhoneOtpSms(string $phone, int $otp): void
    {
        Log::info('Phone OTP generated for verification.', [
            'phone' => $phone,
            'otp' => $otp,
        ]);
    }

    private function phoneOtpCacheKey(int $userId): string
    {
        return 'phone_login_otp_' . $userId;
    }

    private function deleteUnverifiedPhoneAccount(User $user, Request $request): void
    {
        if (! $user->phone_verified_at) {
            $user->delete();
        }

        Cache::forget($this->phoneOtpCacheKey((int) $user->id));
        $request->session()->forget(self::SESSION_PENDING_PHONE_OTP_USER_ID);
        Auth::logout();
    }

    private function maskPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (strlen($digits) <= 4) {
            return $phone;
        }

        return str_repeat('*', max(strlen($digits) - 4, 0)) . substr($digits, -4);
    }
    private function otpCacheKey(string $email): string
    {
        return 'signup_otp_' . sha1(strtolower($email));
    }

    private function clearPendingSignup(Request $request, string $email): void
    {
        $request->session()->forget([self::SESSION_PENDING_SIGNUP, self::SESSION_PENDING_EMAIL]);
        Cache::forget($this->otpCacheKey($email));
    }
}
