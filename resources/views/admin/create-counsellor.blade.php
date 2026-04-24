<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Counsellor • CollegeCare</title>
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
                transform: translate3d(20px, -14px, 0) scale(1.08);
            }

            100% {
                transform: translate3d(-10px, 14px, 0) scale(1);
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
            animation: aurora-drift 13s ease-in-out infinite alternate;
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
    </style>
</head>

<body class="min-h-screen bg-slate-100 text-slate-700 overflow-x-hidden">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#dbeafe_0%,_#e0f2fe_28%,_#eef2ff_55%,_#f8fafc_100%)]">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        <div
            class="absolute -top-20 -left-20 w-[32rem] h-[32rem] rounded-full bg-cyan-300/35 blur-3xl animate-aurora-drift animate-pulse-glow">
        </div>
        <div
            class="absolute top-10-right-24 w-[32rem] h-[32rem] rounded-full bg-indigo-300/30 blur-3xl animate-aurora-drift animate-pulse-glow animation-delay-1">
        </div>
        <div
            class="absolute -bottom-24 left-1/3 w-[28rem] h-[28rem] rounded-full bg-emerald-300/25 blur-3xl animate-aurora-drift animate-pulse-glow animation-delay-2">
        </div>
    </div>

    <main class="min-h-screen p-4 sm:p-8">
        <section
            class="max-w-3xl mx-auto rounded-[2rem] border border-slate-200/80 bg-white/90 backdrop-blur-xl shadow-2xl overflow-hidden animate-fade-up">
            <header
                class="px-5 sm:px-7 py-5 border-b border-slate-200/80 bg-white/90 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-indigo-500 font-semibold">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Admin • Create Counsellor</h1>
                    <p class="mt-1 text-sm text-slate-600">Create a new counsellor account with suggested details in one
                        step.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">

                    <!-- Curved Back Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 14L4 9l5-5M4 9h10a7 7 0 110 14h-3" />
                    </svg>


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

                <form method="POST" action="{{ route('admin.counsellor.store') }}"
                    class="grid gap-5 animate-fade-up animation-delay-1">
                    @csrf

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-slate-700 mb-1.5">Full
                                Name</label>
                            <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}"
                                class="w-full rounded-xl border border-slate-300 bg-white/95 px-3 py-2.5 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500/25 focus:border-indigo-500 transition" />
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">Phone</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                                class="w-full rounded-xl border border-slate-300 bg-white/95 px-3 py-2.5 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500/25 focus:border-indigo-500 transition" />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3 mb-1.5">
                            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                            <button type="button" id="suggest-email"
                                class="text-xs font-semibold text-indigo-700 hover:text-indigo-800 transition">
                                Suggest from name
                            </button>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            class="w-full rounded-xl border border-slate-300 bg-white/95 px-3 py-2.5 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500/25 focus:border-indigo-500 transition" />
                    </div>

                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50/70 p-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Quick suggestions</p>
                                <p class="text-xs text-slate-600 mt-0.5">Use suggested credentials, then share them
                                    securely
                                    with the counsellor.</p>
                            </div>
                            <button type="button" id="generate-password"
                                class="rounded-lg border border-indigo-200 bg-white px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                                Generate strong password
                            </button>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                            <input id="password" name="password" type="text"
                                class="w-full rounded-xl border border-slate-300 bg-white/95 px-3 py-2.5 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500/25 focus:border-indigo-500 transition" />
                        </div>
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-slate-700 mb-1.5">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="text"
                                class="w-full rounded-xl border border-slate-300 bg-white/95 px-3 py-2.5 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500/25 focus:border-indigo-500 transition" />
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-3 pt-1">
                        <a href="{{ route('admin.dashboard') }}"
                            class="w-full text-center rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-700 hover:border-sky-200 hover:text-sky-700 transition">
                            Back
                        </a>
                        <button type="submit"
                            class="w-full rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 text-white font-semibold py-2.5 hover:from-sky-700 hover:to-indigo-700 transition shadow-sm">
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
