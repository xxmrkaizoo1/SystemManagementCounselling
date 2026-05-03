<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pending Requests • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-slate-50 text-slate-700 antialiased">
    @php
        $pendingTotal = count($pendingRequests);
    @endphp

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
                        <h1 class="mt-1 text-2xl font-bold text-slate-800 sm:text-3xl">Pending Requests</h1>
                        <p class="mt-1 text-sm text-slate-500">Counsellor: {{ $user->full_name ?: $user->name }}</p>
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

                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-amber-200 bg-amber-50/80 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Current Pending</p>
                        <p class="mt-1 text-2xl font-bold text-amber-800">{{ $pendingTotal }}</p>
                    </div>
                    <div class="rounded-xl border border-sky-200 bg-sky-50/80 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-sky-700">Review Mode</p>
                        <p class="mt-1 text-sm font-semibold text-sky-800">Approve / Reject quickly</p>
                    </div>
                    <div class="rounded-xl border border-violet-200 bg-violet-50/80 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-violet-700">Tip</p>
                        <p class="mt-1 text-sm font-semibold text-violet-800">Prioritize urgent student cases first</p>
                    </div>
                </div>

            </header>

            <div class="p-5 sm:p-7">
                @if (session('status'))
                    <div
                        class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif
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
                                <th class="px-4 py-3 font-semibold">Notes</th>
                                <th class="px-4 py-3 font-semibold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($pendingRequests as $request)
                                <tr class="transition hover:bg-sky-50/70">
                                    <td class="px-4 py-3 font-semibold text-slate-800">{{ $request['student'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $request['date'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $request['time'] }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="rounded-full border border-amber-200 bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                            {{ ucfirst($request['status']) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">{{ $request['topic'] ?: 'General support' }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-600">
                                        <button type="button"
                                            class="note-trigger inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100"
                                            data-student="{{ $request['student'] }}"
                                            data-topic="{{ $request['topic'] ?: 'General support' }}"
                                            data-date="{{ $request['date'] }}" data-time="{{ $request['time'] }}"
                                            data-note="{{ $request['notes'] ?: 'No note provided' }}"
                                            title="View notes" aria-label="View notes">
                                            📝
                                        </button>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <form method="POST"
                                                action="{{ route('counsellor.booking-request.status', $request['id']) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit"
                                                    class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-100">
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('counsellor.booking-request.status', $request['id']) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit"
                                                    class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-rose-100">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-slate-500">
                                        <div class="mx-auto flex max-w-sm flex-col items-center gap-2">
                                            <span
                                                class="inline-flex h-14 w-14 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-2xl">✅</span>
                                            <p class="text-base font-semibold text-slate-700">No pending requests
                                                available</p>
                                            <p class="text-sm text-slate-500">You're all caught up for now.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>


    <div id="note-popup" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/45 px-4">
        <div class="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-5 shadow-xl">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Student Note</p>
                    <h2 id="popup-student" class="mt-1 text-lg font-bold text-slate-800">-</h2>
                </div>
                <button id="close-note-popup" type="button"
                    class="rounded-lg border border-slate-300 px-2 py-1 text-slate-600 hover:bg-slate-100">✕</button>
            </div>
            <div class="mt-4 space-y-2 text-sm">
                <p><span class="font-semibold text-slate-700">Topic:</span> <span id="popup-topic"
                        class="text-slate-600">-</span></p>
                <p><span class="font-semibold text-slate-700">Date:</span> <span id="popup-date"
                        class="text-slate-600">-</span></p>
                <p><span class="font-semibold text-slate-700">Time:</span> <span id="popup-time"
                        class="text-slate-600">-</span></p>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Notes</p>
                    <p id="popup-note" class="whitespace-pre-wrap text-slate-700">-</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const notePopup = document.getElementById('note-popup');
        const closeNotePopup = document.getElementById('close-note-popup');
        const popupStudent = document.getElementById('popup-student');
        const popupTopic = document.getElementById('popup-topic');
        const popupDate = document.getElementById('popup-date');
        const popupTime = document.getElementById('popup-time');
        const popupNote = document.getElementById('popup-note');

        document.querySelectorAll('.note-trigger').forEach((button) => {
            button.addEventListener('click', () => {
                popupStudent.textContent = button.dataset.student || '-';
                popupTopic.textContent = button.dataset.topic || '-';
                popupDate.textContent = button.dataset.date || '-';
                popupTime.textContent = button.dataset.time || '-';
                popupNote.textContent = button.dataset.note || 'No note provided';
                notePopup.classList.remove('hidden');
                notePopup.classList.add('flex');
            });
        });

        const hidePopup = () => {
            notePopup.classList.add('hidden');
            notePopup.classList.remove('flex');
        };

        closeNotePopup?.addEventListener('click', hidePopup);
        notePopup?.addEventListener('click', (event) => {
            if (event.target === notePopup) {
                hidePopup();
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !notePopup.classList.contains('hidden')) {
                hidePopup();
            }
        });
    </script>
</body>

</html>
