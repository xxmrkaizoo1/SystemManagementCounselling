<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Counsellor Statistics • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 antialiased">
    <main class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-bold text-slate-800">Counsellor Statistics</h1>
            <a href="{{ route('counsellor.dashboard') }}"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:border-sky-300 hover:text-sky-700">Back
                to Dashboard</a>
        </div>

        <div class="mb-6 rounded-2xl border border-sky-100 bg-sky-50 px-4 py-3 text-sm text-sky-800">
            Total appointments for {{ $user->full_name ?: $user->name }}: <span class="font-semibold">{{ $totalBookings }}</span>
        </div>

        <section class="grid gap-6 lg:grid-cols-2">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-800">Most Student Bookings</h2>
                <p class="mt-1 text-sm text-slate-500">Students who booked this counsellor most frequently.</p>

                <div class="mt-4 overflow-hidden rounded-xl border border-slate-100">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-2 text-left">Student</th>
                                <th class="px-4 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($topStudents as $item)
                                <tr>
                                    <td class="px-4 py-2">{{ $item['student'] }}</td>
                                    <td class="px-4 py-2 text-right font-semibold text-slate-800">{{ $item['total'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-slate-500">No booking data yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-800">Most Appointment Topics</h2>
                <p class="mt-1 text-sm text-slate-500">Most common counselling topics for appointments.</p>

                <div class="mt-4 overflow-hidden rounded-xl border border-slate-100">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-2 text-left">Topic</th>
                                <th class="px-4 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($topTopics as $item)
                                <tr>
                                    <td class="px-4 py-2">{{ $item['topic'] }}</td>
                                    <td class="px-4 py-2 text-right font-semibold text-slate-800">{{ $item['total'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-slate-500">No topic data yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </main>
</body>

</html>
