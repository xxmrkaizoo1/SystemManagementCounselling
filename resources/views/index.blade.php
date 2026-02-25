<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Counselling Booking</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex cursor-none flex-col bg-slate-50 text-slate-700 overflow-x-hidden">
    <!-- Custom Cursor -->
    <div id="cursor-dot" class="fixed top-0 left-0 w-3 h-3 bg-sky-600 rounded-full pointer-events-none z-50"></div>
    <div id="cursor-ring" class="fixed top-0 left-0 w-8 h-8 border border-sky-500 rounded-full pointer-events-none z-40">
    </div>
    <!-- LOADER -->
    <div id="loader" class="fixed inset-0 bg-sky-500 flex items-center justify-center z-50">
        <div id="circle" class="w-100 h-100 bg-white rounded-full flex items-center justify-center">
            <span id="logoText" class="text-sky-500 font-bold text-5xl">CollegeCare</span>
        </div>
    </div>

    <!-- modern layered background -->
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
        <div class="absolute inset-0 bg-noise-layer opacity-15"></div>

        <div
            class="absolute -top-32 -left-24 w-[34rem] h-[34rem] bg-sky-300/40 rounded-full blur-3xl animate-blob-float">
        </div>
        <div
            class="absolute top-24 -right-32 w-[36rem] h-[36rem] bg-violet-300/35 rounded-full blur-3xl animate-aurora-drift animation-delay-2">
        </div>
        <div
            class="absolute -bottom-36 left-1/4 w-[32rem] h-[32rem] bg-emerald-300/30 rounded-full blur-3xl animate-blob-float animation-delay-4">
        </div>

        <div class="absolute top-[18%] right-[10%] w-64 h-64 border border-sky-200/60 rounded-full animate-rotate-slow">
        </div>
        <div
            class="absolute bottom-[14%] left-[8%] w-40 h-40 border border-indigo-200/50 rounded-full animate-rotate-slow animation-delay-4">
        </div>

        <div class="aurora-band aurora-band--one"></div>
        <div class="aurora-band aurora-band--two"></div>
    </div>

    <!-- decorative moving characters -->
    <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden hidden md:block" aria-hidden="true">
        <div class="dummy-walker dummy-walker--one">
            <span class="dummy-head"></span>
            <span class="dummy-body"></span>
        </div>
        <div class="dummy-walker dummy-walker--two">
            <span class="dummy-head"></span>
            <span class="dummy-body"></span>
        </div>
        <div class="dummy-walker dummy-walker--three">
            <span class="dummy-head"></span>
            <span class="dummy-body"></span>
        </div>
    </div>

    <div id="content" class="opacity-0 translate-y-2 min-h-screen flex flex-col">
        <!-- HEADER -->
        <header class="shrink-0 sticky top-0 z-40 bg-white/70 backdrop-blur-xl border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-sky-600 shadow-sm"></div>
                    <div>
                        <p class="font-bold leading-tight">CollegeCare</p>
                        <p class="text-xs text-slate-500 -mt-0.5">Counselling Booking System</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="#roles"
                        class="hidden sm:inline text-sm font-medium text-slate-600 hover:text-sky-600 transition">
                        Get Started
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 rounded-xl bg-sky-600 text-white text-sm font-semibold shadow-sm hover:bg-sky-700 transition">
                        Login
                    </a>
                </div>
            </div>
        </header>

        <!-- MAIN -->
        <main class="flex-1 flex items-center justify-center py-14">
            <div class="w-full max-w-7xl mx-auto px-6">
                <section class="grid lg:grid-cols-2 gap-10 items-center">
                    <!-- LEFT -->
                    <div class="text-center lg:text-left animate-fade-in-up">
                        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-800">
                            Book counselling sessions easily,<br class="hidden sm:block" />
                            calmly, and securely.
                        </h1>
                        <p class="mt-4 text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                            Students and teachers can book sessions, counsellors manage schedules, and admins track
                            reports
                            — all in one place.
                        </p>

                        <div class="mt-8 grid grid-cols-3 gap-3 max-w-md mx-auto lg:mx-0">
                            <div class="rounded-2xl bg-white/70 border border-slate-200 p-4 shadow-sm">
                                <p class="text-xs text-slate-500">Today</p>
                                <p class="text-lg font-bold text-slate-800">Open</p>
                            </div>
                            <div class="rounded-2xl bg-white/70 border border-slate-200 p-4 shadow-sm">
                                <p class="text-xs text-slate-500">Slots</p>
                                <p class="text-lg font-bold text-slate-800">12</p>
                            </div>
                            <div class="rounded-2xl bg-white/70 border border-slate-200 p-4 shadow-sm">
                                <p class="text-xs text-slate-500">Support</p>
                                <p class="text-lg font-bold text-slate-800">Fast</p>
                            </div>
                        </div>

                        <div class="doctor-tips-card mt-8 mx-auto lg:mx-0" id="doctorTipsCard">
                            <div class="doctor-scene" aria-hidden="true">
                                <div class="doctor-avatar">
                                    <span class="doc-shadow"></span>
                                    <span class="doc-head"></span>
                                    <span class="doc-hair"></span>
                                    <span class="doc-eye doc-eye--left"></span>
                                    <span class="doc-eye doc-eye--right"></span>
                                    <span class="doc-body"></span>
                                    <span class="doc-arm doc-arm--left"></span>
                                    <span class="doc-arm doc-arm--right"></span>
                                    <span class="doc-leg doc-leg--left"></span>
                                    <span class="doc-leg doc-leg--right"></span>
                                    <span class="doc-clipboard"></span>
                                </div>
                            </div>

                            <div class="doctor-dialog">
                                <p class="text-xs font-semibold uppercase tracking-wide text-sky-700">Doctor Tip</p>
                                <p id="doctorTipText" class="mt-1 text-sm text-slate-700 leading-relaxed">
                                    Take one slow breath before opening your class portal to calm your body first.
                                </p>
                                <p class="mt-2 text-[11px] text-slate-500">Auto tips </p>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div
                        class="mx-auto w-full max-w-xl rounded-3xl bg-white/70 border border-slate-200 shadow-sm p-6 sm:p-8 backdrop-blur-xl animate-fade-in-up animation-delay-2">
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-slate-800">Quick Access</p>
                            <span
                                class="text-xs px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Safe &amp; Private
                            </span>
                        </div>

                        <p class="mt-2 text-sm text-slate-600">Choose your role to continue.</p>

                        <div id="roles" class="mt-6 grid gap-4">
                            <a href="#"
                                class="group rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md transition hover:-translate-y-0.5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold">
                                            S</div>
                                        <div>
                                            <p class="font-semibold text-slate-800">Student</p>
                                            <p class="text-sm text-slate-500">Book &amp; track sessions</p>
                                        </div>
                                    </div>
                                    <span class="text-slate-400 group-hover:text-sky-600 transition">→</span>
                                </div>
                            </a>

                            <a href="#"
                                class="group rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md transition hover:-translate-y-0.5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                                            C</div>
                                        <div>
                                            <p class="font-semibold text-slate-800">Counsellor</p>
                                            <p class="text-sm text-slate-500">Manage schedule &amp; approvals</p>
                                        </div>
                                    </div>
                                    <span class="text-slate-400 group-hover:text-indigo-600 transition">→</span>
                                </div>
                            </a>

                            <a href="#"
                                class="group rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md transition hover:-translate-y-0.5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                                            A</div>
                                        <div>
                                            <p class="font-semibold text-slate-800">Admin</p>
                                            <p class="text-sm text-slate-500">Users, reports &amp; statistics</p>
                                        </div>
                                    </div>
                                    <span class="text-slate-400 group-hover:text-emerald-600 transition">→</span>
                                </div>
                            </a>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <a href="#"
                                class="flex-1 text-center px-4 py-2 rounded-xl bg-sky-600 text-white font-semibold shadow-sm hover:bg-sky-700 transition">
                                Sign Up
                            </a>
                            <a href="#"
                                class="flex-1 text-center px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition">
                                Help
                            </a>
                        </div>
                    </div>
                </section>

                <section class="mt-20 animate-fade-in-up animation-delay-4">
                    <div class="grid lg:grid-cols-2 gap-8">
                        <div
                            class="bg-white/70 backdrop-blur-xl border border-slate-200 rounded-3xl p-8 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                            <h2 class="text-xl font-semibold text-slate-800 mb-4">Announcements</h2>
                            <div class="space-y-4 text-sm text-slate-600">
                                <p>📌 Counselling sessions available Monday – Friday.</p>
                                <p>📌 Emergency booking priority available.</p>
                                <p>📌 Please cancel 24 hours before session.</p>
                            </div>
                        </div>

                        <div
                            class="bg-white/70 backdrop-blur-xl border border-slate-200 rounded-3xl p-8 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                            <h2 class="text-xl font-semibold text-slate-800 mb-4">Available Counsellors</h2>

                            <div class="space-y-4 text-sm">
                                <div
                                    class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                    <span class="text-slate-700 font-medium">Dr. Ahmad</span>
                                    <span
                                        class="text-xs px-3 py-1 rounded-full bg-emerald-100 text-emerald-700">Available</span>
                                </div>

                                <div
                                    class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                    <span class="text-slate-700 font-medium">Ms. Farah</span>
                                    <span
                                        class="text-xs px-3 py-1 rounded-full bg-amber-100 text-amber-700">Busy</span>
                                </div>

                                <div
                                    class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                    <span class="text-slate-700 font-medium">Mr. Daniel</span>
                                    <span
                                        class="text-xs px-3 py-1 rounded-full bg-emerald-100 text-emerald-700">Available</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <footer class="shrink-0 text-center py-5 text-sm text-slate-500">
            © {{ date('Y') }} CollegeCare • Counselling Booking System
        </footer>
    </div>

</body>

</html>
