<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NoMatriksEntry;
use App\Models\Role;
use App\Models\User;
use App\Services\PhoneOtpTelegramSender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
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
    private const PHONE_OTP_CACHE_PREFIX = 'phone_otp_';

    private const COUNSELLOR_LOGIN_OTP_CACHE_PREFIX = 'counsellor_login_otp_';
    private const SESSION_PENDING_COUNSELLOR_LOGIN_USER_ID = 'counsellor_login.user_id';

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

        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->onlyInput('email');
        }
        $isCounsellor = $user->roles()->where('name', 'counsellor')->exists();
        $requiresFirstLoginOtp = $isCounsellor && ! $user->counsellor_first_login_verified_at;

        if ($requiresFirstLoginOtp) {
            $otp = random_int(100000, 999999);

            Cache::put(
                $this->counsellorLoginOtpCacheKey($user->id),
                ['otp' => $otp],
                now()->addMinutes(self::OTP_TTL_MINUTES)
            );

            try {
                $this->sendCounsellorLoginOtpEmail($user->email, $otp);
            } catch (Throwable $exception) {
                Cache::forget($this->counsellorLoginOtpCacheKey($user->id));
                report($exception);

                return back()
                    ->withErrors(['email' => 'Unable to send login OTP right now. Please check mail settings and try again.'])
                    ->onlyInput('email');
            }

            $request->session()->put(self::SESSION_PENDING_COUNSELLOR_LOGIN_USER_ID, $user->id);

            return redirect()
                ->route('counsellor.login.otp.form')
                ->with('status', 'OTP sent to your email. Please verify to continue your first login.');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function showCounsellorLoginOtpForm(Request $request): View|RedirectResponse
    {
        $userId = $request->session()->get(self::SESSION_PENDING_COUNSELLOR_LOGIN_USER_ID);

        if (! $userId) {
            return redirect()->route('login')->withErrors([
                'email' => 'Please login first to verify OTP.',
            ]);
        }

        $user = User::query()->find($userId);

        if (! $user) {
            $request->session()->forget(self::SESSION_PENDING_COUNSELLOR_LOGIN_USER_ID);

            return redirect()->route('login')->withErrors([
                'email' => 'Account not found. Please login again.',
            ]);
        }

        return view('counsellor-login-otp', [
            'email' => $user->email,
        ]);
    }

    public function verifyCounsellorLoginOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'integer', 'digits:6'],
        ]);

        $userId = $request->session()->get(self::SESSION_PENDING_COUNSELLOR_LOGIN_USER_ID);

        if (! $userId) {
            return redirect()->route('login')->withErrors([
                'email' => 'Login session expired. Please login again.',
            ]);
        }

        $user = User::query()->find($userId);

        if (! $user) {
            $request->session()->forget(self::SESSION_PENDING_COUNSELLOR_LOGIN_USER_ID);

            return redirect()->route('login')->withErrors([
                'email' => 'Account not found. Please login again.',
            ]);
        }

        $cachedOtp = Cache::get($this->counsellorLoginOtpCacheKey($user->id));

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

        $user->counsellor_first_login_verified_at = now();
        $user->save();

        Cache::forget($this->counsellorLoginOtpCacheKey($user->id));
        $request->session()->forget(self::SESSION_PENDING_COUNSELLOR_LOGIN_USER_ID);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }
    public function resendCounsellorLoginOtp(Request $request): RedirectResponse
    {
        $userId = $request->session()->get(self::SESSION_PENDING_COUNSELLOR_LOGIN_USER_ID);

        if (! $userId) {
            return redirect()->route('login')->withErrors([
                'email' => 'Login session expired. Please login again.',
            ]);
        }

        $user = User::query()->find($userId);

        if (! $user) {
            $request->session()->forget(self::SESSION_PENDING_COUNSELLOR_LOGIN_USER_ID);

            return redirect()->route('login')->withErrors([
                'email' => 'Account not found. Please login again.',
            ]);
        }

        $otp = random_int(100000, 999999);

        Cache::put(
            $this->counsellorLoginOtpCacheKey($user->id),
            ['otp' => $otp],
            now()->addMinutes(self::OTP_TTL_MINUTES)
        );

        try {
            $this->sendCounsellorLoginOtpEmail($user->email, $otp);
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'otp' => 'Unable to resend OTP right now. Please try again in a moment.',
            ]);
        }

        return back()->with('status', 'A new login OTP has been sent to your email.');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['student', 'teacher'])],
            'no_matriks' => ['nullable', 'string', 'max:50'],
            'years' => ['nullable', 'string', 'max:50'],
            'programme' => ['nullable', 'string', 'max:50'],
            'profile_pic' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'lecturer_access_code' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if ($validated['role'] === 'student') {
            $request->validate(
                [
                    'years' => ['required', 'string', 'max:50'],
                    'programme' => ['required', 'string', 'max:50'],
                    'no_matriks' => [
                        'required',
                        'string',
                        'max:50',
                        'unique:users,no_matriks',
                        static function (string $attribute, mixed $value, \Closure $fail): void {
                            $noMatriks = trim((string) $value);

                            if (! NoMatriksEntry::query()->where('no_matriks', $noMatriks)->exists()) {
                                $fail('No matriks is not registered by admin.');
                            }
                        },
                    ],
                ],
                [
                    'no_matriks.unique' => 'No matriks already used.',
                ]
            );
        }
        if ($validated['role'] === 'teacher') {
            $request->validate([
                'lecturer_access_code' => [
                    'required',
                    'string',
                    static function (string $attribute, mixed $value, \Closure $fail): void {
                        $expectedCode = (string) config('auth_signup.teacher_access_code', '');

                        if ($expectedCode === '' || ! hash_equals($expectedCode, (string) $value)) {
                            $fail('Invalid lecturer access code.');
                        }
                    },
                ],
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
            'no_matriks' => $validated['role'] === 'student' ? trim((string) ($validated['no_matriks'] ?? '')) : null,
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

        if (($pendingSignup['role'] ?? null) === 'student') {
            $noMatriks = trim((string) ($pendingSignup['no_matriks'] ?? ''));

            if ($noMatriks === '' || ! NoMatriksEntry::query()->where('no_matriks', $noMatriks)->exists()) {
                $this->clearPendingSignup($request, $pendingEmail);

                return redirect()->route('signup')->withErrors([
                    'no_matriks' => 'No matriks is not registered by admin.',
                ]);
            }

            if (User::query()->where('no_matriks', $noMatriks)->exists()) {
                $this->clearPendingSignup($request, $pendingEmail);

                return redirect()->route('signup')->withErrors([
                    'no_matriks' => 'No matriks already used.',
                ]);
            }
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
            'no_matriks' => $pendingSignup['no_matriks'] ?? null,
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

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('status', 'Account created successfully.');
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

    public function showPhoneOtpForm(Request $request): View|RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->phone_verified_at) {
            return redirect()->route('profile.edit')->with('status', 'Your phone number is already verified.');
        }

        $otpPayload = Cache::get($this->phoneOtpCacheKey($user->id));

        if (! $otpPayload) {
            $deliveryStatus = $this->issuePhoneOtp($user);

            if ($deliveryStatus === 'inbox') {
                session()->flash('status', 'Telegram service is unavailable right now. OTP was sent to your inbox notifications as a fallback. Check Telegram OTP configuration.');
            }
        }

        return view('phone-otp', [
            'maskedPhone' => $this->maskPhone($user->phone),
            'deliveryChannel' => $otpPayload['channel'] ?? 'telegram',
        ]);
    }

    public function verifyPhoneOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'integer', 'digits:6'],
        ]);

        /** @var User $user */
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $otpPayload = Cache::get($this->phoneOtpCacheKey($user->id));

        if (! $otpPayload) {
            return back()->withErrors([
                'otp' => 'OTP expired. Please resend a new code.',
            ]);
        }

        if ((int) $otpPayload['otp'] !== (int) $request->integer('otp')) {
            return back()->withErrors([
                'otp' => 'Invalid OTP code. Please try again.',
            ])->withInput();
        }

        $user->phone_verified_at = now();
        $user->save();

        Cache::forget($this->phoneOtpCacheKey($user->id));

        return redirect()->route('profile.edit')->with('status', 'Phone number verified successfully.');
    }

    public function resendPhoneOtp(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->phone_verified_at) {
            return redirect()->route('profile.edit')->with('status', 'Your phone number is already verified.');
        }

        $deliveryStatus = $this->issuePhoneOtp($user);

        if ($deliveryStatus === 'telegram') {
            return back()->with('status', 'A new OTP has been sent to your Telegram verification channel.');
        }
        return back()->with('status', 'Telegram service is unavailable. OTP was sent to your inbox notifications as a fallback. Check Telegram OTP configuration.');
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

    private function otpCacheKey(string $email): string
    {
        return 'signup_otp_' . sha1(strtolower($email));
    }

    private function counsellorLoginOtpCacheKey(int $userId): string
    {
        return self::COUNSELLOR_LOGIN_OTP_CACHE_PREFIX . $userId;
    }

    private function sendCounsellorLoginOtpEmail(string $email, int $otp): void
    {
        Mail::mailer(config('mail.default', 'failover'))->raw(
            "Your CollegeCare counsellor first-login OTP is: {$otp}. This code expires in " . self::OTP_TTL_MINUTES . ' minutes.',
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('CollegeCare Counsellor First Login OTP');
            }
        );
    }

    private function clearPendingSignup(Request $request, string $email): void
    {
        $request->session()->forget([self::SESSION_PENDING_SIGNUP, self::SESSION_PENDING_EMAIL]);
        Cache::forget($this->otpCacheKey($email));
    }


    private function phoneOtpCacheKey(int $userId): string
    {
        return self::PHONE_OTP_CACHE_PREFIX . $userId;
    }

    private function storePhoneOtp(User $user, int $otp, string $channel): void
    {
        Cache::put(
            $this->phoneOtpCacheKey($user->id),
            ['otp' => $otp, 'channel' => $channel],
            now()->addMinutes(self::OTP_TTL_MINUTES)
        );
    }

    private function issuePhoneOtp(User $user): string
    {
        $otp = random_int(100000, 999999);

        try {
            $this->sendPhoneOtpTelegram($user->phone, $otp);
            $this->storePhoneOtp($user, $otp, 'telegram');
            return 'telegram';
        } catch (Throwable $exception) {
            report($exception);
            $this->storePhoneOtp($user, $otp, 'inbox');
            $user->inboxNotifications()->create([
                'title' => 'Phone OTP verification code',
                'message' => "Your phone verification OTP is {$otp}. This code expires in " . self::OTP_TTL_MINUTES . ' minutes.',
            ]);
            return 'inbox';
        }
    }

    private function sendPhoneOtpTelegram(?string $phone, int $otp): void
    {
        if (! $phone) {
            throw new \RuntimeException('User does not have a phone number configured.');
        }

        app(PhoneOtpTelegramSender::class)->send($phone, $otp, self::OTP_TTL_MINUTES);
    }

    private function maskPhone(?string $phone): string
    {
        if (! $phone) {
            return 'your registered number';
        }

        $visibleDigits = 3;
        $maskedLength = max(strlen($phone) - $visibleDigits, 0);

        return str_repeat('*', $maskedLength) . substr($phone, -$visibleDigits);
    }
}
