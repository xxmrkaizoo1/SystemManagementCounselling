<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CollegeCare | Counselling Booking System</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body class="min-h-screen bg-[#eef2f8] text-slate-900 antialiased">
    <div id="loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-sky-500 border-t-transparent"></div>
    </div>

    <main id="content" class="opacity-0 min-h-screen bg-gradient-to-br from-sky-100 via-slate-100 to-emerald-100">
        <header class="border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="h-14 w-14 rounded-2xl bg-sky-600"></div>
                    <div>
                        <p class="text-3xl font-bold leading-none text-slate-800">CollegeCare</p>
                        <p class="text-sm text-slate-500">Counselling Booking System</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="#" class="font-semibold text-slate-600 hover:text-slate-900">Get Started</a>
                    <button class="rounded-2xl bg-sky-600 px-6 py-2 font-bold text-white shadow-sm hover:bg-sky-700">Login</button>
                </div>
            </div>
        </header>

        <section class="mx-auto grid w-full max-w-6xl grid-cols-1 gap-10 px-6 py-16 lg:grid-cols-2 lg:py-24">
            <div class="self-center">
                <h1 class="max-w-xl text-5xl font-extrabold leading-tight text-slate-900">
                    Book counselling sessions easily, calmly, and securely.
                </h1>
                <p class="mt-6 max-w-xl text-2xl leading-relaxed text-slate-600">
                    Students and teachers can book sessions, counsellors manage schedules,
                    and admins track reports — all in one place.
                </p>

                <div class="mt-10 grid max-w-2xl grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">Today</p>
                        <p class="text-3xl font-bold text-slate-800">Open</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">Slots</p>
                        <p class="text-3xl font-bold text-slate-800">12</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">Support</p>
                        <p class="text-3xl font-bold text-slate-800">Fast</p>
                    </div>
                </div>
            </div>

            <div class="self-center rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-xl backdrop-blur-sm sm:p-8">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900">Quick Access</h2>
                        <p class="mt-1 text-lg text-slate-600">Choose your role to continue.</p>
                    </div>
                    <span class="rounded-full border border-emerald-300 bg-emerald-50 px-4 py-1 text-sm font-semibold text-emerald-700">Safe &amp; Private</span>
                </div>

                <div class="space-y-4">
                    <button class="flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-white px-5 py-4 text-left shadow-sm hover:border-sky-300">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-sky-100 text-lg font-bold text-sky-700">S</div>
                            <div>
                                <p class="text-2xl font-bold text-slate-900">Student</p>
                                <p class="text-lg text-slate-500">Book &amp; track sessions</p>
                            </div>
                        </div>
                        <span class="text-2xl text-slate-400">→</span>
                    </button>

                    <button class="flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-white px-5 py-4 text-left shadow-sm hover:border-indigo-300">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 text-lg font-bold text-indigo-700">C</div>
                            <div>
                                <p class="text-2xl font-bold text-slate-900">Counsellor</p>
                                <p class="text-lg text-slate-500">Manage schedule &amp; approvals</p>
                            </div>
                        </div>
                        <span class="text-2xl text-slate-400">→</span>
                    </button>

                    <button class="flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-white px-5 py-4 text-left shadow-sm hover:border-emerald-300">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-lg font-bold text-emerald-700">A</div>
                            <div>
                                <p class="text-2xl font-bold text-slate-900">Admin</p>
                                <p class="text-lg text-slate-500">Users, reports &amp; statistics</p>
                            </div>
                        </div>
                        <span class="text-2xl text-slate-400">→</span>
                    </button>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button class="rounded-2xl bg-sky-600 px-6 py-3 text-xl font-bold text-white hover:bg-sky-700">Sign Up</button>
                    <button class="rounded-2xl border border-slate-300 bg-white px-6 py-3 text-xl font-semibold text-slate-700 hover:bg-slate-50">Help</button>
                </div>
            </div>
        </section>

        <footer class="pb-8 text-center text-lg text-slate-500">
            © 2026 CollegeCare · Counselling Booking System
        </footer>
    </main>
</body>

</html>
