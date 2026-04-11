<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Session Home • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">

    <div id="loader" class="fixed inset-0 bg-sky-500 flex items-center justify-center z-50">
        <div id="circle" class="w-64 h-64 bg-white rounded-full flex items-center justify-center">
            <span id="logoText" class="text-sky-500 font-bold text-2xl">CollegeCare</span>
        </div>
    </div>
    <div id="content" class="opacity-0 translate-y-2 min-h-screen flex flex-col">

        <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]">
            </div>
            <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
            <div class="absolute inset-0 bg-noise-layer opacity-15"></div>
            <div
                class="absolute -top-32 -left-24 w-[34rem] h-[34rem] bg-sky-300/35 rounded-full blur-3xl animate-blob-float">
            </div>
            <div
                class="absolute top-24 -right-32 w-[36rem] h-[36rem] bg-violet-300/30 rounded-full blur-3xl animate-aurora-drift animation-delay-2">
            </div>
        </div>

        <main class="min-h-screen p-4 sm:p-8">
            @php
                $dashboardRoleLabel = $role === 'teacher' ? 'Lecturer' : ucfirst($role);
                $sidebarRoleLabel =
                    $role === 'student' ? 'Pelajar' : ($role === 'teacher' ? 'Pensyarah' : ucfirst($role));
            @endphp
            <section
                class="max-w-[96rem] mx-auto rounded-[2rem] border border-slate-200/80 bg-white/75 backdrop-blur-xl shadow-2xl overflow-hidden">
                <header
                    class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/80 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Session Dashboard
                            ({{ $dashboardRoleLabel }})
                        </h1>
                        <p class="text-sm text-slate-500 mt-1">Welcome, {{ $user->full_name ?: $user->name }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('home') }}"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">Refresh
                            page</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">Logout</button>
                        </form>
                    </div>
                </header>

                <div class="p-5 sm:p-7 grid lg:grid-cols-[240px_1fr] gap-5">
                    <aside class="rounded-2xl border border-slate-200 bg-white/85 p-4 shadow-sm">
                        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-200">
                            <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}" alt="Profile"
                                class="w-11 h-11 rounded-full border border-slate-200 object-cover bg-sky-50" />
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                                <p class="text-xs uppercase tracking-wide text-sky-700">
                                    {{ $sidebarRoleLabel }}</p>
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
                                class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Booking</a>
                            <a href="#"
                                class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Booking
                                History</a>
                            <a href="{{ route('profile.edit') }}"
                                class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Edit
                                Profile</a>
                        </nav>
                    </aside>

                    <section class="rounded-2xl border border-slate-200 bg-white/90 p-4 sm:p-6 shadow-sm space-y-5">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-6">
                            <div class="flex items-center justify-between gap-3 mb-2">
                                <p class="text-sm text-slate-500">Slide Show / Animation</p>
                                <span
                                    class="text-xs px-2.5 py-1 rounded-full bg-sky-50 border border-sky-200 text-sky-700">Live</span>
                            </div>
                            <div id="session-slide"
                                class="rounded-xl bg-white border border-slate-200 p-6 min-h-28 text-slate-700 font-medium">
                                {{ $announcements[0] }}
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-base sm:text-lg font-semibold text-slate-800">Live Counsellor Current
                                    Status
                                </h2>
                                <span class="text-xs text-slate-500">Updated now</span>
                            </div>

                            <div class="space-y-2">
                                @foreach ($counsellors as $counsellor)
                                    <div
                                        class="flex items-center justify-between rounded-xl px-3 py-2.5 {{ $counsellor['available'] ? 'border border-emerald-200 bg-emerald-50' : 'border border-rose-200 bg-rose-50' }}">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="w-2.5 h-2.5 rounded-full {{ $counsellor['available'] ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                            <span>{{ $counsellor['name'] }}</span>
                                        </div>
                                        <span
                                            class="text-sm font-medium {{ $counsellor['available'] ? 'text-emerald-700' : 'text-rose-700' }}">
                                            {{ $counsellor['available'] ? 'Available' : 'In Session' }} • Next
                                            {{ $counsellor['next_slot'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-base sm:text-lg font-semibold text-slate-800">Jadual Kaunselor
                                        (Calendar)</h2>
                                    <p class="text-sm text-slate-500">Klik mana-mana tarikh untuk lihat jadual dalam
                                        bentuk table.</p>
                                </div>
                            </div>

                            <div class="grid lg:grid-cols-[1fr_260px] gap-4">
                                <div class="rounded-2xl border border-slate-200 overflow-hidden">
                                    <div
                                        class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                                        <button id="calendar-prev"
                                            class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">←</button>
                                        <h3 id="calendar-title" class="font-semibold text-slate-700">Month Year</h3>
                                        <button id="calendar-next"
                                            class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">→</button>
                                    </div>
                                    <div class="grid grid-cols-7 text-xs sm:text-sm bg-slate-100 text-slate-600">
                                        <div class="p-2 text-center font-semibold">Sun</div>
                                        <div class="p-2 text-center font-semibold">Mon</div>
                                        <div class="p-2 text-center font-semibold">Tue</div>
                                        <div class="p-2 text-center font-semibold">Wed</div>
                                        <div class="p-2 text-center font-semibold">Thu</div>
                                        <div class="p-2 text-center font-semibold">Fri</div>
                                        <div class="p-2 text-center font-semibold">Sat</div>
                                    </div>
                                    <div id="calendar-grid" class="grid grid-cols-7"></div>
                                </div>

                                <aside class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <h3 class="font-semibold text-slate-700 mb-3">Ringkasan</h3>
                                    <ul class="space-y-2 text-sm text-slate-600">
                                        <li class="rounded-lg border border-slate-200 bg-white p-2">🟢 Slot kosong</li>
                                        <li class="rounded-lg border border-slate-200 bg-white p-2">🟡 Hampir penuh
                                        </li>
                                        <li class="rounded-lg border border-slate-200 bg-white p-2">🔴 Penuh</li>
                                    </ul>
                                </aside>
                            </div>
                        </div>
                    </section>
                </div>

                <footer
                    class="px-6 sm:px-8 py-4 border-t border-slate-200/80 text-center text-sm text-slate-500 bg-white/70">
                    © {{ date('Y') }} CollegeCare • Counselling Booking System
                </footer>
            </section>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const slide = document.getElementById('session-slide');
                const items = @json($announcements);
                if (slide && Array.isArray(items) && items.length > 0) {
                    let idx = 0;
                    window.setInterval(() => {
                        idx = (idx + 1) % items.length;
                        slide.classList.remove('tip-swap');
                        void slide.offsetWidth;
                        slide.textContent = items[idx];
                        slide.classList.add('tip-swap');
                    }, 6000);
                }

                const calendarGrid = document.getElementById('calendar-grid');
                const calendarTitle = document.getElementById('calendar-title');
                const prevBtn = document.getElementById('calendar-prev');
                const nextBtn = document.getElementById('calendar-next');

                const modal = document.getElementById('schedule-modal');
                const modalTitle = document.getElementById('schedule-modal-title');
                const modalBody = document.getElementById('schedule-modal-body');
                const modalClose = document.getElementById('schedule-modal-close');

                if (!calendarGrid || !calendarTitle || !prevBtn || !nextBtn || !modal || !modalTitle || !modalBody ||
                    !modalClose) {
                    return;
                }

                const counsellors = ['Dr. Aina', 'Mr. Hakim', 'Ms. Farah', 'Dr. Daniel'];
                const statuses = ['Available', 'Booked', 'Pending', 'Full'];
                const statusClass = {
                    Available: 'text-emerald-700 bg-emerald-50 border-emerald-200',
                    Booked: 'text-sky-700 bg-sky-50 border-sky-200',
                    Pending: 'text-amber-700 bg-amber-50 border-amber-200',
                    Full: 'text-rose-700 bg-rose-50 border-rose-200',
                };
                const weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
                const slotTimes = ['08:00 - 09:00', '09:00 - 10:00', '10:00 - 11:00', '11:00 - 12:00', '02:00 - 03:00',
                    '03:00 - 04:00'
                ];
                const subjects = ['DKA2233', 'UMS2112', 'DKA2243', 'UMC2122', 'MPU2172', 'PER', 'COUNS'];

                let activeDate = new Date();

                const seededStatus = (date, index) => {
                    const seed = date.getFullYear() + (date.getMonth() + 1) * 17 + date.getDate() * 13 + index * 5;
                    return statuses[seed % statuses.length];
                };

                const renderScheduleRows = (date) => {
                    modalBody.innerHTML = '';
                    weekDays.forEach((day, dayIndex) => {
                        const tr = document.createElement('tr');
                        let cells =
                            `<td class="px-4 py-3 border-b border-slate-100 font-semibold">${day}</td>`;

                        slotTimes.forEach((_, slotIndex) => {
                            const status = seededStatus(date, dayIndex + slotIndex);
                            const counsellor = counsellors[(dayIndex + slotIndex) % counsellors
                                .length];
                            const subject = subjects[(date.getDate() + dayIndex + slotIndex) %
                                subjects.length];
                            cells += `
                                <td class="px-2 py-3 border-b border-slate-100 align-top">
                                    <div class="rounded-lg border border-slate-200 p-2 min-w-28">
                                        <p class="font-semibold text-slate-800 leading-tight">${subject}</p>
                                        <p class="text-xs text-slate-500 mt-1">${counsellor}</p>
                                        <span class="mt-2 inline-flex items-center rounded-full border px-2 py-0.5 text-[11px] font-semibold ${statusClass[status]}">${status}</span>
                                    </div>
                                </td>
                            `;
                        });

                        tr.innerHTML = cells;
                        modalBody.appendChild(tr);
                    });
                };

                const openModal = (date) => {
                    modalTitle.textContent =
                        `Jadual Kaunselor • ${date.toLocaleDateString('en-GB', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })}`;
                    renderScheduleRows(date);
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                };

                const closeModal = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                };

                modalClose.addEventListener('click', closeModal);
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) closeModal();
                });

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
                        const status = seededStatus(cellDate, day % weekDays.length);
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className =
                            'min-h-24 sm:min-h-28 p-2 text-left border-r border-b border-slate-200 hover:bg-sky-50 transition';
                        button.innerHTML = `
                            <p class="font-semibold text-slate-700">${day}</p>
                            <span class="mt-2 inline-flex rounded-full border px-2 py-0.5 text-xs ${statusClass[status]}">${status}</span>
                        `;
                        button.addEventListener('click', () => openModal(cellDate));
                        calendarGrid.appendChild(button);
                    }
                };

                prevBtn.addEventListener('click', () => {
                    activeDate = new Date(activeDate.getFullYear(), activeDate.getMonth() - 1, 1);
                    renderCalendar();
                });

                nextBtn.addEventListener('click', () => {
                    activeDate = new Date(activeDate.getFullYear(), activeDate.getMonth() + 1, 1);
                    renderCalendar();
                });

                renderCalendar();
            });
        </script>

    </div>

    <div id="schedule-modal"
        class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-[70] p-4 sm:p-8">
        <div class="w-full max-w-[92rem] bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 id="schedule-modal-title" class="text-lg font-semibold text-slate-800">Jadual Kaunselor</h3>
                <button id="schedule-modal-close"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">
                    Tutup
                </button>
            </div>
            <div class="overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left border-b border-slate-200">Hari / Slot</th>
                            <th class="px-4 py-3 text-left border-b border-slate-200">8:00 - 9:00</th>
                            <th class="px-4 py-3 text-left border-b border-slate-200">9:00 - 10:00</th>
                            <th class="px-4 py-3 text-left border-b border-slate-200">10:00 - 11:00</th>
                            <th class="px-4 py-3 text-left border-b border-slate-200">11:00 - 12:00</th>
                            <th class="px-4 py-3 text-left border-b border-slate-200">2:00 - 3:00</th>
                            <th class="px-4 py-3 text-left border-b border-slate-200">3:00 - 4:00</th>
                        </tr>
                    </thead>
                    <tbody id="schedule-modal-body" class="text-slate-700"></tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
