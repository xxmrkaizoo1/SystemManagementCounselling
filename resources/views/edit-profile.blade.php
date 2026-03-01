<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
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

    <main class="min-h-screen p-4 sm:p-8">
        <section
            class="max-w-4xl mx-auto rounded-[2rem] border border-slate-200/80 bg-white/75 backdrop-blur-xl shadow-2xl overflow-hidden">
            <header
                class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/80 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Edit Profile</h1>
                    <p class="text-sm text-slate-500 mt-1">{{ ucfirst($role) }} account</p>
                </div>
                <a href="{{ route('home.session') }}"
                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">Back</a>
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

                <div class="grid gap-5 lg:grid-cols-[220px_1fr]">
                    <aside class="rounded-2xl border border-slate-200 bg-white/85 p-4 shadow-sm">
                        <div class="flex flex-col items-center text-center gap-2">
                            <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}" alt="Profile"
                                class="w-24 h-24 rounded-full border border-slate-200 object-cover bg-sky-50" />
                            <p class="font-semibold text-slate-800">{{ $user->full_name ?: $user->name }}</p>
                            <p class="text-xs uppercase tracking-wide text-sky-700">{{ ucfirst($role) }}</p>
                        </div>

                        <form method="POST" action="{{ route('profile.picture.update') }}"
                            enctype="multipart/form-data" class="mt-4 space-y-2">
                            @csrf
                            <input type="file" name="profile_pic" accept=".jpg,.jpeg,.png,.webp"
                                class="block w-full text-xs" />
                            <button type="submit"
                                class="w-full rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">Update
                                Photo</button>
                        </form>
                    </aside>

                    <form method="POST" action="{{ route('profile.update') }}"
                        class="rounded-2xl border border-slate-200 bg-white/90 p-4 sm:p-5 shadow-sm space-y-4">
                        @csrf
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-slate-700 mb-1.5">Full
                                Name</label>
                            <input id="full_name" name="full_name" type="text"
                                value="{{ old('full_name', $user->full_name ?: $user->name) }}"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">Phone</label>
                            <input id="phone" name="phone" type="text"
                                value="{{ old('phone', $user->phone) }}"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
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
                                        <select id="programme" name="programme" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition">
                                        <option value="">Select programme</option>
                                        @foreach ($programmeOptions as $programmeOption)
                                            <option value="{{ $programmeOption }}" @selected(old('programme', $user->programme) === $programmeOption)>{{ $programmeOption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <button type="submit"
                            class="w-full rounded-xl bg-sky-600 text-white font-semibold py-2.5 hover:bg-sky-700 transition shadow-sm">Save
                            Changes</button>
                    </form>
                </div>
            </div>

            <footer
                class="px-6 sm:px-8 py-4 border-t border-slate-200/80 text-center text-sm text-slate-500 bg-white/70">
                © {{ date('Y') }} CollegeCare • Counselling Booking System
            </footer>
        </section>
    </main>
</body>

</html>
