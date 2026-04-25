<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>no_matriks Users • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float-orb {
            0% {
                transform: translate3d(0, 0, 0) scale(1);
            }

            50% {
                transform: translate3d(26px, -20px, 0) scale(1.06);
            }

            100% {
                transform: translate3d(-12px, 14px, 0) scale(1);
            }
        }

        @keyframes fade-up {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-float-orb {
            animation: float-orb 16s ease-in-out infinite alternate;
        }

        .animate-fade-up {
            animation: fade-up .5s ease-out both;
        }

        .delay-1 {
            animation-delay: .12s;
        }

        .delay-2 {
            animation-delay: .24s;
        }

        .delay-3 {
            animation-delay: .34s;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 text-slate-700 overflow-x-hidden">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#dbeafe_0%,_#e0f2fe_35%,_#e9d5ff_62%,_#f8fafc_100%)]">
        </div>
        <div
            class="absolute -top-24 -left-16 h-[30rem] w-[30rem] rounded-full bg-cyan-300/35 blur-3xl animate-float-orb">
        </div>
        <div
            class="absolute -bottom-20 -right-20 h-[28rem] w-[28rem] rounded-full bg-indigo-300/30 blur-3xl animate-float-orb">
        </div>
    </div>

    <main class="min-h-screen p-4 sm:p-6 lg:p-8">
        <section
            class="max-w-6xl mx-auto rounded-[1.8rem] border border-slate-200/80 bg-white/85 backdrop-blur-xl shadow-2xl overflow-hidden animate-fade-up">
            <header
                class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/80 flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-indigo-500 font-semibold">CollegeCare</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Admin • Number Matriks Users</h1>
                    <p class="text-sm text-slate-600 mt-1">Manage your no_matriks list with a cleaner experience.</p>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white/90 px-3 py-2 text-sm font-medium text-slate-700 hover:border-sky-300 hover:text-sky-700 transition hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M17 10a.75.75 0 0 1-.75.75H5.56l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 1.06L5.56 9.25h10.69A.75.75 0 0 1 17 10Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Back</span>
                </a>
            </header>

            <div class="p-5 sm:p-7">
                @if (session('status'))
                    <div
                        class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 animate-fade-up delay-1">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 animate-fade-up delay-1">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('error_popup'))
                    <div id="error-popup"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 px-4">
                        <div class="w-full max-w-md rounded-2xl bg-white p-5 shadow-2xl">
                            <h3 class="text-lg font-semibold text-rose-700">Unable to save no_matriks</h3>
                            <p class="mt-2 text-sm text-slate-700">{{ session('error_popup') }}</p>
                            <button id="close-error-popup" type="button"
                                class="mt-4 rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 transition">
                                OK
                            </button>
                        </div>
                    </div>
                @endif

                <div
                    class="mb-5 rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm transition hover:shadow-md animate-fade-up delay-1">
                    <h2 class="text-lg font-semibold text-slate-900">Add many no_matriks numbers</h2>
                    <p class="mt-1 text-sm text-slate-600">Enter one or multiple values (one per line, comma, or
                        semicolon).</p>

                    <form method="POST" action="{{ url('/admin/no-matriks-users') }}" enctype="multipart/form-data"
                        class="mt-4 grid gap-3 sm:grid-cols-[1fr_auto] sm:items-end">
                        @csrf
                        <div>
                            <label for="no_matriks" class="mb-1 block text-sm font-medium text-slate-700">no_matriks
                                list</label>
                            <textarea id="no_matriks" name="no_matriks" rows="4"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-sky-400 focus:ring-sky-400"
                                placeholder="A23CS0001&#10;A23CS0002&#10;A23CS0003">{{ old('no_matriks') }}</textarea>
                            <div id="file-dropzone"
                                class="mt-3 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-3 py-3 text-sm text-slate-600 transition hover:border-sky-400 hover:bg-sky-50">
                                <label for="no_matriks_file" class="block cursor-pointer">
                                    Drop image / TXT / CSV here, or click to choose file.
                                </label>
                                <input id="no_matriks_file" name="no_matriks_file" type="file" class="sr-only"
                                    accept=".txt,.csv,image/png,image/jpeg,image/webp">
                                <p id="file-name" class="mt-1 text-xs text-slate-500">No file selected.</p>
                            </div>
                        </div>

                        <button type="submit"
                            class="rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:from-sky-700 hover:to-indigo-700 transition hover:-translate-y-0.5 shadow-sm">
                            Save
                        </button>
                    </form>
                </div>

                <div
                    class="mb-5 rounded-xl border border-sky-200 bg-sky-50/80 px-4 py-3 text-sm text-sky-700 animate-fade-up delay-2">
                    Total no_matriks in list: <span class="font-semibold">{{ $matriksEntries->count() }}</span>
                </div>

                <div
                    class="overflow-auto rounded-2xl border border-slate-200 bg-white/95 shadow-sm animate-fade-up delay-3">
                    <table class="w-full min-w-[480px] text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">no_matriks</th>
                                <th class="px-4 py-3 text-left font-semibold">Added</th>
                                <th class="px-4 py-3 text-left font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($matriksEntries as $entry)
                                <tr class="border-t border-slate-200 hover:bg-sky-50/60 transition">
                                    <td class="px-4 py-3 font-mono text-slate-800">{{ $entry->no_matriks }}</td>
                                    <td class="px-4 py-3 text-slate-500">
                                        {{ optional($entry->created_at)->diffForHumans() }}</td>
                                    <td class="px-4 py-3">
                                        @if ($entry->is_used)
                                            <span
                                                class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                Used by user
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                                Not used yet
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-slate-500"> No no_matriks
                                        entries found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
    <script>
        (() => {
            const input = document.getElementById('no_matriks_file');
            const dropzone = document.getElementById('file-dropzone');
            const fileName = document.getElementById('file-name');
            if (!input || !dropzone || !fileName) return;

            const updateLabel = (file) => {
                fileName.textContent = file ? `Selected: ${file.name}` : 'No file selected.';
            };

            input.addEventListener('change', () => updateLabel(input.files?.[0]));

            ['dragenter', 'dragover'].forEach((eventName) => {
                dropzone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropzone.classList.add('border-sky-500', 'bg-sky-50');
                });
            });

            ['dragleave', 'drop'].forEach((eventName) => {
                dropzone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropzone.classList.remove('border-sky-500', 'bg-sky-50');
                });
            });

            dropzone.addEventListener('drop', (event) => {
                const droppedFile = event.dataTransfer?.files?.[0];
                if (!droppedFile) return;
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(droppedFile);
                input.files = dataTransfer.files;
                updateLabel(droppedFile);
            });

            const errorPopup = document.getElementById('error-popup');
            const closePopup = document.getElementById('close-error-popup');
            if (errorPopup && closePopup) {
                closePopup.addEventListener('click', () => errorPopup.remove());
                errorPopup.addEventListener('click', (event) => {
                    if (event.target === errorPopup) errorPopup.remove();
                });
            }
        })();
    </script>
</body>

</html>
