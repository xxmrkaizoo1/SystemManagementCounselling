<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking Form • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .form-label {
            display: block;
            margin-bottom: 0.375rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: rgb(51 65 85);
        }

        .form-hint {
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: rgb(100 116 139);
        }

        .form-control {
            width: 100%;
            border-radius: 0.75rem;
            border: 1px solid rgb(226 232 240);
            background: #fff;
            padding: 0.625rem 0.75rem;
            font-size: 0.875rem;
            color: rgb(51 65 85);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
            transition: all 0.2s ease;
        }

        .form-control:hover {
            border-color: rgb(203 213 225);
        }

        .form-control:focus {
            border-color: rgb(56 189 248);
            box-shadow: 0 0 0 4px rgb(224 242 254);
            outline: none;
        }

        .form-control[readonly] {
            background: rgb(248 250 252);
            color: rgb(71 85 105);
        }

        .request-card {
            border-radius: 1rem;
            border: 1px solid rgb(226 232 240);
            background: #fff;
            padding: 0.875rem;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">

    <div id="loginLoader"
        class="fixed inset-0 z-[90] flex items-center justify-center bg-sky-500/95 transition-opacity duration-700">
        <div class="flex flex-col items-center gap-3">
            <span class="h-16 w-16 animate-spin rounded-full border-8 border-white/30 border-t-white"></span>
            <p class="text-xl font-semibold text-white">Loading booking portal...</p>
        </div>
    </div>


    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#dbeafe_0%,_#f8fafc_30%,_#f1f5f9_100%)]">
        </div>
        <div class="absolute -top-24 -left-24 h-80 w-80 rounded-full bg-sky-300/30 blur-3xl"></div>
        <div class="absolute top-24 -right-16 h-80 w-80 rounded-full bg-indigo-300/25 blur-3xl"></div>
    </div>
    <main id="loginContent" class="min-h-screen p-4 sm:p-8 opacity-0 translate-y-2 transition-all duration-700">
        <section
            class="max-w-[96rem] mx-auto rounded-[2rem] border border-slate-200/80 bg-white/75 backdrop-blur-xl shadow-2xl overflow-hidden">
            <header
                class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/80 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Booking Form ({{ ucfirst($role) }})</h1>
                    <p class="text-sm text-slate-500 mt-1">Pilih slot dari calendar untuk buat request kaunselor.</p>
                </div>
                <div class="flex items-center gap-2">


                    <span
                        class="hidden sm:inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Ready
                        to book</span>
                    <div class="flex items-center gap-2">



                        <a href="{{ route('home.session') }}"
                            class="rounded-xl border border-slate-200 bg-white p-3 text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9.75L12 4l9 5.75M4.5 10.5V19.5A1.5 1.5 0 006 21h3.75v-4.5h4.5V21H18a1.5 1.5 0 001.5-1.5v-9" />
                            </svg>

                        </a>
                    </div>
                </div>
            </header>

            <div class="p-5 sm:p-7 grid lg:grid-cols-[220px_1fr] gap-5">
                <aside class="rounded-2xl border border-sky-100 bg-sky-100/60 p-4 shadow-sm">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-sky-200/80">
                        <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}" alt="Profile"
                            class="w-11 h-11 rounded-full border border-slate-200 object-cover bg-sky-50" />
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                            <p class="text-xs uppercase tracking-wide text-sky-700">
                                {{ $role === 'student' ? 'Pelajar' : 'Pensyarah' }}</p>
                        </div>
                    </div>

                    <p class="text-xs uppercase tracking-[0.12em] text-slate-500 mb-3">Menu</p>
                    <nav class="space-y-3 text-sm">
                        <a href="{{ route('inbox') }}" title="Inbox" aria-label="Inbox"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200/90 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-300 hover:bg-sky-50/80 hover:text-sky-700 transition">
                            <span
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M22 12.2V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v5.2" />
                                    <path
                                        d="M2 12.2h4.7a2 2 0 0 1 1.4.6l1 1a2 2 0 0 0 1.4.6h3a2 2 0 0 0 1.4-.6l1-1a2 2 0 0 1 1.4-.6H22" />
                                    <path d="M22 12.2V17a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4.8" />
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-slate-700">Inbox</span>
                        </a>
                        <a href="{{ route('chat.index') }}" title="Chat Box" aria-label="Chat Box"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200/90 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-300 hover:bg-sky-50/80 hover:text-sky-700 transition">
                            <span
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-slate-700">Chat Box</span>
                        </a>
                        <a href="{{ route('booking.index') }}" title="Booking" aria-label="Booking"
                            class="flex w-full items-center gap-3 rounded-xl border border-sky-300 bg-sky-50/90 px-3 py-2.5 text-sky-700 shadow-sm">
                            <span
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-sky-300 bg-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <path d="M16 2v4M8 2v4M3 10h18" />
                                </svg>
                            </span>
                            <span class="text-sm font-semibold">Booking</span>
                        </a>
                        <a href="{{ route('booking.history') }}" title="Booking History" aria-label="Booking History"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200/90 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-300 hover:bg-sky-50/80 hover:text-sky-700 transition">
                            <span
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 3v5h5" />
                                    <path d="M3.05 13A9 9 0 1 0 6 6.3L3 8" />
                                    <path d="M12 7v5l3 2" />
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-slate-700">Booking History</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" title="Edit Profile" aria-label="Edit Profile"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200/90 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-300 hover:bg-sky-50/80 hover:text-sky-700 transition">
                            <span
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Z" />
                                    <path d="M4 20a8 8 0 0 1 16 0" />
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-slate-700">Edit Profile</span>
                        </a>
                    </nav>
                </aside>

                <section class="rounded-2xl border border-slate-200 bg-white/90 p-4 sm:p-6 shadow-sm space-y-5">
                    <div class="grid gap-3 sm:grid-cols-3">
                        <article class="rounded-2xl border border-sky-100 bg-sky-50/70 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.1em] text-sky-700">Waktu operasi</p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">Isnin–Jumaat</p>
                            <p class="text-xs text-slate-600">8:00 pagi hingga 5:00 petang</p>
                        </article>
                        <article class="rounded-2xl border border-amber-100 bg-amber-50/70 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.1em] text-amber-700">Perhatian</p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">Slot hujung minggu ditutup</p>
                            <p class="text-xs text-slate-600">Sistem akan blok tempahan automatik.</p>
                        </article>
                        <article class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.1em] text-emerald-700">Cadangan</p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">Isi nota dengan jelas</p>
                            <p class="text-xs text-slate-600">Supaya kaunselor lebih bersedia untuk sesi anda.</p>
                        </article>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <h2 class="font-semibold text-slate-800">Cara guna</h2>
                        <ol class="text-sm text-slate-600 mt-2 list-decimal pl-5 space-y-1">
                            <li>Klik mana-mana grid tarikh dalam calendar.</li>
                            <li>Table slot harian akan keluar dalam popup.</li>
                            <li>Klik slot <span class="font-semibold text-emerald-700">Available</span> untuk buka form
                                request.</li>
                            <li>Isi nota untuk kaunselor dan submit booking request.</li>
                        </ol>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
                        <div class="grid lg:grid-cols-[1fr_280px] gap-4">
                            <div class="rounded-2xl border border-slate-200 overflow-hidden">
                                <div
                                    class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                                    <button id="calendar-prev"
                                        class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">←</button>
                                    <div class="flex items-center gap-2">
                                        <h3 id="calendar-title" class="font-semibold text-slate-700">Month Year</h3>
                                        <button id="calendar-today"
                                            class="rounded-lg border border-sky-200 bg-sky-50 px-2.5 py-1.5 text-xs font-semibold text-sky-700 hover:bg-sky-100">Today</button>
                                    </div>
                                    <button id="calendar-next"
                                        class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">→</button>
                                </div>
                                <div class="grid text-xs sm:text-sm bg-slate-100 text-slate-600"
                                    style="grid-template-columns: repeat(7, minmax(0, 1fr));">
                                    <div class="p-2 text-center font-semibold">Sun</div>
                                    <div class="p-2 text-center font-semibold">Mon</div>
                                    <div class="p-2 text-center font-semibold">Tue</div>
                                    <div class="p-2 text-center font-semibold">Wed</div>
                                    <div class="p-2 text-center font-semibold">Thu</div>
                                    <div class="p-2 text-center font-semibold">Fri</div>
                                    <div class="p-2 text-center font-semibold">Sat</div>
                                </div>
                                <div id="calendar-grid" class="grid"
                                    style="grid-template-columns: repeat(7, minmax(0, 1fr));"></div>
                            </div>

                            <aside class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <h3 class="font-semibold text-slate-700 mb-3">Status slot</h3>
                                <ul class="space-y-2 text-sm text-slate-600">
                                    <li class="rounded-lg border border-emerald-200 bg-emerald-50 p-2">🟢 Available
                                        (boleh klik)</li>
                                    <li class="rounded-lg border border-amber-200 bg-amber-50 p-2">🟡 Pending</li>
                                    <li class="rounded-lg border border-sky-200 bg-sky-50 p-2">🔵 Booked</li>
                                </ul>
                                <div
                                    class="mt-4 rounded-lg border border-slate-200 bg-white p-3 text-xs text-slate-600">
                                    <p class="font-semibold text-slate-700">Tip cepat</p>
                                    <p class="mt-1">Klik <span class="font-semibold text-sky-700">Today</span> untuk
                                        kembali
                                        ke bulan semasa.</p>
                                </div>
                            </aside>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </main>

    <div id="schedule-modal"
        class="fixed inset-0 bg-slate-900/55 backdrop-blur-[2px] hidden items-center justify-center z-[70] p-4 sm:p-8">
        <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
            <div
                class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
                <h3 id="schedule-modal-title" class="text-lg font-semibold text-slate-800">Table Slot Harian</h3>
                <button id="schedule-modal-close"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">Tutup</button>
            </div>
            <div class="max-h-[65vh] overflow-auto">
                <table class="w-full border-separate [border-spacing:0_10px] text-base">
                    <thead class="sticky top-0 z-10 bg-white/95 text-slate-600 backdrop-blur">
                        <tr>
                            <th
                                class="w-[20%] px-6 py-3 text-left border-b border-slate-200 text-[12px] font-bold uppercase tracking-[0.12em]">
                                Masa</th>
                            <th
                                class="w-[30%] px-6 py-3 text-left border-b border-slate-200 text-[12px] font-bold uppercase tracking-[0.12em]">
                                Kaunselor</th>
                            <th
                                class="w-[20%] px-6 py-3 text-center border-b border-slate-200 text-[12px] font-bold uppercase tracking-[0.12em]">
                                Status</th>
                            <th
                                class="w-[30%] px-6 py-3 text-center border-b border-slate-200 text-[12px] font-bold uppercase tracking-[0.12em]">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody id="schedule-modal-body" class="text-slate-700"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="request-modal"
        class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-[80] p-4 sm:p-8 opacity-0 transition duration-200">
        <div id="request-modal-panel"
            class="w-full max-w-xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition duration-200 opacity-0 translate-y-2 scale-[0.98]">
            <div
                class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-800">Request Kaunselor</h3>
                <button id="request-modal-close"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">Tutup</button>
            </div>
            <form id="request-form" class="p-5 space-y-4 bg-gradient-to-b from-white to-slate-50/50">
                <div class="grid sm:grid-cols-2 gap-3">
                    <div class="request-card">
                        <label class="form-label">Tarikh</label>
                        <input id="request-date" type="text" readonly class="form-control" />
                    </div>
                    <div class="request-card">
                        <label class="form-label">Masa</label>
                        <input id="request-time" type="text" readonly class="form-control" />
                    </div>
                </div>
                <div class="request-card">
                    <label class="form-label">Pilih kaunselor</label>
                    <select id="request-counsellor" required class="form-control">
                    </select>
                </div>
                <div class="request-card">
                    <label for="request-reason" class="form-label">Sebab booking</label>
                    <select id="request-reason" required class="form-control">
                        <option value="">Pilih sebab sesi</option>
                        <option value="Tekanan akademik">Tekanan akademik</option>
                        <option value="Pengurusan masa">Pengurusan masa</option>
                        <option value="Masalah peribadi">Masalah peribadi</option>
                        <option value="Kerjaya dan hala tuju">Kerjaya dan hala tuju</option>
                        <option value="Isu emosi / mental">Isu emosi / mental</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>
                <div id="request-reason-other-wrap"
                    class="request-card hidden opacity-0 -translate-y-1 transition duration-200">
                    <label for="request-reason-other" class="form-label">Nyatakan
                        sebab</label>
                    <input id="request-reason-other" type="text" maxlength="120"
                        placeholder="Contoh: Konflik dengan rakan sebilik" class="form-control" />
                </div>
                <div class="request-card">
                    <label for="request-note" class="form-label">Notes kepada
                        kaunselor</label>
                    <textarea id="request-note" rows="4" required maxlength="420"
                        placeholder="Contoh: Saya perlukan sesi berkaitan tekanan akademik dan pengurusan masa."
                        class="form-control min-h-[120px] resize-y"></textarea>
                    <p class="form-hint">Maksimum 420 aksara. <span id="request-note-counter">0</span>/420</p>
                </div>
                <div class="request-card">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-rose-700">
                        <input id="request-emergency" type="checkbox"
                            class="h-4 w-4 rounded border-rose-300 text-rose-600 focus:ring-rose-500" />
                        Emergency request (dibenarkan maksimum 2 booking aktif)
                    </label>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="request-cancel"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300 hover:bg-slate-50 transition">Cancel</button>
                    <button type="submit"
                        class="rounded-xl bg-sky-600 px-5 py-2 text-sm font-semibold text-white shadow-md shadow-sky-200/60 hover:bg-sky-700 hover:shadow-lg transition">Submit
                        request</button>
                </div>
            </form>
        </div>
    </div>

    <div id="booking-toast-container"
        class="pointer-events-none fixed right-4 top-4 z-[90] flex w-full max-w-sm flex-col gap-3"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const calendarGrid = document.getElementById('calendar-grid');
            const calendarTitle = document.getElementById('calendar-title');
            const prevBtn = document.getElementById('calendar-prev');
            const nextBtn = document.getElementById('calendar-next');
            const todayBtn = document.getElementById('calendar-today');

            const scheduleModal = document.getElementById('schedule-modal');
            const scheduleModalTitle = document.getElementById('schedule-modal-title');
            const scheduleModalBody = document.getElementById('schedule-modal-body');
            const scheduleModalClose = document.getElementById('schedule-modal-close');

            const requestModal = document.getElementById('request-modal');
            const requestModalPanel = document.getElementById('request-modal-panel');
            const requestModalClose = document.getElementById('request-modal-close');
            const requestCancel = document.getElementById('request-cancel');
            const requestForm = document.getElementById('request-form');
            const requestDate = document.getElementById('request-date');
            const requestTime = document.getElementById('request-time');
            const requestCounsellor = document.getElementById('request-counsellor');
            const requestReason = document.getElementById('request-reason');
            const requestReasonOtherWrap = document.getElementById('request-reason-other-wrap');
            const requestReasonOther = document.getElementById('request-reason-other');
            const requestNote = document.getElementById('request-note');
            const requestNoteCounter = document.getElementById('request-note-counter');
            const requestEmergency = document.getElementById('request-emergency');
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
            const toastContainer = document.getElementById('booking-toast-container');

            if (!calendarGrid || !calendarTitle || !prevBtn || !nextBtn) {
                return;
            }

            const rawCounsellors = @json($counsellors ?? []);
            const rawBookingSlots = @json($bookingSlots ?? []);
            const rawUserActiveBookings = @json($userActiveBookings ?? []);
            const counsellors = Array.isArray(rawCounsellors) ? rawCounsellors : Object.values(rawCounsellors ||
            {});
            const bookingSlots = Array.isArray(rawBookingSlots) ? rawBookingSlots : Object.values(rawBookingSlots ||
            {});
            const userActiveBookings = Array.isArray(rawUserActiveBookings) ? rawUserActiveBookings : Object
                .values(rawUserActiveBookings || {});
            const normalizedCounsellors = counsellors.filter(Boolean);
            const hasScheduleModal = Boolean(scheduleModal && scheduleModalTitle && scheduleModalBody &&
                scheduleModalClose);
            const hasRequestModal = Boolean(requestModal && requestModalClose && requestCancel && requestForm &&
                requestDate && requestTime && requestCounsellor && requestReason && requestReasonOtherWrap &&
                requestReasonOther && requestNote && requestModalPanel && requestEmergency);

            const openAnimatedModal = (overlay, panel) => {
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                requestAnimationFrame(() => {
                    overlay.classList.remove('opacity-0');
                    panel.classList.remove('opacity-0', 'translate-y-2', 'scale-[0.98]');
                    panel.classList.add('opacity-100', 'translate-y-0', 'scale-100');
                });
            };

            const closeAnimatedModal = (overlay, panel) => {
                overlay.classList.add('opacity-0');
                panel.classList.add('opacity-0', 'translate-y-2', 'scale-[0.98]');
                panel.classList.remove('opacity-100', 'translate-y-0', 'scale-100');
                window.setTimeout(() => {
                    overlay.classList.add('hidden');
                    overlay.classList.remove('flex');
                }, 180);
            };

            const buildHourlySlots = (startHour, endHour) => {
                const slots = [];
                for (let hour = startHour; hour < endHour; hour++) {
                    const from = String(hour).padStart(2, '0');
                    const to = String(hour + 1).padStart(2, '0');
                    slots.push(`${from}:00 - ${to}:00`);
                }
                return slots;
            };

            const getSlotTimesForDate = (date) => {
                const day = date.getDay();
                if (day === 0 || day === 6) return [];
                if (day === 5) return buildHourlySlots(8, 12);
                if (day >= 1 && day <= 4) return buildHourlySlots(8, 17);
                return buildHourlySlots(8, 17);
            };

            const isWeekend = (date) => {
                const day = date.getDay();
                return day === 0 || day === 6;
            };


            const availableCounsellors = normalizedCounsellors.length ? normalizedCounsellors : ['Counsellor'];
            const statusClass = {
                Available: 'text-emerald-700 bg-emerald-50 border-emerald-200',
                Pending: 'text-amber-700 bg-amber-50 border-amber-200',
                Booked: 'text-sky-700 bg-sky-50 border-sky-200',
            };
            const toastStyleByType = {
                success: 'border-emerald-200 bg-emerald-50 text-emerald-800',
                error: 'border-rose-200 bg-rose-50 text-rose-800',
                warning: 'border-amber-200 bg-amber-50 text-amber-800',
                info: 'border-sky-200 bg-sky-50 text-sky-800',
            };
            const showToast = (message, type = 'info') => {
                if (!toastContainer) return;

                const toast = document.createElement('div');
                toast.className =
                    `pointer-events-auto translate-y-1 opacity-0 rounded-xl border px-4 py-3 text-sm shadow-lg transition duration-300 ease-out ${toastStyleByType[type] ?? toastStyleByType.info}`;
                toast.innerHTML = `
                    <div class="flex items-start gap-3">
                        <p class="flex-1 leading-5">${message}</p>
                        <button type="button" class="rounded-md px-1.5 py-0.5 text-xs font-semibold opacity-70 hover:opacity-100" data-toast-dismiss>Tutup</button>
                    </div>
                `;

                toastContainer.appendChild(toast);
                requestAnimationFrame(() => {
                    toast.classList.add('translate-y-0', 'opacity-100');
                });

                const dismiss = () => {
                    toast.classList.add('translate-y-1', 'opacity-0');
                    window.setTimeout(() => toast.remove(), 250);
                };

                toast.querySelector('[data-toast-dismiss]')?.addEventListener('click', dismiss);
                window.setTimeout(dismiss, 3200);
            };
            const bookedSlotsByKey = new Map(
                bookingSlots.map((slot) => [
                    `${slot.date}|${slot.time}|${slot.counsellor}`,
                    slot.status === 'pending' ? 'Pending' : 'Booked'
                ])
            );
            const userBookingsByKey = new Map(
                userActiveBookings.map((slot) => [
                    `${slot.date}|${slot.time}|${slot.counsellor}`,
                    slot
                ])
            );
            let activeDate = new Date();
            let selectedScheduleDate = null;
            let selectedRequestTime = null;
            const todayStart = new Date();
            todayStart.setHours(0, 0, 0, 0);

            const formatDateForApi = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                return `${year}-${month}-${day}`;
            };

            const slotKey = (date, time, counsellor) => `${formatDateForApi(date)}|${time}|${counsellor}`;

            const computedStatus = (date, time, counsellor) => {
                const key = slotKey(date, time, counsellor);
                return bookedSlotsByKey.get(key) ?? 'Available';
            };

            const getDailyStatus = (date) => {
                const slotTimes = getSlotTimesForDate(date);
                const slotStatuses = slotTimes.map((time, slotIndex) => {
                    const counsellor = availableCounsellors[(date.getDate() + slotIndex) %
                        availableCounsellors.length];
                    return computedStatus(date, time, counsellor);
                });

                if (slotStatuses.some((status) => status === 'Available')) return 'Available';
                if (slotStatuses.some((status) => status === 'Pending')) return 'Pending';
                return 'Booked';
            };

            const renderCounsellorOptions = (selectedCounsellor = null) => {
                if (!requestCounsellor) return;

                requestCounsellor.innerHTML = '';

                availableCounsellors.forEach((name) => {
                    const option = document.createElement('option');
                    option.value = name;
                    option.textContent = name;
                    if (selectedCounsellor && name === selectedCounsellor) {
                        option.selected = true;
                    }
                    requestCounsellor.appendChild(option);
                });
            };

            const openRequestModal = (date, time, counsellor) => {
                if (!hasRequestModal) return;
                selectedRequestTime = time;
                requestDate.value = date.toLocaleDateString('en-GB', {
                    weekday: 'long',
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
                requestTime.value = time;
                renderCounsellorOptions(counsellor);
                requestReason.value = '';
                requestReasonOther.value = '';
                requestReasonOtherWrap.classList.add('hidden', 'opacity-0', '-translate-y-1');
                requestNote.value = '';
                requestEmergency.checked = false;
                openAnimatedModal(requestModal, requestModalPanel);
            };

            const closeRequestModal = () => {
                if (!hasRequestModal) return;
                closeAnimatedModal(requestModal, requestModalPanel);
                selectedRequestTime = null;
            };

            const updateNoteCounter = () => {
                if (!requestNote || !requestNoteCounter) return;
                requestNoteCounter.textContent = String(requestNote.value.length);
            };

            const closeScheduleModal = () => {
                if (!hasScheduleModal) return;
                scheduleModal.classList.add('hidden');
                scheduleModal.classList.remove('flex');
            };

            const renderTableRows = (date) => {
                if (!hasScheduleModal) return;
                scheduleModalBody.innerHTML = '';
                const slotTimes = getSlotTimesForDate(date);

                slotTimes.forEach((time, slotIndex) => {
                    const counsellor = availableCounsellors[(date.getDate() + slotIndex) %
                        availableCounsellors.length];
                    const status = computedStatus(date, time, counsellor);
                    const key = slotKey(date, time, counsellor);
                    const userBooking = userBookingsByKey.get(key);
                    const counsellorLabel = status === 'Available' ? '-' : counsellor;
                    const tr = document.createElement('tr');

                    const actionButton = status === 'Available' ?
                        `<button type="button" data-action="request" data-slot-key="${key}" data-time="${time}" data-counsellor="${counsellor}" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 shadow-sm hover:bg-emerald-100 hover:border-emerald-300 transition">Buat Request</button>` :
                        (userBooking ?
                            `<button type="button" data-action="cancel-booking" data-booking-id="${userBooking.id}" data-slot-key="${key}" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 shadow-sm hover:bg-rose-100 hover:border-rose-300 transition">Cancel Booking</button>` :
                            `<span class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-500">Tidak tersedia</span>`
                        );

                    tr.className = 'group';
                    tr.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-slate-700 bg-white border-y border-l border-slate-200 rounded-l-xl group-hover:border-sky-200 transition">${time}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-700 bg-white border-y border-slate-200 group-hover:border-sky-200 transition">${counsellorLabel}</td>
                        <td class="px-6 py-4 text-center bg-white border-y border-slate-200 group-hover:border-sky-200 transition">
                            <span class="inline-flex min-w-[104px] justify-center rounded-full border px-3 py-1 text-sm font-semibold ${statusClass[status]}">${status}</span>
                        </td>
                        <td class="px-6 py-4 text-center bg-white border-y border-r border-slate-200 rounded-r-xl group-hover:border-sky-200 transition">${actionButton}</td>
                    `;
                    scheduleModalBody.appendChild(tr);
                });
            };

            const openScheduleModal = (date) => {
                if (!hasScheduleModal) return;
                if (date < todayStart) {
                    showToast(
                        'Tarikh lepas tidak boleh dibuat booking. Sila pilih hari ini atau tarikh akan datang.',
                        'warning');
                    return;
                }

                if (isWeekend(date)) {
                    showToast(
                        'Booking pada hari Sabtu dan Ahad tidak dibenarkan. Sila pilih Isnin hingga Jumaat.',
                        'warning');
                    return;
                }
                selectedScheduleDate = date;
                scheduleModalTitle.textContent =
                    `Table Slot • ${date.toLocaleDateString('en-GB', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })}`;
                renderTableRows(date);
                scheduleModal.classList.remove('hidden');
                scheduleModal.classList.add('flex');
            };

            const renderCalendar = () => {
                const year = activeDate.getFullYear();
                const month = activeDate.getMonth();
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const startOffset = firstDay.getDay();

                calendarTitle.textContent = firstDay.toLocaleDateString('en-GB', {
                    month: 'long',
                    year: 'numeric'
                });
                calendarGrid.innerHTML = '';

                for (let i = 0; i < startOffset; i++) {
                    const pad = document.createElement('div');
                    pad.className = 'min-h-24 sm:min-h-28 border-r border-b border-slate-200 bg-slate-50/70';
                    calendarGrid.appendChild(pad);
                }

                for (let day = 1; day <= lastDay.getDate(); day++) {
                    const cellDate = new Date(year, month, day);
                    const weekend = isWeekend(cellDate);
                    const previewStatus = getDailyStatus(cellDate);
                    const isPastDate = cellDate < todayStart;
                    const isDisabledDate = isPastDate || weekend;

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className =
                        `min-h-24 sm:min-h-28 p-2 text-left border-r border-b border-slate-200 transition ${
                            isDisabledDate ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'hover:bg-sky-50'
                        }`;
                    button.innerHTML = `
                        <p class="font-semibold text-slate-700">${day}</p>
                         <span class="mt-2 inline-flex rounded-full border px-2 py-0.5 text-xs ${weekend ? 'text-slate-600 bg-slate-100 border-slate-200' : statusClass[previewStatus]}">${weekend ? 'Tutup' : previewStatus}</span>
                       <p class="text-[11px] text-slate-500 mt-1">${isPastDate ? 'Tarikh lepas' : (weekend ? 'Cuti hujung minggu' : 'Klik untuk buka table')}</p>
                    `;
                    if (!isDisabledDate) {
                        button.addEventListener('click', () => openScheduleModal(cellDate));
                    } else {
                        button.disabled = true;
                    }
                    calendarGrid.appendChild(button);
                }
            };

            if (hasScheduleModal) {
                scheduleModalBody.addEventListener('click', (event) => {
                    const target = event.target;
                    if (!(target instanceof HTMLElement)) return;

                    if (target.dataset.action === 'cancel-booking') {
                        const bookingId = target.dataset.bookingId;
                        const key = target.dataset.slotKey;

                        if (!bookingId || !key) return;

                        const bookingToCancel = userBookingsByKey.get(key);
                        const isConfirmed = window.confirm(
                            `Adakah anda pasti mahu batalkan booking pada ${bookingToCancel?.date ?? ''} (${bookingToCancel?.time ?? ''})?`
                        );
                        if (!isConfirmed) return;

                        fetch(`{{ url('/booking') }}/${bookingId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                            })
                            .then(async (response) => {
                                const responsePayload = await response.json().catch(() => null);
                                if (!response.ok) {
                                    throw new Error(responsePayload?.message ??
                                        'Gagal batalkan booking.');
                                }
                                bookedSlotsByKey.delete(key);
                                userBookingsByKey.delete(key);
                                if (selectedScheduleDate) {
                                    renderTableRows(selectedScheduleDate);
                                }
                                renderCalendar();
                                showToast('Booking berjaya dibatalkan.', 'success');
                            })
                            .catch((error) => {
                                showToast(error instanceof Error ? error.message :
                                    'Gagal batalkan booking.', 'error');
                            });
                        return;
                    }

                    if (target.dataset.action !== 'request') return;

                    const time = target.dataset.time;
                    const counsellor = target.dataset.counsellor;
                    const key = target.dataset.slotKey;

                    if (!time || !counsellor || !key || !selectedScheduleDate) return;
                    openRequestModal(selectedScheduleDate, time, counsellor);
                });
            }

            if (hasRequestModal) {
                requestForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    if (!selectedScheduleDate || !selectedRequestTime || !requestCounsellor.value)
                        return;

                    const note = requestNote.value.trim();
                    if (!note) {
                        showToast('Sila isi nota untuk kaunselor sebelum submit.', 'warning');
                        return;
                    }
                    const selectedReason = requestReason.value.trim();
                    if (!selectedReason) {
                        showToast('Sila pilih sebab booking sebelum submit.', 'warning');
                        return;
                    }

                    const reasonDetail = requestReasonOther.value.trim();
                    if (selectedReason === 'Lain-lain' && !reasonDetail) {
                        showToast('Sila nyatakan sebab lain sebelum submit.', 'warning');
                        return;
                    }

                    const resolvedReason = selectedReason === 'Lain-lain' ? reasonDetail :
                        selectedReason;
                    const isEmergency = requestEmergency.checked;
                    const finalNote =
                        `${isEmergency ? '[EMERGENCY] ' : ''}[Sebab sesi: ${resolvedReason}] ${note}`;

                    const requestDateValue = formatDateForApi(selectedScheduleDate);
                    if (selectedScheduleDate < todayStart) {
                        showToast(
                            'Tarikh lepas tidak boleh dibuat booking. Sila pilih hari ini atau tarikh akan datang.',
                            'warning');
                        return;
                    }
                    if (isWeekend(selectedScheduleDate)) {
                        showToast(
                            'Booking pada hari Sabtu dan Ahad tidak dibenarkan. Sila pilih Isnin hingga Jumaat.',
                            'warning');
                        return;
                    }
                    const selectedCounsellor = requestCounsellor.value;
                    const requestSlotKey =
                        `${requestDateValue}|${selectedRequestTime}|${selectedCounsellor}`;

                    if ((bookedSlotsByKey.get(requestSlotKey) ?? 'Available') !== 'Available') {
                        showToast('Slot kaunselor ini tidak tersedia. Sila pilih kaunselor lain.',
                            'warning');
                        return;
                    }

                    try {
                        if (!csrfToken) throw new Error('Missing CSRF token. Please refresh the page.');

                        const response = await fetch("{{ route('booking.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                booking_date: requestDateValue,
                                booking_time: selectedRequestTime,
                                counsellor_name: selectedCounsellor,
                                reason: selectedReason,
                                reason_other: reasonDetail,
                                is_emergency: isEmergency,
                                note: finalNote,
                            }),
                        });


                        const responsePayload = await response.json().catch(() => null);

                        if (!response.ok) {
                            throw new Error(responsePayload?.message ?? 'Booking request failed.');
                        }

                        bookedSlotsByKey.set(requestSlotKey, 'Pending');
                        closeRequestModal();
                        renderTableRows(selectedScheduleDate);
                        renderCalendar();
                        showToast(
                            'Request berjaya dihantar kepada kaunselor. Sila semak status di Inbox.',
                            'success');
                    } catch (error) {
                        showToast(error instanceof Error ? error.message :
                            'Maaf, request gagal dihantar. Sila cuba lagi.', 'error');
                    }
                });
            }

            prevBtn.addEventListener('click', () => {
                activeDate = new Date(activeDate.getFullYear(), activeDate.getMonth() - 1, 1);
                renderCalendar();
            });

            nextBtn.addEventListener('click', () => {
                activeDate = new Date(activeDate.getFullYear(), activeDate.getMonth() + 1, 1);
                renderCalendar();
            });

            if (todayBtn) {
                todayBtn.addEventListener('click', () => {
                    activeDate = new Date(todayStart);
                    renderCalendar();
                });
            }

            if (hasScheduleModal) {
                scheduleModalClose.addEventListener('click', closeScheduleModal);
                scheduleModal.addEventListener('click', (event) => {
                    if (event.target === scheduleModal) closeScheduleModal();
                });
            }

            if (hasRequestModal) {
                requestReason.addEventListener('change', () => {
                    const isOtherReason = requestReason.value === 'Lain-lain';
                    if (isOtherReason) {
                        requestReasonOtherWrap.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            requestReasonOtherWrap.classList.remove('opacity-0', '-translate-y-1');
                        });
                    } else {
                        requestReasonOtherWrap.classList.add('opacity-0', '-translate-y-1');
                        window.setTimeout(() => requestReasonOtherWrap.classList.add('hidden'), 180);
                        requestReasonOther.value = '';
                    }
                });
                requestNote.addEventListener('input', updateNoteCounter);
                requestModalClose.addEventListener('click', closeRequestModal);
                requestCancel.addEventListener('click', closeRequestModal);
                requestModal.addEventListener('click', (event) => {
                    if (event.target === requestModal) closeRequestModal();
                });
            }

            updateNoteCounter();
            renderCalendar();
        });
    </script>
</body>

</html>
