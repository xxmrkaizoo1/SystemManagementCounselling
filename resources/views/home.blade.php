<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Session Home • CollegeCare</title>
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

        .session-slide-shell {
            position: relative;
            overflow: hidden;
            border-radius: 0.9rem;
            border: 1px solid rgb(186 230 253);
            min-height: 10.5rem;
            background: linear-gradient(135deg, rgb(240 249 255) 0%, rgb(236 254 255) 55%, rgb(224 242 254) 100%);
        }

        .session-slide-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1.04);
            animation: slide-kenburns 10s ease-in-out infinite alternate;
            filter: saturate(1.05) contrast(1.02);
        }

        .session-slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(110deg, rgb(12 74 110 / 0.70) 0%, rgb(14 116 144 / 0.45) 45%, rgb(14 165 233 / 0.22) 100%);
        }

        .session-slide-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            min-height: 10.5rem;
            padding: 1rem;
            color: rgb(240 249 255);
        }

        .slide-fade {
            animation: slide-fade 480ms ease;
        }

        @keyframes slide-fade {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-kenburns {
            from {
                transform: scale(1.02);
            }

            to {
                transform: scale(1.10);
            }
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

<body class="min-h-screen bg-slate-100 text-slate-700 overflow-x-hidden">

    <div id="loader" class="fixed inset-0 bg-sky-500 flex items-center justify-center z-50">
        <div id="circle" class="w-64 h-64 bg-white rounded-full flex items-center justify-center">
            <span id="logoText" class="text-sky-500 font-bold text-2xl">CollegeCare</span>
        </div>
    </div>
    <div id="content" class="opacity-0 translate-y-2 min-h-screen flex flex-col">

        <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]">
            </div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="absolute inset-0 bg-noise-layer opacity-5"></div>
            <div
                class="absolute -top-32 -left-24 w-[34rem] h-[34rem] bg-sky-300/35 rounded-full blur-3xl animate-blob-float">
            </div>
            <div
                class="absolute top-24 -right-32 w-[36rem] h-[36rem] bg-violet-300/30 rounded-full blur-3xl animate-aurora-drift animation-delay-2">
            </div>
        </div>

        <main class="min-h-screen p-4 sm:p-8">
            @php
                $dashboardRoleLabel = $role === 'teacher' ? 'Lecturer' : ucfirst($role);
                $sidebarRoleLabel =
                    $role === 'student' ? 'Pelajar' : ($role === 'teacher' ? 'Pensyarah' : ucfirst($role));
            @endphp
            <section
                class="max-w-[96rem] mx-auto rounded-[2rem] border border-slate-200 bg-white/90 backdrop-blur-md shadow-xl overflow-hidden">
                <header
                    class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/80 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Session Dashboard
                            ({{ $dashboardRoleLabel }})
                        </h1>
                        <p class="text-sm text-slate-500 mt-1">Welcome, {{ $user->full_name ?: $user->name }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="sidebar-toggle"
                            class="sidebar-toggle rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">
                            Menu
                        </button>
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M21 12a9 9 0 1 1-2.64-6.36" />
                                <polyline points="21 3 21 9 15 9" />
                            </svg>

                        </a>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}"> @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" y1="12" x2="9" y2="12" />
                                </svg>

                            </button>
                        </form>
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
                                <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                                <p class="text-xs uppercase tracking-wide text-sky-700">
                                    {{ $sidebarRoleLabel }}</p>
                            </div>
                        </div>

                        <p class="text-xs uppercase tracking-[0.12em] text-slate-500 mb-3">Menu</p>
                        <nav class="space-y-3 text-sm">
                            <a href="{{ route('inbox') }}" title="Inbox" aria-label="Inbox"
                                class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                <span
                                    class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M22 12.2V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v5.2" />
                                        <path
                                            d="M2 12.2h4.7a2 2 0 0 1 1.4.6l1 1a2 2 0 0 0 1.4.6h3a2 2 0 0 0 1.4-.6l1-1a2 2 0 0 1 1.4-.6H22" />
                                        <path d="M22 12.2V17a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4.8" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Inbox</span>
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
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" />
                                        <path d="M16 2v4M8 2v4M3 10h18" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Booking</span>
                            </a>
                            <a href="{{ route('booking.history') }}" title="Booking History"
                                aria-label="Booking History"
                                class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                <span
                                    class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
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
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Z" />
                                        <path d="M4 20a8 8 0 0 1 16 0" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Edit Profile</span>
                            </a>
                        </nav>
                    </aside>

                    <section
                        class="home-main rounded-2xl border border-slate-200 bg-white/90 p-4 sm:p-6 shadow-sm space-y-5">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-6">
                            <div class="flex items-center justify-between gap-3 mb-2">

                                <span
                                    class="text-xs px-2.5 py-1 rounded-full bg-sky-50 border border-sky-200 text-sky-700">Live</span>
                            </div>
                            <div id="session-slide" class="session-slide-shell shadow-sm">
                                <img id="session-slide-image" class="session-slide-img"
                                    src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1400&q=80"
                                    alt="Counselling wellbeing banner">
                                <div class="session-slide-overlay"></div>
                                <div class="session-slide-content">
                                    <p id="session-slide-tag"
                                        class="text-[11px] uppercase tracking-[0.14em] text-sky-100/90 font-semibold">
                                        CollegeCare
                                        Updates</p>
                                    <p id="session-slide-title" class="mt-2 text-base sm:text-lg font-semibold">
                                        {{ $announcements[0] }}</p>
                                    <p id="session-slide-subtitle" class="mt-1 text-sm text-sky-100/95">
                                        Your wellbeing journey starts with one conversation.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-base sm:text-lg font-semibold text-slate-800">Live Counsellor Current
                                    Status
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

                        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-base sm:text-lg font-semibold text-slate-800">Jadual Kaunselor
                                        (Calendar)</h2>
                                    <p class="text-sm text-slate-500">Klik mana-mana tarikh untuk lihat jadual dalam
                                        bentuk table.</p>
                                </div>
                            </div>

                            <div class="grid xl:grid-cols-[minmax(0,1fr)_240px] gap-4">
                                <div class="rounded-2xl border border-slate-200 overflow-hidden bg-white">
                                    <div
                                        class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                                        <button id="calendar-prev"
                                            class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">←</button>
                                        <h3 id="calendar-title" class="font-semibold text-slate-700">Month Year</h3>
                                        <button id="calendar-next"
                                            class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">→</button>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <div class="min-w-[720px]">
                                            <div class="text-xs uppercase tracking-wide bg-slate-100 text-slate-500"
                                                style="display:grid;grid-template-columns:repeat(7,minmax(0,1fr));">
                                                <div class="p-2.5 text-center font-semibold border-r border-slate-200">
                                                    Sun</div>
                                                <div class="p-2.5 text-center font-semibold border-r border-slate-200">
                                                    Mon</div>
                                                <div class="p-2.5 text-center font-semibold border-r border-slate-200">
                                                    Tue</div>
                                                <div class="p-2.5 text-center font-semibold border-r border-slate-200">
                                                    Wed</div>
                                                <div class="p-2.5 text-center font-semibold border-r border-slate-200">
                                                    Thu</div>
                                                <div class="p-2.5 text-center font-semibold border-r border-slate-200">
                                                    Fri</div>
                                                <div class="p-2.5 text-center font-semibold">Sat</div>
                                            </div>
                                            <div id="calendar-grid" class="gap-2 p-2 bg-slate-100"
                                                style="display:grid;grid-template-columns:repeat(7,minmax(0,1fr));">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <aside class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <h3 class="font-semibold text-slate-700 mb-3">Ringkasan</h3>
                                    <ul class="space-y-2 text-sm text-slate-600">
                                        <li class="rounded-lg border border-slate-200 bg-white p-2">🟢 Slot kosong</li>
                                        <li class="rounded-lg border border-slate-200 bg-white p-2">🟡 Menunggu
                                        </li>
                                        <li class="rounded-lg border border-slate-200 bg-white p-2">🔵 Ditempah</li>
                                        <li class="rounded-lg border border-slate-200 bg-white p-2">🔴 Penuh</li>
                                    </ul>
                                </aside>
                            </div>
                        </div>
                    </section>
                </div>
                <div id="sidebar-backdrop" class="sidebar-backdrop"></div>

                <footer
                    class="px-6 sm:px-8 py-4 border-t border-slate-200/80 text-center text-sm text-slate-500 bg-white/70">
                    © {{ date('Y') }} CollegeCare • Counselling Booking System
                </footer>
            </section>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const slide = document.getElementById('session-slide');
                const slideImage = document.getElementById('session-slide-image');
                const slideTitle = document.getElementById('session-slide-title');
                const slideSubtitle = document.getElementById('session-slide-subtitle');
                const slideTag = document.getElementById('session-slide-tag');
                const items = @json($announcements ?? []);
                const fallbackSlides = [{
                        title: 'Counselling slots for this week are now open. Book early to secure your preferred time.',
                        subtitle: 'Pick your preferred date and counsellor from the calendar.',
                        tag: 'Weekly Updates',
                        image: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1400&q=80',
                    },
                    {
                        title: 'Need to change time? Use Booking History to reschedule your active appointment.',
                        subtitle: 'Keep your session on track with quick, guided rescheduling.',
                        tag: 'Booking Tips',
                        image: 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=1400&q=80',
                    },
                    {
                        title: 'Check your inbox regularly for OTP and reminder notifications before your session.',
                        subtitle: 'Stay informed and never miss important counselling updates.',
                        tag: 'Reminder',
                        image: 'https://images.unsplash.com/photo-1517022812141-23620dba5c23?auto=format&fit=crop&w=1400&q=80',
                    }
                ];
                const slides = Array.isArray(items) && items.length > 0 ?
                    items.map((title, index) => ({
                        title,
                        subtitle: fallbackSlides[index % fallbackSlides.length].subtitle,
                        tag: fallbackSlides[index % fallbackSlides.length].tag,
                        image: fallbackSlides[index % fallbackSlides.length].image,
                    })) :
                    fallbackSlides;

                if (slide && slideImage && slideTitle && slideSubtitle && slideTag && slides.length > 0) {
                    let idx = 0;
                    const renderSlide = (item) => {
                        slide.classList.remove('slide-fade');
                        void slide.offsetWidth;
                        slideImage.src = item.image;
                        slideTitle.textContent = item.title;
                        slideSubtitle.textContent = item.subtitle;
                        slideTag.textContent = item.tag;
                        slide.classList.add('slide-fade');
                    };

                    renderSlide(slides[idx]);
                    window.setInterval(() => {
                        idx = (idx + 1) % slides.length;
                        renderSlide(slides[idx]);
                    }, 6000);
                }

                const calendarGrid = document.getElementById('calendar-grid');
                const calendarTitle = document.getElementById('calendar-title');
                const prevBtn = document.getElementById('calendar-prev');
                const nextBtn = document.getElementById('calendar-next');
                const sidebar = document.getElementById('home-sidebar');
                const sidebarToggle = document.getElementById('sidebar-toggle');
                const sidebarClose = document.getElementById('sidebar-close');
                const sidebarBackdrop = document.getElementById('sidebar-backdrop');

                const modal = document.getElementById('schedule-modal');
                const modalTitle = document.getElementById('schedule-modal-title');
                const modalBody = document.getElementById('schedule-modal-body');
                const modalClose = document.getElementById('schedule-modal-close');
                const logoutForm = document.getElementById('logout-form');
                const logoutModal = document.getElementById('logout-modal');
                const logoutCancel = document.getElementById('logout-cancel');
                const logoutConfirm = document.getElementById('logout-confirm');

                const closeLogoutModal = () => {
                    if (!logoutModal) return;
                    logoutModal.classList.add('hidden');
                    logoutModal.classList.remove('flex');
                };

                const openLogoutModal = () => {
                    if (!logoutModal) return;
                    logoutModal.classList.remove('hidden');
                    logoutModal.classList.add('flex');
                };

                if (logoutForm && logoutModal) {
                    logoutForm.addEventListener('submit', (event) => {
                        event.preventDefault();
                        openLogoutModal();
                    });

                    if (logoutCancel) {
                        logoutCancel.addEventListener('click', closeLogoutModal);
                    }

                    if (logoutConfirm) {
                        logoutConfirm.addEventListener('click', () => logoutForm.submit());
                    }

                    logoutModal.addEventListener('click', (event) => {
                        if (event.target === logoutModal) closeLogoutModal();
                    });

                    document.addEventListener('keydown', (event) => {
                        if (event.key === 'Escape') closeLogoutModal();
                    });
                }

                if (!calendarGrid || !calendarTitle || !prevBtn || !nextBtn) {
                    return;
                }

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

                const rawCounsellorNames = @json($counsellorNames ?? []);
                const rawBookingSlots = @json($bookingSlots ?? []);
                const counsellors = Array.isArray(rawCounsellorNames) ? rawCounsellorNames : Object.values(
                    rawCounsellorNames || {});
                const bookingSlots = Array.isArray(rawBookingSlots) ? rawBookingSlots : Object.values(
                    rawBookingSlots || {});
                const availableCounsellors = counsellors.filter(Boolean).length ? counsellors.filter(Boolean) : [
                    'Counsellor'
                ];
                const statusClass = {
                    Available: 'text-emerald-700 bg-emerald-50 border-emerald-200',
                    Booked: 'text-sky-700 bg-sky-50 border-sky-200',
                    Pending: 'text-amber-700 bg-amber-50 border-amber-200',
                    Full: 'text-rose-700 bg-rose-50 border-rose-200',
                };

                let activeDate = new Date();

                const buildHourlySlots = (startHour, endHour) => {
                    const slots = [];
                    for (let hour = startHour; hour < endHour; hour++) {
                        const from = String(hour).padStart(2, '0');
                        const to = String(hour + 1).padStart(2, '0');
                        slots.push(`${from}:00 - ${to}:00`);
                    }
                    return slots;
                };

                const getSlotTimesForDate = (date) => {
                    const day = date.getDay();
                    if (day === 5) return buildHourlySlots(8, 12);
                    if (day >= 1 && day <= 4) return buildHourlySlots(8, 17);
                    return [];
                };

                const bookedSlotsByKey = new Map(
                    bookingSlots.map((slot) => [
                        `${slot.date}|${slot.time}|${slot.counsellor}`,
                        slot.status === 'pending' ? 'Pending' : 'Booked'
                    ])
                );

                const slotKey = (date, time, counsellor) =>
                    `${date.toISOString().slice(0, 10)}|${time}|${counsellor}`;

                const computedStatus = (date, time, counsellor) => bookedSlotsByKey.get(slotKey(date, time,
                        counsellor)) ??
                    'Available';

                const getSlotStatusMeta = (date, time) => {
                    const occupied = availableCounsellors
                        .map((counsellor) => ({
                            counsellor,
                            status: computedStatus(date, time, counsellor),
                        }))
                        .filter((item) => item.status !== 'Available');

                    if (occupied.length === 0) {
                        return {
                            counsellorLabel: '-',
                            status: 'Available',
                        };
                    }

                    const allPending = occupied.every((item) => item.status === 'Pending');

                    return {
                        counsellorLabel: occupied.map((item) => item.counsellor).join(', '),
                        status: allPending ? 'Pending' : 'Booked',
                    };
                };

                const getDailyStatus = (date) => {
                    if (date.getDay() === 0 || date.getDay() === 6) return null;

                    const slotTimes = getSlotTimesForDate(date);
                    const slotStatuses = slotTimes.map((time) => getSlotStatusMeta(date, time).status);

                    if (slotStatuses.some((status) => status === 'Available')) return 'Available';
                    if (slotStatuses.some((status) => status === 'Pending')) return 'Pending';
                    return 'Full';
                };

                const renderScheduleRows = (date) => {
                    modalBody.innerHTML = '';
                    const slotTimes = getSlotTimesForDate(date);

                    if (!slotTimes.length) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td colspan="3" class="px-4 py-6 text-center text-sm text-slate-500">
                                Tiada slot kaunseling pada hujung minggu.
                            </td>
                        `;
                        modalBody.appendChild(tr);
                        return;
                    }

                    slotTimes.forEach((time) => {
                        const {
                            counsellorLabel,
                            status
                        } = getSlotStatusMeta(date, time);
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="px-4 py-3 border-b border-slate-100 font-semibold">${time}</td>
                            <td class="px-4 py-3 border-b border-slate-100">${counsellorLabel}</td>
                            <td class="px-4 py-3 border-b border-slate-100">
                                <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[11px] font-semibold ${statusClass[status]}">${status}</span>
                            </td>
                        `;
                        modalBody.appendChild(tr);
                    });
                };

                const openModal = (date) => {
                    if (!modal || !modalTitle || !modalBody) {
                        return;
                    }
                    modalTitle.textContent =
                        `Jadual Kaunselor • ${date.toLocaleDateString('en-GB', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })}`;
                    renderScheduleRows(date);
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                };

                const closeModal = () => {
                    if (!modal) {
                        return;
                    }
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                };

                if (modal && modalClose) {
                    modalClose.addEventListener('click', closeModal);
                    modal.addEventListener('click', (event) => {
                        if (event.target === modal) closeModal();
                    });
                }

                const renderCalendar = () => {
                    const year = activeDate.getFullYear();
                    const month = activeDate.getMonth();
                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);
                    const startOffset = firstDay.getDay();

                    calendarTitle.textContent = firstDay.toLocaleDateString('en-GB', {
                        month: 'long',
                        year: 'numeric'
                    });
                    calendarGrid.innerHTML = '';

                    for (let i = 0; i < startOffset; i++) {
                        const pad = document.createElement('div');
                        pad.className = 'min-h-42 sm:min-h-46 bg-slate-50 rounded-xl border border-slate-200/70';
                        calendarGrid.appendChild(pad);
                    }

                    const today = new Date();
                    const isCurrentMonth = today.getFullYear() === year && today.getMonth() === month;

                    for (let day = 1; day <= lastDay.getDate(); day++) {
                        const cellDate = new Date(year, month, day);
                        const status = getDailyStatus(cellDate);
                        const isWeekend = cellDate.getDay() === 0 || cellDate.getDay() === 6;
                        const isToday = isCurrentMonth && today.getDate() === day;
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className =
                            `min-h-42 sm:min-h-46 p-3 text-left border border-slate-200 rounded-xl transition flex flex-col justify-between ${
                                isWeekend ? 'bg-slate-50 cursor-default' : 'bg-white hover:bg-sky-50'
                            }`;
                        button.innerHTML = `
                            <p class="font-semibold text-base leading-none ${isToday ? 'text-sky-700' : 'text-slate-700'}">${day}</p>
                            ${status ? `<span class="mt-2 inline-flex rounded-full border px-2 py-0.5 text-[11px] ${statusClass[status]}">${status}</span>` : '<span class="mt-2 text-[11px] text-slate-400">Weekend</span>'}
                        `;
                        if (isToday) {
                            button.classList.add('ring-2', 'ring-sky-200', 'ring-inset');
                        }
                        if (!isWeekend) {
                            button.addEventListener('click', () => openModal(cellDate));
                        }
                        calendarGrid.appendChild(button);
                    }

                    const totalCells = startOffset + lastDay.getDate();
                    const trailingPads = (7 - (totalCells % 7)) % 7;
                    for (let i = 0; i < trailingPads; i++) {
                        const pad = document.createElement('div');
                        pad.className = 'min-h-42 sm:min-h-46 bg-slate-50 rounded-xl border border-slate-200/70';
                        calendarGrid.appendChild(pad);
                    }
                };

                prevBtn.addEventListener('click', () => {
                    activeDate = new Date(activeDate.getFullYear(), activeDate.getMonth() - 1, 1);
                    renderCalendar();
                });

                nextBtn.addEventListener('click', () => {
                    activeDate = new Date(activeDate.getFullYear(), activeDate.getMonth() + 1, 1);
                    renderCalendar();
                });

                renderCalendar();
            });
        </script>

    </div>

    <div id="schedule-modal"
        class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-[70] p-3 sm:p-6">
        <div
            class="w-full max-w-[96vw] xl:max-w-[88rem] bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 id="schedule-modal-title" class="text-lg font-semibold text-slate-800">Jadual Kaunselor</h3>
                <button id="schedule-modal-close"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">
                    Tutup
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] lg:min-w-full text-sm table-fixed">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-3 py-3 text-left border-b border-slate-200 whitespace-nowrap">Masa
                            </th>
                            <th class="px-3 py-3 text-left border-b border-slate-200 whitespace-nowrap">Kaunselor
                            </th>
                            <th class="px-3 py-3 text-left border-b border-slate-200 whitespace-nowrap">Status
                            </th>
                        </tr>
                    </thead>
                    <tbody id="schedule-modal-body" class="text-slate-700"></tbody>
                </table>
            </div>
        </div>
    </div>


    <div id="logout-modal" class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-[80] p-4">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
            <h3 class="text-lg font-semibold text-slate-800">Confirm logout</h3>
            <p class="mt-2 text-sm text-slate-600">Are you sure you want to logout?</p>
            <div class="mt-5 flex justify-end gap-2">
                <button id="logout-cancel" type="button"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                    Cancel
                </button>
                <button id="logout-confirm" type="button"
                    class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">
                    Yes, logout
                </button>
            </div>
        </div>
    </div>
</body>

</html>
