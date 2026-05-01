<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fade-up {
            0% {
                opacity: 0;
                transform: translateY(12px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes aurora-drift {
            0% {
                transform: translate3d(0, 0, 0) scale(1);
            }

            50% {
                transform: translate3d(24px, -18px, 0) scale(1.08);
            }

            100% {
                transform: translate3d(-12px, 16px, 0) scale(1);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                opacity: .35;
            }

            50% {
                opacity: .7;
            }
        }

        .animate-fade-up {
            animation: fade-up .5s ease-out both;
        }

        .animate-aurora-drift {
            animation: aurora-drift 14s ease-in-out infinite alternate;
        }

        .animate-pulse-glow {
            animation: pulse-glow 8s ease-in-out infinite;
        }

        .animation-delay-1 {
            animation-delay: .2s;
        }

        .animation-delay-2 {
            animation-delay: .4s;
        }

        .animation-delay-3 {
            animation-delay: .6s;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 text-slate-700 overflow-x-hidden">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#dbeafe_0%,_#e0f2fe_28%,_#eef2ff_55%,_#f8fafc_100%)]">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        <div
            class="absolute -top-24 -left-20 w-[34rem] h-[34rem] rounded-full bg-cyan-300/40 blur-3xl animate-aurora-drift animate-pulse-glow">
        </div>
        <div
            class="absolute top-8 -right-24 w-[34rem] h-[34rem] rounded-full bg-indigo-300/35 blur-3xl animate-aurora-drift animate-pulse-glow animation-delay-1">
        </div>
        <div
            class="absolute -bottom-24 left-1/4 w-[30rem] h-[30rem] rounded-full bg-emerald-300/30 blur-3xl animate-aurora-drift animate-pulse-glow animation-delay-2">
        </div>
    </div>

    <main class="min-h-screen p-3 sm:p-6 lg:p-8">
        <section
            class="max-w-[96rem] mx-auto rounded-[1.6rem] sm:rounded-[2rem] border border-slate-200/80 bg-white/80 backdrop-blur-xl shadow-2xl overflow-hidden animate-fade-up">
            <header
                class="px-4 sm:px-7 py-4 border-b border-slate-200/80 bg-white/85 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs uppercase tracking-[0.14em] text-indigo-500 font-semibold">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight">Admin Dashboard</h1>
                    <p class="text-sm text-slate-600 mt-1 truncate">Welcome back, {{ $user->full_name ?: $user->name }}
                    </p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:from-sky-700 hover:to-indigo-700 transition hover:-translate-y-0.5 shadow-sm">Logout</button>
                </form>
                </div>
            </header>

            <div class="p-4 sm:p-6 lg:p-7 grid xl:grid-cols-[240px_1fr] gap-4 lg:gap-5">
                <aside
                    class="rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-sm flex flex-col animate-fade-up animation-delay-1">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-200">
                        <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}" alt="Profile"
                            class="w-11 h-11 rounded-full border border-slate-200 object-cover bg-sky-50" />
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                            <p class="text-xs uppercase tracking-wide text-emerald-700 font-semibold">Administrator</p>
                        </div>
                    </div>

                    <div class="flex-1">
                        <p class="text-xs uppercase tracking-[0.12em] text-slate-500 mb-2">Admin menu</p>
                        <a href="{{ route('admin.accounts.manage') }}"
                            class="block rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 hover:border-sky-200 hover:text-sky-700 transition hover:-translate-y-0.5">
                            Manage user accounts
                        </a>
                        <a href="{{ route('admin.users.no-matriks') }}"
                            class="mt-2 block rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 hover:border-sky-200 hover:text-sky-700 transition hover:-translate-y-0.5">
                            View no_matriks list
                        </a>


                        <a href="{{ route('admin.student-statistics') }}"
                            class="mt-2 block rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 hover:border-sky-200 hover:text-sky-700 transition hover:-translate-y-0.5">
                            Student booking statistics
                        </a>
                        <a href="{{ route('admin.counsellor.signup') }}"
                            class="mt-2 block rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 hover:border-sky-200 hover:text-sky-700 transition hover:-translate-y-0.5">
                            Sign up counsellor
                        </a>
                    </div>


                </aside>

                <section class="space-y-4 lg:space-y-5">
                    @php
                        $bookingBase = max((int) ($stats['total_bookings'] ?? 0), 1);
                        $pendingRatio = (int) round(((int) ($stats['pending_bookings'] ?? 0) / $bookingBase) * 100);
                        $overviewValues = [
                            (int) ($stats['total_users'] ?? 0),
                            (int) ($stats['total_roles'] ?? 0),
                            (int) ($stats['total_messages'] ?? 0),
                            (int) ($stats['total_notifications'] ?? 0),
                            (int) ($stats['total_bookings'] ?? 0),
                            (int) ($stats['pending_bookings'] ?? 0),
                        ];
                        $maxOverviewValue = max(max($overviewValues), 1);
                    @endphp

                    <div id="overview"
                        class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-3 2xl:grid-cols-6">
                        <article data-stat-card
                            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md animate-fade-up animation-delay-1">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Users
                                    </p>
                                    <p data-view="number" class="mt-2 text-2xl font-bold text-slate-900">
                                        {{ $stats['total_users'] }}</p>
                                    <div data-view="graph" class="mt-3 hidden">
                                        <div class="h-2.5 w-28 rounded-full bg-slate-100">
                                            <div class="h-2.5 rounded-full bg-sky-500"
                                                style="width: {{ (int) round(((int) ($stats['total_users'] ?? 0) / $maxOverviewValue) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    <span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-700">👥</span>
                                    <button type="button" data-toggle-view
                                        class="text-[11px] font-semibold text-sky-700">Graph</button>
                                </div>

                            </div>
                            <p class="mt-2 text-xs font-medium text-emerald-700">Active accounts</p>
                        </article>

                        <article data-stat-card
                            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md animate-fade-up animation-delay-1">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Roles
                                    </p>
                                    <p data-view="number" class="mt-2 text-2xl font-bold text-slate-900">
                                        {{ $stats['total_roles'] }}</p>
                                    <div data-view="graph" class="mt-3 hidden">
                                        <div class="h-2.5 w-28 rounded-full bg-slate-100">
                                            <div class="h-2.5 rounded-full bg-indigo-500"
                                                style="width: {{ (int) round(((int) ($stats['total_roles'] ?? 0) / $maxOverviewValue) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2"><span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">🛡️</span><button
                                        type="button" data-toggle-view
                                        class="text-[11px] font-semibold text-indigo-700">Graph</button></div>
                            </div>
                            <p class="mt-2 text-xs font-medium text-indigo-700">Access levels</p>
                        </article>





                        <article data-stat-card
                            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md animate-fade-up animation-delay-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Bookings
                                    </p>
                                    <p data-view="number" class="mt-2 text-2xl font-bold text-slate-900">
                                        {{ $stats['total_bookings'] }}
                                    </p>
                                    <div data-view="graph" class="mt-3 hidden">
                                        <div class="h-2.5 w-28 rounded-full bg-slate-100">
                                            <div class="h-2.5 rounded-full bg-emerald-500"
                                                style="width: {{ (int) round(((int) ($stats['total_bookings'] ?? 0) / $maxOverviewValue) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2"><span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">📅</span><button
                                        type="button" data-toggle-view
                                        class="text-[11px] font-semibold text-emerald-700">Graph</button></div>
                            </div>
                            <p class="mt-2 text-xs font-medium text-emerald-700">Scheduled total</p>
                        </article>

                        <article data-stat-card
                            class="rounded-2xl border border-amber-200 bg-amber-50/70 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md animate-fade-up animation-delay-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-amber-700">Pending
                                    </p>
                                    <p data-view="number" class="mt-2 text-2xl font-bold text-amber-700">
                                        {{ $stats['pending_bookings'] }} </p>
                                    <div data-view="graph" class="mt-3 hidden">
                                        <div class="h-2.5 w-28 rounded-full bg-amber-100">
                                            <div class="h-2.5 rounded-full bg-amber-500"
                                                style="width: {{ (int) round(((int) ($stats['pending_bookings'] ?? 0) / $maxOverviewValue) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2"><span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-700">⏳</span><button
                                        type="button" data-toggle-view
                                        class="text-[11px] font-semibold text-amber-700">Graph</button></div>
                            </div>
                            <p class="mt-2 text-xs font-medium text-amber-700">{{ $pendingRatio }}% of bookings</p>
                        </article>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-4">
                        <article id="roles"
                            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm animate-fade-up animation-delay-2">
                            <h2 class="text-lg font-semibold text-slate-900">Users by role</h2>
                            <p class="text-sm text-slate-600 mt-1">Snapshot based on current role assignments.</p>

                            <div class="mt-4 space-y-2">
                                @forelse ($userCountsByRole as $roleName => $total)
                                    <div
                                        class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 transition hover:border-sky-200 hover:bg-sky-50/40">
                                        <span class="capitalize font-medium text-slate-700">{{ $roleName }}</span>
                                        <span class="text-sm font-semibold text-indigo-700">{{ $total }}</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No role assignments found yet.</p>
                                @endforelse
                            </div>
                        </article>

                        <article id="users"
                            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm animate-fade-up animation-delay-3">
                            <h2 class="text-lg font-semibold text-slate-900">Recently registered users</h2>
                            <p class="text-sm text-slate-600 mt-1">Latest 8 users in the platform.</p>

                            <div class="mt-4 overflow-auto">
                                <table class="w-full min-w-[520px] text-sm">
                                    <thead>
                                        <tr class="text-left text-slate-500 border-b border-slate-200">
                                            <th class="py-2 pr-2">Name</th>
                                            <th class="py-2 pr-2">Email</th>
                                            <th class="py-2">Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentUsers as $recentUser)
                                            <tr class="border-b border-slate-100 transition hover:bg-slate-50">
                                                <td class="py-2 pr-2 font-medium text-slate-700">
                                                    {{ $recentUser->name }}</td>
                                                <td class="py-2 pr-2 text-slate-600">{{ $recentUser->email }}</td>
                                                <td class="py-2 text-slate-500">
                                                    {{ optional($recentUser->created_at)->diffForHumans() }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="py-2 text-slate-500" colspan="3">No users available.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </article>
                    </div>

                    <article id="notifications"
                        class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm animate-fade-up animation-delay-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Recent inbox notifications</h2>
                                <p class="text-sm text-slate-600 mt-1">Most recent system notices sent to users.</p>
                            </div>

                            {{-- <a href="{{ route('inbox') }}"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-sky-200 hover:text-sky-700 hover:bg-sky-50/60">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path
                                        d="M2.5 4.75A2.25 2.25 0 0 1 4.75 2.5h10.5A2.25 2.25 0 0 1 17.5 4.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.75A2.25 2.25 0 0 1 2.5 15.25V4.75Zm8.28 2.47a.75.75 0 0 0-1.06 0L6.97 9.97a.75.75 0 1 0 1.06 1.06l1.47-1.47V13a.75.75 0 0 0 1.5 0V9.56l1.47 1.47a.75.75 0 1 0 1.06-1.06l-2.75-2.75Z" />
                                </svg>
                                View all
                            </a> --}}
                        </div>

                        <div class="mt-4 grid sm:grid-cols-2 xl:grid-cols-3 gap-3">
                            @forelse ($recentNotifications as $notification)
                                <div
                                    class="rounded-xl border border-slate-200 bg-slate-50 p-3 transition hover:-translate-y-0.5 hover:shadow-sm hover:border-sky-200">
                                    <p class="text-xs text-slate-500">
                                        {{ optional($notification->created_at)->diffForHumans() }}</p>
                                    <p class="font-semibold text-slate-800 mt-1">
                                        {{ $notification->title ?: 'Notification' }}</p>
                                    <p class="text-sm text-slate-600 mt-1">{{ $notification->message }}</p>
                                    <p class="text-xs text-slate-500 mt-2">To:
                                        {{ $notification->user?->name ?: 'Unknown user' }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">No notifications found.</p>
                            @endforelse
                        </div>
                    </article>
                </section>
            </div>
        </section>
    </main>
    <script>
        document.querySelectorAll('[data-stat-card]').forEach((card) => {
            const toggleButton = card.querySelector('[data-toggle-view]');
            const numberView = card.querySelector('[data-view="number"]');
            const graphView = card.querySelector('[data-view="graph"]');
            toggleButton?.addEventListener('click', () => {
                const showingNumber = !numberView.classList.contains('hidden');
                numberView.classList.toggle('hidden', showingNumber);
                graphView.classList.toggle('hidden', !showingNumber);
                toggleButton.textContent = showingNumber ? 'Number' : 'Graph';
            });
        });
    </script>
</body>

</html>
