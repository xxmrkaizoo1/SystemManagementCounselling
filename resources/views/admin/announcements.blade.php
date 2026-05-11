<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-fuchsia-100 via-sky-100 to-emerald-100 text-slate-900">
    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-3xl border border-white/60 bg-white/85 p-6 shadow-xl shadow-slate-900/5 backdrop-blur sm:p-8">
            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-200 pb-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-600">Admin panel</p>
                    <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">Edit Announcements ✨</h1>
                    <p class="mt-2 text-sm text-slate-600">Update announcement messages shown on the student home page.</p>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-sky-200 hover:text-sky-700"
                    aria-label="Back to admin dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14L4 9l5-5M4 9h10a7 7 0 110 14h-3" />
                    </svg>
                    <span>Back</span>
                </a>
            </div>

            @if (session('status'))
                <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
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

            <form method="POST" action="{{ route('admin.announcements.update') }}" class="mt-6 space-y-6" id="announcement-form">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2">
                    @for ($i = 0; $i < 10; $i++)
                        <div class="announcement-card rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-indigo-50/70 p-4 shadow-sm ring-1 ring-indigo-100 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-fuchsia-200"
                            style="animation-delay: {{ $i * 70 }}ms;">
                            <div class="mb-2 flex items-center justify-between">
                                <label for="announcement_{{ $i }}" class="text-sm font-semibold text-slate-800">Announcement {{ $i + 1 }}</label>
                                <span class="announcement-counter text-xs text-slate-500" data-for="announcement_{{ $i }}">0 / 400</span>
                            </div>

                            <textarea id="announcement_{{ $i }}" name="announcements[]" rows="4" maxlength="400"
                                class="announcement-input w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm focus:border-fuchsia-500 focus:outline-none focus:ring-2 focus:ring-fuchsia-200"
                                placeholder="Type announcement message...">{{ old('announcements.' . $i, $announcements[$i]->message ?? '') }}</textarea>

                            <div class="mt-3">
                                <label for="announcement_image_{{ $i }}" class="mb-1 block text-xs font-medium text-slate-500">Picture URL (optional)</label>
                                <input id="announcement_image_{{ $i }}" name="announcement_images[]" type="url"
                                    value="{{ old('announcement_images.' . $i, $announcements[$i]->image_url ?? '') }}"
                                    placeholder="https://example.com/image.jpg"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs focus:border-fuchsia-500 focus:outline-none focus:ring-2 focus:ring-fuchsia-200">
                            </div>

                            <div class="mt-3">
                                <p class="text-xs font-medium text-slate-500">Quick suggestions</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <button type="button" data-suggestion="Counselling sessions are available Monday to Friday."
                                        class="announcement-suggestion rounded-full border border-violet-200 bg-white px-3 py-1 text-xs font-medium text-violet-700 transition hover:-translate-y-0.5 hover:border-fuchsia-300 hover:bg-fuchsia-50 hover:text-fuchsia-700">Availability</button>
                                    <button type="button" data-suggestion="Emergency booking requests will be prioritised by the counselling team."
                                        class="announcement-suggestion rounded-full border border-violet-200 bg-white px-3 py-1 text-xs font-medium text-violet-700 transition hover:-translate-y-0.5 hover:border-fuchsia-300 hover:bg-fuchsia-50 hover:text-fuchsia-700">Emergency</button>
                                    <button type="button" data-suggestion="Please cancel or reschedule at least 24 hours before your session."
                                        class="announcement-suggestion rounded-full border border-violet-200 bg-white px-3 py-1 text-xs font-medium text-violet-700 transition hover:-translate-y-0.5 hover:border-fuchsia-300 hover:bg-fuchsia-50 hover:text-fuchsia-700">Cancellation</button>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="rounded-xl border border-orange-200 bg-gradient-to-r from-amber-50 to-orange-50 px-4 py-3 text-xs text-amber-800">
                    Leave a box empty to remove that announcement. You can save up to 10 announcements in total.
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-1">
                    <button type="submit" id="save-button"
                        class="inline-flex items-center rounded-xl bg-gradient-to-r from-fuchsia-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:from-fuchsia-700 hover:to-indigo-700">
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

    <style>
        .announcement-card {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeSlideIn 480ms cubic-bezier(.22,1,.36,1) forwards;
        }

        @keyframes fadeSlideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .announcement-card {
                animation: none;
                opacity: 1;
                transform: none;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('announcement-form');
            const submitButton = document.getElementById('save-button');

            const syncCounter = (input) => {
                const counter = document.querySelector(`[data-for="${input.id}"]`);
                if (!counter) return;
                counter.textContent = `${input.value.length} / 400`;
            };

            document.querySelectorAll('.announcement-input').forEach((input) => {
                syncCounter(input);
                input.addEventListener('input', () => syncCounter(input));
            });

            document.querySelectorAll('.announcement-suggestion').forEach((button) => {
                button.addEventListener('click', () => {
                    const card = button.closest('.announcement-card');
                    const input = card ? card.querySelector('.announcement-input') : null;
                    if (!input) return;

                    input.value = button.getAttribute('data-suggestion') || '';
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    input.focus();
                });
            });

            if (form && submitButton) {
                form.addEventListener('submit', () => {
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-80', 'cursor-not-allowed');
                    submitButton.innerHTML = '<span class="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>Saving...';
                });
            }
        });
    </script>
</body>

</html>
