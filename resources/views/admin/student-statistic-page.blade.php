<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Booking Statistics • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fade-up {
            0% {
                opacity: 0;
                transform: translateY(12px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes aurora-drift {
            0% {
                transform: translate3d(0, 0, 0) scale(1);
            }

            50% {
                transform: translate3d(24px, -18px, 0) scale(1.08);
            }

            100% {
                transform: translate3d(-12px, 16px, 0) scale(1);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                opacity: .35;
            }

            50% {
                opacity: .7;
            }
        }

        .animate-fade-up {
            animation: fade-up .5s ease-out both;
        }

        .animate-aurora-drift {
            animation: aurora-drift 14s ease-in-out infinite alternate;
        }

        .animate-pulse-glow {
            animation: pulse-glow 8s ease-in-out infinite;
        }

        .animation-delay-1 {
            animation-delay: .2s;
        }

        .animation-delay-2 {
            animation-delay: .4s;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 text-slate-700 overflow-x-hidden">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#dbeafe_0%,_#e0f2fe_28%,_#eef2ff_55%,_#f8fafc_100%)]">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        <div
            class="absolute -top-24 -left-20 w-[34rem] h-[34rem] rounded-full bg-cyan-300/40 blur-3xl animate-aurora-drift animate-pulse-glow">
        </div>
        <div
            class="absolute top-8 -right-24 w-[34rem] h-[34rem] rounded-full bg-indigo-300/35 blur-3xl animate-aurora-drift animate-pulse-glow animation-delay-1">
        </div>
        <div
            class="absolute -bottom-24 left-1/4 w-[30rem] h-[30rem] rounded-full bg-emerald-300/30 blur-3xl animate-aurora-drift animate-pulse-glow animation-delay-2">
        </div>
    </div>

    <main class="min-h-screen p-3 sm:p-6 lg:p-8">
        <section
            class="max-w-[96rem] mx-auto rounded-[1.6rem] sm:rounded-[2rem] border border-slate-200/80 bg-white/80 backdrop-blur-xl shadow-2xl overflow-hidden animate-fade-up">
            <header
                class="px-4 sm:px-7 py-4 border-b border-slate-200/80 bg-white/85 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs uppercase tracking-[0.14em] text-indigo-500 font-semibold">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight">Student Booking Statistics
                    </h1>
                    <p class="text-sm text-slate-600 mt-1 truncate">Analysis of booking topics and current request
                        states.</p>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">

                    <!-- Curved Back Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 14L4 9l5-5M4 9h10a7 7 0 110 14h-3" />
                    </svg>


                </a>
            </header>

            <div class="p-4 sm:p-6 lg:p-7 space-y-5">
                <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <article
                        class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">All Bookings</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ $statusTotals['all'] }}</p>
                    </article>
                    <article
                        class="rounded-2xl border border-amber-200 bg-amber-50/80 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-amber-700">Pending</p>
                        <p class="mt-2 text-2xl font-bold text-amber-700">{{ $statusTotals['pending'] }}</p>
                    </article>
                    <article
                        class="rounded-2xl border border-sky-200 bg-sky-50/80 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-sky-700">Approved</p>
                        <p class="mt-2 text-2xl font-bold text-sky-700">{{ $statusTotals['approved'] }}</p>
                    </article>
                    <article
                        class="rounded-2xl border border-rose-200 bg-rose-50/80 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-rose-700">Rejected</p>
                        <p class="mt-2 text-2xl font-bold text-rose-700">{{ $statusTotals['rejected'] }}</p>
                    </article>
                    <article
                        class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-emerald-700">Completed</p>
                        <p class="mt-2 text-2xl font-bold text-emerald-700">{{ $statusTotals['completed'] }}</p>
                    </article>
                </section>

                {{-- Graphs --}}
                <section class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                    <article
                        class="rounded-2xl border border-slate-200 bg-white/95 p-4 sm:p-5 shadow-sm animate-fade-up">
                        <h2 class="text-lg font-semibold text-slate-900">Booking status overview graph</h2>
                        <p class="text-sm text-slate-600 mt-1">Quick visual split of all booking request states.</p>
                        <div class="mt-4 min-h-64" id="status-chart" role="img"
                            aria-label="Booking status bar chart">
                        </div>
                    </article>

                    <article
                        class="rounded-2xl border border-slate-200 bg-white/95 p-4 sm:p-5 shadow-sm animate-fade-up animation-delay-1">
                        <h2 class="text-lg font-semibold text-slate-900">Top topics graph</h2>
                        <p class="text-sm text-slate-600 mt-1">Most requested counselling topics (top 6).</p>
                        <div class="mt-4 min-h-64" id="topic-chart" role="img" aria-label="Top topic line chart">
                        </div>
                    </article>
                </section>

                <section class="grid lg:grid-cols-2 gap-4">
                    <article
                        class="rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-sm animate-fade-up animation-delay-1">
                        <h2 class="text-lg font-semibold text-slate-900">Bookings by topic / category</h2>
                        <p class="text-sm text-slate-600 mt-1">Identify which counselling topics are requested most
                            often.</p>

                        <div class="mt-4 overflow-auto">
                            <table class="w-full min-w-[680px] text-sm">
                                <thead>
                                    <tr class="text-left text-slate-500 border-b border-slate-200">
                                        <th class="py-2 pr-3">Topic</th>
                                        <th class="py-2 pr-3">Total</th>
                                        <th class="py-2 pr-3">Pending</th>
                                        <th class="py-2 pr-3">Approved</th>
                                        <th class="py-2 pr-3">Rejected</th>
                                        <th class="py-2">Completed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topicStats as $topic)
                                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                                            <td class="py-2 pr-3 font-medium text-slate-700">{{ $topic['topic'] }}</td>
                                            <td class="py-2 pr-3 text-slate-700">{{ $topic['total'] }}</td>
                                            <td class="py-2 pr-3 text-amber-700">{{ $topic['pending'] }}</td>
                                            <td class="py-2 pr-3 text-sky-700">{{ $topic['approved'] }}</td>
                                            <td class="py-2 pr-3 text-rose-700">{{ $topic['rejected'] }}</td>
                                            <td class="py-2 text-emerald-700">{{ $topic['completed'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="py-2 text-slate-500" colspan="6">No topic data available yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </article>

                    <article
                        class="rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-sm animate-fade-up animation-delay-2">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Students & lecturers booking activity
                                </h2>
                                <p class="text-sm text-slate-600 mt-1">Switch between listing and bar chart, then filter
                                    by user type.</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:w-auto">
                                <label class="text-xs font-semibold text-slate-500">
                                    View
                                    <select id="people-view-mode"
                                        class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-sm text-slate-700">
                                        <option value="list">Listing</option>
                                        <option value="bar">Bar chart</option>
                                    </select>
                                </label>
                                <label class="text-xs font-semibold text-slate-500">
                                    Filter
                                    <select id="people-role-filter"
                                        class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-sm text-slate-700">
                                        <option value="all">All (student + lecturer)</option>
                                        <option value="lecturer">Lecturer only</option>
                                        <option value="student">Student only</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="mt-4" id="people-activity-container"></div>
                    </article>
                </section>

            </div>
        </section>

        <section
            class="max-w-[96rem] mx-auto mt-5 rounded-[1.6rem] sm:rounded-[2rem] border border-rose-200/70 bg-gradient-to-br from-rose-50 via-white to-orange-50 shadow-xl overflow-hidden animate-fade-up">
            <div class="p-4 sm:p-6 lg:p-7">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.14em] text-rose-600 font-semibold">Urgent Monitoring
                        </p>
                        <h2 class="text-xl sm:text-2xl font-bold text-rose-900">Emergency booking statistics</h2>
                        <p class="text-sm text-rose-700 mt-1">Filter by date and review status distribution in pie
                            chart.</p>
                    </div>
                    <form method="GET" action="{{ route('admin.student-statistics') }}"
                        class="grid grid-cols-1 sm:grid-cols-4 gap-2 w-full lg:w-auto">
                        <label class="text-xs font-semibold text-rose-700">
                            From date
                            <input type="date" name="emergency_from" value="{{ $emergencyDateFilter['from'] }}"
                                class="mt-1 w-full rounded-lg border border-rose-200 bg-white px-2.5 py-2 text-sm text-slate-700" />
                        </label>
                        <label class="text-xs font-semibold text-rose-700">
                            To date
                            <input type="date" name="emergency_to" value="{{ $emergencyDateFilter['to'] }}"
                                class="mt-1 w-full rounded-lg border border-rose-200 bg-white px-2.5 py-2 text-sm text-slate-700" />
                        </label>
                        <label class="text-xs font-semibold text-rose-700">
                            Role filter
                            <select name="emergency_role"
                                class="mt-1 w-full rounded-lg border border-rose-200 bg-white px-2.5 py-2 text-sm text-slate-700">
                                <option value="all" @selected(($emergencyDateFilter['role'] ?? 'all') === 'all')>All</option>
                                <option value="lecturer" @selected(($emergencyDateFilter['role'] ?? '') === 'lecturer')>Lecturer</option>
                                <option value="student" @selected(($emergencyDateFilter['role'] ?? '') === 'student')>Student</option>
                            </select>
                        </label>
                        <button type="submit"
                            class="self-end rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-4 py-2 transition">Apply
                            filter</button>
                    </form>
                </div>

                <div class="mt-5 grid lg:grid-cols-1 gap-5 items-start">
                    <div class="rounded-2xl border border-rose-200 bg-white p-4 shadow-sm">
                        @php
                            $emergencyTotal = max((int) $emergencyStats['total'], 1);
                            $isAllRole = ($emergencyDateFilter['role'] ?? 'all') === 'all';
                            $pendingPercent = round(($emergencyStats['pending'] / $emergencyTotal) * 100, 2);
                            $approvedPercent = round(($emergencyStats['approved'] / $emergencyTotal) * 100, 2);
                            $rejectedPercent = round(($emergencyStats['rejected'] / $emergencyTotal) * 100, 2);
                            $completedPercent = max(
                                0,
                                round(100 - ($pendingPercent + $approvedPercent + $rejectedPercent), 2),
                            );
                            $lecturerPercent = round(
                                (($emergencyStats['lecturer_total'] ?? 0) / $emergencyTotal) * 100,
                                2,
                            );
                            $studentPercent = max(0, round(100 - $lecturerPercent, 2));

                            $pieBackground = $isAllRole
                                ? "conic-gradient(#8b5cf6 0% {$lecturerPercent}%, #0ea5e9 {$lecturerPercent}% 100%)"
                                : "conic-gradient(#f59e0b 0% {$pendingPercent}%, #0ea5e9 {$pendingPercent}% " .
                                    ($pendingPercent + $approvedPercent) .
                                    '%, #f43f5e ' .
                                    ($pendingPercent + $approvedPercent) .
                                    '% ' .
                                    ($pendingPercent + $approvedPercent + $rejectedPercent) .
                                    '%, #10b981 ' .
                                    ($pendingPercent + $approvedPercent + $rejectedPercent) .
                                    '% 100%)';
                        @endphp
                        <h3 class="text-sm font-semibold text-rose-800 uppercase tracking-wide">
                            {{ $isAllRole ? 'Emergency role comparison pie chart' : 'Emergency status pie chart' }}
                        </h3>
                        <div class="mt-4 mx-auto h-52 w-52 rounded-full border-8 border-white shadow-inner"
                            style="background: {{ $pieBackground }}"></div>
                        <p class="mt-3 text-center text-sm text-slate-600">Total emergency bookings: <span
                                class="font-bold text-rose-700">{{ $emergencyStats['total'] }}</span></p>
                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            @if ($isAllRole)
                                <div
                                    class="rounded-lg border border-violet-200 bg-violet-50 px-3 py-2 text-violet-700">
                                    Lecturer:
                                    <span class="font-semibold">{{ $emergencyStats['lecturer_total'] }}</span>
                                </div>
                                <div class="rounded-lg border border-sky-200 bg-sky-50 px-3 py-2 text-sky-700">Student:
                                    <span class="font-semibold">{{ $emergencyStats['student_total'] }}</span>
                                </div>
                            @else
                                <div class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-amber-700">
                                    Pending:
                                    <span class="font-semibold">{{ $emergencyStats['pending'] }}</span>
                                </div>
                                <div class="rounded-lg border border-sky-200 bg-sky-50 px-3 py-2 text-sky-700">
                                    Approved:
                                    <span class="font-semibold">{{ $emergencyStats['approved'] }}</span>
                                </div>
                                <div class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-rose-700">
                                    Rejected:
                                    <span class="font-semibold">{{ $emergencyStats['rejected'] }}</span>
                                </div>
                                <div
                                    class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-emerald-700">
                                    Completed:
                                    <span class="font-semibold">{{ $emergencyStats['completed'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        <div class="rounded-xl border border-rose-200 bg-white p-3">
                            <p class="text-xs uppercase tracking-wide text-rose-700 font-semibold">Total</p>
                            <p class="mt-1 text-xl font-bold text-rose-900">{{ $emergencyStats['total'] }}</p>
                        </div>
                        <div class="rounded-xl border border-amber-200 bg-white p-3">
                            <p class="text-xs uppercase tracking-wide text-amber-700 font-semibold">Pending</p>
                            <p class="mt-1 text-xl font-bold text-amber-700">{{ $emergencyStats['pending'] }}</p>
                        </div>
                        <div class="rounded-xl border border-sky-200 bg-white p-3">
                            <p class="text-xs uppercase tracking-wide text-sky-700 font-semibold">Approved</p>
                            <p class="mt-1 text-xl font-bold text-sky-700">{{ $emergencyStats['approved'] }}</p>
                        </div>
                        <div class="rounded-xl border border-rose-200 bg-white p-3">
                            <p class="text-xs uppercase tracking-wide text-rose-700 font-semibold">Rejected</p>
                            <p class="mt-1 text-xl font-bold text-rose-700">{{ $emergencyStats['rejected'] }}</p>
                        </div>
                        <div class="rounded-xl border border-emerald-200 bg-white p-3">
                            <p class="text-xs uppercase tracking-wide text-emerald-700 font-semibold">Completed</p>
                            <p class="mt-1 text-xl font-bold text-emerald-700">{{ $emergencyStats['completed'] }}</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-4">
                        <h4 class="text-sm font-semibold text-slate-800">Emergency booking names listing</h4>
                        <div class="mt-3 overflow-auto">
                            <table class="w-full min-w-[620px] text-sm">
                                <thead>
                                    <tr class="text-left text-slate-500 border-b border-slate-200">
                                        <th class="py-2 pr-3">Name</th>
                                        <th class="py-2 pr-3">Email</th>
                                        <th class="py-2 pr-3">Role</th>
                                        <th class="py-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($emergencyListing as $item)
                                        <tr class="border-b border-slate-100">
                                            <td class="py-2 pr-3 font-medium text-slate-700">
                                                <button type="button"
                                                    class="text-left text-sky-700 hover:text-sky-900 underline decoration-sky-300"
                                                    data-profile-open data-name="{{ $item['name'] }}"
                                                    data-email="{{ $item['email'] }}"
                                                    data-role="{{ $item['role'] }}"
                                                    data-status="{{ $item['status'] }}"
                                                    data-phone="{{ $item['phone'] }}"
                                                    data-matriks="{{ $item['no_matriks'] }}"
                                                    data-years="{{ $item['years'] }}"
                                                    data-programme="{{ $item['programme'] }}"
                                                    data-pic="{{ $item['profile_pic'] }}">
                                                    {{ $item['name'] }}
                                                </button>
                                            </td>
                                            <td class="py-2 pr-3 text-slate-600">{{ $item['email'] }}</td>
                                            <td class="py-2 pr-3">
                                                <span
                                                    class="inline-flex rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $item['role'] === 'lecturer' ? 'bg-violet-100 text-violet-700' : 'bg-sky-100 text-sky-700' }}">
                                                    {{ $item['role'] }}
                                                </span>
                                            </td>
                                            <td class="py-2 text-slate-700 capitalize">{{ $item['status'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="py-2 text-slate-500" colspan="4">No emergency booking names
                                                for
                                                this filter.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div id="profile-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-slate-200">
                <div class="p-4 sm:p-5 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900">User Profile Details</h3>
                    <button type="button" id="profile-modal-close"
                        class="rounded-lg border border-slate-200 px-2.5 py-1 text-sm text-slate-600 hover:bg-slate-50">Close</button>
                </div>
                <div class="p-4 sm:p-5">
                    <div class="flex items-center gap-4">
                        <img id="profile-modal-pic" src="/images/default-profile.svg" alt="Profile picture"
                            class="h-20 w-20 rounded-full object-cover border border-slate-200" />
                        <div>
                            <p id="profile-modal-name" class="text-lg font-semibold text-slate-900">-</p>
                            <p id="profile-modal-role" class="text-sm text-slate-600 capitalize">-</p>
                        </div>
                    </div>
                    <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                            <dt class="text-slate-500">Email</dt>
                            <dd id="profile-modal-email" class="font-medium text-slate-800">-</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Phone</dt>
                            <dd id="profile-modal-phone" class="font-medium text-slate-800">-</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">No Matriks</dt>
                            <dd id="profile-modal-matriks" class="font-medium text-slate-800">-</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Programme</dt>
                            <dd id="profile-modal-programme" class="font-medium text-slate-800">-</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Year</dt>
                            <dd id="profile-modal-years" class="font-medium text-slate-800">-</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Emergency Status</dt>
                            <dd id="profile-modal-status" class="font-medium text-slate-800 capitalize">-</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </main>

    <script>
        const createBarChart = (containerId, entries, options = {}) => {
            const container = document.getElementById(containerId);
            if (!container) return;

            if (!Array.isArray(entries) || entries.length === 0) {
                container.innerHTML = '<p class="text-sm text-slate-500">No data available for graph yet.</p>';
                return;
            }

            const maxValue = Math.max(...entries.map((item) => Number(item.value) || 0), 1);
            const chartWidth = 720;
            const chartHeight = 300;
            const paddingX = 44;
            const paddingY = 32;
            const plotWidth = chartWidth - (paddingX * 2);
            const plotHeight = chartHeight - (paddingY * 2);
            const barGap = 18;
            const barWidth = Math.max((plotWidth - (barGap * (entries.length - 1))) / entries.length, 26);

            const bars = entries.map((item, index) => {
                const value = Number(item.value) || 0;
                const barHeight = maxValue === 0 ? 0 : (value / maxValue) * plotHeight;
                const x = paddingX + (index * (barWidth + barGap));
                const y = paddingY + (plotHeight - barHeight);
                return {
                    label: item.label,
                    value,
                    x,
                    y,
                    barHeight
                };
            });

            container.innerHTML = `
                <div class="w-full overflow-x-auto pb-1">
                    <svg viewBox="0 0 ${chartWidth} ${chartHeight}" class="w-full min-w-[520px]" role="img" aria-label="Bar chart for booking status overview">
                        <line x1="${paddingX}" y1="${chartHeight - paddingY}" x2="${chartWidth - paddingX}" y2="${chartHeight - paddingY}" stroke="#94a3b8" stroke-width="1.2" />
                        ${bars.map((bar) => `
                                        <g>
                                            <rect x="${bar.x}" y="${bar.y}" width="${barWidth}" height="${bar.barHeight}" rx="8" fill="url(#statusBarGradient)" />
                                            <text x="${bar.x + (barWidth / 2)}" y="${bar.y - 8}" text-anchor="middle" font-size="12" fill="#334155" font-weight="600">${bar.value}</text>
                                            <text x="${bar.x + (barWidth / 2)}" y="${chartHeight - 10}" text-anchor="middle" font-size="11" fill="#475569">${bar.label}</text>
                                        </g>
                                    `).join('')}
                        <defs>
                            <linearGradient id="statusBarGradient" x1="0" y1="0" x2="1" y2="0">
                                <stop offset="0%" stop-color="#f59e0b" />
                                <stop offset="100%" stop-color="#f43f5e" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            `;
        };

        const createLineChart = (containerId, entries, options = {}) => {
            const container = document.getElementById(containerId);
            if (!container) return;

            if (!Array.isArray(entries) || entries.length === 0) {
                container.innerHTML = '<p class="text-sm text-slate-500">No data available for graph yet.</p>';
                return;
            }

            const color = options.color || '#0ea5e9';
            const maxValue = Math.max(...entries.map((item) => Number(item.value) || 0), 1);
            const minValue = 0;
            const chartWidth = 760;
            const chartHeight = 320;
            const paddingX = 50;
            const paddingY = 36;
            const plotWidth = chartWidth - (paddingX * 2);
            const plotHeight = chartHeight - (paddingY * 2);
            const stepX = entries.length > 1 ? plotWidth / (entries.length - 1) : 0;

            const points = entries.map((item, index) => {
                const value = Number(item.value) || 0;
                const x = paddingX + (index * stepX);
                const y = paddingY + plotHeight - (((value - minValue) / (maxValue - minValue || 1)) *
                    plotHeight);
                return {
                    label: item.label,
                    value,
                    x,
                    y
                };
            });

            const path = points.map((point, index) => `${index === 0 ? 'M' : 'L'} ${point.x} ${point.y}`).join(' ');
            const ticks = 4;
            const yAxisLines = Array.from({
                length: ticks + 1
            }, (_, index) => {
                const value = Math.round((maxValue / ticks) * (ticks - index));
                const y = paddingY + ((plotHeight / ticks) * index);
                return {
                    value,
                    y
                };
            });

            container.innerHTML = `
                <div class="w-full overflow-x-auto pb-1">
                    <svg viewBox="0 0 ${chartWidth} ${chartHeight}" class="w-full min-w-[560px]" role="img" aria-label="Line graph for top requested counselling topics">
                        ${yAxisLines.map((line) => `
                                        <line x1="${paddingX}" y1="${line.y}" x2="${chartWidth - paddingX}" y2="${line.y}" stroke="#e2e8f0" stroke-width="1" />
                                        <text x="${paddingX - 12}" y="${line.y + 4}" text-anchor="end" font-size="10" fill="#64748b">${line.value}</text>
                                    `).join('')}
                        <line x1="${paddingX}" y1="${chartHeight - paddingY}" x2="${chartWidth - paddingX}" y2="${chartHeight - paddingY}" stroke="#94a3b8" stroke-width="1.2" />
                        <path d="${path}" fill="none" stroke="${color}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        ${points.map((point) => `
                                        <g>
                                            <circle cx="${point.x}" cy="${point.y}" r="5" fill="${color}" />
                                            <title>${point.label}: ${point.value}</title>
                                        </g>
                                    `).join('')}
                        ${points.map((point) => `
                                        <text x="${point.x}" y="${chartHeight - 12}" text-anchor="middle" font-size="10" fill="#475569">${point.label.length > 16 ? `${point.label.slice(0, 16)}…` : point.label}</text>
                                    `).join('')}
                    </svg>
                </div>
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                    ${entries.map((entry) => `
                                    <div class="rounded-lg border border-slate-200 px-3 py-2 text-sm flex items-center justify-between bg-slate-50/70">
                                        <span class="text-slate-600 truncate pr-2">${entry.label}</span>
                                        <span class="font-semibold text-slate-800">${Number(entry.value) || 0}</span>
                                    </div>
                                `).join('')}
                </div>
            `;
        };

        const renderPeopleActivity = (entries, filter = 'all', view = 'list') => {
            const container = document.getElementById('people-activity-container');
            if (!container) return;

            const filtered = entries.filter((entry) => filter === 'all' ? true : entry.role === filter);

            if (filtered.length === 0) {
                container.innerHTML = '<p class="text-sm text-slate-500">No booking data found for this filter.</p>';
                return;
            }

            if (view === 'bar') {
                const maxValue = Math.max(...filtered.map((item) => Number(item.total) || 0), 1);
                container.innerHTML = `
                    <div class="space-y-3">
                        ${filtered.map((item) => {
                            const value = Number(item.total) || 0;
                            const width = Math.max((value / maxValue) * 100, value > 0 ? 4 : 0);
                            const badgeClass = item.role === 'lecturer'
                                ? 'bg-violet-100 text-violet-700'
                                : 'bg-sky-100 text-sky-700';
                            return `
                                            <div>
                                                <div class="flex items-center justify-between gap-2 text-xs sm:text-sm mb-1">
                                                    <div class="min-w-0">
                                                        <p class="font-semibold text-slate-700 truncate">${item.student}</p>
                                                        <p class="text-slate-500 truncate">${item.email}</p>
                                                    </div>
                                                    <div class="text-right shrink-0">
                                                        <span class="inline-flex rounded-full px-2 py-0.5 text-[11px] font-semibold ${badgeClass}">${item.role}</span>
                                                        <p class="text-slate-700 font-semibold mt-1">${value}</p>
                                                    </div>
                                                </div>
                                                <div class="h-3 rounded-full bg-slate-100 overflow-hidden">
                                                    <div class="h-full bg-gradient-to-r from-indigo-500 to-cyan-500 rounded-full" style="width: ${width}%"></div>
                                                </div>
                                            </div>
                                        `;
                        }).join('')}
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <div class="overflow-auto">
                    <table class="w-full min-w-[680px] text-sm">
                        <thead>
                            <tr class="text-left text-slate-500 border-b border-slate-200">
                                <th class="py-2 pr-3">Name</th>
                                <th class="py-2 pr-3">Email</th>
                                <th class="py-2 pr-3">Role</th>
                                <th class="py-2 pr-3">Total</th>
                                <th class="py-2 pr-3">Pending</th>
                                <th class="py-2">Approved</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filtered.map((item) => `
                                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                                                <td class="py-2 pr-3 font-medium text-slate-700">${item.student}</td>
                                                <td class="py-2 pr-3 text-slate-600">${item.email}</td>
                                                <td class="py-2 pr-3">
                                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[11px] font-semibold ${item.role === 'lecturer' ? 'bg-violet-100 text-violet-700' : 'bg-sky-100 text-sky-700'}">${item.role}</span>
                                                </td>
                                                <td class="py-2 pr-3 text-slate-700">${item.total}</td>
                                                <td class="py-2 pr-3 text-amber-700">${item.active_pending}</td>
                                                <td class="py-2 text-sky-700">${item.active_approved}</td>
                                            </tr>
                                        `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        };

        createBarChart('status-chart', [{
                label: 'Pending',
                value: @json($statusTotals['pending'])
            },
            {
                label: 'Approved',
                value: @json($statusTotals['approved'])
            },
            {
                label: 'Rejected',
                value: @json($statusTotals['rejected'])
            },
            {
                label: 'Completed',
                value: @json($statusTotals['completed'])
            },
        ], {
            barColor: 'from-amber-500 to-rose-500'
        });

        createLineChart('topic-chart', @json(collect($topicStats)->take(6)->map(fn($topic) => [
                        'label' => $topic['topic'],
                        'value' => $topic['total'],
                    ])->values()), {
            color: '#10b981'
        });

        const peopleStats = @json($studentStats);
        const viewSelect = document.getElementById('people-view-mode');
        const roleSelect = document.getElementById('people-role-filter');

        const rerenderPeople = () => {
            renderPeopleActivity(peopleStats, roleSelect?.value || 'all', viewSelect?.value || 'list');
        };

        viewSelect?.addEventListener('change', rerenderPeople);
        roleSelect?.addEventListener('change', rerenderPeople);
        rerenderPeople();

          const profileModal = document.getElementById('profile-modal');
        const closeProfileModal = () => {
            profileModal?.classList.add('hidden');
            profileModal?.classList.remove('flex');
        };

        document.querySelectorAll('[data-profile-open]').forEach((button) => {
            button.addEventListener('click', () => {
                document.getElementById('profile-modal-name').textContent = button.dataset.name || '-';
                document.getElementById('profile-modal-email').textContent = button.dataset.email || '-';
                document.getElementById('profile-modal-role').textContent = button.dataset.role || '-';
                document.getElementById('profile-modal-phone').textContent = button.dataset.phone || '-';
                document.getElementById('profile-modal-matriks').textContent = button.dataset.matriks || '-';
                document.getElementById('profile-modal-programme').textContent = button.dataset.programme || '-';
                document.getElementById('profile-modal-years').textContent = button.dataset.years || '-';
                document.getElementById('profile-modal-status').textContent = button.dataset.status || '-';
                document.getElementById('profile-modal-pic').src = button.dataset.pic || '/images/default-profile.svg';

                profileModal?.classList.remove('hidden');
                profileModal?.classList.add('flex');
            });
        });

        document.getElementById('profile-modal-close')?.addEventListener('click', closeProfileModal);
        profileModal?.addEventListener('click', (event) => {
            if (event.target === profileModal) {
                closeProfileModal();
            }
        });
    </script>

</body>

</html>
