<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify OTP • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]"></div>
    </div>

    <main class="min-h-screen flex items-center justify-center p-4 sm:p-8">
        <section class="w-full max-w-3xl rounded-[2rem] border border-slate-200/80 bg-white/80 backdrop-blur-xl shadow-2xl overflow-hidden">
            <div class="p-5 sm:p-7 border-b border-slate-200/80 flex items-center justify-between gap-3 bg-white/80">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">OTP Verification</h1>
                </div>
                <a href="{{ route('signup') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">
                    <span>←</span>
                    <span>Back</span>
                </a>
            </div>

            <div class="p-6 sm:p-8">
                <div class="mx-auto max-w-2xl rounded-3xl border border-slate-200 bg-white/90 p-6 sm:p-8 shadow-sm">
                    @if (session('status'))
                        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm text-rose-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="flex flex-col items-center">
                        <img src="{{ $profilePic ?: '/images/default-profile.svg' }}" alt="Profile"
                            class="w-24 h-24 rounded-full border-2 border-sky-200 object-cover bg-sky-50" />
                        <p class="mt-3 text-sm text-slate-600 text-center">
                            Enter the 6-digit OTP sent to
                            <span class="font-semibold text-slate-800">{{ $pendingEmail }}</span>
                        </p>
                    </div>

                    <form class="mt-6" action="{{ route('signup.otp.verify') }}" method="POST">
                        @csrf
                        <label for="otp" class="block text-sm font-medium text-slate-700 mb-1.5 text-center">OTP code</label>
                        <input id="otp" name="otp" type="number" inputmode="numeric" min="100000" max="999999"
                            value="{{ old('otp') }}" placeholder="123456"
                            class="mx-auto block w-full max-w-xs rounded-xl border border-slate-300 px-3 py-2.5 text-center tracking-[0.35em] text-lg outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                        @error('otp')
                            <p class="mt-1 text-xs text-rose-600 text-center">{{ $message }}</p>
                        @enderror

                        <button type="submit"
                            class="mt-4 mx-auto block w-full max-w-xs rounded-xl bg-sky-600 text-white font-semibold py-2.5 hover:bg-sky-700 transition shadow-sm">
                            Verify
                        </button>
                    </form>

                    <form class="mt-3" action="{{ route('signup.otp.resend') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="mx-auto block w-full max-w-xs rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:border-sky-200 hover:text-sky-700 transition">
                            Don't receive code? Resend code
                        </button>
                    </form>
                </div>
            </div>

            <footer class="px-6 sm:px-8 py-4 border-t border-slate-200/80 text-center text-sm text-slate-500 bg-white/70">
                © {{ date('Y') }} CollegeCare • Counselling Booking System
            </footer>
        </section>
    </main>
</body>

</html>
