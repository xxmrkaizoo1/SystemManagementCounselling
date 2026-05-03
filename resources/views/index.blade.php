<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Counselling Booking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-slide {
            opacity: 0;
            transform: translateY(10px) scale(0.985);
            transition: opacity 650ms ease, transform 650ms ease;
            pointer-events: none;
        }

        .hero-slide.is-active {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
            z-index: 2;
        }

        .hero-slide-image {
            transform: scale(1);
            transition: transform 4s ease;
        }

        .hero-slide.is-active .hero-slide-image {
            transform: scale(1.05);
        }

        .role-slide {
            opacity: 0;
            transform: translateY(8px);
            transition: opacity 500ms ease, transform 500ms ease;
            pointer-events: none;
        }

        .role-slide.is-active {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
            z-index: 2;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-slate-50 text-slate-700 overflow-x-hidden scroll-smooth">
    <div id="cursor-dot" class="fixed top-0 left-0 w-3 h-3 bg-sky-600 rounded-full pointer-events-none z-50"></div>
    <div id="cursor-ring" class="fixed top-0 left-0 w-8 h-8 border border-sky-500 rounded-full pointer-events-none z-40">
    </div>
    <!-- LOADER -->
    <div id="loader" class="fixed inset-0 bg-sky-500 flex items-center justify-center z-50">
        <div id="circle" class="w-64 h-64 bg-white rounded-full flex items-center justify-center">
            <span id="logoText" class="text-sky-500 font-bold text-2xl">CollegeCare</span>
        </div>
    </div>

    <!-- modern layered background -->
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
        <div class="absolute inset-0 bg-noise-layer opacity-15"></div>

        <div
            class="absolute -top-32 -left-24 w-[34rem] h-[34rem] bg-sky-300/40 rounded-full blur-3xl animate-blob-float">
        </div>
        <div
            class="absolute top-24 -right-32 w-[36rem] h-[36rem] bg-violet-300/35 rounded-full blur-3xl animate-aurora-drift animation-delay-2">
        </div>
        <div
            class="absolute -bottom-36 left-1/4 w-[32rem] h-[32rem] bg-emerald-300/30 rounded-full blur-3xl animate-blob-float animation-delay-4">
        </div>

        <div class="absolute top-[18%] right-[10%] w-64 h-64 border border-sky-200/60 rounded-full animate-rotate-slow">
        </div>
        <div
            class="absolute bottom-[14%] left-[8%] w-40 h-40 border border-indigo-200/50 rounded-full animate-rotate-slow animation-delay-4">
        </div>

        <div class="aurora-band aurora-band--one"></div>
        <div class="aurora-band aurora-band--two"></div>
    </div>

    <!-- decorative moving characters -->
    <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden hidden md:block" aria-hidden="true">
        <div class="dummy-walker dummy-walker--one">
            <span class="dummy-head"></span>
            <span class="dummy-body"></span>
        </div>
        <div class="dummy-walker dummy-walker--two">
            <span class="dummy-head"></span>
            <span class="dummy-body"></span>
        </div>
        <div class="dummy-walker dummy-walker--three">
            <span class="dummy-head"></span>
            <span class="dummy-body"></span>
        </div>
    </div>

    <div id="content" class="opacity-0 translate-y-2 min-h-screen flex flex-col">
        <!-- HEADER -->
        <header class="site-header shrink-0 sticky top-0 z-40 bg-white/70 backdrop-blur-xl border-b border-slate-200">
            <div class="nav-shell max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/collegecare-logo.png') }}" alt="CollegeCare logo"
                        class="nav-brand-logo w-10 h-10 rounded-2xl object-cover shadow-sm" />
                    <div>
                        <p class="font-bold leading-tight">CollegeCare</p>
                        <p class="text-xs text-slate-500 -mt-0.5">Counselling Booking System</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="#roles" id="getStartedLink"
                        class="nav-cta-link hidden sm:inline text-sm font-medium text-slate-600 hover:text-sky-600 transition">
                        Get Started
                    </a>
                    <a href="{{ route('login') }}"
                        class="nav-login-btn px-4 py-2 rounded-xl bg-sky-600 text-white text-sm font-semibold shadow-sm hover:bg-sky-700 transition">
                        Login
                    </a>
                </div>
            </div>
        </header>

        <!-- MAIN -->
        <main class="flex-1 flex items-center justify-center py-14">
            <div class="w-full max-w-7xl mx-auto px-6">
                <section class="grid lg:grid-cols-2 gap-10 items-center">
                    <!-- LEFT -->
                    <div class="text-center lg:text-left animate-fade-in-up">
                        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-800">
                            Book counselling sessions easily,<br class="hidden sm:block" />
                            calmly, and securely.
                        </h1>
                        <p class="mt-4 text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                            Students and teachers can book sessions, counsellors manage schedules, and admins track
                            reports
                            — all in one place.
                        </p>

                        @php
                            $todayStatus = strtoupper((string) ($liveCalendarStatus ?? 'OPEN'));
                            $supportStatus = strtoupper((string) ($liveSupportStatus ?? 'LIVE'));
                            $slotsValue = (int) ($liveOpenSlots ?? 0);

                            $todayStatusClass = match ($todayStatus) {
                                'OPEN' => 'border-emerald-200 bg-emerald-50/80',
                                'FULL' => 'border-amber-200 bg-amber-50/80',
                                'CLOSED' => 'border-rose-200 bg-rose-50/80',
                                default => 'border-slate-200 bg-white/70',
                            };

                            $supportStatusClass = match ($supportStatus) {
                                'LIVE' => 'border-emerald-200 bg-emerald-50/80',
                                'BUSY' => 'border-amber-200 bg-amber-50/80',
                                'OFFLINE' => 'border-rose-200 bg-rose-50/80',
                                default => 'border-slate-200 bg-white/70',
                            };

                            $slotsStatusClass =
                                $slotsValue > 0
                                    ? 'border-emerald-200 bg-emerald-50/80'
                                    : 'border-rose-200 bg-rose-50/80';
                        @endphp

                        <div class="mt-8 grid grid-cols-3 gap-3 max-w-md mx-auto lg:mx-0">
                            <div class="rounded-2xl border p-4 shadow-sm {{ $todayStatusClass }}">
                                <p class="text-xs text-slate-500">Today</p>
                                <p class="text-lg font-bold text-slate-800">{{ $liveCalendarStatus ?? 'Open' }}</p>
                            </div>
                            <div class="rounded-2xl border p-4 shadow-sm {{ $slotsStatusClass }}">
                                <p class="text-xs text-slate-500">Slots</p>
                                <p class="text-lg font-bold text-slate-800">{{ $liveOpenSlots ?? 0 }}</p>
                            </div>
                            <div class="rounded-2xl border p-4 shadow-sm {{ $supportStatusClass }}">
                                <p class="text-xs text-slate-500">Support</p>
                                <p class="text-lg font-bold text-slate-800">{{ $liveSupportStatus ?? 'Live' }}</p>
                            </div>
                        </div>

                        <div class="doctor-tips-card mt-8 mx-auto lg:mx-0" id="doctorTipsCard">
                            <div class="doctor-scene" aria-hidden="true">
                                <div class="doctor-avatar">
                                    <span class="doc-shadow"></span>
                                    <span class="doc-head"></span>
                                    <span class="doc-hair"></span>
                                    <span class="doc-eye doc-eye--left"></span>
                                    <span class="doc-eye doc-eye--right"></span>
                                    <span class="doc-mouth"></span>
                                    <span class="doc-neck"></span>
                                    <span class="doc-body"></span>
                                    <span class="doc-coat-panel doc-coat-panel--left"></span>
                                    <span class="doc-coat-panel doc-coat-panel--right"></span>
                                    <span class="doc-stethoscope"></span>
                                    <span class="doc-arm doc-arm--left"></span>
                                    <span class="doc-arm doc-arm--right"></span>
                                    <span class="doc-leg doc-leg--left"></span>
                                    <span class="doc-leg doc-leg--right"></span>
                                    <span class="doc-clipboard"></span>
                                </div>
                            </div>

                            <div class="doctor-dialog">
                                <p class="text-xs font-semibold uppercase tracking-wide text-sky-700">Doctor Tip</p>
                                <p id="doctorTipText" class="mt-1 text-sm text-slate-700 leading-relaxed">
                                    Take one slow breath before opening your class portal to calm your body first.
                                </p>
                                <p class="mt-2 text-[11px] text-slate-500">Auto tips on loop</p>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div
                        class="mx-auto w-full max-w-xl rounded-3xl bg-white/70 border border-slate-200 shadow-sm p-6 sm:p-8 backdrop-blur-xl animate-fade-in-up animation-delay-2">
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-slate-800">Campus Wellness Highlights</p>
                            <span class="text-xs px-3 py-1 rounded-full bg-sky-50 text-sky-700 border border-sky-200">
                                Slideshow
                            </span>
                        </div>

                        <p class="mt-2 text-sm text-slate-600">Latest guidance, reminders, and support updates.</p>

                        <div
                            class="mt-6 relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div id="heroSlides" class="relative min-h-[25rem]">
                                <article class="hero-slide is-active absolute inset-0 p-6 sm:p-7">
                                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80"
                                        alt="University students discussing class tasks together"
                                        class="hero-slide-image h-40 w-full rounded-xl object-cover mb-5" />
                                    <p class="text-xs uppercase tracking-[0.18em] text-sky-600 font-semibold">Tip Of
                                        The
                                        Week</p>
                                    <h3 class="mt-2 text-xl font-bold text-slate-800">Start small to reduce study
                                        stress</h3>
                                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                                        Break tasks into 20-minute blocks and rest briefly between sessions to protect
                                        your focus.
                                    </p>
                                </article>

                                <article class="hero-slide absolute inset-0 p-6 sm:p-7">
                                    <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=1200&q=80"
                                        alt="Counsellor speaking with a student in a support session"
                                        class="hero-slide-image h-40 w-full rounded-xl object-cover mb-5" />
                                    <p class="text-xs uppercase tracking-[0.18em] text-indigo-600 font-semibold">
                                        Service Update</p>
                                    <h3 class="mt-2 text-xl font-bold text-slate-800">Extended counselling support
                                        hours
                                    </h3>
                                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                                        New evening slots are available for students with packed class schedules.
                                    </p>
                                </article>

                                <article class="hero-slide absolute inset-0 p-6 sm:p-7">
                                    <img src="https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&w=1200&q=80"
                                        alt="Peaceful bedtime setup with water and lamp for healthy sleep habits"
                                        class="hero-slide-image h-40 w-full rounded-xl object-cover mb-5" />
                                    <p class="text-xs uppercase tracking-[0.18em] text-emerald-600 font-semibold">
                                        Wellbeing Reminder</p>
                                    <h3 class="mt-2 text-xl font-bold text-slate-800">Hydration and sleep boost
                                        resilience
                                    </h3>
                                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                                        Aim for enough water and a regular bedtime to improve mood and concentration.
                                    </p>
                                </article>
                            </div>

                            <div class="px-6 pb-5 flex items-center justify-between">
                                <div id="heroSlideDots" class="flex items-center gap-2">
                                    <button type="button" class="hero-slide-dot w-2.5 h-2.5 rounded-full bg-sky-500"
                                        aria-label="Show slide 1"></button>
                                    <button type="button"
                                        class="hero-slide-dot w-2.5 h-2.5 rounded-full bg-slate-300"
                                        aria-label="Show slide 2"></button>
                                    <button type="button"
                                        class="hero-slide-dot w-2.5 h-2.5 rounded-full bg-slate-300"
                                        aria-label="Show slide 3"></button>
                                </div>
                                <span class="text-xs text-slate-500">Auto-rotates every 4s</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mt-20 animate-fade-in-up animation-delay-4">
                    <div class="grid lg:grid-cols-2 gap-8">
                        <div
                            class="bg-white/70 backdrop-blur-xl border border-slate-200 rounded-3xl p-8 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                            <h2 class="text-xl font-semibold text-slate-800 mb-4">Announcements</h2>
                            <div class="space-y-4 text-sm text-slate-600">
                                <p>📌 Counselling sessions available Monday – Friday.</p>
                                <p>📌 Emergency booking priority available.</p>
                                <p>📌 Please cancel 24 hours before session.</p>
                            </div>
                        </div>

                        <div
                            class="bg-white/70 backdrop-blur-xl border border-slate-200 rounded-3xl p-8 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                            <h2 class="text-xl font-semibold text-slate-800 mb-4">Available Counsellors</h2>

                            <div class="space-y-4 text-sm">
                                @forelse (($landingCounsellors ?? []) as $counsellor)
                                    @php
                                        $isAvailable = ($counsellor['status'] ?? 'Available') === 'Available';
                                    @endphp
                                    <div
                                        class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 text-sm font-semibold text-sky-700">
                                                {{ strtoupper(substr(trim((string) ($counsellor['name'] ?? '-')), 0, 1)) }}
                                            </div>
                                            <span
                                                class="text-slate-700 font-medium">{{ $counsellor['name'] ?? '-' }}</span>
                                        </div>s
                                        <span
                                            class="text-xs px-3 py-1 rounded-full {{ $isAvailable ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $isAvailable ? 'Available' : 'Busy' }}
                                        </span>
                                    </div>
                                @empty
                                    <div
                                        class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-sm font-semibold text-slate-600">
                                                ?
                                            </div>
                                            <span class="text-slate-500">No counsellors available yet</span>
                                        </div>
                                        <span
                                            class="text-xs px-3 py-1 rounded-full bg-slate-200 text-slate-600">Offline</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>

                <section id="roles" class="mt-8 animate-fade-in-up animation-delay-4">
                    <div
                        class="mx-auto w-full max-w-4xl rounded-3xl bg-white/70 border border-slate-200 shadow-sm p-6 sm:p-8 backdrop-blur-xl">
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-slate-800">Quick Access</p>
                            <span
                                class="text-xs px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Safe &amp; Private
                            </span>
                        </div>

                        <p class="mt-2 text-sm text-slate-600">User types and what each role can do in the system.</p>

                        <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                            <div id="roleSlides" class="relative min-h-[11.5rem] sm:min-h-[10rem]">
                                <article class="role-slide is-active absolute inset-0 p-5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold">
                                                S</div>
                                            <div>
                                                <p class="font-semibold text-slate-800">Student</p>
                                                <p class="text-sm text-slate-500">Book sessions, follow progress, and
                                                    get
                                                    wellbeing support.</p>
                                            </div>
                                        </div>
                                        <span
                                            class="text-xs px-2.5 py-1 rounded-full bg-sky-50 border border-sky-200 text-sky-700">User
                                            Type</span>
                                    </div>
                                </article>

                                <article class="role-slide absolute inset-0 p-5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                                                C</div>
                                            <div>
                                                <p class="font-semibold text-slate-800">Counsellor</p>
                                                <p class="text-sm text-slate-500">Manage appointments, notes, and
                                                    student
                                                    support plans.</p>
                                            </div>
                                        </div>
                                        <span
                                            class="text-xs px-2.5 py-1 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700">User
                                            Type</span>
                                    </div>
                                </article>

                                <article class="role-slide absolute inset-0 p-5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                                                A</div>
                                            <div>
                                                <p class="font-semibold text-slate-800">Admin</p>
                                                <p class="text-sm text-slate-500">Control users, reports, and platform
                                                    settings.</p>
                                            </div>
                                        </div>
                                        <span
                                            class="text-xs px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700">User
                                            Type</span>
                                    </div>
                                </article>
                            </div>

                            <div
                                class="border-t border-slate-100 px-5 py-3 flex items-center justify-between bg-slate-50/70">
                                <div id="roleSlideDots" class="flex items-center gap-2">
                                    <button type="button" class="role-slide-dot w-2.5 h-2.5 rounded-full bg-sky-500"
                                        aria-label="Show role slide 1"></button>
                                    <button type="button"
                                        class="role-slide-dot w-2.5 h-2.5 rounded-full bg-slate-300"
                                        aria-label="Show role slide 2"></button>
                                    <button type="button"
                                        class="role-slide-dot w-2.5 h-2.5 rounded-full bg-slate-300"
                                        aria-label="Show role slide 3"></button>
                                </div>
                                <span class="text-xs text-slate-500">Role overview</span>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <a href="{{ route('signup') }}"
                                class="flex-1 text-center px-4 py-2 rounded-xl bg-sky-600 text-white font-semibold shadow-sm hover:bg-sky-700 transition">
                                Sign Up
                            </a>
                            <a href="#"
                                class="flex-1 text-center px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition">
                                Help
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <footer class="shrink-0 text-center py-5 text-sm text-slate-500">
            © {{ date('Y') }} CollegeCare • Counselling Booking System
        </footer>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const getStartedLink = document.getElementById('getStartedLink');
            const quickAccessSection = document.getElementById('roles');

            if (getStartedLink && quickAccessSection) {
                getStartedLink.addEventListener('click', (event) => {
                    event.preventDefault();
                    quickAccessSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });

                    quickAccessSection.classList.add('ring-2', 'ring-sky-300', 'ring-offset-4',
                        'ring-offset-white');
                    window.setTimeout(() => {
                        quickAccessSection.classList.remove('ring-2', 'ring-sky-300',
                            'ring-offset-4', 'ring-offset-white');
                    }, 1200);
                });
            }
            const heroSlides = Array.from(document.querySelectorAll('.hero-slide'));
            const heroDots = Array.from(document.querySelectorAll('.hero-slide-dot'));
            if (heroSlides.length && heroSlides.length === heroDots.length) {
                let currentHeroSlide = 0;
                const showHeroSlide = (targetIndex) => {
                    currentHeroSlide = (targetIndex + heroSlides.length) % heroSlides.length;
                    heroSlides.forEach((slide, index) => {
                        slide.classList.toggle('is-active', index === currentHeroSlide);
                    });
                    heroDots.forEach((dot, index) => {
                        dot.classList.toggle('bg-sky-500', index === currentHeroSlide);
                        dot.classList.toggle('bg-slate-300', index !== currentHeroSlide);
                    });
                };

                heroDots.forEach((dot, index) => {
                    dot.addEventListener('click', () => showHeroSlide(index));
                });

                setInterval(() => {
                    showHeroSlide(currentHeroSlide + 1);
                }, 4000);
            }

            const roleSlides = Array.from(document.querySelectorAll('.role-slide'));
            const roleDots = Array.from(document.querySelectorAll('.role-slide-dot'));
            if (roleSlides.length && roleSlides.length === roleDots.length) {
                let currentRoleSlide = 0;
                const showRoleSlide = (targetIndex) => {
                    currentRoleSlide = (targetIndex + roleSlides.length) % roleSlides.length;
                    roleSlides.forEach((slide, index) => {
                        slide.classList.toggle('is-active', index === currentRoleSlide);
                    });
                    roleDots.forEach((dot, index) => {
                        dot.classList.toggle('bg-sky-500', index === currentRoleSlide);
                        dot.classList.toggle('bg-slate-300', index !== currentRoleSlide);
                    });
                };

                roleDots.forEach((dot, index) => {
                    dot.addEventListener('click', () => showRoleSlide(index));
                });

                setInterval(() => {
                    showRoleSlide(currentRoleSlide + 1);
                }, 5000);
            }
        });
    </script>
</body>

</html>
