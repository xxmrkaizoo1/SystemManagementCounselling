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
                    <p class="text-sm text-slate-600 mt-1 truncate">Welcome back, {{ $user->full_name ?: $user->name }}</p>
                </div>
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <a href="{{ route('home') }}"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:text-sky-700 hover:border-sky-200 transition hover:-translate-y-0.5">Home</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:from-sky-700 hover:to-indigo-700 transition hover:-translate-y-0.5 shadow-sm">Logout</button>
                    </form>
                </div>
            </header>

            <div class="p-4 sm:p-6 lg:p-7 grid xl:grid-cols-[240px_1fr] gap-4 lg:gap-5">
                <aside class="rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-sm flex flex-col animate-fade-up animation-delay-1">
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
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-200">
                        <a href="{{ route('admin.counsellor.signup') }}"
                            class="block w-full rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white hover:from-sky-700 hover:to-indigo-700 transition hover:-translate-y-0.5 shadow-sm">
                            Sign up counsellor
                        </a>
                    </div>
                </aside>

                <section class="space-y-4 lg:space-y-5">
                    <div id="overview" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-6 gap-3 sm:gap-4">
                        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md hover:-translate-y-0.5 animate-fade-up animation-delay-1">
                            <p class="text-sm text-slate-500">Total users</p>
                            <p class="text-2xl font-bold text-slate-900 mt-2">{{ $stats['total_users'] }}</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md hover:-translate-y-0.5 animate-fade-up animation-delay-1">
                            <p class="text-sm text-slate-500">Roles configured</p>
                            <p class="text-2xl font-bold text-slate-900 mt-2">{{ $stats['total_roles'] }}</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md hover:-translate-y-0.5 animate-fade-up animation-delay-2">
                            <p class="text-sm text-slate-500">Chat messages</p>
                            <p class="text-2xl font-bold text-slate-900 mt-2">{{ $stats['total_messages'] }}</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md hover:-translate-y-0.5 animate-fade-up animation-delay-2">
                            <p class="text-sm text-slate-500">Inbox notifications</p>
                            <p class="text-2xl font-bold text-slate-900 mt-2">{{ $stats['total_notifications'] }}</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md hover:-translate-y-0.5 animate-fade-up animation-delay-3">
                            <p class="text-sm text-slate-500">Total bookings</p>
                            <p class="text-2xl font-bold text-slate-900 mt-2">{{ $stats['total_bookings'] }}</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md hover:-translate-y-0.5 animate-fade-up animation-delay-3">
                            <p class="text-sm text-slate-500">Pending bookings</p>
                            <p class="text-2xl font-bold text-amber-600 mt-2">{{ $stats['pending_bookings'] }}</p>
                        </article>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-4">
                        <article id="roles" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm animate-fade-up animation-delay-2">
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

                        <article id="users" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm animate-fade-up animation-delay-3">
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
                                                <td class="py-2 text-slate-500" colspan="3">No users available.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </article>
                    </div>

                    <article id="notifications" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm animate-fade-up animation-delay-3">
                        <h2 class="text-lg font-semibold text-slate-900">Recent inbox notifications</h2>
                        <p class="text-sm text-slate-600 mt-1">Most recent system notices sent to users.</p>

                        <div class="mt-4 grid sm:grid-cols-2 xl:grid-cols-3 gap-3">
                            @forelse ($recentNotifications as $notification)
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 transition hover:-translate-y-0.5 hover:shadow-sm hover:border-sky-200">
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
</body>

</html>
