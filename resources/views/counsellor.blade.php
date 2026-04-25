<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Counsellor Dashboard • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-slate-50 text-slate-700 antialiased">
    @php
        $pendingCount = collect($applications)->where('status', 'Menunggu')->count();
        $approvedCount = collect($applications)->where('status', 'Diluluskan')->count();
        $bookedSlots = collect($scheduleSlots)->where('slot_status', 'Ditempah')->count();
        $completedCount = collect($sessionRecords)->count();
        $chatItems = collect($applications)->take(10)->values();
    @endphp

    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_36%,_#f1f5f9_100%)]">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        <div class="absolute -top-24 -left-24 h-[30rem] w-[30rem] rounded-full bg-sky-300/20 blur-3xl"></div>
        <div class="absolute top-12 -right-24 h-[28rem] w-[28rem] rounded-full bg-violet-300/20 blur-3xl"></div>
    </div>

    <main class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-8 sm:py-10 lg:px-10">
        <section
            class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/85 shadow-2xl ring-1 ring-white/70 backdrop-blur-xl">
            <header class="border-b border-slate-200/90 bg-white/85 px-4 py-4 sm:px-6 sm:py-5 lg:px-8 lg:py-6">
                <div class="rounded-2xl border border-slate-200 bg-slate-100/80 p-4 shadow-sm sm:p-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <a href="{{ route('profile.edit') }}"
                                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full border border-slate-300 bg-white text-base font-bold text-slate-600 shadow-sm transition hover:border-sky-300 hover:text-sky-700"
                                title="Profile">
                                {{ strtoupper(substr($user->name ?? 'D', 0, 1)) }}
                            </a>

                            <div class="rounded-xl border border-slate-300/80 bg-white px-4 py-3 shadow-sm">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">
                                    CollegeCare</p>
                                <h1 class="text-lg font-semibold text-slate-800 lg:text-xl">Counsellor Session Dashboard
                                </h1>
                            </div>
                        </div>

                        <div class="flex w-full flex-wrap items-center gap-2 lg:w-auto lg:justify-end">
                            <a href="{{ route('counsellor.dashboard') }}"
                                class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-300 hover:text-sky-700">
                                Refresh
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <div class="rounded-xl border border-amber-200 bg-amber-50/80 px-4 py-3 text-sm">
                            <p class="font-medium text-amber-700">Pending</p>
                            <p class="text-xl font-bold text-amber-800">{{ $pendingCount }}</p>
                        </div>
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50/80 px-4 py-3 text-sm">
                            <p class="font-medium text-emerald-700">Approved</p>
                            <p class="text-xl font-bold text-emerald-800">{{ $approvedCount }}</p>
                        </div>
                        <div class="rounded-xl border border-sky-200 bg-sky-50/80 px-4 py-3 text-sm">
                            <p class="font-medium text-sky-700">Booked</p>
                            <p class="text-xl font-bold text-sky-800">{{ $bookedSlots }}</p>
                        </div>
                        <div class="rounded-xl border border-violet-200 bg-violet-50/80 px-4 py-3 text-sm">
                            <p class="font-medium text-violet-700">Completed</p>
                            <p class="text-xl font-bold text-violet-800">{{ $completedCount }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <div class="px-4 pb-6 pt-6 sm:px-6 sm:pb-8 lg:px-8 lg:pb-10 lg:pt-8">


                <section
                    class="mb-6 overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-r from-slate-900 via-sky-900 to-violet-900 shadow-xl">
                    <div class="relative h-64 sm:h-72 lg:h-80">
                        <img id="counsellor-hero-image" src="{{ asset('images/slides/counselling-session.svg') }}"
                            alt="Counsellor wellbeing slide"
                            class="absolute inset-0 h-full w-full object-cover opacity-70 transition-all duration-700" />
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-900/55 to-slate-900/30">
                        </div>

                        <div class="relative z-10 flex h-full flex-col justify-end px-6 pb-6 pt-8 sm:px-8 sm:pb-8">
                            <p id="counsellor-hero-tag"
                                class="text-xs font-semibold uppercase tracking-[0.16em] text-sky-200/95">CollegeCare
                                Focus</p>
                            <h2 id="counsellor-hero-title"
                                class="mt-2 max-w-3xl text-2xl font-bold text-white sm:text-3xl">
                                Guide every student with empathy and structure.
                            </h2>
                            <p id="counsellor-hero-subtitle" class="mt-2 max-w-2xl text-sm text-slate-100 sm:text-base">
                                Review requests, confirm sessions, and keep counselling support consistent every week.
                            </p>

                            <div class="mt-4 flex items-center gap-2">
                                <button id="counsellor-hero-prev" type="button"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/35 bg-white/10 text-white transition hover:bg-white/20"
                                    aria-label="Previous slide">←</button>
                                <button id="counsellor-hero-next" type="button"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/35 bg-white/10 text-white transition hover:bg-white/20"
                                    aria-label="Next slide">→</button>
                                <div id="counsellor-hero-dots" class="ml-1 flex items-center gap-1.5"></div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="grid gap-6 lg:grid-cols-2">
                    <article
                        class="rounded-3xl border border-slate-200/90 bg-gradient-to-br from-white via-sky-50/45 to-sky-100/55 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-8 lg:min-h-[430px]">
                        <div
                            class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border border-sky-200 bg-white text-3xl shadow-sm">
                            🧑‍🏫
                        </div>

                        <h2 class="mt-5 text-center text-2xl font-semibold text-slate-800">Pending Requests</h2>
                        <p class="mt-2 text-center text-base text-slate-500">Semak permohonan pelajar yang masih
                            menunggu kelulusan.</p>

                        <div class="mt-6 rounded-2xl border border-sky-100 bg-white/80 px-4 py-4 text-center shadow-sm">
                            <p class="text-xs uppercase tracking-wide text-sky-700">Current pending</p>
                            <p class="mt-1 text-4xl font-bold text-sky-800">{{ $pendingCount }}</p>
                        </div>

                        <div class="mt-8 flex justify-center">
                            <a href="{{ route('counsellor.pending-requests') }}"
                                class="inline-flex min-w-[260px] justify-center rounded-full bg-sky-600 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-sky-700">
                                View Pending Requests
                            </a>
                        </div>
                    </article>

                    <article
                        class="rounded-3xl border border-slate-200/90 bg-gradient-to-br from-white via-violet-50/45 to-violet-100/55 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-8 lg:min-h-[430px]">
                        <div
                            class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border border-violet-200 bg-white text-3xl shadow-sm">
                            📅
                        </div>

                        <h2 class="mt-5 text-center text-2xl font-semibold text-slate-800">Session Status</h2>
                        <p class="mt-2 text-center text-base text-slate-500">Lihat status sesi yang telah approved,
                            booked, dan complete.</p>

                        <div class="mt-6 grid grid-cols-3 gap-3 text-center text-xs sm:text-sm">
                            <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-2 py-3 sm:px-3">
                                <p class="text-emerald-700">Approved</p>
                                <p class="mt-1 text-xl font-bold text-emerald-800">{{ $approvedCount }}</p>
                            </div>
                            <div class="rounded-xl border border-sky-100 bg-sky-50 px-2 py-3 sm:px-3">
                                <p class="text-sky-700">Booked</p>
                                <p class="mt-1 text-xl font-bold text-sky-800">{{ $bookedSlots }}</p>
                            </div>
                            <div class="rounded-xl border border-violet-100 bg-violet-50 px-2 py-3 sm:px-3">
                                <p class="text-violet-700">Complete</p>
                                <p class="mt-1 text-xl font-bold text-violet-800">{{ $completedCount }}</p>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-center">
                            <a href="{{ route('counsellor.session-status-list') }}"
                                class="inline-flex min-w-[260px] justify-center rounded-full bg-violet-600 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-violet-700">
                                View Approved • Booked • Completed
                            </a>
                        </div>
                    </article>
                </section>


            </div>
        </section>
    </main>

    <aside id="messages-widget"
        class="fixed bottom-4 right-4 z-40 w-[calc(100%-2rem)] max-w-md overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-2xl ring-1 ring-slate-200/70 sm:bottom-6 sm:right-6">
        <div
            class="flex items-center justify-between bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 px-4 py-3 text-white">
            <h3 class="text-lg font-semibold">Messages</h3>
            <div class="flex items-center gap-2">
                <span class="text-sm text-amber-300">🔔</span>
                <button id="messages-toggle" type="button" aria-expanded="true"
                    class="rounded p-1 text-sm text-slate-200 transition hover:bg-white/10">˅</button>
            </div>
        </div>

        <div id="messages-body" class="space-y-4 p-4">
            <div class="flex items-center gap-3">
                <div class="flex flex-1 items-center gap-2 rounded-full border border-slate-300 bg-slate-50 px-3 py-2">
                    <span class="text-slate-500">🔎</span>
                    <input id="chat-search" type="text" placeholder="Search"
                        class="w-full border-none bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0" />
                </div>
                <a href="{{ route('chat.index') }}"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-500 transition hover:bg-slate-50"
                    title="Open full chat">
                    ☰
                </a>
            </div>

            <div class="flex items-center justify-between text-sm">
                <p class="font-semibold text-slate-900">Chats</p>
                <a href="{{ route('counsellor.pending-requests') }}"
                    class="font-medium text-sky-600 hover:text-sky-700">Requests</a>
            </div>

            <div id="chat-list" class="max-h-64 space-y-3 overflow-y-auto pr-1">
                @forelse ($chatItems as $index => $item)
                    <a href="{{ route('chat.index') }}" data-chat-item="true"
                        data-name="{{ strtolower($item['student'] ?? 'student') }}"
                        data-topic="{{ strtolower($item['topic'] ?: 'general counseling support') }}"
                        class="flex items-start gap-3 rounded-xl border border-slate-100 px-2 py-2 transition hover:bg-slate-50">
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-sky-100 to-violet-100 font-semibold text-slate-700">
                            {{ strtoupper(substr($item['student'] ?? 'S', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="truncate text-base font-semibold leading-tight text-slate-800">
                                        {{ $item['student'] ?? 'Student' }}</p>
                                    <p class="truncate text-sm text-slate-500">
                                        {{ $item['topic'] ?: 'General counseling support' }}</p>
                                </div>
                                <div class="flex items-center gap-2 pt-0.5">
                                    <span class="text-xs text-slate-400">{{ $item['request_date'] ?? 'Today' }}</span>
                                    @if ($index === 0)
                                        <span
                                            class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-pink-600 px-1 text-[10px] font-semibold text-white">1</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div id="chat-empty"
                        class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                        No chats yet. New student requests will appear here.
                    </div>
                @endforelse
            </div>

            <div id="chat-no-results"
                class="hidden rounded-xl border border-slate-100 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                No matching chats found.
            </div>
        </div>
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('messages-toggle');
            const widgetBody = document.getElementById('messages-body');
            const searchInput = document.getElementById('chat-search');
            const chatList = document.getElementById('chat-list');
            const chatRows = chatList ? Array.from(chatList.querySelectorAll('[data-chat-item="true"]')) : [];
            const noResults = document.getElementById('chat-no-results');
            const heroImage = document.getElementById('counsellor-hero-image');
            const heroTag = document.getElementById('counsellor-hero-tag');
            const heroTitle = document.getElementById('counsellor-hero-title');
            const heroSubtitle = document.getElementById('counsellor-hero-subtitle');
            const heroPrev = document.getElementById('counsellor-hero-prev');
            const heroNext = document.getElementById('counsellor-hero-next');
            const heroDots = document.getElementById('counsellor-hero-dots');

            const heroSlides = [{
                    image: "{{ asset('images/slides/counselling-session.svg') }}",
                    tag: 'CollegeCare Focus',
                    title: 'Guide every student with empathy and structure.',
                    subtitle: 'Review requests, confirm sessions, and keep counselling support consistent every week.',
                },
                {
                    image: "{{ asset('images/slides/study-focus.svg') }}",
                    tag: 'Student Readiness',
                    title: 'Support better academic focus with timely check-ins.',
                    subtitle: 'Approve important sessions quickly to help students stay on track.',
                },
                {
                    image: "{{ asset('images/slides/sleep-hydrate.svg') }}",
                    tag: 'Wellbeing Reminder',
                    title: 'Promote healthy routines beyond the counselling room.',
                    subtitle: 'Encourage practical habits students can follow every day.',
                }
            ];
            let currentHeroSlide = 0;
            let heroSlideInterval = null;

            const renderHeroDots = function() {
                if (!heroDots) return;
                heroDots.innerHTML = '';
                heroSlides.forEach(function(_, index) {
                    const dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'h-2.5 w-2.5 rounded-full transition ' + (index ===
                        currentHeroSlide ?
                        'bg-white' : 'bg-white/40 hover:bg-white/70');
                    dot.setAttribute('aria-label', 'Go to slide ' + (index + 1));
                    dot.addEventListener('click', function() {
                        setHeroSlide(index);
                    });
                    heroDots.appendChild(dot);
                });
            };

            const setHeroSlide = function(index) {
                if (!heroImage || !heroTag || !heroTitle || !heroSubtitle) return;
                currentHeroSlide = (index + heroSlides.length) % heroSlides.length;
                const slide = heroSlides[currentHeroSlide];

                heroImage.classList.add('opacity-40');
                setTimeout(function() {
                    heroImage.src = slide.image;
                    heroTag.textContent = slide.tag;
                    heroTitle.textContent = slide.title;
                    heroSubtitle.textContent = slide.subtitle;
                    heroImage.classList.remove('opacity-40');
                }, 120);

                renderHeroDots();
            };

            const startHeroAutoplay = function() {
                if (!heroImage) return;
                if (heroSlideInterval) {
                    clearInterval(heroSlideInterval);
                }
                heroSlideInterval = setInterval(function() {
                    setHeroSlide(currentHeroSlide + 1);
                }, 4500);
            };

            if (toggleButton && widgetBody) {
                toggleButton.addEventListener('click', function() {
                    const collapsed = widgetBody.classList.toggle('hidden');
                    toggleButton.setAttribute('aria-expanded', String(!collapsed));
                    toggleButton.textContent = collapsed ? '˄' : '˅';
                });
            }

            if (searchInput && chatRows.length > 0) {
                searchInput.addEventListener('input', function() {
                    const query = searchInput.value.trim().toLowerCase();
                    let visibleCount = 0;

                    chatRows.forEach(function(row) {
                        const name = row.dataset.name || '';
                        const topic = row.dataset.topic || '';
                        const matches = name.includes(query) || topic.includes(query);
                        row.classList.toggle('hidden', !matches);
                        if (matches) visibleCount += 1;
                    });

                    if (noResults) {
                        noResults.classList.toggle('hidden', visibleCount > 0);
                    }
                });
            }

            if (heroImage && heroTag && heroTitle && heroSubtitle) {
                setHeroSlide(0);
                startHeroAutoplay();

                if (heroPrev) {
                    heroPrev.addEventListener('click', function() {
                        setHeroSlide(currentHeroSlide - 1);
                        startHeroAutoplay();
                    });
                }

                if (heroNext) {
                    heroNext.addEventListener('click', function() {
                        setHeroSlide(currentHeroSlide + 1);
                        startHeroAutoplay();
                    });
                }
            }
        });
    </script>
</body>

</html>
