<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Counsellor Dashboard • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
    @php
        $pendingCount = collect($applications)->where('status', 'Menunggu')->count();
        $approvedCount = collect($applications)->where('status', 'Diluluskan')->count();
        $bookedSlots = collect($scheduleSlots)->where('slot_status', 'Ditempah')->count();
        $completedCount = collect($sessionRecords)->count();
    @endphp

    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        <div class="absolute -top-24 -left-24 h-[26rem] w-[26rem] rounded-full bg-sky-300/25 blur-3xl"></div>
        <div class="absolute top-12 -right-20 h-[24rem] w-[24rem] rounded-full bg-violet-300/20 blur-3xl"></div>
    </div>

    <main class="mx-auto min-h-screen max-w-6xl p-4 sm:p-8">
        <section class="rounded-[2rem] border border-slate-200/80 bg-white/85 backdrop-blur-xl shadow-2xl overflow-hidden">
            <header class="border-b border-slate-200/80 bg-white/90 px-5 py-4 sm:px-7">
                <div class="flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3">
                    <a href="{{ route('profile.edit') }}"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-300 bg-white text-sm font-bold text-slate-700 hover:border-sky-300 hover:text-sky-700 transition"
                        title="Profile">
                        {{ strtoupper(substr($user->name ?? 'C', 0, 1)) }}
                    </a>

                    <div class="min-w-[180px] flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2">
                        <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                        <h1 class="text-sm sm:text-base font-semibold text-slate-800">Counsellor Session Dashboard</h1>
                    </div>

                    <a href="{{ route('chat.index') }}"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                        Chat
                    </a>

                    <a href="{{ route('counsellor.dashboard') }}"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                        Refresh
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <div class="space-y-5 p-5 sm:p-7">
                <section class="grid gap-4 lg:grid-cols-2">
                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full border border-sky-200 bg-sky-50 text-3xl">🧑‍🏫</div>

                        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-center text-sm font-semibold text-slate-700">
                            Pending Requests: {{ $pendingCount }}
                        </div>

                        <div class="mt-4 max-h-64 overflow-auto rounded-xl border border-slate-200">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                                    <tr>
                                        <th class="px-3 py-2 font-semibold">Student</th>
                                        <th class="px-3 py-2 font-semibold">Date</th>
                                        <th class="px-3 py-2 font-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse ($applications as $application)
                                        <tr>
                                            <td class="px-3 py-2 font-medium text-slate-700">{{ $application['student'] }}</td>
                                            <td class="px-3 py-2 text-slate-600">{{ $application['request_date'] }}</td>
                                            <td class="px-3 py-2">
                                                <span
                                                    class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $application['status'] === 'Diluluskan' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                                    {{ $application['status'] === 'Diluluskan' ? 'Approved' : 'Pending' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-3 py-5 text-center text-slate-500">No applications yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <a href="{{ route('chat.index') }}"
                            class="mt-4 inline-flex rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">
                            Button 1
                        </a>
                    </article>

                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full border border-violet-200 bg-violet-50 text-3xl">📅</div>

                        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-center text-sm font-semibold text-slate-700">
                            Approved: {{ $approvedCount }} • Booked: {{ $bookedSlots }} • Completed: {{ $completedCount }}
                        </div>

                        <ul class="mt-4 max-h-64 space-y-2 overflow-auto rounded-xl border border-slate-200 bg-white p-3 text-sm">
                            @forelse ($scheduleSlots as $slot)
                                <li class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-medium text-slate-700">{{ $slot['date'] }} • {{ $slot['time'] }}</span>
                                        <span
                                            class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $slot['slot_status'] === 'Selesai' ? 'bg-violet-100 text-violet-700' : 'bg-sky-100 text-sky-700' }}">
                                            {{ $slot['slot_status'] }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <li class="text-slate-500">No schedule entries yet.</li>
                            @endforelse
                        </ul>

                        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Recent Session Notes</p>
                            <ul class="mt-2 space-y-2 text-sm">
                                @forelse ($sessionRecords as $record)
                                    <li class="rounded-lg border border-slate-200 bg-white px-3 py-2">
                                        <p class="font-medium text-slate-700">{{ $record['student'] }} <span class="text-xs font-normal text-slate-500">({{ $record['date'] }})</span></p>
                                        <p class="text-slate-600">{{ $record['notes'] ?: 'No notes available.' }}</p>
                                    </li>
                                @empty
                                    <li class="text-slate-500">No completed session notes yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    </article>
                </section>

                <footer class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <div class="flex flex-wrap items-center justify-between gap-2 text-sm text-slate-600">
                        <span>Total Applications: {{ count($applications) }}</span>
                        <span>Theme matched with Home/Admin (slate + sky palette)</span>
                        <a href="{{ route('profile.edit') }}" class="font-medium text-sky-700 hover:text-sky-800">Manage Profile</a>
                    </div>
                </footer>
            </div>
        </section>
    </main>
</body>

</html>
