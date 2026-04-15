<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking Form • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
    <main class="min-h-screen p-4 sm:p-8">
        <section
            class="max-w-[96rem] mx-auto rounded-[2rem] border border-slate-200/80 bg-white/75 backdrop-blur-xl shadow-2xl overflow-hidden">
            <header
                class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/80 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Booking Form ({{ ucfirst($role) }})</h1>
                    <p class="text-sm text-slate-500 mt-1">Pilih slot dari calendar untuk buat request kaunselor.</p>
                </div>
                <a href="{{ route('home.session') }}"
                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">Back
                    to Home</a>
            </header>

            <div class="p-5 sm:p-7 grid lg:grid-cols-[220px_1fr] gap-5">
                <aside class="rounded-2xl border border-slate-200 bg-white/85 p-4 shadow-sm">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-200">
                        <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}" alt="Profile"
                            class="w-11 h-11 rounded-full border border-slate-200 object-cover bg-sky-50" />
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                            <p class="text-xs uppercase tracking-wide text-sky-700">
                                {{ $role === 'student' ? 'Pelajar' : 'Pensyarah' }}</p>
                        </div>
                    </div>

                    <p class="text-xs uppercase tracking-[0.12em] text-slate-500 mb-3">Menu</p>
                    <nav class="space-y-2 text-sm">
                        <a href="{{ route('inbox') }}"
                            class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Inbox</a>
                        <a href="{{ route('chat.index') }}"
                            class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Chat
                            Box</a>
                        <a href="{{ route('booking.index') }}"
                            class="block rounded-xl border border-sky-200 bg-sky-50 px-3 py-2 text-sky-700">Booking</a>
                        <a href="{{ route('profile.edit') }}"
                            class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Edit
                            Profile</a>
                    </nav>
                </aside>

                <section class="rounded-2xl border border-slate-200 bg-white/90 p-4 sm:p-6 shadow-sm space-y-5">
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
                                    <h3 id="calendar-title" class="font-semibold text-slate-700">Month Year</h3>
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

    <div id="request-modal" class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-[80] p-4 sm:p-8">
        <div class="w-full max-w-xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
            <div
                class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-800">Request Kaunselor</h3>
                <button id="request-modal-close"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">Tutup</button>
            </div>
            <form id="request-form" class="p-5 space-y-4">
                <div class="grid sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tarikh</label>
                        <input id="request-date" type="text" readonly
                            class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Masa</label>
                        <input id="request-time" type="text" readonly
                            class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Pilih kaunselor</label>
                    <select id="request-counsellor" required
                        class="w-full rounded-xl border-slate-200 bg-white text-sm">
                    </select>
                </div>
                <div>
                    <label for="request-note" class="block text-sm font-medium text-slate-700 mb-1">Notes kepada
                        kaunselor</label>
                    <textarea id="request-note" rows="4" required maxlength="500"
                        placeholder="Contoh: Saya perlukan sesi berkaitan tekanan akademik dan pengurusan masa."
                        class="w-full rounded-xl border-slate-200 text-sm"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="request-cancel"
                        class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600">Cancel</button>
                    <button type="submit"
                        class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">Submit
                        request</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const calendarGrid = document.getElementById('calendar-grid');
            const calendarTitle = document.getElementById('calendar-title');
            const prevBtn = document.getElementById('calendar-prev');
            const nextBtn = document.getElementById('calendar-next');

            const scheduleModal = document.getElementById('schedule-modal');
            const scheduleModalTitle = document.getElementById('schedule-modal-title');
            const scheduleModalBody = document.getElementById('schedule-modal-body');
            const scheduleModalClose = document.getElementById('schedule-modal-close');

            const requestModal = document.getElementById('request-modal');
            const requestModalClose = document.getElementById('request-modal-close');
            const requestCancel = document.getElementById('request-cancel');
            const requestForm = document.getElementById('request-form');
            const requestDate = document.getElementById('request-date');
            const requestTime = document.getElementById('request-time');
            const requestCounsellor = document.getElementById('request-counsellor');
            const requestNote = document.getElementById('request-note');
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

            if (!calendarGrid || !calendarTitle || !prevBtn || !nextBtn) {
                return;
            }

            const rawCounsellors = @json($counsellors ?? []);
            const rawBookingSlots = @json($bookingSlots ?? []);
            const counsellors = Array.isArray(rawCounsellors) ? rawCounsellors : Object.values(rawCounsellors ||
            {});
            const bookingSlots = Array.isArray(rawBookingSlots) ? rawBookingSlots : Object.values(rawBookingSlots ||
            {});
            const normalizedCounsellors = counsellors.filter(Boolean);
            const hasScheduleModal = Boolean(scheduleModal && scheduleModalTitle && scheduleModalBody &&
                scheduleModalClose);
            const hasRequestModal = Boolean(requestModal && requestModalClose && requestCancel && requestForm &&
                requestDate && requestTime && requestCounsellor && requestNote);

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
                if (day === 5) return buildHourlySlots(8, 12);
                if (day >= 1 && day <= 4) return buildHourlySlots(8, 17);
                return buildHourlySlots(8, 17);
            };

            const availableCounsellors = normalizedCounsellors.length ? normalizedCounsellors : ['Counsellor'];
            const statusClass = {
                Available: 'text-emerald-700 bg-emerald-50 border-emerald-200',
                Pending: 'text-amber-700 bg-amber-50 border-amber-200',
                Booked: 'text-sky-700 bg-sky-50 border-sky-200',
            };

            const bookedSlotsByKey = new Map(
                bookingSlots.map((slot) => [
                    `${slot.date}|${slot.time}|${slot.counsellor}`,
                    slot.status === 'pending' ? 'Pending' : 'Booked'
                ])
            );

            let activeDate = new Date();
            let selectedScheduleDate = null;
            let selectedRequestTime = null;
            const todayStart = new Date();
            todayStart.setHours(0, 0, 0, 0);

            const slotKey = (date, time, counsellor) => `${date.toISOString().slice(0, 10)}|${time}|${counsellor}`;

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
                requestNote.value = '';
                requestModal.classList.remove('hidden');
                requestModal.classList.add('flex');
            };

            const closeRequestModal = () => {
                if (!hasRequestModal) return;
                requestModal.classList.add('hidden');
                requestModal.classList.remove('flex');
                selectedRequestTime = null;
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
                    const counsellorLabel = status === 'Available' ? '-' : counsellor;
                    const tr = document.createElement('tr');

                    const actionButton = status === 'Available' ?
                        `<button type="button" data-action="request" data-slot-key="${key}" data-time="${time}" data-counsellor="${counsellor}" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 shadow-sm hover:bg-emerald-100 hover:border-emerald-300 transition">Buat Request</button>` :
                        `<span class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-500">Tidak tersedia</span>`;

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
                    alert(
                        'Tarikh lepas tidak boleh dibuat booking. Sila pilih hari ini atau tarikh akan datang.'
                    );
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
                    const previewStatus = getDailyStatus(cellDate);
                    const isPastDate = cellDate < todayStart;

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className =
                        `min-h-24 sm:min-h-28 p-2 text-left border-r border-b border-slate-200 transition ${
                            isPastDate ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'hover:bg-sky-50'
                        }`;
                    button.innerHTML = `
                        <p class="font-semibold text-slate-700">${day}</p>
                        <span class="mt-2 inline-flex rounded-full border px-2 py-0.5 text-xs ${statusClass[previewStatus]}">${previewStatus}</span>
                       <p class="text-[11px] text-slate-500 mt-1">${isPastDate ? 'Tarikh lepas' : 'Klik untuk buka table'}</p>
                    `;
                    if (!isPastDate) {
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
                    if (!(target instanceof HTMLElement) || target.dataset.action !== 'request') return;

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
                        alert('Sila isi nota untuk kaunselor sebelum submit.');
                        return;
                    }

                    const requestDateValue = selectedScheduleDate.toISOString().slice(0, 10);
                    if (selectedScheduleDate < todayStart) {
                        alert(
                            'Tarikh lepas tidak boleh dibuat booking. Sila pilih hari ini atau tarikh akan datang.'
                            );
                        return;
                    }
                    const selectedCounsellor = requestCounsellor.value;
                    const requestSlotKey =
                        `${requestDateValue}|${selectedRequestTime}|${selectedCounsellor}`;

                    if ((bookedSlotsByKey.get(requestSlotKey) ?? 'Available') !== 'Available') {
                        alert('Slot kaunselor ini tidak tersedia. Sila pilih kaunselor lain.');
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
                                note,
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
                        alert('Request berjaya dihantar kepada kaunselor. Sila semak status di Inbox.');
                    } catch (error) {
                        alert(error instanceof Error ? error.message :
                            'Maaf, request gagal dihantar. Sila cuba lagi.');
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

            if (hasScheduleModal) {
                scheduleModalClose.addEventListener('click', closeScheduleModal);
                scheduleModal.addEventListener('click', (event) => {
                    if (event.target === scheduleModal) closeScheduleModal();
                });
            }

            if (hasRequestModal) {
                requestModalClose.addEventListener('click', closeRequestModal);
                requestCancel.addEventListener('click', closeRequestModal);
                requestModal.addEventListener('click', (event) => {
                    if (event.target === requestModal) closeRequestModal();
                });
            }

            renderCalendar();
        });
    </script>
</body>

</html>
