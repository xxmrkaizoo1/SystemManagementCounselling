<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inbox Notification Center • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
        <div class="absolute inset-0 bg-noise-layer opacity-15"></div>
        <div class="absolute -top-32 -left-24 w-[34rem] h-[34rem] bg-sky-300/35 rounded-full blur-3xl animate-blob-float"></div>
        <div class="absolute top-24 -right-32 w-[36rem] h-[36rem] bg-violet-300/30 rounded-full blur-3xl animate-aurora-drift animation-delay-2"></div>
    </div>

    <main class="min-h-screen p-4 sm:p-8">
        <section class="max-w-6xl mx-auto rounded-[2rem] border border-slate-200/80 bg-white/75 backdrop-blur-xl shadow-2xl overflow-hidden">
            <header class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/80 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Student & Teacher Inbox</h1>
                    <p class="text-sm text-slate-500 mt-1">{{ $user->full_name ?: $user->name }} • {{ ucfirst($role) }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('home.session') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">Home Session</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">Logout</button>
                    </form>
                </div>
            </header>

            <div class="p-5 sm:p-7 grid lg:grid-cols-[220px_1fr] gap-5">
                <aside class="rounded-2xl border border-slate-200 bg-white/85 p-4 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.12em] text-slate-500 mb-3">Menu</p>
                    <nav class="space-y-2 text-sm">
                        <a href="{{ route('inbox') }}" class="block rounded-xl bg-sky-600 text-white px-3 py-2 font-medium">Inbox</a>
                        <a href="#" class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Booking</a>
                        <a href="#" class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Booking History</a>
                        <a href="#" class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Edit Profile</a>
                    </nav>
                </aside>

                <section class="rounded-2xl border border-slate-200 bg-white/90 p-6 sm:p-8 shadow-sm flex items-center justify-center min-h-[26rem]">
                    @if (empty($notifications))
                        <div class="text-center max-w-md">
                            <div class="mx-auto w-14 h-14 rounded-2xl bg-sky-100 text-sky-700 grid place-items-center text-2xl">📭</div>
                            <h2 class="mt-4 text-lg sm:text-xl font-semibold text-slate-800">Inbox is empty</h2>
                            <p class="mt-2 text-sm text-slate-500">You don’t have notifications yet. Booking updates and reminders will appear here.</p>
                        </div>
                    @else
                        <div class="w-full">Notifications available.</div>
                    @endif
                </section>
            </div>

            <footer class="px-6 sm:px-8 py-4 border-t border-slate-200/80 text-center text-sm text-slate-500 bg-white/70">
                © {{ date('Y') }} CollegeCare • Counselling Booking System
            </footer>
        </section>
    </main>
</body>

</html>
