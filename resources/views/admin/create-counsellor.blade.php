<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Counsellor • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
    <main class="min-h-screen p-4 sm:p-8">
        <section
            class="max-w-2xl mx-auto rounded-[2rem] border border-slate-200/80 bg-white/90 backdrop-blur-xl shadow-2xl overflow-hidden">
            <header class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/90 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Admin • Create Counsellor</h1>
                </div>
                <a href="{{ route('profile.edit') }}"
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

                <form method="POST" action="{{ route('admin.counsellor.store') }}" class="grid gap-4">
                    @csrf

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

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                            <input id="password" name="password" type="password"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full rounded-xl bg-sky-600 text-white font-semibold py-2.5 hover:bg-sky-700 transition shadow-sm">
                        Create Counsellor
                    </button>
                </form>
            </div>
        </section>
    </main>
</body>

</html>
