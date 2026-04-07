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
            $openSlots = collect($scheduleSlots)->where('slot_status', 'Kosong')->count();
        @endphp

        <main class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-8">
            <header
                class="rounded-3xl bg-gradient-to-r from-cyan-700 via-sky-700 to-indigo-700 p-6 text-white shadow-lg sm:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-cyan-100">Kolej Vokasional Shah Alam</p>
                        <h1 class="mt-2 text-3xl font-black sm:text-4xl">E-Health • Counsellor Dashboard</h1>
                        <p class="mt-3 text-sm text-cyan-100 sm:text-base">
                            Selamat datang, <span
                                class="font-semibold text-white">{{ $user->name ?? 'Counsellor' }}</span>. Pantau
                            permohonan sesi dan jadual anda di sini.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('chat.index') }}"
                            class="rounded-xl bg-white/20 px-4 py-2 text-sm font-semibold backdrop-blur transition hover:bg-white/30">
                            Buka Chat
                        </a>
                        <a href="{{ route('profile.edit') }}"
                            class="rounded-xl bg-white/20 px-4 py-2 text-sm font-semibold backdrop-blur transition hover:bg-white/30">
                            Kemaskini Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="rounded-xl bg-rose-500 px-4 py-2 text-sm font-semibold transition hover:bg-rose-600">
                                Log Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Permohonan Menunggu</p>
                    <p class="mt-2 text-3xl font-black text-amber-600">{{ $pendingCount }}</p>
                </article>
                <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Permohonan Diluluskan</p>
                    <p class="mt-2 text-3xl font-black text-emerald-600">{{ $approvedCount }}</p>
                </article>
                <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Jumlah Permohonan</p>
                    <p class="mt-2 text-3xl font-black text-sky-700">{{ count($applications) }}</p>
                </article>
                <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Slot Kosong</p>
                    <p class="mt-2 text-3xl font-black text-indigo-700">{{ $openSlots }}</p>
                </article>
            </section>

            <section class="mt-6 grid gap-6 xl:grid-cols-3">
                <article class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 xl:col-span-2">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-xl font-bold">Senarai Permohonan Kaunseling</h2>
                            <p class="text-sm text-slate-500">Kemaskini status permohonan pelajar berdasarkan semakan
                                semasa.</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                            {{ count($applications) }} rekod
                        </span>
                    </div>

                    <div class="mt-5 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 text-left text-slate-500">
                                    <th class="px-3 py-2 font-semibold">Nama Pelajar</th>
                                    <th class="px-3 py-2 font-semibold">Tarikh</th>
                                    <th class="px-3 py-2 font-semibold">Topik</th>
                                    <th class="px-3 py-2 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications as $application)
                                    <tr class="border-b border-slate-100">
                                        <td class="px-3 py-3 font-medium">{{ $application['student'] }}</td>
                                        <td class="px-3 py-3">{{ $application['request_date'] }}</td>
                                        <td class="px-3 py-3">{{ $application['topic'] }}</td>
                                        <td class="px-3 py-3">
                                            @if ($application['status'] === 'Diluluskan')
                                                <span
                                                    class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                    Diluluskan
                                                </span>
                                            @else
                                                <span
                                                    class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                    Menunggu
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-3 py-5 text-center text-slate-500">Tiada permohonan
                                            setakat ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-xl font-bold">Tindakan Pantas</h2>
                    <div class="mt-4 space-y-3 text-sm">
                        <a href="{{ route('chat.index') }}"
                            class="block rounded-xl bg-slate-50 px-4 py-3 font-semibold transition hover:bg-slate-100">
                            Hantar mesej kepada pelajar
                        </a>
                        <a href="{{ route('profile.edit') }}"
                            class="block rounded-xl bg-slate-50 px-4 py-3 font-semibold transition hover:bg-slate-100">
                            Semak & kemaskini profil
                        </a>
                        <a href="{{ route('counsellor.dashboard') }}"
                            class="block rounded-xl bg-slate-50 px-4 py-3 font-semibold transition hover:bg-slate-100">
                            Muat semula dashboard
                        </a>
                    </div>

                    <div class="mt-6 rounded-xl border border-sky-100 bg-sky-50 p-4 text-sm text-sky-900">
                        <p class="font-semibold">Peringatan:</p>
                        <p class="mt-1">Sila semak setiap permohonan baharu sebelum jam <span
                                class="font-semibold">3:00 PM</span> untuk pengesahan slot hari yang sama.</p>
                    </div>
                </article>
            </section>

            <section class="mt-6 grid gap-6 lg:grid-cols-2">
                <article class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-xl font-bold">Jadual Slot Kaunseling</h2>
                    <p class="mt-1 text-sm text-slate-500">Lihat slot kosong dan slot yang telah ditempah.</p>
                    <ul class="mt-4 space-y-3">
                        @foreach ($scheduleSlots as $slot)
                            <li
                                class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                <div>
                                    <p class="font-semibold">{{ $slot['time'] }}</p>
                                    <p class="text-slate-500">{{ $slot['date'] }}</p>
                                </div>
                                @if ($slot['slot_status'] === 'Kosong')
                                    <span
                                        class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Kosong</span>
                                @else
                                    <span
                                        class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Ditempah</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </article>

                <article class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-xl font-bold">Rekod Sesi Terkini</h2>
                    <p class="mt-1 text-sm text-slate-500">Ringkasan sesi kaunseling terbaru untuk rujukan pantas.</p>
                    <ul class="mt-4 space-y-3">
                        @forelse ($sessionRecords as $record)
                            <li class="rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="font-semibold">{{ $record['student'] }}</p>
                                    <span
                                        class="rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-600">{{ $record['date'] }}</span>
                                </div>
                                <p class="mt-2 text-slate-700">{{ $record['notes'] }}</p>
                            </li>
                        @empty
                            <li class="rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-500">Tiada rekod
                                sesi tersedia.</li>
                        @endforelse
                    </ul>
                </article>
            </section>
        </main>
    </body>

    </html>
