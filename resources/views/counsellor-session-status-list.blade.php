<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Approved, Booked & Completed • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes softPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.04);
            }
        }

        .status-fade-up {
            animation: fadeSlideUp 0.35s ease-out both;
        }

        .status-pulse {
            animation: softPulse 0.35s ease-out;
        }
    </style>
</head>

<body class="min-h-screen overflow-x-hidden bg-slate-50 text-slate-700 antialiased">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_40%,_#eef2ff_100%)]">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        <div class="absolute -top-20 -left-24 h-[28rem] w-[28rem] rounded-full bg-sky-300/20 blur-3xl"></div>
        <div class="absolute top-10 -right-24 h-[26rem] w-[26rem] rounded-full bg-violet-300/20 blur-3xl"></div>
    </div>

    <main class="mx-auto max-w-6xl p-4 sm:p-8 lg:p-10">
        <section
            class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/85 shadow-2xl ring-1 ring-white/70 backdrop-blur-xl">
            <header class="border-b border-slate-200/90 bg-white/90 px-5 py-5 sm:px-7 sm:py-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">CollegeCare</p>
                        <h1 class="mt-1 text-2xl font-bold text-slate-800 sm:text-3xl">Approved, Booked & Completed</h1>
                        <p class="mt-1 text-sm text-slate-500">Counsellor: {{ $user->full_name ?: $user->name }}</p>
                    </div>
                    <a href="{{ route('counsellor.dashboard') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7   w-7" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M3 9.5 12 3l9 6.5"></path>
                            <path d="M5 10v10h14V10"></path>
                            <path d="M9 20v-6h6v6"></path>
                        </svg>

                    </a>
                </div>

            </header>

            <div class="p-5 sm:p-7">
                @if (session('status'))
                    <div
                        class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif
                <div
                    class="mb-5 rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 via-white to-slate-50 p-4 sm:p-5 shadow-sm status-fade-up">
                    <div class="grid gap-4 lg:grid-cols-[1.4fr_1fr]">
                        <section class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
                            <div
                                class="flex items-center justify-between border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-4 py-3">
                                <button id="status-calendar-prev" type="button"
                                    class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-sm shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-sky-200 hover:text-sky-700">←</button>
                                <h2 id="status-calendar-title" class="font-semibold text-slate-700">Month Year</h2>
                                <button id="status-calendar-next" type="button"
                                    class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-sm shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-sky-200 hover:text-sky-700">→</button>
                            </div>
                            <div class="grid bg-slate-100 text-[11px] text-slate-600 sm:text-xs"
                                style="grid-template-columns: repeat(7, minmax(0, 1fr));">
                                <div class="p-2 text-center font-semibold">Sun</div>
                                <div class="p-2 text-center font-semibold">Mon</div>
                                <div class="p-2 text-center font-semibold">Tue</div>
                                <div class="p-2 text-center font-semibold">Wed</div>
                                <div class="p-2 text-center font-semibold">Thu</div>
                                <div class="p-2 text-center font-semibold">Fri</div>
                                <div class="p-2 text-center font-semibold">Sat</div>
                            </div>
                            <div id="status-calendar-grid" class="grid bg-white"
                                style="grid-template-columns: repeat(7, minmax(0, 1fr));"></div>
                        </section>

                        <aside class="flex flex-col gap-3">
                            <form id="session-filter-form" class="grid gap-3" autocomplete="off">
                                <label class="block">
                                    <span
                                        class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Selected Date
                                    </span>
                                    <input id="session-date-filter" type="text" readonly placeholder="All dates"
                                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-sky-300 focus:ring-2 focus:ring-sky-100">
                                </label>
                                <label class="block">
                                    <span
                                        class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Booking Status
                                    </span>
                                    <select id="session-status-filter"
                                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-sky-300 focus:ring-2 focus:ring-sky-100">
                                        <option value="">All status</option>
                                        <option value="approved">Approved</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </label>
                                <button id="session-clear-date" type="button"
                                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 transition-all duration-200 hover:-translate-y-0.5 hover:border-sky-200 hover:text-sky-700">
                                    Clear Date Filter
                                </button>
                            </form>

                            <div class="grid gap-2 sm:grid-cols-3 lg:grid-cols-1 xl:grid-cols-3">
                                <div class="rounded-xl border border-slate-200 bg-white px-3 py-2">
                                    <p class="text-[11px] uppercase tracking-wide text-slate-500">Visible</p>
                                    <p id="visible-count" class="text-base font-semibold text-slate-700">0</p>
                                </div>
                                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2">
                                    <p class="text-[11px] uppercase tracking-wide text-emerald-600">Approved</p>
                                    <p id="approved-count" class="text-base font-semibold text-emerald-700">0</p>
                                </div>
                                <div class="rounded-xl border border-violet-200 bg-violet-50 px-3 py-2">
                                    <p class="text-[11px] uppercase tracking-wide text-violet-600">Completed</p>
                                    <p id="completed-count" class="text-base font-semibold text-violet-700">0</p>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
                <div
                    class="overflow-auto rounded-2xl border border-slate-200/90 bg-white shadow-inner shadow-slate-100/60">
                    <table class="w-full min-w-[760px] text-sm">
                        <thead class="bg-slate-100/90 text-left text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Student</th>
                                <th class="px-4 py-3 font-semibold">Date</th>
                                <th class="px-4 py-3 font-semibold">Time</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 font-semibold">Topic</th>
                                <th class="px-4 py-3 font-semibold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="session-table-body" class="divide-y divide-slate-100 bg-white">
                            @forelse ($sessions as $session)
                                <tr data-session-date="{{ $session['date'] }}"
                                    data-session-status="{{ $session['status_value'] }}"
                                    class="transition hover:bg-sky-50/70">
                                    <td class="px-4 py-3 font-semibold text-slate-800">{{ $session['student'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $session['date'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $session['time'] }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="rounded-full border px-2.5 py-1 text-xs font-semibold {{ $session['status'] === 'Completed' ? 'border-violet-200 bg-violet-100 text-violet-700' : ($session['status'] === 'Approved' ? 'border-emerald-200 bg-emerald-100 text-emerald-700' : 'border-sky-200 bg-sky-100 text-sky-700') }}">
                                            {{ $session['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">{{ $session['topic'] ?: 'General support' }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($session['status_value'] === 'approved')
                                            <form method="POST"
                                                action="{{ route('counsellor.booking-request.status', $session['id']) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit"
                                                    class="rounded-lg border border-violet-200 bg-violet-50 px-3 py-1.5 text-xs font-semibold text-violet-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-violet-100">
                                                    Mark Completed
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-row">
                                    <td colspan="6" class="px-4 py-10 text-center text-slate-500">
                                        <div class="mx-auto flex max-w-sm flex-col items-center gap-2">
                                            <span
                                                class="inline-flex h-14 w-14 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-2xl">📅</span>
                                            <p class="text-base font-semibold text-slate-700">No
                                                approved/booked/completed sessions available</p>
                                            <p class="text-sm text-slate-500">New updates will appear here
                                                automatically.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            <tr id="no-results-row" class="hidden">
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500">No sessions match the
                                    selected date/status
                                    filter.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script>
        (() => {
            const calendarTitle = document.getElementById('status-calendar-title');
            const calendarGrid = document.getElementById('status-calendar-grid');
            const calendarPrev = document.getElementById('status-calendar-prev');
            const calendarNext = document.getElementById('status-calendar-next');
            const dateFilter = document.getElementById('session-date-filter');
            const statusFilter = document.getElementById('session-status-filter');
            const clearDateButton = document.getElementById('session-clear-date');
            const tableBody = document.getElementById('session-table-body');
            const emptyRow = document.getElementById('empty-row');
            const noResultsRow = document.getElementById('no-results-row');
            const visibleCount = document.getElementById('visible-count');
            const approvedCount = document.getElementById('approved-count');
            const completedCount = document.getElementById('completed-count');

            if (!tableBody || !dateFilter || !statusFilter || !calendarTitle || !calendarGrid || !calendarPrev || !
                calendarNext) {
                return;
            }

            const rows = Array.from(tableBody.querySelectorAll('tr[data-session-date]'));
            const monthLabel = new Intl.DateTimeFormat('en-US', {
                month: 'long',
                year: 'numeric',
            });
            const dateLabel = new Intl.DateTimeFormat('en-GB', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
            });
            const bookedDates = new Set(rows.map((row) => row.dataset.sessionDate).filter(Boolean));
            let selectedDate = '';
            let currentMonthDate = selectedDate ? new Date(`${selectedDate}T00:00:00`) : new Date();

            const renderCalendar = () => {
                const year = currentMonthDate.getFullYear();
                const month = currentMonthDate.getMonth();
                const firstDay = new Date(year, month, 1);
                const dayOffset = firstDay.getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                calendarTitle.textContent = monthLabel.format(firstDay);
                calendarGrid.innerHTML = '';

                for (let offset = 0; offset < dayOffset; offset += 1) {
                    const spacer = document.createElement('div');
                    spacer.className = 'h-10 border-r border-b border-slate-100 bg-slate-50/60';
                    calendarGrid.appendChild(spacer);
                }

                for (let day = 1; day <= daysInMonth; day += 1) {
                    const date = new Date(year, month, day);
                    const isoDate = date.toISOString().slice(0, 10);
                    const weekDay = date.getDay();
                    const isWeekend = weekDay === 0 || weekDay === 6;
                    const isSelected = selectedDate === isoDate;
                    const hasBooking = bookedDates.has(isoDate);
                    const button = document.createElement('button');

                    button.type = 'button';
                    button.dataset.date = isoDate;
                    button.className = [
                        'h-10 border-r border-b border-slate-100 text-sm transition-all duration-200',
                        isWeekend ? 'bg-slate-50 text-slate-300 cursor-not-allowed' :
                        'bg-white text-slate-700 hover:bg-sky-50 hover:scale-[1.02]',
                        isSelected ? 'bg-sky-600 font-semibold text-white shadow-inner' : '',
                        hasBooking && !isSelected && !isWeekend ?
                        'font-semibold text-emerald-700 ring-1 ring-emerald-100' : '',
                    ].join(' ').trim();
                    button.textContent = String(day);
                    button.title = isWeekend ? 'Weekend is not selectable' : (hasBooking ?
                        'Date with session record' : 'Filter by this date');

                    if (!isWeekend) {
                        button.addEventListener('click', () => {
                            selectedDate = selectedDate === isoDate ? '' : isoDate;
                            dateFilter.value = selectedDate ? dateLabel.format(date) : '';
                            updateRows();
                            renderCalendar();
                        });
                    } else {
                        button.disabled = true;
                        button.setAttribute('aria-disabled', 'true');
                    }

                    calendarGrid.appendChild(button);
                }
            };

            const updateRows = () => {
                const selectedStatus = statusFilter.value;
                let visible = 0;
                let approved = 0;
                let completed = 0;

                rows.forEach((row) => {
                    const rowDate = row.dataset.sessionDate || '';
                    const rowStatus = row.dataset.sessionStatus || '';
                    const matchDate = !selectedDate || rowDate === selectedDate;
                    const matchStatus = !selectedStatus || rowStatus === selectedStatus;
                    const shouldShow = matchDate && matchStatus;

                    row.classList.toggle('hidden', !shouldShow);

                    if (!shouldShow) {
                        return;
                    }

                    visible += 1;
                    if (rowStatus === 'approved') {
                        approved += 1;
                    }
                    if (rowStatus === 'completed') {
                        completed += 1;
                    }
                });

                if (emptyRow) {
                    emptyRow.classList.toggle('hidden', visible > 0);
                }
                if (noResultsRow) {
                    const shouldShowNoResults = rows.length > 0 && visible === 0;
                    noResultsRow.classList.toggle('hidden', !shouldShowNoResults);
                }

                if (visibleCount) {
                    visibleCount.textContent = String(visible);
                    visibleCount.classList.remove('status-pulse');
                    void visibleCount.offsetWidth;
                    visibleCount.classList.add('status-pulse');
                }
                if (approvedCount) {
                    approvedCount.textContent = String(approved);
                    approvedCount.classList.remove('status-pulse');
                    void approvedCount.offsetWidth;
                    approvedCount.classList.add('status-pulse');
                }
                if (completedCount) {
                    completedCount.textContent = String(completed);
                    completedCount.classList.remove('status-pulse');
                    void completedCount.offsetWidth;
                    completedCount.classList.add('status-pulse');
                }
            };

            statusFilter.addEventListener('change', updateRows);
            calendarPrev.addEventListener('click', () => {
                currentMonthDate = new Date(currentMonthDate.getFullYear(), currentMonthDate.getMonth() - 1, 1);
                renderCalendar();
            });
            calendarNext.addEventListener('click', () => {
                currentMonthDate = new Date(currentMonthDate.getFullYear(), currentMonthDate.getMonth() + 1, 1);
                renderCalendar();
            });
            if (clearDateButton) {
                clearDateButton.addEventListener('click', () => {
                    selectedDate = '';
                    dateFilter.value = '';
                    updateRows();
                    renderCalendar();
                });
            }

            dateFilter.value = '';
            updateRows();
            renderCalendar();
        })();
    </script>
</body>

</html>
