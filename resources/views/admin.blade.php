<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fade-up {
            0% {
                opacity: 0;
                transform: translateY(12px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes aurora-drift {
            0% {
                transform: translate3d(0, 0, 0) scale(1);
            }

            50% {
                transform: translate3d(24px, -18px, 0) scale(1.08);
            }

            100% {
                transform: translate3d(-12px, 16px, 0) scale(1);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                opacity: .35;
            }

            50% {
                opacity: .7;
            }
        }

        .animate-fade-up {
            animation: fade-up .5s ease-out both;
        }

        .animate-aurora-drift {
            animation: aurora-drift 14s ease-in-out infinite alternate;
        }

        .animate-pulse-glow {
            animation: pulse-glow 8s ease-in-out infinite;
        }

        .animation-delay-1 {
            animation-delay: .2s;
        }

        .animation-delay-2 {
            animation-delay: .4s;
        }

        .animation-delay-3 {
            animation-delay: .6s;
        }

        .admin-loader {
            transition: opacity .35s ease, visibility .35s ease;
        }

        .admin-loader.is-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 text-slate-700 overflow-x-hidden">


    <!-- LOADER -->
    <div id="loader" data-admin-loader="true" class="fixed inset-0 bg-purple-800 flex items-center justify-center z-50">
        <div id="circle"
            class="w-64 h-64 bg-white rounded-full flex flex-col items-center justify-center text-center px-4">
            <span id="logoText" class="text-purple-800 font-bold text-2xl">CollegeCare</span>
            <span class="mt-1 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Welcome to admin</span>

        </div>
    </div>

    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#dbeafe_0%,_#e0f2fe_28%,_#eef2ff_55%,_#f8fafc_100%)]">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        <div
            class="absolute -top-24 -left-20 w-[34rem] h-[34rem] rounded-full bg-cyan-300/40 blur-3xl animate-aurora-drift animate-pulse-glow">
        </div>
        <div
            class="absolute top-8 -right-24 w-[34rem] h-[34rem] rounded-full bg-indigo-300/35 blur-3xl animate-aurora-drift animate-pulse-glow animation-delay-1">
        </div>
        <div
            class="absolute -bottom-24 left-1/4 w-[30rem] h-[30rem] rounded-full bg-emerald-300/30 blur-3xl animate-aurora-drift animate-pulse-glow animation-delay-2">
        </div>
    </div>

    <main class="min-h-screen p-2 sm:p-5 lg:p-8">
        <section
            class="max-w-[96rem] mx-auto rounded-[1.6rem] sm:rounded-[2rem] border border-slate-200/80 bg-white/80 backdrop-blur-xl shadow-2xl overflow-hidden animate-fade-up">
            <header
                class="px-4 sm:px-6 lg:px-7 py-4 border-b border-slate-200/80 bg-white/85 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-xs uppercase tracking-[0.14em] text-indigo-500 font-semibold">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight">Admin Dashboard</h1>
                    <p class="text-sm text-indigo-500 mt-1 break-words">Welcome back, <a
                            class="text-sm text-indigo-500 mt-1 font-semibold">{{ $user->full_name ?: $user->name }}</a>
                    </p>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <button id="admin-sidebar-open" type="button"
                        class="inline-flex xl:hidden items-center justify-center rounded-xl border border-slate-200 bg-white p-2 text-slate-700 hover:bg-slate-50"
                        aria-controls="admin-sidebar" aria-expanded="false" aria-label="Open admin menu">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm1 4a1 1 0 100 2h12a1 1 0 100-2H4z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <form id="admin-logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:from-sky-700 hover:to-indigo-700 transition hover:-translate-y-0.5 shadow-sm">Logout</button>
                    </form>
                </div>
            </header>

            <div class="p-4 sm:p-5 lg:p-7 grid xl:grid-cols-[260px_1fr] gap-4 lg:gap-5">
                <div id="admin-sidebar-overlay" class="fixed inset-0 z-40 hidden bg-slate-900/45 xl:hidden"></div>
                <aside id="admin-sidebar"
                    class="fixed xl:static inset-y-0 left-0 z-50 w-[85vw] max-w-[320px] xl:w-auto xl:max-w-none -translate-x-full xl:translate-x-0 transition-transform duration-300 rounded-none xl:rounded-2xl border-0 xl:border border-slate-200 bg-white/95 p-4 shadow-2xl xl:shadow-sm flex flex-col animate-fade-up animation-delay-1">
                    <div class="mb-3 flex items-center justify-between xl:hidden">
                        <p class="text-xs uppercase tracking-[0.12em] text-slate-500 font-semibold">Admin menu</p>
                        <button id="admin-sidebar-close" type="button"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-2 text-slate-700 hover:bg-slate-50"
                            aria-label="Close admin menu">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-200">
                        <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}" alt="Profile"
                            class="w-11 h-11 rounded-full border border-slate-200 object-cover bg-sky-50" />
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                            <p class="text-xs uppercase tracking-wide text-emerald-700 font-semibold">Administrator</p>
                        </div>
                    </div>

                    <div class="flex-1">
                        <p class="hidden xl:block text-xs uppercase tracking-[0.2em] text-slate-500 mb-3 font-semibold">
                            Admin menu
                        </p> <a href="{{ route('admin.accounts.manage') }}"
                            class="group flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-gradient-to-r from-white to-slate-50 px-3.5 py-3 text-sm font-medium text-slate-700 shadow-sm hover:border-sky-200 hover:from-sky-50 hover:to-indigo-50 hover:text-sky-800 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                            <span
                                class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition group-hover:bg-sky-100 group-hover:text-sky-700">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6z" />
                                    <path fill-rule="evenodd" d="M3 16a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>Manage user accounts</span>
                            <svg class="ml-auto h-4 w-4 text-slate-300 transition group-hover:text-sky-500"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="{{ url('/admin/no-matriks-users') }}"
                            class="group mt-2.5 flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-gradient-to-r from-white to-slate-50 px-3.5 py-3 text-sm font-medium text-slate-700 shadow-sm hover:border-sky-200 hover:from-sky-50 hover:to-indigo-50 hover:text-sky-800 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                            <span
                                class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition group-hover:bg-sky-100 group-hover:text-sky-700">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7.414A2 2 0 0017.414 6L14 2.586A2 2 0 0012.586 2H4zm3 6a1 1 0 000 2h6a1 1 0 100-2H7zm0 4a1 1 0 000 2h6a1 1 0 100-2H7z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>View no_matriks list</span>
                            <svg class="ml-auto h-4 w-4 text-slate-300 transition group-hover:text-sky-500"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>


                        <a href="{{ route('admin.student-statistics') }}"
                            class="group mt-2.5 flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-gradient-to-r from-white to-slate-50 px-3.5 py-3 text-sm font-medium text-slate-700 shadow-sm hover:border-sky-200 hover:from-sky-50 hover:to-indigo-50 hover:text-sky-800 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                            <span
                                class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition group-hover:bg-sky-100 group-hover:text-sky-700">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path
                                        d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5H2v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9H8V7zM14 3a1 1 0 011-1h2a1 1 0 011 1v13h-4V3z" />
                                </svg>
                            </span>
                            <span>Student booking statistics</span>
                            <svg class="ml-auto h-4 w-4 text-slate-300 transition group-hover:text-sky-500"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.counsellor.signup') }}"
                            class="group mt-2.5 flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-gradient-to-r from-white to-slate-50 px-3.5 py-3 text-sm font-medium text-slate-700 shadow-sm hover:border-sky-200 hover:from-sky-50 hover:to-indigo-50 hover:text-sky-800 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                            <span
                                class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition group-hover:bg-sky-100 group-hover:text-sky-700">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 2a4 4 0 00-4 4v1H5a2 2 0 00-2 2v7a2 2 0 002 2h10a2 2 0 002-2V9a2 2 0 00-2-2h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>Sign up counsellor</span>
                            <svg class="ml-auto h-4 w-4 text-slate-300 transition group-hover:text-sky-500"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>


                </aside>

                <section class="space-y-4 lg:space-y-5">
                    @php
                        $bookingBase = max((int) ($stats['total_bookings'] ?? 0), 1);
                        $pendingRatio = (int) round(((int) ($stats['pending_bookings'] ?? 0) / $bookingBase) * 100);
                        $overviewValues = [
                            (int) ($stats['total_users'] ?? 0),
                            (int) ($stats['total_roles'] ?? 0),
                            (int) ($stats['total_messages'] ?? 0),
                            (int) ($stats['total_notifications'] ?? 0),
                            (int) ($stats['total_bookings'] ?? 0),
                            (int) ($stats['pending_bookings'] ?? 0),
                        ];
                        $maxOverviewValue = max(max($overviewValues), 1);
                    @endphp

                    <div id="overview"
                        class="mx-auto grid w-full max-w-6xl grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
                        <article data-stat-card
                            class="group rounded-2xl border border-slate-200/80 bg-gradient-to-br from-white to-slate-50 p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-lg animate-fade-up animation-delay-1">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Users
                                    </p>
                                    <p data-view="number" class="mt-2 text-2xl font-bold text-slate-900">
                                        {{ $stats['total_users'] }}</p>
                                    <div data-view="graph" class="mt-3 hidden">
                                        <div class="h-2.5 w-28 rounded-full bg-slate-100">
                                            <div class="h-2.5 rounded-full bg-sky-500"
                                                style="width: {{ (int) round(((int) ($stats['total_users'] ?? 0) / $maxOverviewValue) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    <span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-700">👥</span>
                                    <button type="button" data-toggle-view
                                        class="text-[11px] font-semibold text-sky-700">Graph</button>
                                </div>

                            </div>
                            <p class="mt-2 text-xs font-medium text-emerald-700">Active accounts</p>
                        </article>

                        <article data-stat-card
                            class="group rounded-2xl border border-slate-200/80 bg-gradient-to-br from-white to-slate-50 p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-lg animate-fade-up animation-delay-1">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Roles
                                    </p>
                                    <p data-view="number" class="mt-2 text-2xl font-bold text-slate-900">
                                        {{ $stats['total_roles'] }}</p>
                                    <div data-view="graph" class="mt-3 hidden">
                                        <div class="h-2.5 w-28 rounded-full bg-slate-100">
                                            <div class="h-2.5 rounded-full bg-indigo-500"
                                                style="width: {{ (int) round(((int) ($stats['total_roles'] ?? 0) / $maxOverviewValue) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2"><span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">🛡️</span><button
                                        type="button" data-toggle-view
                                        class="text-[11px] font-semibold text-indigo-700">Graph</button></div>
                            </div>
                            <p class="mt-2 text-xs font-medium text-indigo-700">Access levels</p>
                        </article>

                        <article data-stat-card
                            class="group rounded-2xl border border-slate-200/80 bg-gradient-to-br from-white to-slate-50 p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-lg animate-fade-up animation-delay-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">
                                        Bookings
                                    </p>
                                    <p data-view="number" class="mt-2 text-2xl font-bold text-slate-900">
                                        {{ $stats['total_bookings'] }}
                                    </p>
                                    <div data-view="graph" class="mt-3 hidden">
                                        <div class="h-2.5 w-28 rounded-full bg-slate-100">
                                            <div class="h-2.5 rounded-full bg-emerald-500"
                                                style="width: {{ (int) round(((int) ($stats['total_bookings'] ?? 0) / $maxOverviewValue) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2"><span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">📅</span><button
                                        type="button" data-toggle-view
                                        class="text-[11px] font-semibold text-emerald-700">Graph</button></div>
                            </div>
                            <p class="mt-2 text-xs font-medium text-emerald-700">Scheduled total</p>
                        </article>

                        <article data-stat-card
                            class="group rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 to-orange-50 p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg animate-fade-up animation-delay-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-amber-700">Pending
                                    </p>
                                    <p data-view="number" class="mt-2 text-2xl font-bold text-amber-700">
                                        {{ $stats['pending_bookings'] }} </p>
                                    <div data-view="graph" class="mt-3 hidden">
                                        <div class="h-2.5 w-28 rounded-full bg-amber-100">
                                            <div class="h-2.5 rounded-full bg-amber-500"
                                                style="width: {{ (int) round(((int) ($stats['pending_bookings'] ?? 0) / $maxOverviewValue) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2"><span
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-700">⏳</span><button
                                        type="button" data-toggle-view
                                        class="text-[11px] font-semibold text-amber-700">Graph</button></div>
                            </div>
                            <p class="mt-2 text-xs font-medium text-amber-700">{{ $pendingRatio }}% of bookings</p>
                        </article>
                    </div>


                    <section
                        class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm animate-fade-up animation-delay-2">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-lg font-semibold text-slate-900">Card information &amp; slides</h2>
                            <span
                                class="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">Highlights</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-600">Quick cards and horizontally scrollable slides for
                            important updates.</p>

                        <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                            <article class="rounded-xl border border-sky-100 bg-sky-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-sky-700">Top priority
                                </p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">Review pending bookings</p>
                                <p class="mt-1 text-xs text-slate-600">Keep wait times low by confirming requests
                                    daily.</p>
                            </article>
                            <article class="rounded-xl border border-emerald-100 bg-emerald-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-emerald-700">
                                    Engagement</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">Follow up with new users</p>
                                <p class="mt-1 text-xs text-slate-600">Send welcome guidance within 24 hours of signup.
                                </p>
                            </article>
                            <article class="rounded-xl border border-indigo-100 bg-indigo-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-indigo-700">Operations
                                </p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">Check counsellor availability</p>
                                <p class="mt-1 text-xs text-slate-600">Ensure schedules are up-to-date each week.</p>
                            </article>
                            <article class="rounded-xl border border-amber-100 bg-amber-50 p-3">
                                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-amber-700">Reminder
                                </p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">Update announcements</p>
                                <p class="mt-1 text-xs text-slate-600">Share policy changes with staff and counsellors.
                                </p>
                            </article>
                        </div>

                        <div class="mt-4">
                            <h3 class="text-sm font-semibold text-slate-800">Slides</h3>
                            <div data-slideshow class="mt-2 rounded-xl border border-slate-200 bg-slate-50/40 p-3">
                                <div class="relative min-h-[132px] overflow-hidden rounded-lg">
                                    <article data-slide-item
                                        class="absolute inset-0 rounded-xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 opacity-100 transition-opacity duration-500">
                                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">
                                            Slide 1
                                        </p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">Daily admin checklist</p>
                                        <p class="mt-1 text-xs text-slate-600">Verify logins, unread messages, and
                                            queue
                                            status.</p>
                                    </article>
                                    <article data-slide-item
                                        class="absolute inset-0 rounded-xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 opacity-0 pointer-events-none transition-opacity duration-500">
                                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">
                                            Slide 2
                                        </p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">Counsellor performance</p>
                                        <p class="mt-1 text-xs text-slate-600">Track session volume and response
                                            consistency.</p>
                                    </article>
                                    <article data-slide-item
                                        class="absolute inset-0 rounded-xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 opacity-0 pointer-events-none transition-opacity duration-500">
                                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">
                                            Slide 3
                                        </p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">System health snapshot</p>
                                        <p class="mt-1 text-xs text-slate-600">Confirm notification jobs and booking
                                            flows
                                            are stable.</p>
                                    </article>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <button type="button" data-slide-prev
                                            class="rounded-md border border-slate-300 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100">Prev</button>
                                        <button type="button" data-slide-next
                                            class="rounded-md border border-slate-300 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100">Next</button>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span data-slide-dot class="h-2 w-2 rounded-full bg-slate-900"></span>
                                        <span data-slide-dot class="h-2 w-2 rounded-full bg-slate-300"></span>
                                        <span data-slide-dot class="h-2 w-2 rounded-full bg-slate-300"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>


                    <div class="grid lg:grid-cols-2 gap-4">
                        <article id="roles"
                            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm animate-fade-up animation-delay-2">
                            <h2 class="text-lg font-semibold text-slate-900">Users by role</h2>
                            <p class="text-sm text-slate-600 mt-1">Snapshot based on current role assignments.</p>

                            @php
                                $maxRoleCount = max((int) collect($userCountsByRole)->max(), 1);
                            @endphp

                            <div class="mt-4 space-y-3">
                                @forelse ($userCountsByRole as $roleName => $total)
                                    @php
                                        $rolePercent = (int) round(((int) $total / $maxRoleCount) * 100);
                                    @endphp
                                    <div
                                        class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 transition hover:border-sky-200 hover:bg-sky-50/40">
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="capitalize font-medium text-slate-700">{{ $roleName }}</span>
                                            <span
                                                class="text-sm font-semibold text-indigo-700">{{ $total }}</span>
                                        </div>
                                        <div class="mt-2 h-2.5 w-full rounded-full bg-slate-200/70">
                                            <div class="h-2.5 rounded-full bg-gradient-to-r from-sky-500 to-indigo-500"
                                                style="width: {{ $rolePercent }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No role assignments found yet.</p>
                                @endforelse
                            </div>
                        </article>

                        <article id="users"
                            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm animate-fade-up animation-delay-3">
                            <h2 class="text-lg font-semibold text-slate-900">Recently registered users</h2>
                            <p class="text-sm text-slate-600 mt-1">Latest 8 users in the platform.</p>

                            <div class="mt-4 overflow-auto">
                                <table class="w-full min-w-[520px] text-sm">
                                    <thead>
                                        <tr class="text-left text-slate-500 border-b border-slate-200">
                                            <th class="py-2 pr-2">Name</th>
                                            <th class="py-2 pr-2">Email</th>
                                            <th class="py-2">Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentUsers as $recentUser)
                                            <tr class="border-b border-slate-100 transition hover:bg-slate-50">
                                                <td class="py-2 pr-2 font-medium text-slate-700">
                                                    {{ $recentUser->name }}</td>
                                                <td class="py-2 pr-2 text-slate-600">{{ $recentUser->email }}</td>
                                                <td class="py-2 text-slate-500">
                                                    {{ optional($recentUser->created_at)->diffForHumans() }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="py-2 text-slate-500" colspan="3">No users available.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </article>
                    </div>


                </section>
            </div>
        </section>
    </main>

    <div id="admin-logout-modal" class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-[80] p-4">
        <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800">Confirm logout</h3>
            <p class="mt-2 text-sm text-slate-600">Are you sure you want to logout?</p>
            <div class="mt-5 flex justify-end gap-3">
                <button id="admin-logout-cancel" type="button"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button id="admin-logout-confirm" type="button"
                    class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Yes,
                    logout</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('admin-sidebar');
            const sidebarOverlay = document.getElementById('admin-sidebar-overlay');
            const sidebarOpen = document.getElementById('admin-sidebar-open');
            const sidebarClose = document.getElementById('admin-sidebar-close');
            const logoutForm = document.getElementById('admin-logout-form');
            const logoutModal = document.getElementById('admin-logout-modal');
            const logoutCancel = document.getElementById('admin-logout-cancel');
            const logoutConfirm = document.getElementById('admin-logout-confirm');

            const desktopMediaQuery = window.matchMedia('(min-width: 1280px)');

            const syncSidebarButtonState = (isOpen) => {
                sidebarOpen?.setAttribute('aria-expanded', String(isOpen));
            };

            const closeSidebar = () => {
                if (!sidebar || !sidebarOverlay || desktopMediaQuery.matches) return;
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                syncSidebarButtonState(false);
            };

            const openSidebar = () => {
                if (!sidebar || !sidebarOverlay || desktopMediaQuery.matches) return;
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                syncSidebarButtonState(true);
            };

            const handleViewportChange = () => {
                if (!sidebar || !sidebarOverlay) return;
                if (desktopMediaQuery.matches) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    syncSidebarButtonState(false);
                    return;
                }
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                syncSidebarButtonState(false);
            };

            const closeLogoutModal = () => {
                if (!logoutModal) return;
                logoutModal.classList.add('hidden');
                logoutModal.classList.remove('flex');
            };

            const openLogoutModal = () => {
                if (!logoutModal) return;
                logoutModal.classList.remove('hidden');
                logoutModal.classList.add('flex');
            };

            if (logoutForm && logoutModal) {
                logoutForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    openLogoutModal();
                });

                logoutCancel?.addEventListener('click', closeLogoutModal);
                logoutConfirm?.addEventListener('click', () => logoutForm.submit());

                logoutModal.addEventListener('click', (event) => {
                    if (event.target === logoutModal) closeLogoutModal();
                });
            }

            sidebarOpen?.addEventListener('click', openSidebar);
            sidebarClose?.addEventListener('click', closeSidebar);
            sidebarOverlay?.addEventListener('click', closeSidebar);
            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeSidebar();
                    closeLogoutModal();
                }
            });
            desktopMediaQuery.addEventListener('change', handleViewportChange);
            handleViewportChange();
            const slideshow = document.querySelector('[data-slideshow]');
            const slideItems = slideshow ? Array.from(slideshow.querySelectorAll('[data-slide-item]')) : [];
            const dots = slideshow ? Array.from(slideshow.querySelectorAll('[data-slide-dot]')) : [];
            const prevButton = slideshow?.querySelector('[data-slide-prev]');
            const nextButton = slideshow?.querySelector('[data-slide-next]');
            let currentSlideIndex = 0;
            let slideInterval;

            const showSlide = (index) => {
                slideItems.forEach((slide, slideIndex) => {
                    const isActive = slideIndex === index;
                    slide.classList.toggle('opacity-100', isActive);
                    slide.classList.toggle('opacity-0', !isActive);
                    slide.classList.toggle('pointer-events-none', !isActive);
                });
                dots.forEach((dot, dotIndex) => {
                    dot.classList.toggle('bg-slate-900', dotIndex === index);
                    dot.classList.toggle('bg-slate-300', dotIndex !== index);
                });
                currentSlideIndex = index;
            };

            const moveSlide = (step) => {
                if (!slideItems.length) return;
                const nextIndex = (currentSlideIndex + step + slideItems.length) % slideItems.length;
                showSlide(nextIndex);
            };

            const startAutoSlide = () => {
                if (!slideItems.length) return;
                slideInterval = window.setInterval(() => moveSlide(1), 3500);
            };

            const resetAutoSlide = () => {
                window.clearInterval(slideInterval);
                startAutoSlide();
            };

            if (slideshow && slideItems.length > 1) {
                prevButton?.addEventListener('click', () => {
                    moveSlide(-1);
                    resetAutoSlide();
                });
                nextButton?.addEventListener('click', () => {
                    moveSlide(1);
                    resetAutoSlide();
                });
                dots.forEach((dot, dotIndex) => {
                    dot.addEventListener('click', () => {
                        showSlide(dotIndex);
                        resetAutoSlide();
                    });
                });
                slideshow.addEventListener('mouseenter', () => window.clearInterval(slideInterval));
                slideshow.addEventListener('mouseleave', startAutoSlide);
                showSlide(0);
                startAutoSlide();
            }
        });
    </script>
</body>

</html>
