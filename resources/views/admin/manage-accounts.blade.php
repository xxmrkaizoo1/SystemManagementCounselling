<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Accounts • CollegeCare</title>
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
            class="max-w-6xl mx-auto rounded-[2rem] border border-slate-200/80 bg-white/90 backdrop-blur-xl shadow-2xl overflow-hidden">
            <header
                class="px-5 sm:px-7 py-5 border-b border-slate-200/80 bg-white/90 flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Urus Akaun Pengguna</h1>
                    <p class="mt-1 text-sm text-slate-500">Kelola akaun pelajar, pensyarah, dan kaunselor dengan
                        cadangan automatik.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">
                    ← Back to dashboard
                </a>
            </header>

            <div class="p-6 sm:p-7 space-y-5">
                <div>
                    <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <h2 class="text-lg font-semibold text-slate-800">Ringkasan Akaun</h2>
                        <p class="text-sm text-slate-500 mt-1">20 akaun terbaru mengikut peranan pengguna.</p>
                        <div class="mt-4 grid gap-3 sm:grid-cols-[1fr_200px]">
                            <div>
                                <label for="user_search" class="block text-sm font-medium text-slate-700 mb-1.5">
                                    Carian nama / emel
                                </label>
                                <input id="user_search" type="text"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition"
                                    placeholder="Contoh: harith atau gmail.com" />
                            </div>
                            <div>
                                <label for="role_filter" class="block text-sm font-medium text-slate-700 mb-1.5">
                                    Tapis peranan
                                </label>
                                <select id="role_filter"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition">
                                    <option value="">Semua peranan</option>
                                    <option value="student">Student</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="counsellor">Counsellor</option>
                                    <option value="admin">Admin</option>
                                    <option value="unassigned">Unassigned</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 overflow-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-slate-500 border-b border-slate-200">
                                        <th class="py-2 pr-2">Nama</th>
                                        <th class="py-2 pr-2">Peranan</th>
                                        <th class="py-2 pr-2">Email</th>
                                        <th class="py-2">Telefon</th>
                                    </tr>
                                </thead>
                                <tbody id="managed_users_table">
                                    @forelse ($managedUsers as $managedUser)
                                        <tr class="border-b border-slate-100 managed-user-row"
                                            data-name="{{ strtolower($managedUser->name) }}"
                                            data-email="{{ strtolower($managedUser->email) }}"
                                            data-role="{{ strtolower($managedUser->role_name) }}">
                                            <td class="py-2 pr-2 font-medium text-slate-700">{{ $managedUser->name }}
                                            </td>
                                            <td class="py-2 pr-2 text-sky-700 capitalize">{{ $managedUser->role_name }}
                                            </td>
                                            <td class="py-2 pr-2 text-slate-600">{{ $managedUser->email }}</td>
                                            <td class="py-2 text-slate-500">{{ $managedUser->phone ?: '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr id="no-data-row">
                                            <td class="py-2 text-slate-500" colspan="4">Tiada data akaun ditemui.
                                            </td>
                                        </tr>
                                    @endforelse
                                    <tr id="empty-filter-row" class="hidden">
                                        <td class="py-2 text-slate-500" colspan="4">Tiada akaun padan dengan carian
                                            atau penapis semasa.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('user_search');
            const roleFilter = document.getElementById('role_filter');
            const userRows = Array.from(document.querySelectorAll('.managed-user-row'));
            const emptyFilterRow = document.getElementById('empty-filter-row');
            const noDataRow = document.getElementById('no-data-row');

            const filterRows = () => {
                if (!userRows.length) {
                    return;
                }

                const keyword = (searchInput?.value || '').trim().toLowerCase();
                const role = (roleFilter?.value || '').trim().toLowerCase();
                let visibleRows = 0;

                userRows.forEach((row) => {
                    const name = row.dataset.name || '';
                    const email = row.dataset.email || '';
                    const userRole = row.dataset.role || '';
                    const keywordMatch = !keyword || name.includes(keyword) || email.includes(keyword);
                    const roleMatch = !role || userRole === role;
                    const shouldShow = keywordMatch && roleMatch;

                    row.classList.toggle('hidden', !shouldShow);
                    if (shouldShow) {
                        visibleRows += 1;
                    }
                });

                emptyFilterRow?.classList.toggle('hidden', visibleRows > 0);
                noDataRow?.classList.add('hidden');
            };

            searchInput?.addEventListener('input', filterRows);
            roleFilter?.addEventListener('change', filterRows);
        });
    </script>
</body>

</html>
