<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chat Box • CollegeCare</title>
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

    <div class="max-w-6xl mx-auto px-3 sm:px-5 py-5 sm:py-7">
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <header
                class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/80 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Chat Box</h1>
                    <p class="text-sm text-slate-500 mt-1">{{ $user->full_name ?: $user->name }} • {{ ucfirst($role) }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="sidebar-toggle"
                        class="sidebar-toggle rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">
                        Menu
                    </button>
                    <a href="{{ route('home.session') }}"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">Home
                        Session</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">Logout</button>
                    </form>
                </div>
            </header>

            <div class="p-5 sm:p-7 home-shell">
                <aside id="home-sidebar" class="home-sidebar rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-sm space-y-4">
                    <div class="flex justify-end xl:hidden mb-2">
                        <button type="button" id="sidebar-close"
                            class="sidebar-close-btn rounded-lg border border-slate-200 px-2.5 py-1 text-sm text-slate-600 hover:text-sky-700 hover:border-sky-200">
                            ✕
                        </button>
                    </div>

                    <div class="flex items-center gap-3 mb-1 pb-3 border-b border-slate-200">
                        <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}" alt="Profile"
                            class="w-11 h-11 rounded-full border border-slate-200 object-cover bg-sky-50" />
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                            <p class="text-xs uppercase tracking-wide text-sky-700">{{ $sidebarRoleLabel }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-[0.12em] text-slate-500 mb-3">Menu</p>
                        <nav class="space-y-3 text-sm">
                            <a href="{{ route('inbox') }}" title="Inbox" aria-label="Inbox"
                                class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 12.2V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v5.2" />
                                        <path d="M2 12.2h4.7a2 2 0 0 1 1.4.6l1 1a2 2 0 0 0 1.4.6h3a2 2 0 0 0 1.4-.6l1-1a2 2 0 0 1 1.4-.6H22" />
                                        <path d="M22 12.2V17a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4.8" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Inbox</span>
                            </a>

                            <a href="{{ route('chat.index') }}" title="Chat Box" aria-label="Chat Box"
                                class="flex w-full items-center gap-3 rounded-xl border border-sky-200 bg-sky-50 px-3 py-2.5 text-sky-700 transition">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-sky-200 bg-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                    </svg>
                                </span>
                                <span class="text-sm font-semibold">Chat Box</span>
                            </a>

                            <a href="{{ route('booking.index') }}" title="Booking" aria-label="Booking"
                                class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" />
                                        <path d="M16 2v4M8 2v4M3 10h18" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Booking</span>
                            </a>

                            <a href="{{ route('booking.history') }}" title="Booking History" aria-label="Booking History"
                                class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3v5h5" />
                                        <path d="M3.05 13A9 9 0 1 0 6 6.3L3 8" />
                                        <path d="M12 7v5l3 2" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Booking History</span>
                            </a>

                            <a href="{{ route('profile.edit') }}" title="Edit Profile" aria-label="Edit Profile"
                                class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Z" />
                                        <path d="M4 20a8 8 0 0 1 16 0" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Edit Profile</span>
                            </a>
                        </nav>
                    </div>

                    <div class="space-y-2 max-h-80 overflow-auto pr-1">
                        @forelse ($users as $listedUser)
                            <a href="{{ route('chat.index', ['user_id' => $listedUser->id, 'search' => $search]) }}"
                                class="block rounded-xl border px-3 py-2 text-sm transition {{ $selectedUser?->id === $listedUser->id ? 'border-sky-300 bg-sky-50 text-sky-700' : 'border-slate-200 bg-white hover:border-sky-200' }}">
                                <p class="font-semibold">{{ $listedUser->full_name ?: $listedUser->name }}</p>
                                <p class="text-xs text-slate-500">{{ $listedUser->email }}</p>
                            </a>
                        @empty
                            <p class="text-sm text-slate-500">No users found.</p>
                        @endforelse
                    </div>
                </aside>

                <section class="home-main rounded-2xl border border-slate-200 bg-white/90 p-4 sm:p-6 shadow-sm min-h-[28rem] flex flex-col">
                    <div class="mb-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <form method="GET" action="{{ route('chat.index') }}" class="flex gap-2 items-end">
                            @if ($selectedUser)
                                <input type="hidden" name="user_id" value="{{ $selectedUser->id }}" />
                            @endif
                            <div class="flex-1">
                                <label for="search" class="mb-1 block text-xs uppercase tracking-[0.12em] text-slate-500">Search user</label>
                                <input id="search" type="text" name="search" value="{{ $search }}" placeholder="Name / email"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-400 focus:ring-sky-200" />
                            </div>
                            <button type="submit"
                                class="rounded-xl border border-sky-200 bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700 hover:bg-sky-100 transition">Search</button>
                        </form>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (!$selectedUser)
                        <div class="flex-1 grid place-items-center text-center">
                            <div>
                                <div class="text-4xl">💬</div>
                                <h2 class="mt-3 text-lg font-semibold text-slate-800">Select a user to start chat</h2>
                                <p class="text-sm text-slate-500 mt-1">Search for a student or teacher, then send your message.</p>
                            </div>
                        </div>
                    @else
                        <div class="pb-3 border-b border-slate-200">
                            <h2 class="text-lg font-semibold text-slate-800">Chat with {{ $selectedUser->full_name ?: $selectedUser->name }}</h2>
                            <p class="text-sm text-slate-500">{{ $selectedUser->email }}</p>
                        </div>

                        <div class="flex-1 my-4 space-y-3 overflow-auto pr-1">
                            @forelse ($messages as $message)
                                <div class="flex {{ (int) $message->sender_id === (int) $user->id ? 'justify-end' : 'justify-start' }}">
                                    <article class="max-w-[80%] rounded-2xl px-4 py-2 {{ (int) $message->sender_id === (int) $user->id ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-700' }}">
                                        <p class="text-sm">{{ $message->message }}</p>
                                        <p class="mt-1 text-[11px] {{ (int) $message->sender_id === (int) $user->id ? 'text-sky-100' : 'text-slate-500' }}">
                                            {{ $message->created_at?->format('Y-m-d H:i') }}
                                        </p>
                                    </article>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">No messages yet. Start the conversation below.</p>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('chat.store') }}" class="space-y-2 border-t border-slate-200 pt-3">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}" />
                            <textarea name="message" rows="3" maxlength="3000" required
                                placeholder="Type your message..."
                                class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-400 focus:ring-sky-200">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                            <button type="submit"
                                class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">Send</button>
                        </form>
                    @endif
                </section>
            </div>

            <div id="sidebar-backdrop" class="sidebar-backdrop"></div>

            <footer class="px-6 sm:px-8 py-4 border-t border-slate-200/80 text-center text-sm text-slate-500 bg-white/80">
                © {{ date('Y') }} CollegeCare • Counselling Booking System
            </footer>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('home-sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarClose = document.getElementById('sidebar-close');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');

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
