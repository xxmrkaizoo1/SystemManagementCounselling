<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin • Generate Report</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 min-h-screen text-slate-800">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Admin • Generate Report</h1>
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                Back to dashboard
            </a>
        </div>

        <p class="mt-3 text-sm text-slate-600">Create quick activity summaries to export or share with the team.</p>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Total bookings</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($reportStats['total_bookings']) }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Completed sessions</p>
                <p class="mt-2 text-2xl font-bold text-emerald-700">{{ number_format($reportStats['completed_bookings']) }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Active users</p>
                <p class="mt-2 text-2xl font-bold text-indigo-700">{{ number_format($reportStats['active_users']) }}</p>
            </article>
        </div>

        <form method="GET" action="{{ route('admin.reports.generate') }}"
            class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <h2 class="text-lg font-semibold text-slate-900">Report filters</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-slate-700">Start date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="mt-1 block w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-slate-700">End date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="mt-1 block w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <button type="submit"
                class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                Refresh report
            </button>
        </form>
    </div>
</body>

</html>