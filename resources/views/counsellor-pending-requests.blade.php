<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pending Requests • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700">
    <main class="mx-auto max-w-5xl p-4 sm:p-8">
        <section class="rounded-3xl border border-slate-200 bg-white shadow-xl overflow-hidden">
            <header class="border-b border-slate-200 bg-white px-5 py-4 sm:px-7 flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl font-bold text-slate-800">Pending Requests</h1>
                    <p class="text-sm text-slate-500">Counsellor: {{ $user->full_name ?: $user->name }}</p>
                </div>
                <a href="{{ route('counsellor.dashboard') }}"
                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                    Back to Dashboard
                </a>
            </header>

            <div class="p-5 sm:p-7">
                @if (session('status'))
                    <div
                        class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="overflow-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Student</th>
                                <th class="px-4 py-3 font-semibold">Date</th>
                                <th class="px-4 py-3 font-semibold">Time</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 font-semibold">Topic</th>
                                <th class="px-4 py-3 font-semibold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($pendingRequests as $request)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-slate-700">{{ $request['student'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $request['date'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $request['time'] }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                            {{ ucfirst($request['status']) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">{{ $request['topic'] ?: 'General support' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <form method="POST"
                                                action="{{ route('counsellor.booking-request.status', $request['id']) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit"
                                                    class="rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100">
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('counsellor.booking-request.status', $request['id']) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit"
                                                    class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500">No pending requests
                                        available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
