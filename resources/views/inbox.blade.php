<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inbox Notification Center • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .home-shell {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .home-sidebar {
            width: 100%;
        }

        .home-main {
            flex: 1 1 auto;
            min-width: 0;
        }

        .sidebar-toggle {
            display: inline-flex;
        }

        .home-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: min(18rem, 88vw);
            transform: translateX(-105%);
            transition: transform 0.25s ease;
            z-index: 70;
            overflow-y: auto;
            border-radius: 0;
            background: linear-gradient(180deg, rgb(14 116 144 / 0.22) 0%, rgb(14 165 233 / 0.12) 55%, rgb(240 249 255 / 0.95) 100%);
            backdrop-filter: blur(10px);
        }

        .home-sidebar.is-open {
            transform: translateX(0);
        }

        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgb(15 23 42 / 0.45);
            z-index: 60;
            display: none;
        }

        .sidebar-backdrop.is-open {
            display: block;
        }

        .word-pill {
            display: inline-flex;
            border-radius: 0.45rem;
            background: rgb(255 255 255 / 0.96);
            padding: 0.1rem 0.45rem;
        }

        @media (min-width: 1280px) {
            .home-shell {
                flex-direction: row;
                align-items: flex-start;
            }

            .home-sidebar {
                width: 16rem;
                flex: 0 0 16rem;
                position: sticky;
                top: 1rem;
                transform: none;
                border-radius: 1rem;
                z-index: auto;
                overflow: visible;
            }

            .sidebar-toggle,
            .sidebar-close-btn,
            .sidebar-backdrop {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-slate-100 min-h-screen text-slate-700">



    @php
        $sidebarRoleLabel = $role === 'teacher' ? 'PENSYARAH' : 'PELAJAR';
    @endphp

    <div id="loginLoader"
        class="fixed inset-0 z-[90] flex items-center justify-center bg-sky-500/95 transition-opacity duration-700">
        <div class="flex flex-col items-center gap-3">
            <span class="h-16 w-16 animate-spin rounded-full border-8 border-white/30 border-t-white"></span>
            <p class="text-xl font-semibold text-white">Loading to Inbox portal...</p>
        </div>
    </div>
    <div id="loginContent"
        class="max-w-6xl mx-auto px-3 sm:px-5 py-5 sm:py-7 opacity-0 translate-y-2 transition-all duration-700">
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <header
                class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/80 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Student & Lecturer  Inbox</h1>
                    <p class="text-sm text-slate-500 mt-1">{{ $user->full_name ?: $user->name }} • {{ ucfirst($role) }}
                    </p>
                </div>
                <div class="flex items-center gap-2">

                    {{-- Sidebar toggle --}}
                    <button type="button" id="sidebar-toggle"
                        class="sidebar-toggle sidebar-toggle  rounded-xl border border-slate-200 bg-white p-3 text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>

                    </button>



                    <div class="flex items-center gap-2">
                        <a href="{{ route('home.session') }}"
                            class="rounded-xl border border-slate-200 bg-white p-3 text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9.75L12 4l9 5.75M4.5 10.5V19.5A1.5 1.5 0 006 21h3.75v-4.5h4.5V21H18a1.5 1.5 0 001.5-1.5v-9" />
                            </svg>

                        </a>
                    </div>

                </div>
            </header>

            <div class="p-5 sm:p-7 home-shell">
                <aside id="home-sidebar"
                    class="home-sidebar rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-sm">
                    <div class="flex justify-end xl:hidden mb-2">
                        <button type="button" id="sidebar-close"
                            class="sidebar-close-btn rounded-lg border border-slate-200 px-2.5 py-1 text-sm text-slate-600 hover:text-sky-700 hover:border-sky-200">
                            ✕
                        </button>
                    </div>

                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-200">
                        <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}" alt="Profile"
                            class="w-11 h-11 rounded-full border border-slate-200 object-cover bg-sky-50" />
                        <div>
                            <p class="flex flex-wrap gap-1 text-sm font-semibold text-slate-800">
                                @foreach (preg_split('/\s+/', trim($user->name)) as $nameWord)
                                    @if ($nameWord !== '')
                                        <span class="word-pill">{{ $nameWord }}</span>
                                    @endif
                                @endforeach
                            </p>
                            <p class="text-xs uppercase tracking-wide text-sky-700"><span
                                    class="word-pill">{{ $sidebarRoleLabel }}</span></p>
                        </div>
                    </div>

                    <p class="text-xs uppercase tracking-[0.12em] text-slate-500 mb-3"><span class="word-pill">Menu</span>
                    </p>
                    <nav class="space-y-3 text-sm">
                        <a href="{{ route('inbox') }}" title="Inbox" aria-label="Inbox"
                            class="flex w-full items-center gap-3 rounded-xl border border-sky-200 bg-sky-50 px-3 py-2.5 text-sky-700 transition">
                            <span
                                class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-sky-200 bg-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M22 12.2V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v5.2" />
                                    <path
                                        d="M2 12.2h4.7a2 2 0 0 1 1.4.6l1 1a2 2 0 0 0 1.4.6h3a2 2 0 0 0 1.4-.6l1-1a2 2 0 0 1 1.4-.6H22" />
                                    <path d="M22 12.2V17a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4.8" />
                                </svg>
                            </span>
                            <span class="text-sm font-semibold">Inbox</span>
                        </a>

                        <a href="{{ route('chat.index') }}" title="Chat Box" aria-label="Chat Box"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                            <span
                                class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-slate-700">Chat Box</span>
                        </a>

                        <a href="{{ route('booking.index') }}" title="Booking" aria-label="Booking"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                            <span
                                class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <path d="M16 2v4M8 2v4M3 10h18" />
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-slate-700">Booking</span>
                        </a>

                        <a href="{{ route('booking.history') }}" title="Booking History" aria-label="Booking History"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                            <span
                                class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 3v5h5" />
                                    <path d="M3.05 13A9 9 0 1 0 6 6.3L3 8" />
                                    <path d="M12 7v5l3 2" />
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-slate-700">Booking History</span>
                        </a>

                        <a href="{{ route('profile.edit') }}" title="Edit Profile" aria-label="Edit Profile"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                            <span
                                class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Z" />
                                    <path d="M4 20a8 8 0 0 1 16 0" />
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-slate-700">Edit Profile</span>
                        </a>
                    </nav>
                </aside>

                <section
                    class="home-main rounded-2xl border border-slate-200 bg-white/90 p-5 sm:p-6 shadow-sm min-h-[26rem]">
                    @if (session('status'))
                        <div
                            class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <form method="GET" action="{{ route('inbox') }}"
                            class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5 items-end">
                            <div>
                                <label
                                    class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Date
                                    from</label>
                                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                                    class="w-full rounded-lg border-slate-200 bg-white text-sm" />
                            </div>
                            <div>
                                <label
                                    class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Date
                                    to</label>
                                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                                    class="w-full rounded-lg border-slate-200 bg-white text-sm" />
                            </div>
                            <div>
                                <label
                                    class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Time
                                    from</label>
                                <input type="time" name="time_from" value="{{ $filters['time_from'] ?? '' }}"
                                    class="w-full rounded-lg border-slate-200 bg-white text-sm" />
                            </div>
                            <div>
                                <label
                                    class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Time
                                    to</label>
                                <input type="time" name="time_to" value="{{ $filters['time_to'] ?? '' }}"
                                    class="w-full rounded-lg border-slate-200 bg-white text-sm" />
                            </div>
                            <div class="flex gap-2">
                                <button type="submit"
                                    class="rounded-lg bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">
                                    Filter
                                </button>
                                <a href="{{ route('inbox') }}"
                                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    @if ($notifications->isEmpty())
                        <div class="flex min-h-[18rem] items-center justify-center text-center max-w-md mx-auto">
                            <div>
                                <div
                                    class="mx-auto w-14 h-14 rounded-2xl bg-sky-100 text-sky-700 grid place-items-center text-2xl">
                                    📭</div>
                                <h2 class="mt-4 text-lg sm:text-xl font-semibold text-slate-800">No notifications found
                                </h2>
                                <p class="mt-2 text-sm text-slate-500">Try changing your date/time filter, or check
                                    again later for new updates.</p>
                            </div>
                        </div>
                    @else
                        <div class="w-full space-y-3">
                            @foreach ($notifications as $notification)
                                <article class="rounded-xl border border-slate-200 bg-white p-4">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                        <div>
                                            <h3 class="font-semibold text-slate-800">{{ $notification->title }}</h3>
                                            <p class="mt-1 text-sm text-slate-600">{{ $notification->message }}</p>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-xs text-slate-500 whitespace-nowrap">{{ $notification->created_at?->format('Y-m-d H:i:s') }}</span>
                                            <form method="POST"
                                                action="{{ route('inbox.notification.delete', $notification) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-lg border border-rose-200 bg-rose-50 px-2 py-1 text-xs font-medium text-rose-700 hover:bg-rose-100 transition"
                                                    aria-label="Delete notification">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>

            <div id="sidebar-backdrop" class="sidebar-backdrop"></div>

            <footer
                class="px-6 sm:px-8 py-4 border-t border-slate-200/80 text-center text-sm text-slate-500 bg-white/80">
                © {{ date('Y') }} CollegeCare • Counselling Booking System
            </footer>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inboxLoader = document.getElementById('inbox-loader');
            const sidebar = document.getElementById('home-sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarClose = document.getElementById('sidebar-close');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');
            const loaderShownAt = Date.now();
            const minimumLoaderMs = 3500;

            const hideInboxLoader = () => {
                if (!inboxLoader) return;
                const elapsed = Date.now() - loaderShownAt;
                const remaining = Math.max(0, minimumLoaderMs - elapsed);
                window.setTimeout(() => {
                    inboxLoader.classList.add('is-hidden');
                }, remaining);
            };

            window.addEventListener('load', hideInboxLoader, {
                once: true
            });
            window.setTimeout(hideInboxLoader, 5000);
            const closeSidebar = () => {
                if (!sidebar || !sidebarBackdrop) return;
                sidebar.classList.remove('is-open');
                sidebarBackdrop.classList.remove('is-open');
            };

            const openSidebar = () => {
                if (!sidebar || !sidebarBackdrop) return;
                sidebar.classList.add('is-open');
                sidebarBackdrop.classList.add('is-open');
            };

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', openSidebar);
            }
            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeSidebar);
            }
            if (sidebarBackdrop) {
                sidebarBackdrop.addEventListener('click', closeSidebar);
            }
        });
    </script>
</body>

</html>
