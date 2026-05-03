<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Counsellor Statistics • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="min-h-screen overflow-x-hidden bg-slate-50 text-slate-700 antialiased">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_36%,_#f1f5f9_100%)]">
        </div>
    </div>

    <main class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-8 sm:py-10 lg:px-10">
        <section
            class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/85 shadow-2xl ring-1 ring-white/70 backdrop-blur-xl">
            <header class="border-b border-slate-200/90 bg-white/90 px-4 py-5 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="mt-1 text-2xl font-bold text-slate-800 sm:text-3xl">Counsellor Statistics</h1>
                        <p class="mt-2 text-sm text-slate-500">Top Topics</p>
                    </div>
                    <a href="{{ route('counsellor.dashboard') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M3 9.5 12 3l9 6.5"></path>
                            <path d="M5 10v10h14V10"></path>
                            <path d="M9 20v-6h6v6"></path>
                        </svg>

                    </a>
                </div>
            </header>

            <div class="px-4 pb-6 pt-6 sm:px-6 sm:pb-8 lg:px-8 lg:pb-10">
                <section class="mb-6 grid gap-4 sm:grid-cols-3">
                    <article class="rounded-2xl border border-sky-200/80 bg-sky-50 p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">Total Appointments</p>
                        <p class="mt-2 text-3xl font-bold text-sky-800">{{ $totalBookings }}</p>
                    </article>
                    <article class="rounded-2xl border border-rose-200/80 bg-rose-50 p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-rose-600">Emergency Bookings</p>
                        <p class="mt-2 text-3xl font-bold text-rose-800">{{ $emergencyBookingsCount }}</p>
                    </article>
                    <article class="rounded-2xl border border-indigo-200/80 bg-indigo-50 p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600">Counsellor</p>
                        <p class="mt-2 text-lg font-semibold text-indigo-800">{{ $user->full_name ?: $user->name }}</p>
                    </article>
                </section>

                <section class="space-y-6">
                    <article class="rounded-3xl border border-slate-200/90 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                            <h2 class="text-xl font-semibold text-slate-800">Top Topics (Pie Chart)</h2>
                            <div class="flex flex-wrap items-center gap-2">
                                <select id="topics-weeks-filter"
                                    class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    <option value="4">Last 4 weeks</option>
                                    <option value="8">Last 8 weeks</option>
                                    <option value="12" selected>Last 12 weeks</option>
                                </select>

                                <input id="emergency-date-from" type="date"
                                    class="rounded-lg border border-rose-300 px-3 py-2 text-sm" />
                                <input id="emergency-date-to" type="date"
                                    class="rounded-lg border border-rose-300 px-3 py-2 text-sm" />
                                <input id="topics-start-date" type="date"
                                    class="rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                                <input id="topics-end-date" type="date"
                                    class="rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </div>
                        </div>
                        <div class="relative h-80 w-full"><canvas id="topicsPieChart"></canvas></div>
                    </article>

                    <article class="rounded-3xl border border-rose-200/90 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                            <h2 class="text-xl font-semibold text-rose-800">Emergency Booking Trend (Line Chart)</h2>
                            <div class="flex flex-wrap items-center gap-2">
                                <select id="emergency-weeks-filter"
                                    class="rounded-lg border border-rose-300 px-3 py-2 text-sm">
                                    <option value="4">Last 4 weeks</option>
                                    <option value="8">Last 8 weeks</option>
                                    <option value="12" selected>Last 12 weeks</option>
                                </select>
                                <input id="emergency-start-date" type="date"
                                    class="rounded-lg border border-rose-300 px-3 py-2 text-sm" />
                                <input id="emergency-end-date" type="date"
                                    class="rounded-lg border border-rose-300 px-3 py-2 text-sm" />
                            </div>
                        </div>
                        <canvas id="emergencyLineChart" height="120"></canvas>
                    </article>
                </section>

                <section class="mt-6 grid gap-6 lg:grid-cols-2">
                    <article class="rounded-3xl border border-indigo-200/80 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-indigo-800">Top Students by Name</h3>
                            <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">Top
                                {{ count($topStudents) }}</span>
                        </div>
                        <div class="relative overflow-visible rounded-2xl border border-slate-100">
                            <table class="min-w-full divide-y divide-slate-100 text-sm">
                                <thead class="bg-slate-50 text-slate-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold">Student Name</th>
                                        <th class="px-4 py-3 text-right font-semibold">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse($topStudents as $item)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="relative inline-block student-popup-wrap">
                                                    <button type="button"
                                                        class="student-popup-trigger underline decoration-dotted underline-offset-2 text-left"
                                                        aria-expanded="false">
                                                        {{ $item['student'] }}
                                                    </button>
                                                    <div
                                                        class="student-popup hidden absolute left-full top-1/2 z-30 ml-3 w-72 -translate-y-1/2 rounded-xl border border-indigo-200 bg-white p-3 text-xs text-slate-700 shadow-xl">
                                                        <p><span class="font-semibold text-indigo-700">Name:</span>
                                                            {{ $item['user_info']['name'] ?? 'N/A' }}</p>
                                                        <p><span class="font-semibold text-indigo-700">Email:</span>
                                                            {{ $item['user_info']['email'] ?? 'N/A' }}</p>
                                                        <p><span class="font-semibold text-indigo-700">Phone:</span>
                                                            {{ $item['user_info']['phone'] ?? 'N/A' }}</p>
                                                        <p><span class="font-semibold text-indigo-700">Year:</span>
                                                            {{ $item['user_info']['years'] ?? 'N/A' }}</p>
                                                        <p><span class="font-semibold text-indigo-700">Programme:</span>
                                                            {{ $item['user_info']['programme'] ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-right font-bold">{{ $item['total'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-4 py-6 text-center text-slate-500">No student
                                                statistics yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </article>

                    <article class="rounded-3xl border border-sky-200/80 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-sky-800">Top Topics by Name</h3>
                            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Top
                                {{ count($topTopics) }}</span>
                        </div>
                        <div class="overflow-hidden rounded-2xl border border-slate-100">
                            <table class="min-w-full divide-y divide-slate-100 text-sm">
                                <thead class="bg-slate-50 text-slate-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold">Topic Name</th>
                                        <th class="px-4 py-3 text-right font-semibold">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse($topTopics as $item)
                                        <tr>
                                            <td class="px-4 py-3">{{ $item['topic'] }}</td>
                                            <td class="px-4 py-3 text-right font-bold">{{ $item['total'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-4 py-6 text-center text-slate-500">No topic
                                                statistics yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </article>
                </section>

            </div>
        </section>
    </main>

    <script>
        const bookings = @json($chartBookings);

        const toDate = (value) => {
            if (!value) return null;
            const raw = String(value).trim();
            if (!raw) return null;
            const isoDateMatch = raw.match(/^(\d{4})-(\d{2})-(\d{2})(?:[ T].*)?$/);
            if (isoDateMatch) {
                const [, y, m, d] = isoDateMatch;
                return new Date(Number(y), Number(m) - 1, Number(d));
            }

            const dmyMatch = raw.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})(?:\s.*)?$/);
            if (dmyMatch) {
                const [, d, m, y] = dmyMatch;
                return new Date(Number(y), Number(m) - 1, Number(d));
            }
            const hasTime = raw.includes('T') || raw.includes(' ');
            const parsed = new Date(hasTime ? raw : `${raw}T00:00:00`);
            return Number.isNaN(parsed.getTime()) ? null : parsed;
        };

        const inRange = (date, fromRaw, toRaw) => {
            if (!date) return false;
            const from = toDate(fromRaw);
            const to = toDate(toRaw);
            if (from && date < from) return false;
            if (to) {
                const toInclusive = new Date(to);
                toInclusive.setHours(23, 59, 59, 999);
                if (date > toInclusive) return false;
            }
            return true;
        };

        const weekStart = (value) => {
            const d = toDate(value);
            if (!d) return null;
            const day = d.getDay();
            const diff = (day === 0 ? -6 : 1) - day;
            d.setDate(d.getDate() + diff);
            d.setHours(0, 0, 0, 0);
            return d;
        };

        const fmt = (d) =>
            `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;

        const getLastWeeks = (n) => {
            const now = new Date();
            const current = weekStart(now) || now;
            return Array.from({
                length: n
            }, (_, i) => {
                const x = new Date(current);
                x.setDate(current.getDate() - (n - 1 - i) * 7);
                return fmt(x);
            });
        };

        const getDateRangeWeeks = (weeks, startValue, endValue) => {
            const startDate = toDate(startValue);
            const endDate = toDate(endValue);

            if (startDate && endDate && startDate <= endDate) {
                startDate.setHours(0, 0, 0, 0);
                endDate.setHours(23, 59, 59, 999);
                return {
                    mode: 'date',
                    startDate,
                    endDate
                };
            }

            return {
                mode: 'weeks',
                weekList: getLastWeeks(weeks)
            };
        };

        const topicChartCtx = document.getElementById('topicsPieChart');
        const emergencyChartCtx = document.getElementById('emergencyLineChart');
        let topicChart;
        let emergencyChart;

        function renderTopicsPie(weeks, startDateValue = null, endDateValue = null) {
            const range = getDateRangeWeeks(weeks, startDateValue, endDateValue);
            const selected = bookings.filter((b) => {
                const dateObj = toDate(b.date);
                if (!dateObj) return false;

                if (range.mode === 'date') {
                    return dateObj >= range.startDate && dateObj <= range.endDate;
                }

                const week = weekStart(dateObj);
                return week ? range.weekList.includes(fmt(week)) : false;
            });
            const topicCount = {};
            selected.forEach(b => topicCount[b.topic] = (topicCount[b.topic] || 0) + 1);
            const top = Object.entries(topicCount).sort((a, b) => b[1] - a[1]).slice(0, 8);

            const hasData = top.length > 0;
            const labels = hasData ? top.map(x => x[0]) : ['No data for selected period'];
            const values = hasData ? top.map(x => x[1]) : [1];

            if (topicChart) topicChart.destroy();
            topicChart = new Chart(topicChartCtx, {
                type: 'pie',
                data: {
                    labels,
                    datasets: [{
                        label: 'Bookings',
                        data: values,
                        backgroundColor: hasData ? ['#0ea5e9', '#38bdf8', '#7dd3fc', '#0284c7', '#0369a1',
                            '#14b8a6', '#22d3ee',
                            '#60a5fa'
                        ] : ['#cbd5e1']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => hasData ?
                                    `${context.label}: ${context.raw}` : 'No topic bookings found'
                            }
                        }
                    }
                }
            });
        }

        function renderEmergencyLine(weeks, fromDate = null, toDateValue = null) {
            const range = getDateRangeWeeks(weeks, startDateValue, endDateValue);
            const weekList = range.mode === 'weeks' ? range.weekList : getLastWeeks(weeks);
            const counts = Object.fromEntries(weekList.map(w => [w, 0]));
            bookings.filter(b => b.is_emergency).forEach(b => {
                const dateObj = toDate(b.date);
                if (!dateObj) return;

                if (range.mode === 'date' && (dateObj < range.startDate || dateObj > range.endDate)) return;

                const week = weekStart(dateObj);
                if (!week) return;
                const w = fmt(week);
                if (counts[w] !== undefined) counts[w]++;
            });

            if (emergencyChart) emergencyChart.destroy();
            emergencyChart = new Chart(emergencyChartCtx, {
                type: 'line',
                data: {
                    labels: weekList,
                    datasets: [{
                        label: 'Emergency bookings',
                        data: weekList.map(w => counts[w]),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.2)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }

        const topicsFilter = document.getElementById('topics-weeks-filter');
        const topicsStartDate = document.getElementById('topics-start-date');
        const topicsEndDate = document.getElementById('topics-end-date');
        const emergencyFilter = document.getElementById('emergency-weeks-filter');
        const emergencyStartDate = document.getElementById('emergency-start-date');
        const emergencyEndDate = document.getElementById('emergency-end-date');

        const renderTopics = () => renderTopicsPie(
            parseInt(topicsFilter.value, 10),
            topicsStartDate.value,
            topicsEndDate.value
        );

        const renderEmergency = () => renderEmergencyLine(
            parseInt(emergencyFilter.value, 10),
            emergencyStartDate.value,
            emergencyEndDate.value
        );

        topicsFilter.addEventListener('change', renderTopics);
        topicsStartDate.addEventListener('change', renderTopics);
        topicsEndDate.addEventListener('change', renderTopics);
        emergencyFilter.addEventListener('change', renderEmergency);
        emergencyStartDate.addEventListener('change', renderEmergency);
        emergencyEndDate.addEventListener('change', renderEmergency);

        renderTopics();
        renderEmergency();

        document.querySelectorAll('.student-popup-wrap').forEach((wrap) => {
            const trigger = wrap.querySelector('.student-popup-trigger');
            const popup = wrap.querySelector('.student-popup');
            if (!trigger || !popup) return;

            trigger.addEventListener('click', (event) => {
                event.stopPropagation();
                const isHidden = popup.classList.contains('hidden');

                document.querySelectorAll('.student-popup').forEach((item) => item.classList.add('hidden'));
                document.querySelectorAll('.student-popup-trigger').forEach((btn) => btn.setAttribute(
                    'aria-expanded', 'false'));

                if (isHidden) {
                    popup.classList.remove('hidden');
                    trigger.setAttribute('aria-expanded', 'true');
                }
            });
        });

        document.addEventListener('click', () => {
            document.querySelectorAll('.student-popup').forEach((item) => item.classList.add('hidden'));
            document.querySelectorAll('.student-popup-trigger').forEach((btn) => btn.setAttribute('aria-expanded',
                'false'));
        });
    </script>
</body>

</html>
