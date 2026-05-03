<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Counsellor Dashboard • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes heroZoom {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.08);
            }
        }

        @keyframes heroFadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-slide-image {
            animation: heroZoom 9s ease-in-out infinite alternate;
            will-change: transform, opacity;
        }

        .hero-fade-enter {
            animation: heroFadeUp 0.55s ease;
        }

        .glass-card {
            background: linear-gradient(140deg, rgba(255, 255, 255, 0.9), rgba(241, 245, 249, 0.78));
            backdrop-filter: blur(10px);
        }
    </style>
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
        @if (session('status'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <section
            class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/85 shadow-2xl ring-1 ring-white/70 backdrop-blur-xl">
            <header class="border-b border-slate-200/90 bg-white/85 px-4 py-4 sm:px-6 sm:py-5 lg:px-8 lg:py-6">
                <div
                    class="rounded-3xl border border-white/70 bg-gradient-to-br from-white via-slate-50 to-sky-50/40 p-4 shadow-[0_18px_40px_-28px_rgba(15,23,42,0.65)] sm:p-6">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <a href="{{ route('profile.edit') }}"
                                class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-[18px] border border-sky-200 bg-slate-100 text-base font-bold text-slate-700 shadow-[0_3px_10px_rgba(15,23,42,0.08)] transition hover:-translate-y-0.5 hover:border-sky-300 hover:text-sky-700"
                                title="Profile">
                                <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}"
                                    alt="Counsellor profile" class="h-full w-full object-cover" />
                            </a>

                            <div class="rounded-2xl border border-slate-200 bg-white/95 px-5 py-3.5 shadow-sm">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-sky-500/80">
                                    CollegeCare</p>
                                <h1 class="text-lg font-semibold text-slate-800 lg:text-xl">Counsellor Session Dashboard
                                </h1>
                            </div>
                        </div>

                        <div class="flex w-full flex-wrap items-center gap-2.5 lg:w-auto lg:justify-end">
                            <a href="{{ route('counsellor.dashboard') }}"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-300 hover:text-sky-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 2v6h-6"></path>
                                    <path d="M3 12a9 9 0 0 1 15.55-6.36L21 8"></path>
                                    <path d="M3 22v-6h6"></path>
                                    <path d="M21 12a9 9 0 0 1-15.55 6.36L3 16"></path>
                                </svg>
                                <span>Refresh</span>
                            </a>
                            <form id="counsellor-logout-form" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-sky-600 to-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:from-sky-700 hover:to-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <div
                            class="group rounded-2xl border border-amber-200/80 bg-gradient-to-br from-amber-50 to-white px-4 py-3 text-sm shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <p class="font-semibold text-amber-700">Pending</p>
                            <div class="mt-1 flex items-end justify-between">
                                <p class="text-2xl font-bold text-amber-800">{{ $pendingCount }}</p>
                                <span class="text-xs font-medium text-amber-600/80">Needs action</span>
                            </div>
                        </div>
                        <div
                            class="group rounded-2xl border border-emerald-200/80 bg-gradient-to-br from-emerald-50 to-white px-4 py-3 text-sm shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <p class="font-semibold text-emerald-700">Approved</p>
                            <div class="mt-1 flex items-end justify-between">
                                <p class="text-2xl font-bold text-emerald-800">{{ $approvedCount }}</p>
                                <span class="text-xs font-medium text-emerald-600/80">Ready</span>
                            </div>
                        </div>
                        <div
                            class="group rounded-2xl border border-sky-200/80 bg-gradient-to-br from-sky-50 to-white px-4 py-3 text-sm shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <p class="font-semibold text-sky-700">Booked</p>
                            <div class="mt-1 flex items-end justify-between">
                                <p class="text-2xl font-bold text-sky-800">{{ $bookedSlots }}</p>
                                <span class="text-xs font-medium text-sky-600/80">Scheduled</span>
                            </div>
                        </div>
                        <div
                            class="group rounded-2xl border border-violet-200/80 bg-gradient-to-br from-violet-50 to-white px-4 py-3 text-sm shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <p class="font-semibold text-violet-700">Completed</p>
                            <div class="mt-1 flex items-end justify-between">
                                <p class="text-2xl font-bold text-violet-800">{{ $completedCount }}</p>
                                <span class="text-xs font-medium text-violet-600/80">Closed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="px-4 pb-6 pt-6 sm:px-6 sm:pb-8 lg:px-8 lg:pb-10 lg:pt-8">
                <section
                    class="mb-6 overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-r from-slate-900 via-sky-900 to-violet-900 shadow-xl">
                    <div class="relative h-64 sm:h-72 lg:h-80">
                        <img id="counsellor-hero-image"
                            src="https://images.unsplash.com/photo-1714976694525-71eb29a7c500?auto=format&fit=crop&w=1400&q=80"
                            alt="Counsellor wellbeing slide"
                            class="hero-slide-image absolute inset-0 h-full w-full object-cover opacity-70 transition-all duration-700" />
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
                <div class="mb-7 flex justify-center">
                    <a href="{{ route('counsellor.statistics') }}"
                        class="group inline-flex items-center gap-2 rounded-full border border-emerald-300/90 bg-gradient-to-r from-emerald-50 via-white to-teal-50 px-7 py-3.5 text-sm font-semibold text-emerald-700 shadow-[0_10px_24px_-16px_rgba(16,185,129,0.8)] transition duration-200 hover:-translate-y-0.5 hover:border-emerald-400 hover:from-emerald-100 hover:to-teal-100 hover:shadow-[0_14px_32px_-16px_rgba(16,185,129,0.95)]">
                        <span
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 transition group-hover:bg-emerald-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M3 3v18h18" />
                                <path d="m7 14 4-4 3 3 5-5" />
                            </svg>
                        </span>
                        <span>View Statistics: Students & Topics</span>
                        <span
                            class="rounded-full bg-emerald-200/80 px-2 py-0.5 text-[11px] font-bold uppercase tracking-wide text-emerald-800">New</span>
                    </a>
                </div>
                <section class="grid gap-6 xl:grid-cols-12">
                    <article
                        class="rounded-3xl border border-slate-200/90 bg-gradient-to-br from-white via-sky-50/45 to-sky-100/55 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-8 xl:col-span-5 xl:min-h-[440px]">
                        <div
                            class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border border-sky-200 bg-white text-3xl shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-sky-600" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M4 6l8-4 8 4-8 4-8-4z" />
                                <path d="M8 10v4c0 1.7 1.8 3 4 3s4-1.3 4-3v-4" />
                                <path d="M20 10v6" />
                                <path d="M20 18a1.5 1.5 0 1 1-3 0c0-1 1.5-2.6 1.5-2.6S20 17 20 18z" />
                            </svg>
                        </div>

                        <h2 class="mt-5 text-center text-2xl font-semibold text-slate-800">Pending Requests</h2>
                        <p class="mt-2 text-center text-base text-slate-500">Semak permohonan pelajar yang masih
                            menunggu kelulusan.</p>

                        <div
                            class="mt-6 rounded-2xl border border-sky-100 bg-white/80 px-4 py-4 text-center shadow-sm">
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
                        class="rounded-3xl border border-slate-200/90 bg-gradient-to-br from-white via-violet-50/45 to-violet-100/55 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-8 xl:col-span-4 xl:min-h-[440px]">
                        <div
                            class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border border-violet-200 bg-white text-3xl shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-violet-600"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="5" width="18" height="16" rx="2" />
                                <path d="M16 3v4M8 3v4M3 10h18" />
                                <path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01" />
                            </svg>
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

                    <article
                        class="glass-card rounded-3xl border border-slate-200/90 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-8 xl:col-span-3">
                        <h2 class="text-lg font-semibold text-slate-800">Quick Actions</h2>
                        <p class="mt-2 text-sm text-slate-500">Jump into your most common daily tasks.</p>

                        <div class="mt-6 space-y-3">
                            <a href="{{ route('counsellor.pending-requests') }}"
                                class="flex items-center justify-between rounded-2xl border border-sky-100 bg-white/80 px-4 py-3 text-sm font-medium text-sky-700 transition hover:border-sky-300 hover:bg-sky-50">
                                <span>Review Pending Requests</span>
                                <span
                                    class="rounded-full bg-sky-100 px-2 py-0.5 text-xs font-semibold">{{ $pendingCount }}</span>
                            </a>
                            <a href="{{ route('counsellor.session-status-list') }}"
                                class="flex items-center justify-between rounded-2xl border border-violet-100 bg-white/80 px-4 py-3 text-sm font-medium text-violet-700 transition hover:border-violet-300 hover:bg-violet-50">
                                <span>View Session Progress</span>
                                <span
                                    class="rounded-full bg-violet-100 px-2 py-0.5 text-xs font-semibold">{{ $bookedSlots + $completedCount }}</span>
                            </a>
                            <a href="{{ route('counsellor.statistics') }}"
                                class="flex items-center justify-between rounded-2xl border border-amber-100 bg-white/80 px-4 py-3 text-sm font-medium text-amber-700 transition hover:border-amber-300 hover:bg-amber-50">
                                <span>View Statistics</span>
                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold">New</span>
                            </a>
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center justify-between rounded-2xl border border-emerald-100 bg-white/80 px-4 py-3 text-sm font-medium text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-50">
                                <span>Update Profile</span>
                                <span
                                    class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold">Account</span>
                            </a>
                        </div>
                    </article>
                </section>

                <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50/90 px-4 py-3 text-sm text-slate-600">
                    <span>Total Applications: {{ count($applications) }}</span>
                </div>
            </div>
        </section>
    </main>

    <aside id="messages-widget"
        class="fixed bottom-4 right-4 z-40 w-[calc(100%-2rem)] max-w-md overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-2xl ring-1 ring-slate-200/70 sm:bottom-6 sm:right-6">
        <div
            class="flex items-center justify-between bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 px-4 py-3 text-white">
            <h3 class="text-lg font-semibold">Messages</h3>
            <div class="flex items-center gap-2">
                <span
                    class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-amber-400/20 text-amber-200"
                    aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                    </svg>
                </span>
                <button id="messages-toggle" type="button" aria-expanded="true"
                    class="rounded p-1 text-slate-200 transition hover:bg-white/10"
                    aria-label="Collapse messages panel">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="messages-body" class="space-y-4 p-4">
            <div class="flex items-center gap-3">
                <div class="flex flex-1 items-center gap-2 rounded-full border border-slate-300 bg-slate-50 px-3 py-2">
                    <span class="text-slate-500">🔎</span>
                    <input id="chat-search" type="text" placeholder="Search"
                        class="w-full border-none bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0" />
                </div>

            </div>

            <div class="flex items-center justify-between text-sm">
                <p class="font-semibold text-slate-900">Chats</p>
                <a href="{{ route('counsellor.pending-requests') }}"
                    class="font-medium text-sky-600 hover:text-sky-700">Requests</a>
            </div>

            <div id="chat-list" class="max-h-64 space-y-3 overflow-y-auto pr-1">
                @forelse ($chatItems as $index => $item)
                    @php
                        $requestDate = \Carbon\Carbon::parse($item['request_date']);
                        $requestTime = $item['request_time'] ?: '-';
                        $displayDate = $requestDate->format('l, d M Y') . ' • ' . $requestTime;
                    @endphp
                    <button type="button" data-chat-item="true"
                        data-booking-request-id="{{ $item['booking_request_id'] ?? '' }}"
                        data-student-id="{{ $item['student_id'] ?? '' }}"
                        data-email="{{ $item['student_email'] ?? '' }}"
                        data-phone="{{ $item['student_phone'] ?? '' }}"
                        data-student-phone="{{ $item['student_phone'] ?? '' }}"
                        data-student-name="{{ $item['student'] ?? 'Student' }}"
                        data-display-date="{{ $displayDate }}" data-request-date="{{ $item['request_date'] }}"
                        data-request-time="{{ $item['request_time'] ?? '' }}"
                        data-requester-role="{{ $item['requester_role'] ?? 'Student' }}"
                        data-topic="{{ $item['topic'] ?: 'General counseling support' }}"
                        data-reminder-url="{{ isset($item['booking_request_id']) ? route('counsellor.booking-request.reminder', $item['booking_request_id']) : '' }}"
                        data-name="{{ strtolower($item['student'] ?? 'student') }}"
                        data-topic-search="{{ strtolower($item['topic'] ?: 'general counseling support') }}"
                        class="flex w-full items-start gap-3 rounded-xl border border-slate-100 px-2 py-2 text-left transition hover:bg-slate-50">
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
                                    <span class="text-xs text-slate-400">{{ $displayDate }}</span>
                                    @if ($index === 0)
                                        <span
                                            class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-pink-600 px-1 text-[10px] font-semibold text-white">1</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </button>
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

    <div id="chat-popup-backdrop" class="fixed inset-0 z-40 hidden bg-slate-950/50 p-4">
        <div id="chat-popup"
            class="fixed left-1/2 top-20 z-50 w-[calc(100%-2rem)] max-w-lg -translate-x-1/2 rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div id="chat-popup-header"
                class="flex cursor-move items-center justify-between border-b border-slate-200 px-4 py-3">
                <div>
                    <p class="text-sm text-slate-500">Counsellor Chatbox</p>
                    <h4 id="chat-popup-student" class="text-base font-semibold text-slate-900">Student</h4>
                </div>
                <div class="flex items-center gap-1">
                    <button id="chat-popup-minimize" type="button"
                        class="rounded-md px-2 py-1 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                        aria-label="Minimize popup">—</button>
                    <button id="chat-popup-close" type="button"
                        class="rounded-md px-2 py-1 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                        aria-label="Close popup">✕</button>
                </div>
            </div>
            <div id="chat-popup-body" class="space-y-3 px-4 py-4">
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
                    <p><span class="font-medium text-slate-700">Topic:</span> <span id="chat-popup-topic"></span></p>
                    <p><span class="font-medium text-slate-700">Request slot:</span> <span
                            id="chat-popup-slot"></span></p>
                    <p><span class="font-medium text-slate-700">Request date:</span> <span
                            id="chat-popup-date"></span></p>
                    <p><span class="font-medium text-slate-700">Status:</span> <span id="chat-popup-role"></span></p>
                    <p><span class="font-medium text-slate-700">Email:</span> <span id="chat-popup-email"></span></p>
                    <p><span class="font-medium text-slate-700">Phone:</span> <span id="chat-popup-phone"></span></p>
                </div>
                <form id="reminder-form" method="POST" action="">
                    @csrf
                    <div id="chat-popup-thread"
                        class="max-h-56 space-y-2 overflow-y-auto rounded-xl border border-slate-200 bg-slate-50 p-3">
                    </div>
                    <label for="chat-popup-message-input"
                        class="mb-1 mt-3 block text-sm font-medium text-slate-700">Chat message</label>
                    <button id="chat-popup-suggest" type="button"
                        class="mb-2 rounded-lg border border-sky-200 bg-sky-50 px-3 py-1.5 text-sm font-medium text-sky-700 transition hover:bg-sky-100">
                        Suggest message from appointment
                    </button>
                    <div class="flex items-center gap-2">
                        <input id="chat-popup-message-input" type="text"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                            placeholder="Type a message..." />
                        <button id="chat-popup-send" type="button"
                            class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Send</button>

                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <button type="submit"
                            class="rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">Send
                            Reminder</button>
                        <a id="chat-popup-open-full" href="{{ route('chat.index') }}"
                            class="rounded-xl border border-slate-300 px-3 py-2 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Open
                            full chat</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="counsellor-logout-modal"
        class="fixed inset-0 z-[90] hidden items-center justify-center bg-slate-900/50 p-4">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
            <h3 class="text-lg font-semibold text-slate-800">Confirm logout</h3>
            <p class="mt-2 text-sm text-slate-600">Are you sure you want to logout?</p>
            <div class="mt-5 flex justify-end gap-2">
                <button id="counsellor-logout-cancel" type="button"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:border-sky-200 hover:text-sky-700">Cancel</button>
                <button id="counsellor-logout-confirm" type="button"
                    class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">Yes,
                    logout</button>
            </div>
        </div>
    </div <script>
        (function() {

            const logoutForm = document.getElementById('counsellor-logout-form');
            const logoutModal = document.getElementById('counsellor-logout-modal');
            const logoutCancel = document.getElementById('counsellor-logout-cancel');
            const logoutConfirm = document.getElementById('counsellor-logout-confirm');


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
                    logoutConfirm.addEventListener('click', () => {
                        closeLogoutModal();
                        logoutForm.submit();
                    });
                }

                logoutModal.addEventListener('click', (event) => {
                    if (event.target === logoutModal) closeLogoutModal();
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') closeLogoutModal();
                });
            }
            const slides = [{
                    image: 'https://images.unsplash.com/photo-1714976694525-71eb29a7c500?auto=format&fit=crop&w=1400&q=80',
                    tag: 'CollegeCare Focus',
                    title: 'Guide every student with empathy and structure.',
                    subtitle: 'Review requests, confirm sessions, and keep counselling support consistent every week.'
                },
                {
                    image: 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?auto=format&fit=crop&w=1400&q=80',
                    tag: 'Session Planning',
                    title: 'Coordinate sessions without losing context.',
                    subtitle: 'Track approved requests and align each booking with the right follow-up actions.'
                },
                {
                    image: 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=1400&q=80',
                    tag: 'Student Support',
                    title: 'Build trust through timely communication.',
                    subtitle: 'Respond to requests quickly and maintain meaningful support for every student.'
                }
            ];

            const imageEl = document.getElementById('counsellor-hero-image');
            const tagEl = document.getElementById('counsellor-hero-tag');
            const titleEl = document.getElementById('counsellor-hero-title');
            const subtitleEl = document.getElementById('counsellor-hero-subtitle');
            const prevBtn = document.getElementById('counsellor-hero-prev');
            const nextBtn = document.getElementById('counsellor-hero-next');
            const dotsWrap = document.getElementById('counsellor-hero-dots');

            if (!imageEl || !tagEl || !titleEl || !subtitleEl || !prevBtn || !nextBtn || !dotsWrap || !slides.length) {
                return;
            }

            let current = 0;
            let intervalId;

            const renderDots = () => {
                dotsWrap.innerHTML = '';
                slides.forEach((_, index) => {
                    const dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className =
                        `h-2.5 rounded-full transition ${index === current ? 'w-6 bg-white' : 'w-2.5 bg-white/40 hover:bg-white/70'}`;
                    dot.setAttribute('aria-label', `Go to slide ${index + 1}`);
                    dot.addEventListener('click', () => {
                        current = index;
                        applySlide();
                        restartInterval();
                    });
                    dotsWrap.appendChild(dot);
                });
            };

            const applySlide = () => {
                const slide = slides[current];
                imageEl.classList.remove('hero-fade-enter');
                titleEl.classList.remove('hero-fade-enter');
                subtitleEl.classList.remove('hero-fade-enter');

                imageEl.style.opacity = '0.3';

                window.requestAnimationFrame(() => {
                    imageEl.src = slide.image;
                    tagEl.textContent = slide.tag;
                    titleEl.textContent = slide.title;
                    subtitleEl.textContent = slide.subtitle;

                    imageEl.classList.add('hero-fade-enter');
                    titleEl.classList.add('hero-fade-enter');
                    subtitleEl.classList.add('hero-fade-enter');
                    imageEl.style.opacity = '0.7';
                    renderDots();
                });
            };

            const nextSlide = () => {
                current = (current + 1) % slides.length;
                applySlide();
            };

            const prevSlide = () => {
                current = (current - 1 + slides.length) % slides.length;
                applySlide();
            };

            const restartInterval = () => {
                if (intervalId) {
                    clearInterval(intervalId);
                }
                intervalId = setInterval(nextSlide, 6500);
            };

            nextBtn.addEventListener('click', () => {
                nextSlide();
                restartInterval();
            });

            prevBtn.addEventListener('click', () => {
                prevSlide();
                restartInterval();
            });

            applySlide();
            restartInterval();
        })();

        (function() {
            const widget = document.getElementById('messages-widget');
            const toggleBtn = document.getElementById('messages-toggle');
            const body = document.getElementById('messages-body');
            const list = document.getElementById('chat-list');
            const searchInput = document.getElementById('chat-search');
            const noResults = document.getElementById('chat-no-results');
            const popupBackdrop = document.getElementById('chat-popup-backdrop');
            const popup = document.getElementById('chat-popup');
            const popupStudent = document.getElementById('chat-popup-student');
            const popupTopic = document.getElementById('chat-popup-topic');
            const popupSlot = document.getElementById('chat-popup-slot');
            const popupDate = document.getElementById('chat-popup-date');
            const popupRole = document.getElementById('chat-popup-role');
            const popupEmail = document.getElementById('chat-popup-email');
            const popupPhone = document.getElementById('chat-popup-phone');
            const popupThread = document.getElementById('chat-popup-thread');
            const popupMessageInput = document.getElementById('chat-popup-message-input');
            const popupSendBtn = document.getElementById('chat-popup-send');
            const popupSuggestBtn = document.getElementById('chat-popup-suggest');
            const popupCloseBtn = document.getElementById('chat-popup-close');
            const popupMinBtn = document.getElementById('chat-popup-minimize');
            const reminderForm = document.getElementById('reminder-form');
            const popupOpenFull = document.getElementById('chat-popup-open-full');

            if (!widget || !toggleBtn || !body || !list || !popupBackdrop || !popup || !popupCloseBtn || !popupMinBtn) {
                return;
            }

            let collapsed = false;
            let dragOffsetX = 0;
            let dragOffsetY = 0;
            let isDragging = false;

            const escapeHtml = (str) => String(str || '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');

            const setCollapsed = (value) => {
                collapsed = value;
                body.classList.toggle('hidden', collapsed);
                toggleBtn.setAttribute('aria-expanded', String(!collapsed));
                toggleBtn.innerHTML = collapsed ?
                    `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>` :
                    `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>`;
            };

            toggleBtn.addEventListener('click', () => setCollapsed(!collapsed));

            const setPopupPosition = (x, y) => {
                popup.style.left = `${x}px`;
                popup.style.top = `${y}px`;
                popup.style.transform = 'translate(0, 0)';
            };

            const openPopup = (button) => {
                const student = button.dataset.studentName || 'Student';
                const topic = button.dataset.topic || 'General counseling support';
                const date = button.dataset.displayDate || '-';
                const role = button.dataset.requesterRole || 'Student';
                const email = button.dataset.email || '-';
                const phone = button.dataset.phone || '-';
                const reminderUrl = button.dataset.reminderUrl || '';
                const bookingRequestId = button.dataset.bookingRequestId || '';
                const studentId = button.dataset.studentId || '';

                popupStudent.textContent = student;
                popupTopic.textContent = topic;
                popupSlot.textContent = button.dataset.requestTime || '-';
                popupDate.textContent = date;
                popupRole.textContent = role;
                popupEmail.textContent = email;
                popupPhone.textContent = phone;

                popupThread.innerHTML = `
                    <div class="rounded-lg bg-white px-3 py-2 text-sm text-slate-700 shadow-sm">Hi ${escapeHtml(student)}, this is your counsellor. I reviewed your request for <span class="font-medium">${escapeHtml(topic)}</span>.</div>
                    <div class="rounded-lg bg-sky-50 px-3 py-2 text-sm text-sky-900 shadow-sm">Please confirm if <span class="font-medium">${escapeHtml(date)}</span> still works for you.</div>
                `;

                reminderForm.action = reminderUrl;
                reminderForm.dataset.bookingRequestId = bookingRequestId;
                reminderForm.dataset.studentId = studentId;

                popupOpenFull.href =
                    `{{ route('chat.index') }}?student_id=${encodeURIComponent(studentId)}&booking_request_id=${encodeURIComponent(bookingRequestId)}`;
                popup.dataset.student = student;
                popup.dataset.topic = topic;
                popup.dataset.date = date;
                popup.dataset.slot = button.dataset.requestTime || '-';
                popupBackdrop.classList.remove('hidden');
                setPopupPosition(window.innerWidth / 2 - popup.offsetWidth / 2, 80);
                popupMessageInput.focus();
            };

            const closePopup = () => {
                popupBackdrop.classList.add('hidden');
            };

            list.querySelectorAll('[data-chat-item="true"]').forEach((button) => {
                button.addEventListener('click', () => openPopup(button));
            });

            popupCloseBtn.addEventListener('click', closePopup);
            popupMinBtn.addEventListener('click', closePopup);
            popupBackdrop.addEventListener('click', (event) => {
                if (event.target === popupBackdrop) closePopup();
            });

            popupSendBtn.addEventListener('click', () => {
                const value = popupMessageInput.value.trim();
                if (!value) return;
                const bubble = document.createElement('div');
                bubble.className = 'rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-900 shadow-sm';
                bubble.textContent = value;
                popupThread.appendChild(bubble);
                popupThread.scrollTop = popupThread.scrollHeight;
                popupMessageInput.value = '';
                popupMessageInput.focus();
            });

            popupMessageInput.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    popupSendBtn.click();
                }
            });
            if (popupSuggestBtn) {
                popupSuggestBtn.addEventListener('click', () => {
                    const student = popup.dataset.student || 'Student';
                    const topic = popup.dataset.topic || 'your counselling request';
                    const date = popup.dataset.date || 'your requested date';
                    const slot = popup.dataset.slot || 'your requested time';
                    popupMessageInput.value =
                        `Hi ${student}, this is a reminder for your appointment about ${topic} on ${date} (${slot}). Please reply to confirm your availability.`;
                    popupMessageInput.focus();
                });
            }
            const popupHeader = document.getElementById('chat-popup-header');

            popupHeader.addEventListener('mousedown', (event) => {
                isDragging = true;
                const rect = popup.getBoundingClientRect();
                dragOffsetX = event.clientX - rect.left;
                dragOffsetY = event.clientY - rect.top;
                popupHeader.classList.add('cursor-grabbing');
            });

            window.addEventListener('mousemove', (event) => {
                if (!isDragging) return;
                const x = Math.max(8, Math.min(window.innerWidth - popup.offsetWidth - 8, event.clientX -
                    dragOffsetX));
                const y = Math.max(8, Math.min(window.innerHeight - popup.offsetHeight - 8, event.clientY -
                    dragOffsetY));
                setPopupPosition(x, y);
            });

            window.addEventListener('mouseup', () => {
                isDragging = false;
                popupHeader.classList.remove('cursor-grabbing');
            });

            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    const query = searchInput.value.trim().toLowerCase();
                    let visibleCount = 0;

                    list.querySelectorAll('[data-chat-item="true"]').forEach((item) => {
                        const name = item.dataset.name || '';
                        const topic = item.dataset.topicSearch || '';
                        const show = !query || name.includes(query) || topic.includes(query);
                        item.classList.toggle('hidden', !show);
                        if (show) visibleCount++;
                    });

                    noResults.classList.toggle('hidden', visibleCount !== 0);
                });
            }
        })();
    </script>
</body>

</html>
