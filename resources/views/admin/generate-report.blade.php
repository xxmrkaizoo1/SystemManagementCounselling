<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin • Generate Report</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 min-h-screen text-slate-800">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Admin • Generate Report</h1>
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                Back to dashboard
            </a>
        </div>

        <p class="mt-3 text-sm text-slate-600">Select each user booking and generate a popup report with locked student/session details.</p>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Total bookings</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($reportStats['total_bookings']) }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Completed sessions</p>
                <p class="mt-2 text-2xl font-bold text-emerald-700">{{ number_format($reportStats['completed_bookings']) }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Active users</p>
                <p class="mt-2 text-2xl font-bold text-indigo-700">{{ number_format($reportStats['active_users']) }}</p>
            </article>
        </div>

        <form method="GET" action="{{ route('admin.reports.generate') }}" class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <h2 class="text-lg font-semibold text-slate-900">Report filters</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-slate-700">Start date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="mt-1 block w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-slate-700">End date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="mt-1 block w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <button type="submit" class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                Refresh report
            </button>
        </form>

        <section class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Select User Session</h2>
            <p class="mt-1 text-sm text-slate-600">Click <strong>Generate report</strong> for any booking to open a popup report form.</p>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-left text-slate-500">
                            <th class="py-2 pr-4">Nama pelajar</th>
                            <th class="py-2 pr-4">ID pelajar / matric</th>
                            <th class="py-2 pr-4">Tarikh sesi</th>
                            <th class="py-2 pr-4">Masa sesi</th>
                            <th class="py-2 pr-4">Nama kaunselor</th>
                            <th class="py-2 pr-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportRows as $row)
                            <tr class="border-b border-slate-100">
                                <td class="py-3 pr-4">{{ $row->user->full_name ?? $row->user->name ?? '-' }}</td>
                                <td class="py-3 pr-4">{{ $row->user->no_matriks ?? '-' }}</td>
                                <td class="py-3 pr-4">{{ $row->booking_date ? \Carbon\Carbon::parse($row->booking_date)->format('d/m/Y') : '-' }}</td>
                                <td class="py-3 pr-4">{{ $row->booking_time ?? '-' }}</td>
                                <td class="py-3 pr-4">{{ $row->counsellor_name ?? '-' }}</td>
                                <td class="py-3 pr-0">
                                    <button type="button" class="open-report inline-flex items-center rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700"
                                        data-student-name="{{ $row->user->full_name ?? $row->user->name ?? '-' }}"
                                        data-student-id="{{ $row->user->no_matriks ?? '-' }}"
                                        data-date="{{ $row->booking_date ? \Carbon\Carbon::parse($row->booking_date)->format('d/m/Y') : '-' }}"
                                        data-time="{{ $row->booking_time ?? '-' }}"
                                        data-counsellor="{{ $row->counsellor_name ?? '-' }}">
                                        Generate report
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-5 text-center text-slate-500">No bookings found for selected date range.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div id="report-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
        <div class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-900">Session Report</h3>
                <button type="button" id="close-modal" class="rounded-md px-2 py-1 text-slate-500 hover:bg-slate-100">✕</button>
            </div>

            <form class="mt-4 space-y-4" onsubmit="event.preventDefault(); window.print();">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="text-xs font-semibold text-slate-500">Nama pelajar</label><input id="m-name" type="text" readonly class="mt-1 w-full rounded-xl border-slate-300 bg-slate-100"></div>
                    <div><label class="text-xs font-semibold text-slate-500">ID pelajar / matric</label><input id="m-id" type="text" readonly class="mt-1 w-full rounded-xl border-slate-300 bg-slate-100"></div>
                    <div><label class="text-xs font-semibold text-slate-500">Tarikh sesi</label><input id="m-date" type="text" readonly class="mt-1 w-full rounded-xl border-slate-300 bg-slate-100"></div>
                    <div><label class="text-xs font-semibold text-slate-500">Masa sesi</label><input id="m-time" type="text" readonly class="mt-1 w-full rounded-xl border-slate-300 bg-slate-100"></div>
                    <div class="sm:col-span-2"><label class="text-xs font-semibold text-slate-500">Nama kaunselor</label><input id="m-counsellor" type="text" readonly class="mt-1 w-full rounded-xl border-slate-300 bg-slate-100"></div>
                </div>

                <div>
                    <label for="summary" class="block text-sm font-semibold text-slate-700">Custom report summary</label>
                    <textarea id="summary" rows="5" placeholder="Type custom report notes..." class="mt-1 w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Print / Save PDF</button>
                    <button type="button" id="close-modal-2" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Close</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('report-modal');
        const closeButtons = [document.getElementById('close-modal'), document.getElementById('close-modal-2')];

        document.querySelectorAll('.open-report').forEach((button) => {
            button.addEventListener('click', () => {
                document.getElementById('m-name').value = button.dataset.studentName || '-';
                document.getElementById('m-id').value = button.dataset.studentId || '-';
                document.getElementById('m-date').value = button.dataset.date || '-';
                document.getElementById('m-time').value = button.dataset.time || '-';
                document.getElementById('m-counsellor').value = button.dataset.counsellor || '-';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });

        closeButtons.forEach((btn) => btn?.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }));

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    </script>
</body>

</html>
