<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Booking Statistics • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-700 overflow-x-hidden">
    <main class="min-h-screen p-3 sm:p-6 lg:p-8">
        <section
            class="max-w-[96rem] mx-auto rounded-[1.6rem] sm:rounded-[2rem] border border-slate-200/80 bg-white/90 backdrop-blur-xl shadow-2xl overflow-hidden">
            <header
                class="px-4 sm:px-7 py-4 border-b border-slate-200/80 bg-white/85 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs uppercase tracking-[0.14em] text-indigo-500 font-semibold">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight">Student Booking Statistics</h1>
                    <p class="text-sm text-slate-600 mt-1 truncate">Analysis of booking topics and current request states.</p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.dashboard') }}"
                        class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 hover:border-sky-200 hover:text-sky-700 transition">Back
                        to dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:from-sky-700 hover:to-indigo-700 transition shadow-sm">Logout</button>
                    </form>
                </div>
            </header>

            <div class="p-4 sm:p-6 lg:p-7 space-y-5">
                <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">All Bookings</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">{{ $statusTotals['all'] }}</p>
                    </article>
                    <article class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-amber-700">Pending</p>
                        <p class="mt-2 text-2xl font-bold text-amber-700">{{ $statusTotals['pending'] }}</p>
                    </article>
                    <article class="rounded-2xl border border-sky-200 bg-sky-50 p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-sky-700">Approved</p>
                        <p class="mt-2 text-2xl font-bold text-sky-700">{{ $statusTotals['approved'] }}</p>
                    </article>
                    <article class="rounded-2xl border border-rose-200 bg-rose-50 p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-rose-700">Rejected</p>
                        <p class="mt-2 text-2xl font-bold text-rose-700">{{ $statusTotals['rejected'] }}</p>
                    </article>
                    <article class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-emerald-700">Completed</p>
                        <p class="mt-2 text-2xl font-bold text-emerald-700">{{ $statusTotals['completed'] }}</p>
                    </article>
                </section>

                <section class="grid lg:grid-cols-2 gap-4">
                    <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <h2 class="text-lg font-semibold text-slate-900">Bookings by topic / category</h2>
                        <p class="text-sm text-slate-600 mt-1">Identify which counselling topics are requested most often.</p>

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
                                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                                            <td class="py-2 pr-3 font-medium text-slate-700">{{ $topic['topic'] }}</td>
                                            <td class="py-2 pr-3 text-slate-700">{{ $topic['total'] }}</td>
                                            <td class="py-2 pr-3 text-amber-700">{{ $topic['pending'] }}</td>
                                            <td class="py-2 pr-3 text-sky-700">{{ $topic['approved'] }}</td>
                                            <td class="py-2 pr-3 text-rose-700">{{ $topic['rejected'] }}</td>
                                            <td class="py-2 text-emerald-700">{{ $topic['completed'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="py-2 text-slate-500" colspan="6">No topic data available yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <h2 class="text-lg font-semibold text-slate-900">Top students by booking activity</h2>
                        <p class="text-sm text-slate-600 mt-1">Current booking load per student (pending + approved active requests).</p>

                        <div class="mt-4 overflow-auto">
                            <table class="w-full min-w-[620px] text-sm">
                                <thead>
                                    <tr class="text-left text-slate-500 border-b border-slate-200">
                                        <th class="py-2 pr-3">Student</th>
                                        <th class="py-2 pr-3">Email</th>
                                        <th class="py-2 pr-3">Total</th>
                                        <th class="py-2 pr-3">Pending</th>
                                        <th class="py-2">Approved</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($studentStats as $student)
                                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                                            <td class="py-2 pr-3 font-medium text-slate-700">{{ $student['student'] }}</td>
                                            <td class="py-2 pr-3 text-slate-600">{{ $student['email'] }}</td>
                                            <td class="py-2 pr-3 text-slate-700">{{ $student['total'] }}</td>
                                            <td class="py-2 pr-3 text-amber-700">{{ $student['active_pending'] }}</td>
                                            <td class="py-2 text-sky-700">{{ $student['active_approved'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="py-2 text-slate-500" colspan="5">No student booking data available yet.</td>
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
</body>

</html>
