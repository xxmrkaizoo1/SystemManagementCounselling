<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Messages • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 p-6 text-slate-800">
    <main class="mx-auto w-full max-w-md rounded-2xl border border-slate-200 bg-white shadow-xl overflow-hidden">
        <header class="flex items-center justify-between bg-slate-950 px-5 py-4 text-white">
            <h1 class="text-2xl font-bold">Messages</h1>
            <div class="flex items-center gap-3 text-slate-300">
                <button class="rounded-full border border-amber-300/40 p-2 text-amber-300" aria-label="Notifications">🔔</button>
                <span>⌄</span>
            </div>
        </header>

        <section class="p-5 space-y-4">
            <label class="flex items-center gap-3 rounded-full border border-slate-300 bg-slate-100 px-4 py-2 text-slate-500">
                <span>🔎</span>
                <input type="text" placeholder="Search" class="w-full bg-transparent text-sm outline-none" />
            </label>

            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Chats</h2>
                <a href="#" class="text-sm font-medium text-sky-600 hover:text-sky-700">Requests</a>
            </div>

            <article class="rounded-2xl border border-slate-200 p-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700 font-semibold">A</div>
                    <div class="min-w-0">
                        <h3 class="truncate text-sm font-semibold uppercase text-slate-700">AIDY ILHAM RAFIQUE BIN MOHD RAFZAN NUR</h3>
                        <p class="text-sm text-slate-500">Tekanan akademik</p>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2 text-slate-400">
                    <span>◂</span>
                    <div class="h-2 w-full rounded-full bg-slate-200">
                        <div class="h-2 w-4/5 rounded-full bg-slate-500"></div>
                    </div>
                    <span>▸</span>
                </div>
            </article>
        </section>
    </main>
</body>

</html>
