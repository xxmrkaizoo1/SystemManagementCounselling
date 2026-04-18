<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fade-up {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes glow {
            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.18);
            }

            50% {
                box-shadow: 0 0 0 8px rgba(14, 165, 233, 0.05);
            }
        }

        .animate-fade-up {
            animation: fade-up 0.55s ease-out both;
        }

        .animate-fade-up-delay {
            animation: fade-up 0.55s ease-out 0.1s both;
        }

        .field-shell {
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .field-shell:focus-within {
            transform: translateY(-1px);
            animation: glow 1.5s ease-in-out infinite;
        }
    </style>
</head>

<body class="min-h-screen overflow-x-hidden bg-slate-50 text-slate-700">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]"></div>
        <div class="absolute -top-28 -left-20 h-[30rem] w-[30rem] rounded-full bg-sky-300/35 blur-3xl"></div>
        <div class="absolute top-16 -right-24 h-[32rem] w-[32rem] rounded-full bg-violet-300/30 blur-3xl"></div>
        <div class="absolute -bottom-32 left-1/4 h-[30rem] w-[30rem] rounded-full bg-emerald-300/25 blur-3xl"></div>
    </div>

    <main class="flex min-h-screen items-center justify-center p-4 sm:p-8">
        <section
            class="w-full max-w-5xl overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/75 shadow-2xl backdrop-blur-xl animate-fade-up">
            <div class="grid lg:grid-cols-[1.1fr_1fr]">
                <aside class="border-b border-slate-200/80 p-6 sm:p-8 lg:border-b-0 lg:border-r lg:p-10 animate-fade-up-delay">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-sky-200 hover:text-sky-700">
                        <span>←</span>
                        <span>Back to login</span>
                    </a>

                    <div class="mt-6 rounded-2xl border border-slate-200 bg-white/80 p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-sky-700">Secure Access</p>
                        <h1 class="mt-2 text-3xl font-extrabold text-slate-800">Create a new password</h1>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">
                            Set a fresh password for your account. Use at least 8 characters with a mix of letters,
                            numbers, and symbols for stronger security.
                        </p>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs text-slate-500">Account</p>
                            <p class="mt-1 break-all text-sm font-semibold text-slate-800">{{ old('email', $request->email) }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs text-slate-500">Security tip</p>
                            <p class="mt-1 text-sm font-medium text-emerald-700">Avoid reusing an old password.</p>
                        </div>
                    </div>
                </aside>

                <div class="p-6 sm:p-8 lg:p-10 animate-fade-up-delay">
                    <div class="mx-auto max-w-md rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
                        <p class="text-xs uppercase tracking-[0.14em] text-slate-500">Password Reset</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-800">Finish account recovery</h2>
                        <p class="mt-2 text-sm text-slate-600">Confirm your email and choose your new password below.</p>

                        <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-4">
                            @csrf

                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div>
                                <div class="field-shell relative rounded-xl border border-slate-300 bg-white/90">
                                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9m19.5 0A2.25 2.25 0 0019.5 5.25h-15A2.25 2.25 0 002.25 7.5m19.5 0l-8.69 5.518a2.25 2.25 0 01-2.12 0L2.25 7.5" />
                                        </svg>
                                    </span>
                                    <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus
                                        autocomplete="username" placeholder=" "
                                        class="peer block w-full rounded-xl border-0 bg-transparent px-10 pb-2.5 pt-5 text-sm text-slate-800 placeholder:text-transparent focus:outline-none focus:ring-2 focus:ring-sky-500/30" />
                                    <label for="email"
                                        class="pointer-events-none absolute left-10 top-2 text-xs font-medium text-slate-500 transition-all duration-200 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-focus:top-2 peer-focus:translate-y-0 peer-focus:text-xs peer-focus:font-medium peer-focus:text-sky-700">
                                        Email
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <div class="field-shell relative rounded-xl border border-slate-300 bg-white/90">
                                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.875a4.5 4.5 0 10-9 0V10.5m-1.5 0h12a1.5 1.5 0 011.5 1.5v7.5a1.5 1.5 0 01-1.5 1.5h-12A1.5 1.5 0 014.5 19.5V12a1.5 1.5 0 011.5-1.5z" />
                                        </svg>
                                    </span>
                                    <x-text-input id="password" type="password" name="password" required autocomplete="new-password"
                                        placeholder=" "
                                        class="peer block w-full rounded-xl border-0 bg-transparent px-10 pb-2.5 pt-5 text-sm text-slate-800 placeholder:text-transparent focus:outline-none focus:ring-2 focus:ring-sky-500/30" />
                                    <label for="password"
                                        class="pointer-events-none absolute left-10 top-2 text-xs font-medium text-slate-500 transition-all duration-200 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-focus:top-2 peer-focus:translate-y-0 peer-focus:text-xs peer-focus:font-medium peer-focus:text-sky-700">
                                        Password
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <div class="field-shell relative rounded-xl border border-slate-300 bg-white/90">
                                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.875a4.5 4.5 0 10-9 0V10.5m-1.5 0h12a1.5 1.5 0 011.5 1.5v7.5a1.5 1.5 0 01-1.5 1.5h-12A1.5 1.5 0 014.5 19.5V12a1.5 1.5 0 011.5-1.5z" />
                                        </svg>
                                    </span>
                                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" required
                                        autocomplete="new-password" placeholder=" "
                                        class="peer block w-full rounded-xl border-0 bg-transparent px-10 pb-2.5 pt-5 text-sm text-slate-800 placeholder:text-transparent focus:outline-none focus:ring-2 focus:ring-sky-500/30" />
                                    <label for="password_confirmation"
                                        class="pointer-events-none absolute left-10 top-2 text-xs font-medium text-slate-500 transition-all duration-200 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-focus:top-2 peer-focus:translate-y-0 peer-focus:text-xs peer-focus:font-medium peer-focus:text-sky-700">
                                        Confirm Password
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <button type="submit"
                                class="mt-2 w-full rounded-xl bg-sky-600 py-2.5 font-semibold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-sky-700">
                                {{ __('Reset Password') }}
                            </button>

                            <p class="text-center text-sm text-slate-500">
                                Need to sign in instead?
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
