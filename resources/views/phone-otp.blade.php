<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Phone OTP Verification • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
        <div class="absolute inset-0 bg-noise-layer opacity-15"></div>
    </div>

    <main class="min-h-screen p-4 sm:p-8 grid place-items-center">
        <section class="w-full max-w-md rounded-3xl border border-slate-200/80 bg-white/90 shadow-2xl p-6 sm:p-8 space-y-4">
            <div>
                <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                <h1 class="text-2xl font-bold text-slate-800 mt-1">Verify Phone Number</h1>
                <p class="mt-2 text-sm text-slate-600">We sent an SMS OTP to <span class="font-semibold">{{ $maskedPhone }}</span>.</p>
            </div>

            @if (session('status'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('phone.otp.verify') }}" class="space-y-3">
                @csrf
                <label for="otp" class="block text-sm font-medium text-slate-700">SMS OTP</label>
                <input id="otp" name="otp" type="text" inputmode="numeric" maxlength="6" value="{{ old('otp') }}" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" placeholder="Enter 6-digit OTP" />

                <button type="submit" class="w-full rounded-xl bg-sky-600 text-white font-semibold py-2.5 hover:bg-sky-700 transition shadow-sm">Verify OTP</button>
            </form>

            <form method="POST" action="{{ route('phone.otp.resend') }}">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-slate-200 bg-white py-2.5 text-sm font-medium text-slate-700 hover:border-sky-200 hover:text-sky-700 transition">Resend OTP</button>
            </form>
        </section>
    </main>
</body>

</html>
