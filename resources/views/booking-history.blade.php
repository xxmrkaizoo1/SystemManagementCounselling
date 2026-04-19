<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Booking History • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 min-h-screen">
    <div class="max-w-6xl mx-auto px-3 sm:px-5 py-5 sm:py-7">
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <header
                class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/85 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Booking History ({{ ucfirst($role) }})</h1>
                    <p class="text-sm text-slate-500 mt-1">Semua rekod tempahan sesi kaunseling anda.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('home.session') }}"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">Back
                        to Home</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">Logout</button>
                    </form>
                </div>
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
                    <nav class="space-y-3 text-sm">
                        <a href="{{ route('inbox') }}" title="Inbox" aria-label="Inbox"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                            <span
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
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
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                            <span
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-slate-700">Chat Box</span>
                        </a>
                        <a href="{{ route('booking.index') }}" title="Booking" aria-label="Booking"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                            <span
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <path d="M16 2v4M8 2v4M3 10h18" />
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-slate-700">Booking</span>
                        </a>
                        <a href="{{ route('booking.history') }}" title="Booking History" aria-label="Booking History"
                            class="flex w-full items-center gap-3 rounded-xl border border-sky-200 bg-sky-50 px-3 py-2.5 text-sky-700 shadow-sm">
                            <span
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-sky-200 bg-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 3v5h5" />
                                    <path d="M3.05 13A9 9 0 1 0 6 6.3L3 8" />
                                    <path d="M12 7v5l3 2" />
                                </svg>
                            </span>
                            <span class="text-sm font-semibold">Booking History</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" title="Edit Profile" aria-label="Edit Profile"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                            <span
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
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
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-sm">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Total</p>
                            <p class="mt-1 text-lg font-bold text-slate-800">{{ $bookingStats['all'] }}</p>
                        </div>
                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-3">
                            <p class="text-xs uppercase tracking-wide text-amber-700">Pending</p>
                            <p class="mt-1 text-lg font-bold text-amber-700">{{ $bookingStats['pending'] }}</p>
                        </div>
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3">
                            <p class="text-xs uppercase tracking-wide text-emerald-700">Approved</p>
                            <p class="mt-1 text-lg font-bold text-emerald-700">{{ $bookingStats['approved'] }}</p>
                        </div>
                        <div class="rounded-xl border border-rose-200 bg-rose-50 p-3">
                            <p class="text-xs uppercase tracking-wide text-rose-700">Rejected</p>
                            <p class="mt-1 text-lg font-bold text-rose-700">{{ $bookingStats['rejected'] }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-300 bg-slate-100 p-3">
                            <p class="text-xs uppercase tracking-wide text-slate-600">Completed</p>
                            <p class="mt-1 text-lg font-bold text-slate-700">{{ $bookingStats['completed'] }}</p>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('booking.history') }}" class="flex items-end gap-3">
                        <div>
                            <label for="status" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                            <select id="status" name="status" class="rounded-lg border-slate-300 text-sm focus:border-sky-400 focus:ring-sky-200">
                                <option value="all" @selected($selectedStatus === 'all')>All</option>
                                <option value="pending" @selected($selectedStatus === 'pending')>Pending</option>
                                <option value="approved" @selected($selectedStatus === 'approved')>Approved</option>
                                <option value="rejected" @selected($selectedStatus === 'rejected')>Rejected</option>
                                <option value="completed" @selected($selectedStatus === 'completed')>Completed</option>
                            </select>
                        </div>
                        <button type="submit"
                            class="rounded-xl border border-sky-200 bg-sky-50 px-3 py-2 text-sm font-semibold text-sky-700 hover:bg-sky-100 transition">Filter</button>
                    </form>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-slate-600 uppercase text-xs tracking-wide">
                                <tr>
                                    <th class="px-4 py-3 text-left">Date</th>
                                    <th class="px-4 py-3 text-left">Time</th>
                                    <th class="px-4 py-3 text-left">Counsellor</th>
                                    <th class="px-4 py-3 text-left">Topic / Note</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse ($bookings as $booking)
                                    <tr class="align-top">
                                        <td class="px-4 py-3 text-slate-700">{{ $booking['date'] }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $booking['time'] }}</td>
                                        <td class="px-4 py-3 text-slate-800 font-medium">{{ $booking['counsellor'] }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $booking['note'] }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $booking['status_badge_class'] }}">{{ $booking['status_label'] }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                            No booking history found for the selected filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <footer class="px-5 sm:px-7 py-4 border-t border-slate-200 text-xs text-slate-500 bg-white/80">
                © {{ date('Y') }} CollegeCare • Counselling Booking System
            </footer>
        </div>
    </div>
</body>

</html>
