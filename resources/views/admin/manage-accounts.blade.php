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
                <div class="grid lg:grid-cols-[1.1fr_1fr] gap-5">
                    <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm space-y-4">
                        <h2 class="text-lg font-semibold text-slate-800">Cadangan Akaun Baru</h2>
                        <p class="text-sm text-slate-500">Isi nama dan peranan, kemudian guna fungsi cadangan untuk jana
                            emel dan kata laluan.</p>

                        <div class="grid sm:grid-cols-2 gap-3">
                            <div class="sm:col-span-2">
                                <label for="suggest_name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama
                                    penuh</label>
                                <input id="suggest_name" type="text"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition"
                                    placeholder="Contoh: Nur Aina Binti Ismail" />
                            </div>
                            <div>
                                <label for="suggest_role"
                                    class="block text-sm font-medium text-slate-700 mb-1.5">Peranan</label>
                                <select id="suggest_role"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition">
                                    <option value="student">Pelajar</option>
                                    <option value="teacher">Pensyarah</option>
                                    <option value="counsellor">Kaunselor</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="button" id="suggest_email"
                                    class="w-full rounded-xl border border-sky-200 bg-sky-50 px-3 py-2.5 text-sm font-semibold text-sky-700 hover:bg-sky-100 transition">
                                    Suggest email
                                </button>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="suggested_email"
                                    class="block text-sm font-medium text-slate-700 mb-1.5">Email dicadangkan</label>
                                <input id="suggested_email" type="text"
                                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-700"
                                    readonly />
                            </div>
                            <div class="sm:col-span-2">
                                <label for="suggested_password"
                                    class="block text-sm font-medium text-slate-700 mb-1.5">Kata laluan
                                    dicadangkan</label>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <input id="suggested_password" type="text"
                                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-700"
                                        readonly />
                                    <button type="button" id="generate_password"
                                        class="rounded-xl bg-sky-600 px-3 py-2.5 text-sm font-semibold text-white hover:bg-sky-700 transition">
                                        Generate password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <h2 class="text-lg font-semibold text-slate-800">Ringkasan Akaun</h2>
                        <p class="text-sm text-slate-500 mt-1">20 akaun terbaru mengikut peranan pengguna.</p>

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
                                <tbody>
                                    @forelse ($managedUsers as $managedUser)
                                        <tr class="border-b border-slate-100">
                                            <td class="py-2 pr-2 font-medium text-slate-700">{{ $managedUser->name }}
                                            </td>
                                            <td class="py-2 pr-2 text-sky-700 capitalize">{{ $managedUser->role_name }}
                                            </td>
                                            <td class="py-2 pr-2 text-slate-600">{{ $managedUser->email }}</td>
                                            <td class="py-2 text-slate-500">{{ $managedUser->phone ?: '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="py-2 text-slate-500" colspan="4">Tiada data akaun ditemui.
                                            </td>
                                        </tr>
                                    @endforelse
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
            const nameInput = document.getElementById('suggest_name');
            const roleInput = document.getElementById('suggest_role');
            const emailInput = document.getElementById('suggested_email');
            const passwordInput = document.getElementById('suggested_password');
            const emailButton = document.getElementById('suggest_email');
            const passwordButton = document.getElementById('generate_password');

            const normalizeName = (name) => name
                .trim()
                .toLowerCase()
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, '.');

            const roleDomain = (role) => {
                if (role === 'teacher') return 'lecturer.collegecare.edu';
                if (role === 'counsellor') return 'counsellor.collegecare.edu';
                return 'student.collegecare.edu';
            };

            const shuffle = (value) => value
                .split('')
                .sort(() => Math.random() - 0.5)
                .join('');

            const pick = (value) => value.charAt(Math.floor(Math.random() * value.length));

            emailButton?.addEventListener('click', () => {
                const normalized = normalizeName(nameInput?.value || '');
                if (!normalized) {
                    nameInput?.focus();
                    return;
                }

                emailInput.value = `${normalized}@${roleDomain(roleInput?.value)}`;
            });

            passwordButton?.addEventListener('click', () => {
                const uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
                const lowercase = 'abcdefghijkmnopqrstuvwxyz';
                const numbers = '23456789';
                const symbols = '!@#$%';
                const allChars = `${uppercase}${lowercase}${numbers}${symbols}`;
                let password = `${pick(uppercase)}${pick(lowercase)}${pick(numbers)}${pick(symbols)}`;

                for (let i = 0; i < 8; i += 1) {
                    password += pick(allChars);
                }

                passwordInput.value = shuffle(password);
            });
        });
    </script>
</body>

</html>
