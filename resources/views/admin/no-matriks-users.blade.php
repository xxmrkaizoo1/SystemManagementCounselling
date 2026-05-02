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
                    <p class="text-sm text-indigo-500 mt-1">Manage your number matriks list with a cleaner experience.</p>
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
                    <p class="mt-1 text-sm text-slate-600">Enter one per line. Supported format: <span
                            class="font-medium">NAME | NO_MATRIKS</span> (or CSV: <span
                            class="font-medium">NAME,NO_MATRIKS</span>).</p>

                    <form method="POST" action="{{ url('/admin/no-matriks-users') }}" enctype="multipart/form-data"
                        class="mt-4 grid gap-3 sm:grid-cols-[1fr_auto] sm:items-end">
                        @csrf
                        <div>
                            <label for="no_matriks" class="mb-1 block text-sm font-medium text-slate-700">no_matriks
                                list</label>
                            <textarea id="no_matriks" name="no_matriks" rows="4"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-sky-400 focus:ring-sky-400"
                                placeholder="Ali Bin Abu | A23CS0001&#10;Siti Aminah | A23CS0002&#10;A23CS0003">{{ old('no_matriks') }}</textarea>
                            <div id="file-dropzone"
                                class="mt-3 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-3 py-3 text-sm text-slate-600 transition hover:border-sky-400 hover:bg-sky-50">
                                <label for="no_matriks_file" class="block cursor-pointer">
                                    Drop TXT / CSV here, or click to choose file. </label>
                                <input id="no_matriks_file" name="no_matriks_file" type="file" class="sr-only"
                                    accept=".txt,.csv">
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
                    Showing <span class="font-semibold">{{ $matriksEntries->count() }}</span> result(s)
                    @if (!empty($filters['search']) || ($filters['status'] ?? 'all') !== 'all')
                        (filtered)
                    @endif
                    • Total no_matriks in list: <span
                        class="font-semibold">{{ $totalEntriesCount ?? $matriksEntries->count() }}</span>
                </div>

                <div class="mb-5 rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm animate-fade-up delay-2">
                    <form method="GET" action="{{ route('admin.users.no-matriks') }}"
                        class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_220px_auto_auto] sm:items-end">
                        <div>
                            <label for="search" class="mb-1 block text-sm font-medium text-slate-700">Search
                                no_matriks / name</label>
                            <input id="search" name="search" type="text" value="{{ $filters['search'] ?? '' }}"
                                placeholder="e.g. BKV0625"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-sky-400 focus:ring-sky-400">
                        </div>
                        <div>
                            <label for="status" class="mb-1 block text-sm font-medium text-slate-700">Category</label>
                            <select id="status" name="status"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-sky-400 focus:ring-sky-400">
                                <option value="all" @selected(($filters['status'] ?? 'all') === 'all')>All entries</option>
                                <option value="used" @selected(($filters['status'] ?? 'all') === 'used')>Used by user</option>
                                <option value="unused" @selected(($filters['status'] ?? 'all') === 'unused')>Not used yet</option>
                            </select>
                        </div>
                        <button type="submit"
                            class="inline-flex h-10 items-center justify-center rounded-xl border border-sky-200 bg-sky-50 px-4 text-sm font-semibold text-sky-700 hover:bg-sky-100">
                            Apply
                        </button>
                        <a href="{{ route('admin.users.no-matriks') }}"
                            class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Reset
                        </a>
                    </form>
                </div>

                <div
                    class="overflow-auto rounded-2xl border border-slate-200 bg-white/95 shadow-sm animate-fade-up delay-3">
                    <form id="bulk-delete-form" method="POST"
                        action="{{ route('admin.users.no-matriks.bulk-destroy') }}">
                        @csrf
                        @method('DELETE')
                        <div
                            class="flex flex-col gap-2 border-b border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-600 sm:flex-row sm:items-center sm:justify-between">
                            <p>Select one or more no_matriks and click delete.</p>
                            <button id="bulk-delete-btn" type="submit"
                                class="inline-flex w-fit items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 disabled:cursor-not-allowed disabled:opacity-50"
                                disabled>
                                Delete selected
                            </button>
                        </div>

                        <table class="w-full min-w-[760px] text-sm">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">no_matriks</th>
                                    <th class="px-4 py-3 text-left font-semibold">Name</th>
                                    <th class="px-4 py-3 text-left font-semibold">Added</th>
                                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                                    <th class="px-4 py-3 text-left font-semibold">
                                        <label class="inline-flex items-center gap-2">
                                            <input id="select-all-entries" type="checkbox"
                                                class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                            <span>Delete</span>
                                        </label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($matriksEntries as $entry)
                                    <tr class="border-t border-slate-200 hover:bg-sky-50/60 transition">
                                        <td class="px-4 py-3 font-mono text-slate-800">{{ $entry->no_matriks }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $entry->label_name ?: '-' }}</td>
                                        <td class="px-4 py-3 text-slate-500">
                                            {{ optional($entry->created_at)->diffForHumans() }}</td>
                                        <td class="px-4 py-3">
                                            @if ($entry->is_used)
                                                <button type="button"
                                                    class="used-by-user-btn inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-200 transition"
                                                    data-user-name="{{ $entry->used_by_user?->name ?? 'Unknown' }}"
                                                    data-user-email="{{ $entry->used_by_user?->email ?? 'N/A' }}"
                                                    data-user-phone="{{ $entry->used_by_user?->phone ?? 'N/A' }}"
                                                    data-user-id="{{ $entry->used_by_user?->id ?? 'N/A' }}"
                                                    data-user-created="{{ optional($entry->used_by_user?->created_at)?->diffForHumans() ?? 'N/A' }}"
                                                    data-user-no-matriks="{{ $entry->no_matriks }}">
                                                    Used by user
                                                </button>
                                            @else
                                                <span
                                                    class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                                    Not used yet
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="checkbox" name="entry_ids[]" value="{{ $entry->id }}"
                                                class="entry-checkbox h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-500"> No no_matriks
                                            entries found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <div id="user-info-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4"
        aria-hidden="true">
        <div class="w-full max-w-md rounded-2xl bg-white p-5 shadow-2xl">
            <h3 class="text-lg font-semibold text-slate-900">User account information</h3>
            <dl class="mt-3 space-y-2 text-sm text-slate-700">
                <div class="grid grid-cols-[120px_1fr] gap-2">
                    <dt class="font-semibold text-slate-500">User ID</dt>
                    <dd id="modal-user-id">-</dd>
                </div>
                <div class="grid grid-cols-[120px_1fr] gap-2">
                    <dt class="font-semibold text-slate-500">Name</dt>
                    <dd id="modal-user-name">-</dd>
                </div>
                <div class="grid grid-cols-[120px_1fr] gap-2">
                    <dt class="font-semibold text-slate-500">Email</dt>
                    <dd id="modal-user-email">-</dd>
                </div>
                <div class="grid grid-cols-[120px_1fr] gap-2">
                    <dt class="font-semibold text-slate-500">Phone</dt>
                    <dd id="modal-user-phone">-</dd>
                </div>
                <div class="grid grid-cols-[120px_1fr] gap-2">
                    <dt class="font-semibold text-slate-500">no_matriks</dt>
                    <dd id="modal-user-no-matriks">-</dd>
                </div>
                <div class="grid grid-cols-[120px_1fr] gap-2">
                    <dt class="font-semibold text-slate-500">Registered</dt>
                    <dd id="modal-user-created">-</dd>
                </div>
            </dl>
            <div class="mt-5 flex justify-end">
                <button id="close-user-info-modal" type="button"
                    class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 transition">
                    Close
                </button>
            </div>
        </div>
    </div>
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

            const selectAll = document.getElementById('select-all-entries');
            const entryCheckboxes = Array.from(document.querySelectorAll('.entry-checkbox'));
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
            const bulkDeleteForm = document.getElementById('bulk-delete-form');

            const refreshBulkDeleteButton = () => {
                if (!bulkDeleteBtn) return;
                const checkedCount = entryCheckboxes.filter((checkbox) => checkbox.checked).length;
                bulkDeleteBtn.disabled = checkedCount === 0;
                bulkDeleteBtn.textContent = checkedCount > 0 ? `Delete selected (${checkedCount})` :
                    'Delete selected';
            };

            if (selectAll) {
                selectAll.addEventListener('change', () => {
                    entryCheckboxes.forEach((checkbox) => {
                        checkbox.checked = selectAll.checked;
                    });
                    refreshBulkDeleteButton();
                });
            }

            entryCheckboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', () => {
                    if (!selectAll) return;
                    const allChecked = entryCheckboxes.length > 0 && entryCheckboxes.every((item) =>
                        item.checked);
                    selectAll.checked = allChecked;
                    refreshBulkDeleteButton();
                });
            });

            if (bulkDeleteForm) {
                bulkDeleteForm.addEventListener('submit', (event) => {
                    const selected = entryCheckboxes.filter((checkbox) => checkbox.checked).length;
                    if (selected === 0) {
                        event.preventDefault();
                        return;
                    }

                    const confirmed = confirm(
                        `Delete ${selected} selected no_matriks entr${selected === 1 ? 'y' : 'ies'}?`);
                    if (!confirmed) {
                        event.preventDefault();
                    }
                });
            }

            const userInfoModal = document.getElementById('user-info-modal');
            const closeUserInfoModal = document.getElementById('close-user-info-modal');
            const usedByUserButtons = Array.from(document.querySelectorAll('.used-by-user-btn'));

            const modalUserId = document.getElementById('modal-user-id');
            const modalUserName = document.getElementById('modal-user-name');
            const modalUserEmail = document.getElementById('modal-user-email');
            const modalUserPhone = document.getElementById('modal-user-phone');
            const modalUserNoMatriks = document.getElementById('modal-user-no-matriks');
            const modalUserCreated = document.getElementById('modal-user-created');

            const hideUserInfoModal = () => {
                if (!userInfoModal) return;
                userInfoModal.classList.add('hidden');
                userInfoModal.classList.remove('flex');
                userInfoModal.setAttribute('aria-hidden', 'true');
            };

            const showUserInfoModal = () => {
                if (!userInfoModal) return;
                userInfoModal.classList.remove('hidden');
                userInfoModal.classList.add('flex');
                userInfoModal.setAttribute('aria-hidden', 'false');
            };

            usedByUserButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    if (modalUserId) modalUserId.textContent = button.dataset.userId || '-';
                    if (modalUserName) modalUserName.textContent = button.dataset.userName || '-';
                    if (modalUserEmail) modalUserEmail.textContent = button.dataset.userEmail || '-';
                    if (modalUserPhone) modalUserPhone.textContent = button.dataset.userPhone || '-';
                    if (modalUserNoMatriks) modalUserNoMatriks.textContent = button.dataset
                        .userNoMatriks ||
                        '-';
                    if (modalUserCreated) modalUserCreated.textContent = button.dataset.userCreated ||
                        '-';
                    showUserInfoModal();
                });
            });

            if (closeUserInfoModal) {
                closeUserInfoModal.addEventListener('click', hideUserInfoModal);
            }
            if (userInfoModal) {
                userInfoModal.addEventListener('click', (event) => {
                    if (event.target === userInfoModal) hideUserInfoModal();
                });
            }
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') hideUserInfoModal();
            });

            refreshBulkDeleteButton();
        })();
    </script>
</body>

</html>
