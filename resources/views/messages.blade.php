<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Messages • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-800">
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#eef2ff_40%,_#f8fafc_100%)]"></div>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
            <section class="grid gap-6 lg:grid-cols-[18rem_1fr]">
                <aside class="rounded-3xl border border-white/80 bg-white/90 p-5 shadow-xl backdrop-blur">
                    <div class="mb-6">
                        <p class="text-xs uppercase tracking-[0.15em] text-sky-600">CollegeCare</p>
                        <h1 class="mt-2 text-2xl font-bold text-slate-900">Messages Hub</h1>
                        <p class="mt-1 text-sm text-slate-500">Stay connected with students and session requests.</p>
                    </div>

                    <div class="space-y-3 text-sm" id="message-tabs">
                        <button type="button" data-filter="active"
                            class="filter-btn flex w-full items-center justify-between rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sky-700">
                            <span class="font-semibold">Active Chats</span>
                            <span class="rounded-full bg-white px-2 py-0.5 text-xs font-bold">12</span>
                        </button>
                        <button type="button" data-filter="request"
                            class="filter-btn flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 hover:border-sky-200 hover:text-sky-700">
                            <span class="font-medium">Requests</span>
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold">5</span>
                        </button>
                        <button type="button" data-filter="archived"
                            class="filter-btn flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 hover:border-sky-200 hover:text-sky-700">
                            <span class="font-medium">Archived</span>
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold">18</span>
                        </button>
                    </div>
                </aside>

                <section class="rounded-3xl border border-white/80 bg-white/90 shadow-xl backdrop-blur">
                    <header class="flex items-center justify-between rounded-t-3xl bg-slate-950 px-6 py-5 text-white">
                        <div class="flex items-center gap-3">
                            <a href="{{ url()->previous() }}"
                                class="inline-flex items-center rounded-lg border border-slate-700 px-3 py-2 text-xs font-semibold text-slate-200 transition hover:bg-slate-800">←
                                Back</a>
                            <div>
                                <h2 class="text-2xl font-bold">Messages</h2>
                                <p class="text-sm text-slate-300">Search, review, and reply quickly.</p>
                            </div>
                        </div>
                        <button class="rounded-xl border border-amber-300/40 bg-amber-300/10 px-3 py-2 text-amber-200">🔔 3 New</button>
                    </header>

                    <div class="space-y-5 p-6">
                        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span>🔎</span>
                            <input id="message-search" type="text" placeholder="Search by name or topic"
                                class="w-full bg-transparent outline-none placeholder:text-slate-400" />
                        </label>

                        <div id="message-list" class="grid gap-4 md:grid-cols-2">
                            <article data-category="active" data-search="aidy ilham rafique tekanan akademik"
                                class="message-card rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md transition">
                                <div class="flex gap-3">
                                    <div
                                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-100 font-bold text-indigo-700">
                                        A</div>
                                    <div>
                                        <h3 class="font-semibold text-slate-800">Aidy Ilham Rafique</h3>
                                        <p class="text-sm text-slate-500">Tekanan akademik</p>
                                    </div>
                                </div>
                                <p class="mt-3 text-sm text-slate-600">"Hi counsellor, I need help balancing assignments
                                    and exams."</p>
                                <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                                    <span>2 min ago</span>
                                    <a href="{{ route('chat.index') }}"
                                        class="rounded-lg bg-sky-600 px-3 py-1.5 font-semibold text-white">Open Chat</a>
                                </div>
                            </article>

                            <article data-category="request" data-search="nur syafiqah family concern"
                                class="message-card rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md transition">
                                <div class="flex gap-3">
                                    <div
                                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 font-bold text-emerald-700">
                                        N</div>
                                    <div>
                                        <h3 class="font-semibold text-slate-800">Nur Syafiqah</h3>
                                        <p class="text-sm text-slate-500">Family concern</p>
                                    </div>
                                </div>
                                <p class="mt-3 text-sm text-slate-600">"Can we schedule a follow-up session this week?"</p>
                                <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                                    <span>18 min ago</span>
                                    <a href="{{ route('chat.index') }}"
                                        class="rounded-lg bg-sky-600 px-3 py-1.5 font-semibold text-white">Open Chat</a>
                                </div>
                            </article>
                        </div>

                        <p id="empty-state" class="hidden rounded-xl border border-dashed border-slate-300 p-5 text-sm text-slate-500">
                            No messages found for this filter/search.
                        </p>
                    </div>
                </section>
            </section>
        </main>
    </div>

    <script>
        (() => {
            const buttons = Array.from(document.querySelectorAll('.filter-btn'));
            const cards = Array.from(document.querySelectorAll('.message-card'));
            const searchInput = document.getElementById('message-search');
            const emptyState = document.getElementById('empty-state');
            let activeFilter = 'active';

            const applyFilter = () => {
                const query = (searchInput?.value || '').toLowerCase().trim();
                let visibleCount = 0;

                cards.forEach((card) => {
                    const category = card.dataset.category || '';
                    const text = card.dataset.search || '';
                    const matchFilter = activeFilter === 'all' || category === activeFilter;
                    const matchSearch = query === '' || text.includes(query);
                    const visible = matchFilter && matchSearch;
                    card.classList.toggle('hidden', !visible);
                    if (visible) visibleCount += 1;
                });

                emptyState?.classList.toggle('hidden', visibleCount > 0);
            };

            buttons.forEach((button) => {
                button.addEventListener('click', () => {
                    activeFilter = button.dataset.filter || 'all';
                    buttons.forEach((btn) => {
                        const selected = btn === button;
                        btn.classList.toggle('border-sky-200', selected);
                        btn.classList.toggle('bg-sky-50', selected);
                        btn.classList.toggle('text-sky-700', selected);
                    });
                    applyFilter();
                });
            });

            searchInput?.addEventListener('input', applyFilter);
            applyFilter();
        })();
    </script>
</body>

</html>
