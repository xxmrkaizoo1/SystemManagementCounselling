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
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_36%,_#f1f5f9_100%)]"></div>
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
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">CollegeCare</p>
                                <h1 class="text-lg font-semibold text-slate-800 lg:text-xl">Counsellor Session Dashboard</h1>
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
                <section class="grid gap-6 lg:grid-cols-2">
                    <article
                        class="rounded-3xl border border-slate-200/90 bg-gradient-to-br from-white via-sky-50/45 to-sky-100/55 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-8 lg:min-h-[430px]">
                        <div
                            class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border border-sky-200 bg-white text-3xl shadow-sm">
                            🧑‍🏫
                        </div>

                        <h2 class="mt-5 text-center text-2xl font-semibold text-slate-800">Pending Requests</h2>
                        <p class="mt-2 text-center text-base text-slate-500">Semak permohonan pelajar yang masih menunggu kelulusan.</p>

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
                        <p class="mt-2 text-center text-base text-slate-500">Lihat status sesi yang telah approved, booked, dan complete.</p>

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

                <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50/90 px-4 py-3 text-sm text-slate-600">
                    <span>Total Applications: {{ count($applications) }}</span>
                </div>
            </div>
        </section>
    </main>

    <aside id="messages-widget"
        class="fixed bottom-4 right-4 z-40 w-[calc(100%-2rem)] max-w-md overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-2xl ring-1 ring-slate-200/70 sm:bottom-6 sm:right-6">
        <div class="flex items-center justify-between bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 px-4 py-3 text-white">
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
                <a href="{{ route('counsellor.pending-requests') }}" class="font-medium text-sky-600 hover:text-sky-700">Requests</a>
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
                                    <p class="truncate text-base font-semibold leading-tight text-slate-800">{{ $item['student'] ?? 'Student' }}</p>
                                    <p class="truncate text-sm text-slate-500">{{ $item['topic'] ?: 'General counseling support' }}</p>
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

            <div id="chat-no-results" class="hidden rounded-xl border border-slate-100 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
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
        });
    </script>
</body>

</html>
