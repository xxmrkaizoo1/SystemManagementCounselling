<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-25"></div>
        <div class="absolute -top-24 -left-16 w-[30rem] h-[30rem] bg-sky-300/30 rounded-full blur-3xl"></div>
        <div class="absolute top-24 -right-24 w-[30rem] h-[30rem] bg-emerald-300/25 rounded-full blur-3xl"></div>
    </div>

    <main class="min-h-screen p-4 sm:p-8">
        <section
            class="max-w-[96rem] mx-auto rounded-[2rem] border border-slate-200/80 bg-white/80 backdrop-blur-xl shadow-2xl overflow-hidden">
            <header
                class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/85 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Admin Dashboard</h1>
                    <p class="text-sm text-slate-500 mt-1">Welcome back, {{ $user->full_name ?: $user->name }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('home') }}"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">Home</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">Logout</button>
                    </form>
                </div>
            </header>

            <div class="p-5 sm:p-7 grid xl:grid-cols-[230px_1fr] gap-5">
                <aside class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm flex flex-col">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-200">
                        <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}" alt="Profile"
                            class="w-11 h-11 rounded-full border border-slate-200 object-cover bg-sky-50" />
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                            <p class="text-xs uppercase tracking-wide text-emerald-700">Administrator</p>
                        </div>
                    </div>

                    <div class="flex-1">
                        <p class="text-xs uppercase tracking-[0.12em] text-slate-500 mb-3">Admin menu</p>
                        <nav class="space-y-2 text-sm">
                            <a href="#overview"
                                class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">System
                                overview</a>
                            <a href="#roles"
                                class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Role
                                distribution</a>
                            <a href="#users"
                                class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Recent
                                users</a>
                            <a href="#notifications"
                                class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Recent
                                inbox</a>
                        </nav>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-200">
                        <a href="{{ route('admin.counsellor.signup') }}"
                            class="block w-full rounded-xl bg-sky-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-sky-700 transition">
                            Sign up counsellor
                        </a>
                    </div>
                </aside>

                <section class="space-y-5">
                    <div id="overview" class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4">
                        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-sm text-slate-500">Total users</p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $stats['total_users'] }}</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-sm text-slate-500">Roles configured</p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $stats['total_roles'] }}</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-sm text-slate-500">Chat messages</p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $stats['total_messages'] }}</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-sm text-slate-500">Inbox notifications</p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $stats['total_notifications'] }}</p>
                        </article>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-4">
                        <article id="roles" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-800">Users by role</h2>
                            <p class="text-sm text-slate-500 mt-1">Snapshot based on current role assignments.</p>

                            <div class="mt-4 space-y-2">
                                @forelse ($userCountsByRole as $roleName => $total)
                                    <div
                                        class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5">
                                        <span class="capitalize font-medium text-slate-700">{{ $roleName }}</span>
                                        <span class="text-sm font-semibold text-sky-700">{{ $total }}</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No role assignments found yet.</p>
                                @endforelse
                            </div>
                        </article>

                        <article id="users" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-800">Recently registered users</h2>
                            <p class="text-sm text-slate-500 mt-1">Latest 8 users in the platform.</p>

                            <div class="mt-4 overflow-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-slate-500 border-b border-slate-200">
                                            <th class="py-2 pr-2">Name</th>
                                            <th class="py-2 pr-2">Email</th>
                                            <th class="py-2">Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentUsers as $recentUser)
                                            <tr class="border-b border-slate-100">
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

                    <article id="notifications" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <h2 class="text-lg font-semibold text-slate-800">Recent inbox notifications</h2>
                        <p class="text-sm text-slate-500 mt-1">Most recent system notices sent to users.</p>

                        <div class="mt-4 grid md:grid-cols-2 xl:grid-cols-3 gap-3">
                            @forelse ($recentNotifications as $notification)
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">
                                        {{ optional($notification->created_at)->diffForHumans() }}</p>
                                    <p class="font-semibold text-slate-700 mt-1">
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
