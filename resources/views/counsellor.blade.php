<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Counselor Dashboard • E-Health</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    @php
        $pendingCount = collect($applications)->where('status', 'Menunggu')->count();
        $approvedCount = collect($applications)->where('status', 'Diluluskan')->count();
        $bookedSlots = collect($scheduleSlots)->where('slot_status', 'Ditempah')->count();
        $completedSessions = count($sessionRecords);
    @endphp

    <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-300/40">
            <header class="bg-gradient-to-r from-sky-700 via-cyan-700 to-indigo-700 px-6 py-6 text-white sm:px-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/35 bg-white/15 text-xl font-bold">
                            {{ strtoupper(substr($user->name ?? 'C', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.22em] text-cyan-100">E-Health Portal</p>
                            <h1 class="text-xl font-bold sm:text-2xl">Counselor Dashboard</h1>
                            <p class="text-sm text-cyan-100">Track student requests, upcoming sessions, and counseling notes.</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('chat.index') }}"
                            class="rounded-lg border border-white/40 bg-white/10 px-4 py-2 text-sm font-semibold transition hover:bg-white/20">
                            Chat
                        </a>
                        <a href="{{ route('profile.edit') }}"
                            class="rounded-lg border border-white/40 bg-white/10 px-4 py-2 text-sm font-semibold transition hover:bg-white/20">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="rounded-lg border border-rose-300/70 bg-rose-500 px-4 py-2 text-sm font-semibold transition hover:bg-rose-600">
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <div class="px-6 py-6 sm:px-8">
                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-2xl border border-amber-100 bg-amber-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Pending Requests</p>
                        <p class="mt-2 text-3xl font-bold text-amber-900">{{ $pendingCount }}</p>
                    </article>
                    <article class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Approved Requests</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-900">{{ $approvedCount }}</p>
                    </article>
                    <article class="rounded-2xl border border-sky-100 bg-sky-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-sky-700">Booked Slots</p>
                        <p class="mt-2 text-3xl font-bold text-sky-900">{{ $bookedSlots }}</p>
                    </article>
                    <article class="rounded-2xl border border-violet-100 bg-violet-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-violet-700">Completed Sessions</p>
                        <p class="mt-2 text-3xl font-bold text-violet-900">{{ $completedSessions }}</p>
                    </article>
                </section>

                <section class="mt-6 grid gap-6 lg:grid-cols-5">
                    <article class="lg:col-span-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-lg font-bold text-slate-900">Incoming Requests</h2>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ count($applications) }} total</span>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-600">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">Student</th>
                                        <th class="px-4 py-3 font-semibold">Date</th>
                                        <th class="px-4 py-3 font-semibold">Topic</th>
                                        <th class="px-4 py-3 font-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse ($applications as $application)
                                        <tr>
                                            <td class="px-4 py-3 font-medium text-slate-800">{{ $application['student'] }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ $application['request_date'] }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ $application['topic'] ?: 'General counseling support' }}</td>
                                            <td class="px-4 py-3">
                                                @if ($application['status'] === 'Diluluskan')
                                                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Approved</span>
                                                @else
                                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-slate-500">No counseling requests yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </article>

                    <article class="lg:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">Upcoming Schedule</h2>
                        <p class="mt-1 text-sm text-slate-600">Approved and completed time slots.</p>

                        <ul class="mt-4 space-y-3">
                            @forelse ($scheduleSlots as $slot)
                                <li class="rounded-xl border border-slate-200 bg-white p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800">{{ $slot['date'] }}</p>
                                            <p class="text-sm text-slate-600">{{ $slot['time'] }}</p>
                                        </div>
                                        <span
                                            class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $slot['slot_status'] === 'Selesai' ? 'bg-violet-100 text-violet-700' : 'bg-sky-100 text-sky-700' }}">
                                            {{ $slot['slot_status'] === 'Selesai' ? 'Completed' : 'Booked' }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <li class="rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-500">No scheduled slots yet.</li>
                            @endforelse
                        </ul>
                    </article>
                </section>

                <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <h2 class="text-lg font-bold text-slate-900">Recent Session Notes</h2>
                        <a href="{{ route('counsellor.dashboard') }}"
                            class="rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                            Refresh
                        </a>
                    </div>

                    <div class="grid gap-3 md:grid-cols-2">
                        @forelse ($sessionRecords as $record)
                            <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="font-semibold text-slate-900">{{ $record['student'] }}</h3>
                                    <span class="text-xs font-medium text-slate-500">{{ $record['date'] }}</span>
                                </div>
                                <p class="mt-2 text-sm text-slate-700">{{ $record['notes'] ?: 'No additional notes for this session.' }}</p>
                            </article>
                        @empty
                            <p class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">
                                Session notes will appear here after appointments are marked completed.
                            </p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>

</html>
