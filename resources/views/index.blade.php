<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Counselling Booking</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-screen flex flex-col">
    <!-- LOADER -->
    <div id="loader" class="fixed inset-0 bg-sky-500 flex items-center justify-center z-50">

        <div id="circle" class="w-64 h-64 bg-white rounded-full flex items-center justify-center">
            <span id="logoText" class="text-sky-500 font-bold text-2xl">
                SCHOLA
            </span>
        </div>

    </div>
    <!-- Soft background blobs -->
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-sky-300/40 rounded-full blur-3xl"></div>
        <div class="absolute top-40 -right-24 w-[30rem] h-[30rem] bg-indigo-300/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 left-1/3 w-[28rem] h-[28rem] bg-emerald-300/25 rounded-full blur-3xl"></div>
    </div>

    <!-- Header -->
    <header class="sticky top-0 z-40 bg-white/70 backdrop-blur-xl border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-sky-600 shadow-sm"></div>
                <div>
                    <p class="font-bold leading-tight">ScholaCare</p>
                    <p class="text-xs text-slate-500 -mt-0.5">Counselling Booking System</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="#roles"
                    class="hidden sm:inline text-sm font-medium text-slate-600 hover:text-sky-600 transition">
                    Get Started
                </a>

                <a href="#"
                    class="px-4 py-2 rounded-xl bg-sky-600 text-white text-sm font-semibold shadow-sm hover:bg-sky-700 transition">
                    Login
                </a>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="flex-1 flex items-center"> <!-- Hero -->
        <section class="grid lg:grid-cols-2 gap-10 items-center">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-800">
                    Book counselling sessions easily, calmly, and securely.
                </h1>
                <p class="mt-4 text-slate-600 leading-relaxed">
                    Students and teachers can book sessions, counsellors manage schedules, and admins track reports —
                    all in one place.
                </p>

                <!-- Quick stats (optional) -->
                <div class="mt-8 grid grid-cols-3 gap-3 max-w-md">
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
            </div>

            <!-- Soft UI panel -->
            <div class="rounded-3xl bg-white/70 border border-slate-200 shadow-sm p-6 sm:p-8 backdrop-blur-xl">
                <div class="flex items-center justify-between">
                    <p class="font-bold text-slate-800">Quick Access</p>
                    <span
                        class="text-xs px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Safe & Private
                    </span>
                </div>

                <p class="mt-2 text-sm text-slate-600">
                    Choose your role to continue.
                </p>

                <!-- Role Cards -->
                <div id="roles" class="mt-6 grid gap-4">
                    <!-- Student -->
                    <a href="#"
                        class="group rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md transition
                              hover:-translate-y-0.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold">
                                    S
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">Student</p>
                                    <p class="text-sm text-slate-500">Book & track sessions</p>
                                </div>
                            </div>
                            <span class="text-slate-400 group-hover:text-sky-600 transition">→</span>
                        </div>
                    </a>

                    <!-- Counsellor -->
                    <a href="#"
                        class="group rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md transition
                              hover:-translate-y-0.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                                    C
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">Counsellor</p>
                                    <p class="text-sm text-slate-500">Manage schedule & approvals</p>
                                </div>
                            </div>
                            <span class="text-slate-400 group-hover:text-indigo-600 transition">→</span>
                        </div>
                    </a>

                    <!-- Admin -->
                    <a href="#"
                        class="group rounded-2xl bg-white border border-slate-200 p-5 shadow-sm hover:shadow-md transition
                              hover:-translate-y-0.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                                    A
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">Admin</p>
                                    <p class="text-sm text-slate-500">Users, reports & statistics</p>
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

        <!-- Footer -->
        <footer class="mt-16 text-center text-sm text-slate-500">
            © {{ date('Y') }} ScholaCare • Counselling Booking System
        </footer>
    </main>

</body>

</html>
