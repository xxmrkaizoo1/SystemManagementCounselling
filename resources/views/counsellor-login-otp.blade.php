<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Counsellor Login OTP • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-sky-100 via-slate-100 to-indigo-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-3xl bg-white/95 shadow-2xl border border-white/70 p-6 sm:p-8">
        <h1 class="text-2xl font-bold text-slate-900 text-center">First Login Verification</h1>
        <p class="mt-2 text-sm text-slate-600 text-center">
            Enter the 6-digit OTP sent to <span class="font-semibold text-slate-800">{{ $email }}</span>.
        </p>

        @if (session('status'))
            <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('counsellor.login.otp.verify') }}" class="mt-5 space-y-3">
            @csrf
            <label for="otp" class="block text-sm font-medium text-slate-700">Email OTP</label>
            <input id="otp" name="otp" type="text" inputmode="numeric" maxlength="6" value="{{ old('otp') }}"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-200"
                placeholder="Enter 6-digit OTP" />
            @error('otp')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
            @error('email')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror

            <button type="submit"
                class="w-full rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:from-sky-700 hover:to-indigo-700">
                Verify OTP
            </button>
        </form>

        <form method="POST" action="{{ route('counsellor.login.otp.resend') }}" class="mt-3">
            @csrf
            <button type="submit"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                Resend OTP
            </button>
        </form>

        <a href="{{ route('login') }}" class="mt-4 inline-block text-sm text-sky-700 hover:text-sky-800">Back to login</a>
    </div>
</body>

</html>
