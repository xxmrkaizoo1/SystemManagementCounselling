<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Overview • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700">
    <main class="min-h-screen p-4 sm:p-8">
        <section class="max-w-5xl mx-auto rounded-[2rem] border border-slate-200/80 bg-white shadow-xl overflow-hidden">
            <header class="px-6 py-5 border-b border-slate-200/80 flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-2xl font-bold text-slate-800">Admin Dashboard</h1>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.counsellor.create') }}"
                        class="rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">Sign
                        up counsellor</a>
                    <a href="{{ route('home') }}"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">Home</a>
                </div>
            </header>

            <div class="p-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('admin.counsellor.create') }}"
                    class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 hover:bg-emerald-100 transition">
                    <p class="text-sm font-semibold text-emerald-700">Quick action</p>
                    <h2 class="mt-1 text-lg font-bold text-slate-800">+ New counsellor account</h2>
                    <p class="mt-1 text-sm text-slate-500">Open counsellor signup form.</p>
                </a>
                <a href="{{ route('admin.counsellor.create') }}"
                    class="rounded-2xl border border-sky-200 bg-sky-50 p-5 hover:bg-sky-100 transition">
                    <p class="text-sm font-semibold text-sky-700">Counsellor</p>
                    <h2 class="mt-1 text-lg font-bold text-slate-800">Sign up counsellor</h2>
                    <p class="mt-1 text-sm text-slate-500">Create a new counsellor user account.</p>
                </a>
            </div>
        </section>
    </main>
</body>

</html>
