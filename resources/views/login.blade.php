<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
        <div class="absolute inset-0 bg-noise-layer opacity-15"></div>

        <div class="absolute -top-32 -left-24 w-[34rem] h-[34rem] bg-sky-300/40 rounded-full blur-3xl animate-blob-float"></div>
        <div class="absolute top-24 -right-32 w-[36rem] h-[36rem] bg-violet-300/35 rounded-full blur-3xl animate-aurora-drift animation-delay-2"></div>
        <div class="absolute -bottom-36 left-1/4 w-[32rem] h-[32rem] bg-emerald-300/30 rounded-full blur-3xl animate-blob-float animation-delay-4"></div>

        <div class="aurora-band aurora-band--one"></div>
        <div class="aurora-band aurora-band--two"></div>
    </div>

    <main class="min-h-screen flex items-center justify-center p-4 sm:p-8">
        <section class="w-full max-w-6xl rounded-[2rem] border border-slate-200/80 bg-white/75 backdrop-blur-xl shadow-2xl overflow-hidden">
            <div class="grid lg:grid-cols-[1.35fr_1fr] gap-0">
                <div class="p-6 sm:p-8 lg:p-10 border-b lg:border-b-0 lg:border-r border-slate-200/80">
                    <div class="flex items-center justify-between gap-3">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">
                            <span>←</span>
                            <span>Back</span>
                        </a>
                        <span class="text-xs px-3 py-1 rounded-full bg-sky-100 text-sky-700 font-medium">Secure Access</span>
                    </div>

                    <div class="mt-6 rounded-2xl border border-slate-200 bg-white/80 p-5">
                        <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                        <h1 class="mt-2 text-2xl sm:text-3xl font-extrabold text-slate-800">Welcome back</h1>
                        <p class="mt-2 text-sm text-slate-600">Sign in to manage counselling sessions, monitor live slots, and view updates.</p>
                    </div>

                    <div class="mt-6 grid sm:grid-cols-3 gap-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs text-slate-500">Active Queue</p>
                            <p class="text-lg font-bold text-slate-800">8</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs text-slate-500">Available Slots</p>
                            <p class="text-lg font-bold text-slate-800">12</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs text-slate-500">Status</p>
                            <p class="text-lg font-bold text-emerald-700">Online</p>
                        </div>
                    </div>

                    <div class="mt-6 rounded-3xl border border-slate-200 bg-gradient-to-br from-white via-sky-50 to-indigo-50 p-6 min-h-[15rem]">
                        <div class="grid sm:grid-cols-[130px_1fr] items-center gap-4 h-full">
                            <div class="mx-auto rounded-3xl border border-sky-100 bg-white/90 w-[120px] h-[140px] relative shadow-sm">
                                <div class="absolute top-5 left-1/2 -translate-x-1/2 w-12 h-12 rounded-full bg-amber-100 border border-amber-200"></div>
                                <div class="absolute top-3 left-1/2 -translate-x-1/2 w-14 h-5 rounded-t-full bg-slate-800"></div>
                                <div class="absolute top-[4.4rem] left-1/2 -translate-x-1/2 w-16 h-12 rounded-2xl bg-white border border-slate-200"></div>
                                <div class="absolute top-[4.8rem] left-[1.7rem] w-4 h-8 rounded-full border-2 border-slate-300 rotate-[25deg]"></div>
                                <div class="absolute top-[4.8rem] right-[1.7rem] w-4 h-8 rounded-full border-2 border-slate-300 -rotate-[25deg]"></div>
                                <div class="absolute top-[6.9rem] left-[2.9rem] w-4 h-9 rounded-full bg-slate-700"></div>
                                <div class="absolute top-[6.9rem] right-[2.9rem] w-4 h-9 rounded-full bg-slate-700"></div>
                                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 w-16 h-2.5 rounded-full bg-slate-300/70"></div>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-[0.14em] text-sky-700 font-semibold">Teacher support</p>
                                <p class="mt-2 text-sm text-slate-700 leading-relaxed">
                                    “Your class adviser can help with study stress, attendance concerns, and referrals. Don't wait to ask for help.”
                                </p>
                                <div class="mt-4 flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-2xl bg-sky-100 text-sky-700 grid place-items-center font-bold">T</div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Teacher On Duty</p>
                                        <p class="text-xs text-slate-500">Mon–Fri, 8:00 AM – 6:00 PM</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8 lg:p-10" x-data="{ showPassword: false }">
                    <div class="max-w-md mx-auto rounded-3xl border border-slate-200 bg-white p-6 sm:p-7 shadow-sm">
                        <p class="text-xs uppercase tracking-[0.14em] text-slate-500">Account login</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-800">Sign in</h2>
                        <p class="mt-2 text-sm text-slate-600">Use your college email and password.</p>

                        <form class="mt-6 space-y-4" action="#" method="POST">
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                                <input id="email" name="email" type="email" placeholder="name@college.edu"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                                <div class="relative">
                                    <input id="password" name="password" x-bind:type="showPassword ? 'text' : 'password'" placeholder="••••••••"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 pr-12 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                                    <button type="button" x-on:click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-2 my-auto h-8 px-2 rounded-lg text-xs font-medium text-slate-500 hover:text-sky-700 hover:bg-sky-50 transition"
                                        x-text="showPassword ? 'Hide' : 'Show'"></button>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full rounded-xl bg-sky-600 text-white font-semibold py-2.5 hover:bg-sky-700 transition shadow-sm">
                                Log In
                            </button>
                        </form>

                        <div class="mt-4 text-sm text-center space-y-2">
                            <a href="#" class="text-sky-700 hover:text-sky-800 font-medium">Forgot password?</a>
                            <p class="text-slate-500">Don’t have an account?
                                <a href="#" class="text-indigo-700 hover:text-indigo-800 font-medium">Create one</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
