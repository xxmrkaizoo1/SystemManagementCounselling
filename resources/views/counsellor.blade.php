<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Counsellor Dashboard • E-Health</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-800">
    @php
        $pendingCount = collect($applications)->where('status', 'Menunggu')->count();
        $approvedCount = collect($applications)->where('status', 'Diluluskan')->count();
        $bookedSlots = collect($scheduleSlots)->where('slot_status', 'Ditempah')->count();
    @endphp

    <main class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-8">
        <section class="rounded-[2rem] border-2 border-sky-800 bg-white p-4 shadow-xl sm:p-6">
            <header
                class="rounded-2xl border border-sky-200 bg-gradient-to-r from-cyan-700 via-sky-700 to-indigo-700 p-4 text-white sm:p-5">
                <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-white/80 bg-white/20 text-lg font-bold">
                        {{ strtoupper(substr($user->name ?? 'C', 0, 1)) }}
                    </div>

                    <div
                        class="min-w-[180px] flex-1 rounded-xl border border-white/30 bg-white/20 px-4 py-2 backdrop-blur">
                        <p class="text-xs uppercase tracking-[0.2em] text-cyan-100">Text Title</p>
                        <h1 class="text-lg font-bold sm:text-xl">Sessions => Counsellor</h1>
                    </div>

                    <a href="{{ route('counsellor.dashboard') }}"
                        class="rounded-lg border border-white/40 bg-white/20 px-4 py-2 text-sm font-semibold transition hover:bg-white/30">
                        Session Record
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-lg border border-rose-300 bg-rose-500 px-4 py-2 text-sm font-semibold transition hover:bg-rose-600">
                            Log out
                        </button>
                    </form>

                    <a href="{{ route('profile.edit') }}"
                        class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-white bg-white/20 text-sm font-bold transition hover:bg-white/30">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </a>
                </div>
            </header>

            <section class="mt-6 grid gap-5 lg:grid-cols-2">
                <article class="rounded-2xl border border-sky-200 bg-slate-50 p-5 shadow-sm">
                    <div class="mx-auto h-20 w-20 rounded-full border-2 border-sky-500 bg-white"></div>
                    <div
                        class="mt-4 rounded-lg border border-slate-300 bg-white px-4 py-2 text-center text-sm font-semibold text-slate-700">
                        Pending Applications: {{ $pendingCount }}
                    </div>

                    <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200 bg-white">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 text-left text-slate-500">
                                    <th class="px-3 py-2 font-semibold">Student</th>
                                    <th class="px-3 py-2 font-semibold">Date</th>
                                    <th class="px-3 py-2 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications as $application)
                                    <tr class="border-b border-slate-100">
                                        <td class="px-3 py-2 font-medium">{{ $application['student'] }}</td>
                                        <td class="px-3 py-2">{{ $application['request_date'] }}</td>
                                        <td class="px-3 py-2">
                                            @if ($application['status'] === 'Diluluskan')
                                                <span
                                                    class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Diluluskan</span>
                                            @else
                                                <span
                                                    class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Menunggu</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-4 text-center text-slate-500">Tiada permohonan
                                            setakat ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <a href="{{ route('chat.index') }}"
                        class="mt-4 inline-flex rounded-lg bg-sky-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-800">
                        Button 1
                    </a>
                </article>

                <article class="rounded-2xl border border-sky-200 bg-slate-50 p-5 shadow-sm">
                    <div class="mx-auto h-20 w-20 rounded-full border-2 border-indigo-500 bg-white"></div>
                    <div
                        class="mt-4 rounded-lg border border-slate-300 bg-white px-4 py-2 text-center text-sm font-semibold text-slate-700">
                        Approved: {{ $approvedCount }} • Booked Slots: {{ $bookedSlots }}
                    </div>

                    <ul class="mt-4 space-y-3">
                        @forelse ($sessionRecords as $record)
                            <li class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="font-semibold">{{ $record['student'] }}</p>
                                    <span
                                        class="rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-600">{{ $record['date'] }}</span>
                                </div>
                                <p class="mt-2 text-slate-700">{{ $record['notes'] }}</p>
                            </li>
                        @empty
                            <li class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-500">
                                Tiada rekod sesi tersedia.
                            </li>
                        @endforelse
                    </ul>

                    <a href="{{ route('profile.edit') }}"
                        class="mt-4 inline-flex rounded-lg bg-indigo-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-800">
                        Manage Profile
                    </a>
                </article>
            </section>

            <footer class="mt-8 rounded-2xl border border-sky-300 bg-sky-50 px-4 py-3">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('counsellor.dashboard') }}"
                            class="rounded-lg bg-sky-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-800">
                            Session Record
                        </a>
                        <span class="rounded-lg bg-white px-3 py-2 text-sm font-medium text-slate-600">
                            Total Applications: {{ count($applications) }}
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        @foreach ($scheduleSlots as $slot)
                            <span
                                class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                                {{ $slot['time'] }} • {{ $slot['slot_status'] }}
                            </span>
                        @endforeach
                    </div>

                    <a href="{{ url()->previous() }}"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Back Button
                    </a>
                </div>
            </footer>
        </section>
    </main>
</body>

</html>
