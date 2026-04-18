<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Approved, Booked & Completed • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700">
    <main class="mx-auto max-w-5xl p-4 sm:p-8">
        <section class="rounded-3xl border border-slate-200 bg-white shadow-xl overflow-hidden">
            <header class="border-b border-slate-200 bg-white px-5 py-4 sm:px-7 flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl font-bold text-slate-800">Approved, Booked & Completed</h1>
                    <p class="text-sm text-slate-500">Counsellor: {{ $user->full_name ?: $user->name }}</p>
                </div>
                <a href="{{ route('counsellor.dashboard') }}"
                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                    Back to Dashboard
                </a>
            </header>

            <div class="p-5 sm:p-7">
                @if (session('status'))
                    <div
                        class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="mb-5 rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <form id="session-filter-form" class="grid w-full gap-3 sm:grid-cols-2 lg:max-w-2xl" autocomplete="off">
                            <label class="block">
                                <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Calendar Date
                                </span>
                                <input id="session-date-filter" type="date"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-sky-300 focus:ring-2 focus:ring-sky-100">
                            </label>
                            <label class="block">
                                <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Booking Status
                                </span>
                                <select id="session-status-filter"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-sky-300 focus:ring-2 focus:ring-sky-100">
                                    <option value="">All status</option>
                                    <option value="approved">Approved</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </label>
                        </form>
                        <div class="grid w-full gap-2 sm:grid-cols-3 lg:max-w-md">
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
                    </div>
                </div>
                <div class="overflow-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
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
                                    data-session-status="{{ $session['status_value'] }}">
                                    <td class="px-4 py-3 font-medium text-slate-700">{{ $session['student'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $session['date'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $session['time'] }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $session['status'] === 'Completed' ? 'bg-violet-100 text-violet-700' : ($session['status'] === 'Approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700') }}">
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
                                                    class="rounded-lg border border-violet-200 bg-violet-50 px-3 py-1.5 text-xs font-semibold text-violet-700 hover:bg-violet-100">
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
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500">No
                                        approved/booked/completed sessions available.</td>
                                </tr>
                            @endforelse
                            <tr id="no-results-row" class="hidden">
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500">No sessions match the selected date/status filter.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script>
        (() => {
            const dateFilter = document.getElementById('session-date-filter');
            const statusFilter = document.getElementById('session-status-filter');
            const tableBody = document.getElementById('session-table-body');
            const emptyRow = document.getElementById('empty-row');
            const noResultsRow = document.getElementById('no-results-row');
            const visibleCount = document.getElementById('visible-count');
            const approvedCount = document.getElementById('approved-count');
            const completedCount = document.getElementById('completed-count');

            if (!tableBody || !dateFilter || !statusFilter) {
                return;
            }

            const rows = Array.from(tableBody.querySelectorAll('tr[data-session-date]'));

            const updateRows = () => {
                const selectedDate = dateFilter.value;
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
                }
                if (approvedCount) {
                    approvedCount.textContent = String(approved);
                }
                if (completedCount) {
                    completedCount.textContent = String(completed);
                }
            };

            dateFilter.addEventListener('input', updateRows);
            statusFilter.addEventListener('change', updateRows);
            updateRows();
        })();
    </script>
</body>

</html>
