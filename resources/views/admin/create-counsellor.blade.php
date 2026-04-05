<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Counsellor • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-25"></div>
        <div class="absolute -top-24 -left-16 w-[30rem] h-[30rem] bg-sky-300/30 rounded-full blur-3xl"></div>
        <div class="absolute top-24 -right-24 w-[30rem] h-[30rem] bg-emerald-300/25 rounded-full blur-3xl"></div>
    </div>

    <main class="min-h-screen p-4 sm:p-8">
        <section
            class="max-w-3xl mx-auto rounded-[2rem] border border-slate-200/80 bg-white/90 backdrop-blur-xl shadow-2xl overflow-hidden">
            <header
                class="px-5 sm:px-7 py-5 border-b border-slate-200/80 bg-white/90 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Admin • Create Counsellor</h1>
                    <p class="mt-1 text-sm text-slate-500">Create a new counsellor account with suggested details in one step.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">
                    ← Back to dashboard
                </a>
            </header>

            <div class="p-6 sm:p-8 space-y-5">
                @if (session('status'))
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.counsellor.store') }}" class="grid gap-5">
                    @csrf

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-slate-700 mb-1.5">Full Name</label>
                            <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">Phone</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3 mb-1.5">
                            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                            <button type="button" id="suggest-email"
                                class="text-xs font-semibold text-sky-700 hover:text-sky-800 transition">
                                Suggest from name
                            </button>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                    </div>

                    <div class="rounded-2xl border border-sky-100 bg-sky-50/70 p-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Quick suggestions</p>
                                <p class="text-xs text-slate-500 mt-0.5">Use suggested credentials, then share them securely with the counsellor.</p>
                            </div>
                            <button type="button" id="generate-password"
                                class="rounded-lg border border-sky-200 bg-white px-3 py-2 text-xs font-semibold text-sky-700 hover:bg-sky-100 transition">
                                Generate strong password
                            </button>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                            <input id="password" name="password" type="text"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                        </div>
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-slate-700 mb-1.5">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="text"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-3 pt-1">
                        <a href="{{ route('admin.dashboard') }}"
                            class="w-full text-center rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-700 hover:border-sky-200 hover:text-sky-700 transition">
                            Back
                        </a>
                        <button type="submit"
                            class="w-full rounded-xl bg-sky-600 text-white font-semibold py-2.5 hover:bg-sky-700 transition shadow-sm">
                            Create Counsellor
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fullNameInput = document.getElementById('full_name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const suggestEmailButton = document.getElementById('suggest-email');
            const generatePasswordButton = document.getElementById('generate-password');

            const normalizeName = (name) => name
                .trim()
                .toLowerCase()
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, '.');

            suggestEmailButton?.addEventListener('click', () => {
                const normalized = normalizeName(fullNameInput?.value || '');
                if (!normalized) {
                    fullNameInput?.focus();
                    return;
                }

                emailInput.value = `${normalized}@collegecare.edu`;
            });

            const shuffle = (value) => value
                .split('')
                .sort(() => Math.random() - 0.5)
                .join('');

            const pick = (value) => value.charAt(Math.floor(Math.random() * value.length));

            generatePasswordButton?.addEventListener('click', () => {
                const uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
                const lowercase = 'abcdefghijkmnopqrstuvwxyz';
                const numbers = '23456789';
                const symbols = '!@#$%';
                const allChars = `${uppercase}${lowercase}${numbers}${symbols}`;
                let password = `${pick(uppercase)}${pick(lowercase)}${pick(numbers)}${pick(symbols)}`;

                for (let i = 0; i < 8; i += 1) {
                    password += pick(allChars);
                }

                password = shuffle(password);

                passwordInput.value = password;
                passwordConfirmationInput.value = password;
            });
        });
    </script>
</body>

</html>
