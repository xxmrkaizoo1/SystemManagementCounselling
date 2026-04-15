<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]"></div>
        <div class="absolute -top-32 -left-24 w-[34rem] h-[34rem] bg-sky-300/40 rounded-full blur-3xl"></div>
        <div class="absolute top-24 -right-32 w-[36rem] h-[36rem] bg-violet-300/35 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-36 left-1/4 w-[32rem] h-[32rem] bg-emerald-300/30 rounded-full blur-3xl"></div>
    </div>

    <main class="min-h-screen flex items-center justify-center p-4 sm:p-8">
        <section class="w-full max-w-5xl rounded-[2rem] border border-slate-200/80 bg-white/75 backdrop-blur-xl shadow-2xl overflow-hidden">
            <div class="grid lg:grid-cols-[1.2fr_1fr] gap-0">
                <div class="p-6 sm:p-8 lg:p-10 border-b lg:border-b-0 lg:border-r border-slate-200/80">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">
                        <span>←</span>
                        <span>Back to login</span>
                    </a>

                    <div class="mt-6 rounded-2xl border border-slate-200 bg-white/80 p-6">
                        <p class="text-xs uppercase tracking-[0.14em] text-sky-700 font-semibold">Account Recovery</p>
                        <h1 class="mt-2 text-3xl font-extrabold text-slate-800">Forgot your password?</h1>
                        <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                            No worries. Enter your email and we’ll send a secure reset link so you can create a new password.
                        </p>
                    </div>

                    <div class="mt-6 grid sm:grid-cols-3 gap-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs text-slate-500">Reset Method</p>
                            <p class="text-base font-bold text-slate-800">Email Link</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs text-slate-500">Delivery Time</p>
                            <p class="text-base font-bold text-slate-800">1-2 Minutes</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs text-slate-500">Support</p>
                            <p class="text-base font-bold text-emerald-700">Available</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8 lg:p-10">
                    <div class="max-w-md mx-auto rounded-3xl border border-slate-200 bg-white p-6 sm:p-7 shadow-sm">
                        <p class="text-xs uppercase tracking-[0.14em] text-slate-500">Secure Access</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-800">Send reset link</h2>
                        <p class="mt-2 text-sm text-slate-600">Use the same email you use to sign in.</p>

                        @if (session('status'))
                            <div
                                class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-700">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
                            @csrf
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                    placeholder="name@college.edu"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition">
                                @error('email')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="w-full rounded-xl bg-sky-600 text-white font-semibold py-2.5 hover:bg-sky-700 transition shadow-sm">
                                Email Password Reset Link
                            </button>

                            <p class="text-center text-sm text-slate-500">
                                Remembered your password?
                                <a href="{{ route('login') }}" class="font-medium text-sky-700 hover:text-sky-800">Back to login</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
