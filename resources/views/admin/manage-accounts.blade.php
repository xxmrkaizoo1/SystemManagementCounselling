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

    <main class="min-h-screen p-4 sm:p-8" data-current-user-id="{{ $user->id }}">
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
                @if (session('status'))
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <div>
                    <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <h2 class="text-lg font-semibold text-slate-800">Ringkasan Akaun</h2>
                        <p class="text-sm text-slate-500 mt-1">20 akaun terbaru mengikut peranan pengguna.</p>
                        <div class="mt-4 grid gap-3 sm:grid-cols-[1fr_200px]">
                            <div>
                                <label for="user_search" class="block text-sm font-medium text-slate-700 mb-1.5">
                                    Carian nama / emel
                                </label>
                                <input id="user_search" type="text"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition"
                                    placeholder="Contoh: harith atau gmail.com" />
                            </div>
                            <div>
                                <label for="role_filter" class="block text-sm font-medium text-slate-700 mb-1.5">
                                    Tapis peranan
                                </label>
                                <select id="role_filter"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition">
                                    <option value="">Semua peranan</option>
                                    <option value="student">Student</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="counsellor">Counsellor</option>
                                    <option value="admin">Admin</option>
                                    <option value="unassigned">Unassigned</option>
                                </select>
                            </div>
                        </div>

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
                                <tbody id="managed_users_table">
                                    @forelse ($managedUsers as $managedUser)
                                        <tr class="border-b border-slate-100 managed-user-row cursor-pointer hover:bg-sky-50/60 transition"
                                            data-id="{{ $managedUser->id }}"
                                            data-name="{{ strtolower($managedUser->name) }}"
                                            data-display-name="{{ $managedUser->name }}"
                                            data-email="{{ strtolower($managedUser->email) }}"
                                            data-display-email="{{ $managedUser->email }}"
                                            data-role="{{ strtolower($managedUser->role_name) }}">
                                            <td class="py-2 pr-2 font-medium text-slate-700">{{ $managedUser->name }}
                                            </td>
                                            <td class="py-2 pr-2 text-sky-700 capitalize">{{ $managedUser->role_name }}
                                            </td>
                                            <td class="py-2 pr-2 text-slate-600">{{ $managedUser->email }}</td>
                                            <td class="py-2 text-slate-500">{{ $managedUser->phone ?: '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr id="no-data-row">
                                            <td class="py-2 text-slate-500" colspan="4">Tiada data akaun ditemui.
                                            </td>
                                        </tr>
                                    @endforelse
                                    <tr id="empty-filter-row" class="hidden">
                                        <td class="py-2 text-slate-500" colspan="4">Tiada akaun padan dengan carian
                                            atau penapis semasa.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <div id="edit_user_modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true"
        aria-labelledby="edit_user_modal_title">
        <div class="absolute inset-0 bg-slate-900/45" data-modal-close></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-xl rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 id="edit_user_modal_title" class="text-lg font-semibold text-slate-800">Edit Akaun
                            Pengguna</h3>
                        <p class="text-sm text-slate-500 mt-1">Kemaskini maklumat pengguna dipilih.</p>
                    </div>
                    <button type="button" id="close_edit_modal"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:text-slate-700 hover:border-slate-300 transition"
                        aria-label="Tutup">✕</button>
                </div>

                <form id="delete_user_form" method="POST" class="hidden"
                    data-action-template="{{ route('admin.accounts.delete', ['managedUser' => '__USER__']) }}">
                    @csrf
                    @method('DELETE')
                </form>

                <form id="edit_user_form" method="POST" class="mt-5 space-y-3"
                    data-action-template="{{ route('admin.accounts.update', ['managedUser' => '__USER__']) }}">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="edit_user_name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama</label>
                        <input id="edit_user_name" name="name" type="text" required
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                    </div>
                    <div>
                        <label for="edit_user_email"
                            class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <input id="edit_user_email" name="email" type="email" required
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                    </div>
                    <div>
                        <label for="edit_user_phone"
                            class="block text-sm font-medium text-slate-700 mb-1.5">Telefon</label>
                        <input id="edit_user_phone" name="phone" type="text"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition"
                            placeholder="Contoh: 0112345678" />
                    </div>
                    <div>
                        <label for="edit_user_role"
                            class="block text-sm font-medium text-slate-700 mb-1.5">Peranan</label>
                        <select id="edit_user_role" name="role" required
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition">
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                            <option value="counsellor">Counsellor</option>
                            <option value="admin">Admin</option>
                            <option value="unassigned">Unassigned</option>
                        </select>
                    </div>

                    <div class="pt-2 flex items-center justify-between gap-2">
                        <button type="button" id="delete_user_button"
                            class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-100 transition">
                            Padam Pengguna
                        </button>

                        <div class="flex items-center gap-2">
                        <button type="button" data-modal-close
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 hover:border-slate-300 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-700 transition">
                            Simpan Perubahan
                        </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="delete_confirm_modal" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true"
        aria-labelledby="delete_confirm_title">
        <div class="absolute inset-0 bg-slate-900/50" data-delete-confirm-close></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl">
                <h3 id="delete_confirm_title" class="text-lg font-semibold text-slate-800">Sahkan Padam Pengguna</h3>
                <p id="delete_confirm_message" class="mt-2 text-sm text-slate-600">
                    Adakah anda pasti mahu padam akaun ini?
                </p>
                <div class="mt-5 flex items-center justify-end gap-2">
                    <button type="button" id="delete_confirm_cancel"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 hover:border-slate-300 transition">
                        Batal
                    </button>
                    <button type="button" id="delete_confirm_submit"
                        class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-700 transition">
                        Ya, Padam
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('user_search');
            const roleFilter = document.getElementById('role_filter');
            const userRows = Array.from(document.querySelectorAll('.managed-user-row'));
            const emptyFilterRow = document.getElementById('empty-filter-row');
            const noDataRow = document.getElementById('no-data-row');
            const modal = document.getElementById('edit_user_modal');
            const closeModalButton = document.getElementById('close_edit_modal');
            const modalCloseTriggers = document.querySelectorAll('[data-modal-close]');
            const editForm = document.getElementById('edit_user_form');
            const editNameInput = document.getElementById('edit_user_name');
            const editEmailInput = document.getElementById('edit_user_email');
            const editPhoneInput = document.getElementById('edit_user_phone');
            const editRoleInput = document.getElementById('edit_user_role');
            const deleteForm = document.getElementById('delete_user_form');
            const deleteUserButton = document.getElementById('delete_user_button');
            const currentUserId = document.querySelector('main')?.dataset.currentUserId || '';
            const deleteConfirmModal = document.getElementById('delete_confirm_modal');
            const deleteConfirmMessage = document.getElementById('delete_confirm_message');
            const deleteConfirmSubmit = document.getElementById('delete_confirm_submit');
            const deleteConfirmCancel = document.getElementById('delete_confirm_cancel');
            const deleteConfirmCloseTriggers = document.querySelectorAll('[data-delete-confirm-close]');

            const filterRows = () => {
                if (!userRows.length) {
                    return;
                }

                const keyword = (searchInput?.value || '').trim().toLowerCase();
                const role = (roleFilter?.value || '').trim().toLowerCase();
                let visibleRows = 0;

                userRows.forEach((row) => {
                    const name = row.dataset.name || '';
                    const email = row.dataset.email || '';
                    const userRole = row.dataset.role || '';
                    const keywordMatch = !keyword || name.includes(keyword) || email.includes(keyword);
                    const roleMatch = !role || userRole === role;
                    const shouldShow = keywordMatch && roleMatch;

                    row.classList.toggle('hidden', !shouldShow);
                    if (shouldShow) {
                        visibleRows += 1;
                    }
                });

                emptyFilterRow?.classList.toggle('hidden', visibleRows > 0);
                noDataRow?.classList.add('hidden');
            };

            searchInput?.addEventListener('input', filterRows);
            roleFilter?.addEventListener('change', filterRows);

            const closeModal = () => {
                modal?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            const closeDeleteConfirm = () => {
                deleteConfirmModal?.classList.add('hidden');
                if (modal && !modal.classList.contains('hidden')) {
                    document.body.classList.add('overflow-hidden');
                    return;
                }

                document.body.classList.remove('overflow-hidden');
            };

            const openModalWithUser = (row) => {
                if (!modal || !editForm) {
                    return;
                }

                const userId = row.dataset.id || '';
                const actionTemplate = editForm.dataset.actionTemplate || '';
                const deleteActionTemplate = deleteForm?.dataset.actionTemplate || '';

                editForm.action = actionTemplate.replace('__USER__', userId);
                if (deleteForm) {
                    deleteForm.action = deleteActionTemplate.replace('__USER__', userId);
                }
                if (editNameInput) editNameInput.value = row.dataset.displayName || '';
                if (editEmailInput) editEmailInput.value = row.dataset.displayEmail || '';
                if (editPhoneInput) editPhoneInput.value = row.children[3]?.textContent?.trim() === '—'
                    ? ''
                    : (row.children[3]?.textContent?.trim() || '');
                if (editRoleInput) editRoleInput.value = row.dataset.role || 'unassigned';
                if (deleteUserButton) {
                    deleteUserButton.dataset.userName = row.dataset.displayName || 'pengguna ini';
                    const isOwnAccount = userId && currentUserId && userId === currentUserId;
                    deleteUserButton.disabled = Boolean(isOwnAccount);
                    deleteUserButton.classList.toggle('opacity-60', Boolean(isOwnAccount));
                    deleteUserButton.classList.toggle('cursor-not-allowed', Boolean(isOwnAccount));
                    deleteUserButton.textContent = isOwnAccount ? 'Tidak boleh padam akaun sendiri' : 'Padam Pengguna';
                }

                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            userRows.forEach((row) => {
                row.addEventListener('click', () => openModalWithUser(row));
            });

            closeModalButton?.addEventListener('click', closeModal);
            modalCloseTriggers.forEach((trigger) => {
                trigger.addEventListener('click', closeModal);
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    if (deleteConfirmModal && !deleteConfirmModal.classList.contains('hidden')) {
                        closeDeleteConfirm();
                        return;
                    }

                    closeModal();
                }
            });

            deleteUserButton?.addEventListener('click', () => {
                if (!deleteForm) {
                    return;
                }

                const targetName = deleteUserButton.dataset.userName || 'pengguna ini';
                if (deleteConfirmMessage) {
                    deleteConfirmMessage.textContent =
                        `Padam akaun "${targetName}"? Tindakan ini tidak boleh dibatalkan.`;
                }
                deleteConfirmModal?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });

            deleteConfirmSubmit?.addEventListener('click', () => {
                if (!deleteForm) {
                    return;
                }

                deleteForm.submit();
            });

            deleteConfirmCancel?.addEventListener('click', closeDeleteConfirm);
            deleteConfirmCloseTriggers.forEach((trigger) => {
                trigger.addEventListener('click', closeDeleteConfirm);
            });
        });
    </script>
</body>

</html>
