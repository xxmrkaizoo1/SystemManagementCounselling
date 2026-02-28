<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Session Home • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">

    <div id="loader" class="fixed inset-0 bg-sky-500 flex items-center justify-center z-50">
        <div id="circle" class="w-64 h-64 bg-white rounded-full flex items-center justify-center">
            <span id="logoText" class="text-sky-500 font-bold text-2xl">CollegeCare</span>
        </div>
    </div>
    <div id="content" class="opacity-0 translate-y-2 min-h-screen flex flex-col">

    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
        <div class="absolute inset-0 bg-noise-layer opacity-15"></div>
        <div
            class="absolute -top-32 -left-24 w-[34rem] h-[34rem] bg-sky-300/35 rounded-full blur-3xl animate-blob-float">
        </div>
        <div
            class="absolute top-24 -right-32 w-[36rem] h-[36rem] bg-violet-300/30 rounded-full blur-3xl animate-aurora-drift animation-delay-2">
        </div>
    </div>

    <main class="min-h-screen p-4 sm:p-8">
        <section
            class="max-w-6xl mx-auto rounded-[2rem] border border-slate-200/80 bg-white/75 backdrop-blur-xl shadow-2xl overflow-hidden">
            <header
                class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/80 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Session Dashboard ({{ ucfirst($role) }})
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">Welcome, {{ $user->full_name ?: $user->name }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('home') }}"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">Close</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">Logout</button>
                    </form>
                </div>
            </header>

            <div class="p-5 sm:p-7 grid lg:grid-cols-[220px_1fr] gap-5">
                <aside class="rounded-2xl border border-slate-200 bg-white/85 p-4 shadow-sm">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-200">
                        <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}" alt="Profile"
                            class="w-11 h-11 rounded-full border border-slate-200 object-cover bg-sky-50" />
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                            <p class="text-xs uppercase tracking-wide text-sky-700">
                                {{ $role === 'student' ? 'Pelajar' : 'Guru' }}</p>
                        </div>
                    </div>

                    <p class="text-xs uppercase tracking-[0.12em] text-slate-500 mb-3">Menu</p>
                    <nav class="space-y-2 text-sm">
                        <a href="{{ route('inbox') }}"
                            class="block rounded-xl bg-sky-600 text-white px-3 py-2 font-medium">Inbox</a>
                        <a href="#"
                            class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Booking</a>
                        <a href="#"
                            class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Booking
                            History</a>
                        <a href="#"
                            class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Edit
                            Profile</a>
                    </nav>
                </aside>

                <section class="rounded-2xl border border-slate-200 bg-white/90 p-4 sm:p-6 shadow-sm space-y-5">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-6">
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <p class="text-sm text-slate-500">Slide Show / Animation</p>
                            <span
                                class="text-xs px-2.5 py-1 rounded-full bg-sky-50 border border-sky-200 text-sky-700">Live</span>
                        </div>
                        <div id="session-slide"
                            class="rounded-xl bg-white border border-slate-200 p-6 min-h-28 text-slate-700 font-medium">
                            {{ $announcements[0] }}
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-base sm:text-lg font-semibold text-slate-800">Live Counsellor Current Status
                            </h2>
                            <span class="text-xs text-slate-500">Updated now</span>
                        </div>

                        <div class="space-y-2">
                            @foreach ($counsellors as $counsellor)
                                <div
                                    class="flex items-center justify-between rounded-xl px-3 py-2.5 {{ $counsellor['available'] ? 'border border-emerald-200 bg-emerald-50' : 'border border-rose-200 bg-rose-50' }}">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="w-2.5 h-2.5 rounded-full {{ $counsellor['available'] ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        <span>{{ $counsellor['name'] }}</span>
                                    </div>
                                    <span
                                        class="text-sm font-medium {{ $counsellor['available'] ? 'text-emerald-700' : 'text-rose-700' }}">
                                        {{ $counsellor['available'] ? 'Available' : 'In Session' }} • Next
                                        {{ $counsellor['next_slot'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>

            <footer
                class="px-6 sm:px-8 py-4 border-t border-slate-200/80 text-center text-sm text-slate-500 bg-white/70">
                © {{ date('Y') }} CollegeCare • Counselling Booking System
            </footer>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slide = document.getElementById('session-slide');
            if (!slide) return;

            const items = @json($announcements);
            if (!Array.isArray(items) || items.length === 0) return;

            let idx = 0;
            window.setInterval(() => {
                idx = (idx + 1) % items.length;
                slide.classList.remove('tip-swap');
                void slide.offsetWidth;
                slide.textContent = items[idx];
                slide.classList.add('tip-swap');
            }, 6000);
        });
    </script>

</div>
</body>

</html>
