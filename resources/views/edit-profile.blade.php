<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .profile-shell {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
    @php
        $showSharedMenu = in_array($role, ['student', 'teacher'], true);
        $dashboardRoute = match ($role) {
            'counsellor' => 'counsellor.dashboard',
            'admin' => 'admin.dashboard',
            default => 'home.session',
        };
    @endphp

    @if (session('status'))
        <div id="toast-status"
            class="fixed top-4 right-4 z-50 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-lg">
            {{ session('status') }}
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-status');
                if (toast) toast.remove();
            }, 3500);
        </script>
    @endif



    <div id="loginLoader" class="fixed inset-0 z-[90] flex items-center justify-center bg-sky-500/95 transition-opacity duration-700">
        <div class="flex flex-col items-center gap-3">
            <span class="h-16 w-16 animate-spin rounded-full border-8 border-white/30 border-t-white"></span>
            <p class="text-xl font-semibold text-white">Loading secure portal...</p>
        </div>
    </div>

    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
        <div class="absolute inset-0 bg-noise-layer opacity-15"></div>
        <div
            class="absolute -top-32 -left-24 w-[34rem] h-[34rem] bg-sky-300/35 rounded-full blur-3xl animate-blob-float">
        </div>
        <div
            class="absolute top-24 -right-32 w-[36rem] h-[36rem] bg-violet-300/30 rounded-full blur-3xl animate-aurora-drift animation-delay-2">
        </div>
    </div>

    <main id="loginContent" class="min-h-screen p-4 sm:p-8 opacity-0 translate-y-2 transition-all duration-700">
        <section
            class="profile-shell max-w-[96rem] mx-auto rounded-[2rem] border border-slate-200/80 bg-white/75 backdrop-blur-xl shadow-2xl overflow-hidden">
            <header
                class="px-5 sm:px-8 py-5 border-b border-slate-200/80 bg-white/85 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Edit Profile</h1>
                    <p class="text-sm text-slate-500 mt-1">{{ ucfirst($role) }} account</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route($dashboardRoute) }}"
                        class="rounded-xl border border-slate-200 bg-white p-3 text-slate-600 hover:text-sky-700 hover:border-sky-200 hover:bg-sky-50 transition">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9.75L12 4l9 5.75M4.5 10.5V19.5A1.5 1.5 0 006 21h3.75v-4.5h4.5V21H18a1.5 1.5 0 001.5-1.5v-9" />
                        </svg>

                    </a>
                </div>
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

                @if (!$user->phone_verified_at)

                    @if (blank($user->phone))
                        <div class="rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                            Add your phone number first, then save profile changes to start phone verification.
                        </div>
                    @elseif (is_null($user->phone_verified_at))
                        <div
                            class="rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800 flex items-center justify-between gap-3">
                            <span>Your phone number is not verified yet. Please verify to confirm this is your real
                                number.</span>
                            <a href="{{ route('phone.otp.form') }}"
                                class="shrink-0 rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-amber-600 transition">Verify
                                Number</a>
                        </div>
                    @endif



                    <div class="grid gap-5 {{ $showSharedMenu ? 'xl:grid-cols-[250px_280px_1fr]' : 'xl:grid-cols-[280px_1fr]' }}">
                        @if ($showSharedMenu)
                            <aside class="rounded-2xl border border-[#b9dbef] bg-[#d8ecf7] p-4 shadow-sm">
                                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-sky-200/80">
                                    <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}" alt="Profile"
                                        class="w-11 h-11 rounded-full border border-slate-200 object-cover bg-sky-50" />
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                                        <p class="text-xs uppercase tracking-wide text-sky-700">
                                            {{ $role === 'student' ? 'Pelajar' : 'Pensyarah' }}</p>
                                    </div>
                                </div>

                                <p class="text-xs uppercase tracking-[0.12em] text-slate-500 mb-3">Menu</p>
                                <nav class="space-y-3 text-sm">
                                    <a href="{{ route('inbox') }}" title="Inbox" aria-label="Inbox"
                                        class="flex w-full items-center gap-3 rounded-xl border border-slate-200/80 bg-white/95 px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                        <span
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-100 text-slate-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.8"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 12.2V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v5.2" />
                                                <path
                                                    d="M2 12.2h4.7a2 2 0 0 1 1.4.6l1 1a2 2 0 0 0 1.4.6h3a2 2 0 0 0 1.4-.6l1-1a2 2 0 0 1 1.4-.6H22" />
                                                <path d="M22 12.2V17a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4.8" />
                                            </svg>
                                        </span>
                                        <span class="text-sm font-medium text-slate-700">Inbox</span>
                                    </a>
                                    <a href="{{ route('chat.index') }}" title="Chat Box" aria-label="Chat Box"
                                        class="flex w-full items-center gap-3 rounded-xl border border-slate-200/80 bg-white/95 px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                        <span
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-100 text-slate-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.8"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                            </svg>
                                        </span>
                                        <span class="text-sm font-medium text-slate-700">Chat Box</span>
                                    </a>
                                    <a href="{{ route('booking.index') }}" title="Booking" aria-label="Booking"
                                        class="flex w-full items-center gap-3 rounded-xl border border-slate-200/80 bg-white/95 px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                        <span
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-100 text-slate-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.8"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                                <path d="M16 2v4M8 2v4M3 10h18" />
                                            </svg>
                                        </span>
                                        <span class="text-sm font-medium text-slate-700">Booking</span>
                                    </a>
                                    <a href="{{ route('booking.history') }}" title="Booking History"
                                        aria-label="Booking History"
                                        class="flex w-full items-center gap-3 rounded-xl border border-slate-200/80 bg-white/95 px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                        <span
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-100 text-slate-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.8"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 3v5h5" />
                                                <path d="M3.05 13A9 9 0 1 0 6 6.3L3 8" />
                                                <path d="M12 7v5l3 2" />
                                            </svg>
                                        </span>
                                        <span class="text-sm font-medium text-slate-700">Booking History</span>
                                    </a>
                                    <a href="{{ route('profile.edit') }}" title="Edit Profile" aria-label="Edit Profile"
                                        class="flex w-full items-center gap-3 rounded-xl border border-sky-300 bg-white px-3 py-2.5 text-sky-700 ring-1 ring-sky-200 shadow-sm">
                                        <span
                                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-sky-300 bg-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.8"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Z" />
                                                <path d="M4 20a8 8 0 0 1 16 0" />
                                            </svg>
                                        </span>
                                        <span class="text-sm font-semibold">Edit Profile</span>
                                    </a>
                                </nav>
                            </aside>
                        @endif

                        <aside
                            class="rounded-2xl border border-sky-100 bg-gradient-to-b from-sky-100/70 to-white p-4 shadow-sm">
                            <div class="flex flex-col items-center text-center gap-2">
                                <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}" alt="Profile"
                                    class="w-24 h-24 rounded-full border border-sky-200 object-cover bg-sky-50" />
                                <p class="font-semibold text-slate-800">{{ $user->full_name ?: $user->name }}</p>
                                <p class="text-xs uppercase tracking-wide text-sky-700">{{ ucfirst($role) }}</p>
                            </div>

                            <form method="POST" action="{{ route('profile.picture.update') }}"
                                enctype="multipart/form-data" class="mt-4 space-y-2">
                                @csrf
                                <input type="file" name="profile_pic" accept=".jpg,.jpeg,.png,.webp"
                                    class="block w-full rounded-lg border border-sky-200 bg-white px-2.5 py-2 text-xs" />
                                <button type="submit"
                                    class="w-full rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">Update
                                    Photo</button>
                                <p class="text-[11px] text-slate-500 text-center">Recommended: square image, max 2MB.
                                </p>
                            </form>
                        </aside>

                        <form method="POST" action="{{ route('profile.update') }}"
                            class="rounded-2xl border border-sky-100 bg-white/95 p-4 sm:p-5 shadow-sm space-y-4">
                            @csrf
                            <div class="rounded-xl border border-sky-100 bg-sky-50/70 px-3 py-2">
                                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-sky-700">Account
                                    Details</p>
                                <p class="mt-0.5 text-xs text-slate-600">Keep your personal info up to date for
                                    smoother counselling sessions.</p>
                            </div>
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-slate-700 mb-1.5">Full
                                    Name</label>
                                <input id="full_name" name="full_name" type="text"
                                    value="{{ old('full_name', $user->full_name ?: $user->name) }}"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                            </div>

                            <div>
                                <label for="phone"
                                    class="block text-sm font-medium text-slate-700 mb-1.5">Phone</label>
                                <input id="phone" name="phone" type="text"
                                    value="{{ old('phone', $user->phone) }}"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                                <p class="mt-1 text-xs text-slate-500">Use an active number for OTP verification and
                                    booking updates.</p>
                            </div>

                            @if ($role === 'student')
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="years"
                                            class="block text-sm font-medium text-slate-700 mb-1.5">Years</label>
                                        <select id="years" name="years"
                                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition">
                                            <option value="">Select years</option>
                                            @foreach ($yearOptions as $yearOption)
                                                <option value="{{ $yearOption }}" @selected(old('years', $user->years) === $yearOption)>
                                                    {{ $yearOption }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div>
                                        <label for="programme"
                                            class="block text-sm font-medium text-slate-700 mb-1.5">Programme</label>
                                        <select id="programme" name="programme"
                                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition">
                                            <option value="">Select programme</option>
                                            @foreach ($programmeOptions as $programmeOption)
                                                <option value="{{ $programmeOption }}" @selected(old('programme', $user->programme) === $programmeOption)>
                                                    {{ $programmeOption }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <button type="submit"
                                class="w-full rounded-xl bg-sky-600 text-white font-semibold py-2.5 hover:bg-sky-700 transition shadow-sm">Save
                                Changes</button>

                            @if ($role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block w-full text-center rounded-xl border border-sky-200 bg-sky-50 px-3 py-2.5 text-sm font-semibold text-sky-700 hover:bg-sky-100 transition">
                                    Admin Dashboard
                                </a>
                            @endif
                        </form>
                    </div>
                @endif
            </div>

            <footer
                class="px-6 sm:px-8 py-4 border-t border-slate-200/80 text-center text-sm text-slate-500 bg-white/70">
                © {{ date('Y') }} CollegeCare • Counselling Booking System
            </footer>
        </section>
    </main>
</body>

</html>
