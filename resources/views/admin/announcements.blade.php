<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-100 via-sky-50 to-indigo-100 text-slate-900">
    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <section
            class="rounded-3xl border border-white/60 bg-white/90 p-6 shadow-xl shadow-slate-900/5 backdrop-blur sm:p-8">
            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-200 pb-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600">Admin panel</p>
                    <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">Edit Announcements</h1>
                    <p class="mt-2 text-sm text-slate-600">Update the announcement messages shown on the student home
                        page.</p>
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
            </div>

            @if (session('status'))
                <div
                    class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <p class="font-semibold">Please fix the following:</p>
                    <ul class="mt-1 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.announcements.update') }}" class="mt-6 space-y-5">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2">
                    @for ($i = 0; $i < 5; $i++)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                            <div class="mb-2 flex items-center justify-between">
                                <label for="announcement_{{ $i }}"
                                    class="text-sm font-semibold text-slate-800">Announcement
                                    {{ $i + 1 }}</label>
                                <span class="text-xs text-slate-500">Max 400 chars</span>
                            </div>
                            <textarea id="announcement_{{ $i }}" name="announcements[]" rows="4" maxlength="400"
                                class="announcement-input w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                placeholder="Type announcement message...">{{ old('announcements.' . $i, $announcements[$i]->message ?? '') }}</textarea>

                            <div class="mt-3">
                                <label for="announcement_image_{{ $i }}"
                                    class="mb-1 block text-xs font-medium text-slate-500">Picture URL (optional)</label>
                                <input id="announcement_image_{{ $i }}" name="announcement_images[]"
                                    type="url"
                                    value="{{ old('announcement_images.' . $i, $announcements[$i]->image_url ?? '') }}"
                                    placeholder="https://example.com/image.jpg"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            </div>

                            <div class="mt-3">
                                <p class="text-xs font-medium text-slate-500">Quick suggestions</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <button type="button"
                                        data-suggestion="Counselling sessions are available Monday to Friday."
                                        class="announcement-suggestion rounded-full border border-slate-300 bg-white px-3 py-1 text-xs font-medium text-slate-600 hover:border-indigo-300 hover:text-indigo-700">Availability</button>
                                    <button type="button"
                                        data-suggestion="Emergency booking requests will be prioritised by the counselling team."
                                        class="announcement-suggestion rounded-full border border-slate-300 bg-white px-3 py-1 text-xs font-medium text-slate-600 hover:border-indigo-300 hover:text-indigo-700">Emergency</button>
                                    <button type="button"
                                        data-suggestion="Please cancel or reschedule at least 24 hours before your session."
                                        class="announcement-suggestion rounded-full border border-slate-300 bg-white px-3 py-1 text-xs font-medium text-slate-600 hover:border-indigo-300 hover:text-indigo-700">Cancellation</button>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">
                    Leave a box empty to remove that announcement. You can save up to 10 announcements in total.
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-1">
                    <button type="submit"
                        class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-indigo-700">
                        Save Announcements
                    </button>
                    <a href="{{ url('/admin') }}"
                        class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Cancel &amp; Back
                    </a>
                </div>
            </form>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.announcement-suggestion').forEach((button) => {
                button.addEventListener('click', () => {
                    const card = button.closest('.rounded-2xl');
                    const input = card ? card.querySelector('.announcement-input') : null;
                    const text = button.getAttribute('data-suggestion') || '';

                    if (!input) {
                        return;
                    }

                    input.value = text;
                    input.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                    input.focus();
                });
            });
        });
    </script>
</body>

</html>
