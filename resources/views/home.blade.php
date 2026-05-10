<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Session Home • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .home-shell {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .home-sidebar {
            width: 100%;
        }

        .home-main {
            flex: 1 1 auto;
            min-width: 0;
        }

        .sidebar-toggle {
            display: inline-flex;
        }

        .home-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: min(18rem, 88vw);
            transform: translateX(-105%);
            transition: transform 0.25s ease;
            z-index: 70;
            overflow-y: auto;
            border-radius: 0;
            background: #d8ecf7;
        }

        .home-sidebar.is-open {
            transform: translateX(0);
        }

        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgb(15 23 42 / 0.45);
            z-index: 60;
            display: none;
        }

        .sidebar-backdrop.is-open {
            display: block;
        }

        .session-slide-shell {
            position: relative;
            overflow: hidden;
            border-radius: 0.9rem;
            border: 1px solid rgb(186 230 253);
            min-height: 10.5rem;
            background: linear-gradient(135deg, rgb(240 249 255) 0%, rgb(236 254 255) 55%, rgb(224 242 254) 100%);
        }

        .session-slide-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1.04);
            animation: slide-kenburns 10s ease-in-out infinite alternate;
            filter: saturate(1.05) contrast(1.02);
        }

        .session-slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(110deg, rgb(12 74 110 / 0.70) 0%, rgb(14 116 144 / 0.45) 45%, rgb(14 165 233 / 0.22) 100%);
        }

        .session-slide-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            min-height: 10.5rem;
            padding: 1rem;
            color: rgb(240 249 255);
        }

        .slide-fade {
            animation: slide-fade 480ms ease;
        }

        @keyframes slide-fade {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-kenburns {
            from {
                transform: scale(1.02);
            }

            to {
                transform: scale(1.10);
            }
        }

        .upcoming-slide {
            border-radius: 0.85rem;
            padding: 0.9rem;
            border: 1px solid rgb(186 230 253);
            transition: all 350ms ease;
        }

        .upcoming-slide-progress-track {
            height: 0.30rem;
            border-radius: 999px;
            background: rgb(148 163 184 / 0.24);
            overflow: hidden;
        }

        .upcoming-slide-progress-bar {
            height: 100%;
            width: 100%;
            background: linear-gradient(90deg, rgb(14 116 144), rgb(56 189 248));
            transform-origin: left;
            transform: scaleX(0);
        }

        .upcoming-slide-progress-bar.is-running {
            animation: upcoming-progress 4500ms linear forwards;
        }

        .upcoming-slide.is-animating {
            animation: upcoming-fade 420ms ease;
        }


        .upcoming-theme-a {
            background: linear-gradient(135deg, rgb(239 246 255), rgb(224 242 254));
        }

        .upcoming-theme-b {
            background: linear-gradient(135deg, rgb(240 253 250), rgb(224 242 254));
        }

        .upcoming-theme-c {
            background: linear-gradient(135deg, rgb(245 243 255), rgb(224 231 255));
        }

        .upcoming-theme-emergency {
            background: linear-gradient(135deg, rgb(254 226 226), rgb(254 202 202));
            border-color: rgb(248 113 113);
        }

        @keyframes upcoming-fade {
            from {
                opacity: 0.25;
                transform: translateY(8px) scale(0.99);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }


        @keyframes upcoming-progress {
            from {
                transform: scaleX(0);
            }

            to {
                transform: scaleX(1);
            }
        }


        .hero-gradient {
            background: linear-gradient(120deg, rgb(14 116 144) 0%, rgb(2 132 199) 45%, rgb(99 102 241) 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-gradient::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 20%, rgb(255 255 255 / 0.32), transparent 45%);
            pointer-events: none;
        }

        .quick-stat {
            border: 1px solid rgb(148 163 184 / 0.18);
            background: rgb(255 255 255 / 0.14);
            backdrop-filter: blur(4px);
        }

        .menu-card {
            position: relative;
            overflow: hidden;
        }

        .menu-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgb(14 116 144 / 0.08), transparent 65%);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .menu-card:hover::before {
            opacity: 1;
        }

        .glass-panel {
            border: 1px solid rgb(203 213 225 / 0.75);
            background: linear-gradient(145deg, rgb(255 255 255 / 0.92), rgb(248 250 252 / 0.88));
            box-shadow: 0 12px 32px rgb(15 23 42 / 0.08);
            backdrop-filter: blur(6px);
            transition: transform 220ms ease, box-shadow 220ms ease, border-color 220ms ease;
        }

        .glass-panel:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 34px rgb(14 116 144 / 0.16);
            border-color: rgb(125 211 252 / 0.9);
        }

        .status-step {
            position: relative;
            overflow: hidden;
            transition: transform 220ms ease, box-shadow 220ms ease, border-color 220ms ease;
        }

        .status-step::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgb(14 165 233 / 0.10), transparent 60%);
            opacity: 0;
            transition: opacity 220ms ease;
            pointer-events: none;
        }

        .status-step:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgb(14 116 144 / 0.14);
        }

        .status-step:hover::after {
            opacity: 1;
        }

        .status-card {
            border: 1px solid transparent;
            background: linear-gradient(180deg, rgb(248 250 252 / 0.95) 0%, rgb(255 255 255 / 0.98) 100%);
            box-shadow: 0 14px 36px rgb(15 23 42 / 0.08);
            position: relative;
            overflow: hidden;
            transition: transform 260ms ease, box-shadow 260ms ease;
        }

        .status-card::before {
            content: '';
            position: absolute;
            inset: 0;
            padding: 1px;
            border-radius: inherit;
            background: linear-gradient(120deg, rgb(56 189 248), rgb(99 102 241), rgb(20 184 166), rgb(56 189 248));
            background-size: 300% 300%;
            animation: status-border-rgb 7s linear infinite;
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .status-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 40px rgb(14 116 144 / 0.16);
        }

        @keyframes status-border-rgb {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }

            background: radial-gradient(circle at 90% 10%, rgb(191 219 254 / 0.45), transparent 42%),
            radial-gradient(circle at 8% 90%, rgb(224 242 254 / 0.7), transparent 46%),
            linear-gradient(160deg, rgb(239 246 255 / 0.95) 0%, rgb(255 255 255 / 0.98) 52%, rgb(240 249 255 / 0.98) 100%);
            box-shadow: 0 16px 38px rgb(2 132 199 / 0.14);
        }

        .status-metric {
            position: relative;
            overflow: hidden;
        }

        .status-metric::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: 0.95;
        }

        .status-metric.active::before {
            background: linear-gradient(130deg, rgb(224 242 254 / 0.95), rgb(239 246 255 / 0.8));
        }

        .status-metric.completed::before {
            background: linear-gradient(130deg, rgb(209 250 229 / 0.9), rgb(236 253 245 / 0.78));
        }

        .status-metric.cancelled::before {
            background: linear-gradient(130deg, rgb(254 226 226 / 0.82), rgb(255 241 242 / 0.78));
        }

        .status-metric.available::before {
            background: linear-gradient(130deg, rgb(219 234 254 / 0.95), rgb(224 242 254 / 0.86));
        }

        .status-metric>* {
            position: relative;
        }

        .calendar-collapsible {
            display: grid;
            overflow: hidden;
            max-height: 1200px;
            opacity: 1;
            transform: translateY(0);
            transition: max-height 320ms ease, opacity 240ms ease, transform 240ms ease;
        }

        .calendar-collapsible.is-collapsed {
            max-height: 0;
            opacity: 0;
            transform: translateY(-8px);
            pointer-events: none;
        }

        .calendar-toggle-btn {
            transition: transform 220ms ease, background-color 220ms ease, color 220ms ease;
        }

        .calendar-toggle-btn[aria-expanded="true"] {
            transform: rotate(180deg);
        }

        @media (max-width: 1024px) {
            .calendar-responsive-shell {
                min-width: 560px;
            }

            #calendar-grid {
                gap: 0.45rem;
                padding: 0.45rem;
            }
        }

        @media (max-width: 640px) {
            .calendar-responsive-shell {
                min-width: 540px;
            }
        }

        @media (max-width: 480px) {
            .calendar-responsive-shell {
                min-width: 510px;
            }

            #calendar-grid {
                gap: 0.5rem;
                padding: 0.5rem;
            }
        }

        .progress-fill {
            animation: progress-grow 850ms ease both;
            transform-origin: left;
        }

        @keyframes progress-grow {
            from {
                transform: scaleX(0.2);
                opacity: 0.5;
            }

            to {
                transform: scaleX(1);
                opacity: 1;
            }
        }


        .dashboard-shell {
            border-radius: 1.35rem;
        }

        .top-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .top-actions {
            width: 100%;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .hero-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-cta {
            width: 100%;
            justify-content: center;
        }

        .quick-stats-grid {
            grid-template-columns: 1fr;
        }

        @media (min-width: 640px) {
            .dashboard-shell {
                border-radius: 1.8rem;
            }

            .top-header {
                align-items: center;
                flex-direction: row;
            }

            .top-actions {
                width: auto;
                flex-wrap: nowrap;
            }

            .hero-header {
                flex-direction: row;
                align-items: flex-start;
            }

            .hero-cta {
                width: auto;
            }

            .quick-stats-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }


        @media (min-width: 1280px) {
            .home-shell {
                flex-direction: row;
                align-items: stretch;
            }

            .home-sidebar {
                width: 16rem;
                flex: 0 0 16rem;
                position: sticky;
                top: 1rem;
                align-self: stretch;
                min-height: auto;
                transform: none;
                border-radius: 1rem;
                z-index: auto;
                overflow: visible;
            }

            .sidebar-toggle,
            .sidebar-close-btn,
            .sidebar-backdrop {
                display: none !important;
            }

            #sidebar-toggle {
                cursor: pointer;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 text-slate-700 overflow-x-hidden">

    <div id="loader" class="fixed inset-0 bg-sky-500 flex items-center justify-center z-50">
        <div id="circle" class="w-64 h-64 bg-white rounded-full flex items-center justify-center">
            <span id="logoText" class="text-sky-500 font-bold text-2xl">CollegeCare</span>
        </div>
    </div>
    <div id="content" class="opacity-0 translate-y-2 min-h-screen flex flex-col">

        <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]">
            </div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="absolute inset-0 bg-noise-layer opacity-5"></div>
            <div
                class="absolute -top-32 -left-24 w-[34rem] h-[34rem] bg-sky-300/35 rounded-full blur-3xl animate-blob-float">
            </div>
            <div
                class="absolute top-24 -right-32 w-[36rem] h-[36rem] bg-violet-300/30 rounded-full blur-3xl animate-aurora-drift animation-delay-2">
            </div>
        </div>

        <main class="min-h-screen p-4 sm:p-8">
            @php
                $dashboardRoleLabel = $role === 'teacher' ? 'Lecturer' : ucfirst($role);
                $sidebarRoleLabel =
                    $role === 'student' ? 'Pelajar' : ($role === 'teacher' ? 'Pensyarah' : ucfirst($role));
            @endphp
            <section
                class="max-w-[96rem] mx-auto dashboard-shell border border-slate-200 bg-white/90 backdrop-blur-md shadow-xl overflow-hidden">
                <header
                    class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/80 top-header flex justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Session Dashboard
                            ({{ $dashboardRoleLabel }})
                        </h1>
                        <p class="text-sm text-slate-500 mt-1">Welcome, {{ $user->full_name ?: $user->name }}</p>
                    </div>
                    <div class="top-actions flex items-center gap-2">


                        <button type="button" id="sidebar-toggle"
                            class="sidebar-toggle sidebar-toggle  rounded-xl border border-slate-200 bg-white p-3 text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>

                        </button>


                        <a href="{{ route('home') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M21 12a9 9 0 1 1-2.64-6.36" />
                                <polyline points="21 3 21 9 15 9" />
                            </svg>

                        </a>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}"> @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" y1="12" x2="9" y2="12" />
                                </svg>

                            </button>
                        </form>
                    </div>
                </header>

                <div class="p-5 sm:p-7 home-shell">
                    <aside id="home-sidebar"
                        class="home-sidebar rounded-2xl border border-[#b9dbef] bg-[#d8ecf7] p-4 shadow-sm">
                        <div class="flex justify-end xl:hidden mb-2">
                            <button type="button" id="sidebar-close"
                                class="sidebar-close-btn rounded-lg border border-slate-200 px-2.5 py-1 text-sm text-slate-600 hover:text-sky-700 hover:border-sky-200">
                                ✕
                            </button>
                        </div>
                        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-200">
                            <img src="{{ $user->profile_pic ?: '/images/default-profile.svg' }}" alt="Profile"
                                class="w-11 h-11 rounded-full border border-slate-200 object-cover bg-sky-50" />
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                                <p class="text-xs uppercase tracking-wide text-sky-700">
                                    {{ $sidebarRoleLabel }}</p>
                            </div>
                        </div>

                        <p class="text-xs uppercase tracking-[0.12em] text-slate-500 mb-3">Menu</p>
                        <nav class="space-y-3 text-sm">
                            <a href="{{ route('inbox') }}" title="Inbox" aria-label="Inbox"
                                class="menu-card relative flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                @if (!empty($showInboxNotificationDot))
                                    <span
                                        class="absolute ml-8 -mt-5 h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white"
                                        aria-hidden="true"></span>
                                @endif
                                <span
                                    class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M22 12.2V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v5.2" />
                                        <path
                                            d="M2 12.2h4.7a2 2 0 0 1 1.4.6l1 1a2 2 0 0 0 1.4.6h3a2 2 0 0 0 1.4-.6l1-1a2 2 0 0 1 1.4-.6H22" />
                                        <path d="M22 12.2V17a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4.8" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Inbox</span>
                            </a>
                            <a href="{{ route('chat.index') }}" title="Chat Box" aria-label="Chat Box"
                                class="menu-card flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                <span
                                    class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Chat Box</span>
                            </a>

                            <a href="{{ route('booking.index') }}" title="Booking" aria-label="Booking"
                                class="menu-card flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                <span
                                    class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" />
                                        <path d="M16 2v4M8 2v4M3 10h18" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Booking</span>
                            </a>
                            <a href="{{ route('booking.history') }}" title="Booking History"
                                aria-label="Booking History"
                                class="menu-card flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                <span
                                    class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3v5h5" />
                                        <path d="M3.05 13A9 9 0 1 0 6 6.3L3 8" />
                                        <path d="M12 7v5l3 2" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Booking History</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" title="Edit Profile" aria-label="Edit Profile"
                                class="menu-card flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                                <span
                                    class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Z" />
                                        <path d="M4 20a8 8 0 0 1 16 0" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Edit Profile</span>
                            </a>
                        </nav>
                    </aside>
                    <section
                        class="home-main rounded-2xl border border-slate-200 bg-white/90 p-4 sm:p-6 shadow-sm space-y-5">
                        <div class="hero-gradient rounded-2xl p-5 sm:p-6 text-white shadow-lg">
                            <div class="relative z-10 flex flex-col gap-5">
                                <div class="hero-header flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.14em] text-sky-100">Wellbeing Hub</p>
                                        <h2 class="text-xl sm:text-2xl font-bold mt-1">Hi
                                            {{ $user->full_name ?: $user->name }}, ready to check in today?</h2>
                                        <p class="mt-2 text-sm text-sky-100/90">Track counsellor availability, manage
                                            your booking, and stay updated in one place.</p>
                                    </div>
                                    <a href="{{ route('booking.index') }}"
                                        class="hero-cta shrink-0 inline-flex items-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-sky-700 hover:bg-sky-50 transition">Book
                                        Session</a>
                                </div>
                                <div class="quick-stats-grid grid gap-3">
                                    <div class="quick-stat rounded-xl px-4 py-3">
                                        <p class="text-xs uppercase tracking-wide text-sky-100/90">Role</p>
                                        <p class="text-base font-semibold">{{ $dashboardRoleLabel }}</p>
                                    </div>
                                    <div class="quick-stat rounded-xl px-4 py-3">
                                        <p class="text-xs uppercase tracking-wide text-sky-100/90">Current Time</p>
                                        <p id="current-time-display" class="text-base font-semibold">
                                            {{ now()->setTimezone('Asia/Kuala_Lumpur')->format('g:i A') }}</p>
                                    </div>
                                    <div class="quick-stat rounded-xl px-4 py-3">
                                        <p class="text-xs uppercase tracking-wide text-sky-100/90">Counsellors Ready
                                        </p>
                                        <p class="text-base font-semibold">{{ count($counsellorNames ?? []) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="session-slide-shell shadow-sm">
                            <img id="session-slide-image" class="session-slide-img" src=""
                                alt="Session announcement image">
                            <div class="session-slide-overlay"></div>
                            <div id="session-slide" class="session-slide-content">
                                <span id="session-slide-tag"
                                    class="inline-flex w-fit rounded-full border border-white/30 bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-sky-100">Update</span>
                                <h3 id="session-slide-title"
                                    class="mt-3 text-base sm:text-lg font-semibold leading-snug">
                                </h3>
                                <p id="session-slide-subtitle" class="mt-2 text-sm text-sky-100/90"></p>
                            </div>
                        </div>
                        @php
                            $activeBooking = collect($userActiveBookings ?? [])
                                ->filter(
                                    fn($booking) => in_array(
                                        strtolower($booking['status'] ?? ''),
                                        ['pending', 'approved', 'booked'],
                                        true,
                                    ),
                                )
                                ->sortByDesc(fn($booking) => $booking['booking_date'] ?? ($booking['date'] ?? null))
                                ->first();
                        @endphp
                        <div class="status-card rounded-2xl p-4 sm:p-5">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h3 class="text-base font-semibold text-slate-800">Current Booking Status</h3>
                                    @if ($activeBooking)
                                        <p class="text-sm text-slate-500 mt-1">Latest active request with
                                            <span class="font-semibold text-slate-700">
                                                {{ $activeBooking['counsellor_name'] ?? ($activeBooking['counsellor'] ?? 'Counsellor') }}
                                            </span>
                                            @if (!empty($activeBooking['booking_date'] ?? ($activeBooking['date'] ?? null)))
                                                •
                                                {{ \Carbon\Carbon::parse($activeBooking['booking_date'] ?? $activeBooking['date'])->format('d M Y') }}
                                            @endif
                                        </p>
                                    @else
                                        <p class="text-sm text-slate-500 mt-1">
                                            No active bookings.
                                            <a href="{{ route('booking.index') }}"
                                                class="font-semibold text-sky-700 hover:text-sky-800 underline underline-offset-2">
                                                Book your first counselling session now
                                            </a>.
                                        </p>
                                    @endif
                                </div>
                                <div class="text-sm text-slate-500">
                                    Updated:
                                    <span id="status-updated-time" class="font-semibold text-slate-700">
                                        {{ now()->setTimezone('Asia/Kuala_Lumpur')->format('g:i A') }}
                                    </span>
                                </div>
                            </div>
                            {{-- past, current, future --}}
                            @if ($activeBooking)
                                <div class="mt-4">
                                    @php

                                        $currentStatus = strtolower((string) ($activeBooking->status ?? 'none'));
                                        $statusPillClass = match ($currentStatus) {
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'booked', 'approved' => 'bg-sky-100 text-sky-700',
                                            'completed' => 'bg-emerald-100 text-emerald-700',
                                            'cancelled' => 'bg-rose-100 text-rose-700',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp
                                    @if ($currentStatus !== 'none')
                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $statusPillClass }}">
                                            {{ ucfirst($currentStatus === 'approved' ? 'booked' : $currentStatus) }}
                                        </span>
                                    @endif
                                </div>
                            @endif


                            @if ($activeBooking)
                                <div class="mt-4 text-sm">
                                    <a href="{{ route('booking.history') }}"
                                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">
                                        View Booking History
                                    </a>
                                </div>
                            @endif

                            @php
                                $showStats = $role === 'student';

                                $statusCounts = collect($userActiveBookings ?? [])
                                    ->map(fn($booking) => strtolower($booking['status'] ?? ''))
                                    ->countBy();

                                $activeStatusTotal =
                                    (int) $statusCounts->get('pending', 0) +
                                    (int) $statusCounts->get('booked', 0) +
                                    (int) $statusCounts->get('approved', 0);
                                $completedStatusTotal = (int) $statusCounts->get('completed', 0);
                                $cancelledStatusTotal = (int) $statusCounts->get('cancelled', 0);

                                $availableCounsellorsCount = collect($counsellorNames ?? [])
                                    ->filter(fn($name) => filled($name))
                                    ->count();

                                $nextBooking = collect($userActiveBookings ?? [])
                                    ->filter(function ($booking) {
                                        $bookingDate = $booking['booking_date'] ?? ($booking['date'] ?? null);
                                        if (empty($bookingDate) || empty($booking['status'])) {
                                            return false;
                                        }

                                        if (
                                            !in_array(
                                                strtolower($booking['status']),
                                                ['pending', 'booked', 'approved'],
                                                true,
                                            )
                                        ) {
                                            return false;
                                        }

                                        try {
                                            return \Carbon\Carbon::parse($bookingDate)
                                                ->startOfDay()
                                                ->gte(now()->startOfDay());
                                        } catch (\Throwable $exception) {
                                            return false;
                                        }
                                    })
                                    ->sortBy(fn($booking) => $booking['booking_date'] ?? ($booking['date'] ?? null))
                                    ->first();
                            @endphp

                            @if ($showStats)
                                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                                    <div class="glass-panel status-metric active rounded-2xl p-4">
                                        <p class="text-xs uppercase tracking-[0.12em] text-slate-500">Active Booking
                                        </p>
                                        <p class="mt-2 text-2xl font-semibold text-slate-800">{{ $activeStatusTotal }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-500">Pending, approved and booked counselling
                                            slots.
                                        </p>
                                    </div>
                                    <div class="glass-panel status-metric completed rounded-2xl p-4">
                                        <p class="text-xs uppercase tracking-[0.12em] text-slate-500">Completed Session
                                        </p>
                                        <p class="mt-2 text-2xl font-semibold text-emerald-700">
                                            {{ $completedStatusTotal }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-500">Sessions completed successfully.</p>
                                    </div>
                                    <div class="glass-panel status-metric cancelled rounded-2xl p-4">
                                        <p class="text-xs uppercase tracking-[0.12em] text-slate-500">Cancelled Request
                                        </p>
                                        <p class="mt-2 text-2xl font-semibold text-rose-700">
                                            {{ $cancelledStatusTotal }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-500">Requests cancelled or rejected.</p>
                                    </div>
                                    <div class="glass-panel status-metric available rounded-2xl p-4">
                                        <p class="text-xs uppercase tracking-[0.12em] text-slate-500">Available
                                            Counsellor
                                        </p>
                                        <p class="mt-2 text-2xl font-semibold text-sky-700">
                                            {{ $availableCounsellorsCount }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-500">Counsellors ready for consultation.</p>
                                    </div>
                                </div>

                                <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
                                    <div
                                        class="glass-panel rounded-2xl p-4 border-sky-100/90 bg-gradient-to-br from-sky-50 via-white to-blue-50">
                                        <h3 class="text-sm font-semibold text-slate-800">Next Session Forecast
                                            (Upcoming Appointment)</h3>
                                        @php
                                            $upcomingBookings = collect($userActiveBookings ?? [])
                                                ->filter(
                                                    fn($booking) => in_array(
                                                        strtolower((string) ($booking['status'] ?? '')),
                                                        ['pending', 'approved', 'booked'],
                                                        true,
                                                    ),
                                                )
                                                ->sortBy(
                                                    fn($booking) => $booking['booking_date'] ??
                                                        ($booking['date'] ?? null),
                                                )
                                                ->values();
                                        @endphp
                                        @if ($upcomingBookings->isNotEmpty())
                                            @if ($upcomingBookings->count() > 1)
                                                @php
                                                    $upcomingSliderEntries = $upcomingBookings
                                                        ->map(function ($booking): array {
                                                            $dateValue =
                                                                $booking['booking_date'] ?? ($booking['date'] ?? null);
                                                            $dateLabel = $dateValue
                                                                ? \Carbon\Carbon::parse($dateValue)->format('D, d M Y')
                                                                : 'Date pending';

                                                            return [
                                                                'date' => $dateLabel,
                                                                'status' => ucfirst(
                                                                    strtolower(
                                                                        (string) ($booking['status'] ?? 'pending'),
                                                                    ),
                                                                ),
                                                                'time' =>
                                                                    $booking['booking_time'] ??
                                                                    ($booking['time'] ?? ''),
                                                                'counsellor' =>
                                                                    $booking['counsellor_name'] ??
                                                                    ($booking['counsellor'] ?? ''),
                                                                'is_emergency' => str_contains(
                                                                    strtoupper(
                                                                        trim(
                                                                            (string) (($booking['topic'] ?? '') .
                                                                                ' ' .
                                                                                ($booking['note'] ?? '')),
                                                                        ),
                                                                    ),
                                                                    'EMERGENCY',
                                                                ),
                                                            ];
                                                        })
                                                        ->values();
                                                @endphp
                                                <div id="upcoming-session-slider"
                                                    class="upcoming-slide upcoming-theme-a mt-2 space-y-1"
                                                    data-entries='@json($upcomingSliderEntries)'>


                                                    <p id="upcoming-session-date"
                                                        class="text-lg font-semibold text-slate-800"></p>
                                                    <p id="upcoming-session-status" class="text-sm text-slate-500">
                                                    </p>
                                                    <p id="upcoming-session-time"
                                                        class="mt-1 text-sm text-slate-500 hidden"></p>
                                                    <p id="upcoming-session-counsellor"
                                                        class="mt-1 text-sm text-slate-500 hidden"></p>
                                                    <div class="upcoming-slide-progress-track mt-2"
                                                        aria-hidden="true">
                                                        <div id="upcoming-slide-progress-bar"
                                                            class="upcoming-slide-progress-bar"></div>
                                                    </div>
                                                </div>
                                            @else
                                                @php
                                                    $nextBooking = $upcomingBookings->first();
                                                    $nextBookingDate = \Carbon\Carbon::parse(
                                                        $nextBooking['booking_date'] ?? $nextBooking['date'],
                                                    );
                                                    $nextBookingStatus = ucfirst(
                                                        strtolower((string) ($nextBooking['status'] ?? 'pending')),
                                                    );
                                                    $nextBookingTime =
                                                        $nextBooking['booking_time'] ?? ($nextBooking['time'] ?? null);
                                                    $nextBookingIsEmergency = str_contains(
                                                        strtoupper(
                                                            trim(
                                                                (string) (($nextBooking['topic'] ?? '') .
                                                                    ' ' .
                                                                    ($nextBooking['note'] ?? '')),
                                                            ),
                                                        ),
                                                        'EMERGENCY',
                                                    );
                                                @endphp
                                                <div
                                                    class="{{ $nextBookingIsEmergency ? 'rounded-xl border border-rose-300 bg-rose-50 p-3' : '' }}">
                                                    <p class="mt-2 text-lg font-semibold text-slate-800">
                                                        {{ $nextBookingDate->format('D, d M Y') }}</p>
                                                    <p class="text-sm text-slate-500">Status: {{ $nextBookingStatus }}
                                                    </p>
                                                    @if (!empty($nextBookingTime))
                                                        <p class="mt-1 text-sm text-slate-500">Time:
                                                            {{ $nextBookingTime }}</p>
                                                    @endif
                                                    @if (!empty($nextBooking['counsellor_name'] ?? ($nextBooking['counsellor'] ?? null)))
                                                        <p class="mt-1 text-sm text-slate-500">Counsellor:
                                                            {{ $nextBooking['counsellor_name'] ?? $nextBooking['counsellor'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif
                                        @else
                                            <p class="mt-2 text-sm text-slate-500">No upcoming sessions yet. Select a
                                                slot
                                                to schedule your next appointment.</p>
                                        @endif
                                    </div>

                                    <div
                                        class="glass-panel rounded-2xl p-4 border-sky-100/90 bg-gradient-to-br from-blue-50 via-white to-sky-50">
                                        <h3 class="text-sm font-semibold text-slate-800">Booking Progress</h3>
                                        <div class="mt-3 space-y-3">
                                            @php
                                                $progressCards = [
                                                    [
                                                        'label' => 'Pending',
                                                        'value' => (int) $statusCounts->get('pending', 0),
                                                        'bar' => 'bg-amber-400',
                                                    ],
                                                    [
                                                        'label' => 'Booked / Approved',
                                                        'value' =>
                                                            (int) $statusCounts->get('booked', 0) +
                                                            (int) $statusCounts->get('approved', 0),
                                                        'bar' => 'bg-sky-500',
                                                    ],
                                                    [
                                                        'label' => 'Completed',
                                                        'value' => (int) $statusCounts->get('completed', 0),
                                                        'bar' => 'bg-emerald-500',
                                                    ],
                                                ];

                                                $progressTotal = collect($progressCards)->sum('value');
                                            @endphp

                                            @foreach ($progressCards as $item)
                                                @php
                                                    $percent =
                                                        $progressTotal > 0
                                                            ? round(($item['value'] / $progressTotal) * 100)
                                                            : 0;
                                                @endphp
                                                <div>
                                                    <div
                                                        class="flex items-center justify-between text-xs text-slate-500">
                                                        <span>{{ $item['label'] }}</span>
                                                        <span>{{ $item['value'] }} ({{ $percent }}%)</span>
                                                    </div>
                                                    <div class="mt-1 h-2 rounded-full bg-slate-100 overflow-hidden">
                                                        <div class="progress-fill h-full {{ $item['bar'] }}"
                                                            style="width: {{ $percent }}%;"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <a href="{{ route('inbox') }}"
                                class="menu-card glass-panel rounded-2xl p-4 hover:border-sky-200 transition">
                                <p class="text-xs uppercase tracking-[0.12em] text-slate-500">Inbox</p>
                                <p class="mt-1 text-base font-semibold text-slate-800">View Notifications</p>
                                <p class="mt-2 text-sm text-slate-500">Check OTP and counselling reminders quickly.</p>
                            </a>
                            <a href="{{ route('chat.index') }}"
                                class="menu-card glass-panel rounded-2xl p-4 hover:border-sky-200 transition">
                                <p class="text-xs uppercase tracking-[0.12em] text-slate-500">Chat</p>
                                <p class="mt-1 text-base font-semibold text-slate-800">Open Chat Box</p>
                                <p class="mt-2 text-sm text-slate-500">Continue your conversation with counsellors.</p>
                            </a>
                            <a href="{{ route('booking.history') }}"
                                class="menu-card glass-panel rounded-2xl p-4 hover:border-sky-200 transition">
                                <p class="text-xs uppercase tracking-[0.12em] text-slate-500">History</p>
                                <p class="mt-1 text-base font-semibold text-slate-800">Booking History</p>
                                <p class="mt-2 text-sm text-slate-500">Review past or upcoming appointments.</p>
                            </a>
                            <a href="{{ route('profile.edit') }}"
                                class="menu-card glass-panel rounded-2xl p-4 hover:border-sky-200 transition">
                                <p class="text-xs uppercase tracking-[0.12em] text-slate-500">Profile</p>
                                <p class="mt-1 text-base font-semibold text-slate-800">Update Profile</p>
                                <p class="mt-2 text-sm text-slate-500">Keep your details up to date.</p>
                            </a>
                        </div> --}}

                            <div id="counsellor-calendar-card" class="glass-panel rounded-2xl p-4 sm:p-5">
                                <div class="flex items-center justify-between mb-4 gap-3">
                                    <div>
                                        <h2 class="text-base sm:text-lg font-semibold text-slate-800">Jadual Kaunselor
                                            (Calendar)</h2>
                                        <p class="text-sm text-slate-500">Klik mana-mana tarikh untuk lihat jadual
                                            dalam
                                            bentuk table.</p>
                                    </div>

                                    <button id="calendar-toggle-size" type="button" aria-expanded="false"
                                        aria-controls="calendar-content"
                                        class="calendar-toggle-btn inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-base font-semibold text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">+</button>
                                </div>

                                <div id="calendar-content"
                                    class="calendar-collapsible items-stretch xl:grid-cols-[minmax(0,1fr)_200px] gap-3">
                                    <div class="rounded-2xl border border-slate-200 overflow-hidden bg-white">
                                        <div
                                            class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                                            <button id="calendar-prev"
                                                class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">←</button>
                                            <h3 id="calendar-title" class="font-semibold text-slate-700">Month Year
                                            </h3>
                                            <button id="calendar-next"
                                                class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">→</button>
                                        </div>
                                        <div class="overflow-x-auto px-1 sm:px-0 pb-1">
                                            <div class="calendar-responsive-shell min-w-[560px]">
                                                <div class="text-xs uppercase tracking-wide bg-slate-100 text-slate-500"
                                                    style="display:grid;grid-template-columns:repeat(7,minmax(0,1fr));">
                                                    <div
                                                        class="p-2.5 text-center font-semibold border-r border-slate-200">
                                                        Sun</div>
                                                    <div
                                                        class="p-2.5 text-center font-semibold border-r border-slate-200">
                                                        Mon</div>
                                                    <div
                                                        class="p-2.5 text-center font-semibold border-r border-slate-200">
                                                        Tue</div>
                                                    <div
                                                        class="p-2.5 text-center font-semibold border-r border-slate-200">
                                                        Wed</div>
                                                    <div
                                                        class="p-2.5 text-center font-semibold border-r border-slate-200">
                                                        Thu</div>
                                                    <div
                                                        class="p-2.5 text-center font-semibold border-r border-slate-200">
                                                        Fri</div>
                                                    <div class="p-2.5 text-center font-semibold">Sat</div>
                                                </div>
                                                <div id="calendar-grid" class="gap-2 p-2 bg-slate-100"
                                                    style="display:grid;grid-template-columns:repeat(7,minmax(0,1fr));">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <aside class="h-full rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                        <h3 class="font-semibold text-slate-700 mb-3">Ringkasan</h3>
                                        <ul class="space-y-2 text-sm text-slate-600">
                                            <li class="rounded-lg border border-slate-200 bg-white p-2">🟢 Slot kosong
                                            </li>
                                            <li class="rounded-lg border border-slate-200 bg-white p-2">🟡 Menunggu
                                            </li>
                                            <li class="rounded-lg border border-slate-200 bg-white p-2">🔵 Ditempah
                                            </li>
                                            <li class="rounded-lg border border-slate-200 bg-white p-2">🔴 Penuh</li>
                                        </ul>
                                    </aside>
                                </div>
                            </div>
                    </section>
                </div>
                <div id="sidebar-backdrop" class="sidebar-backdrop"></div>

                <footer
                    class="px-6 sm:px-8 py-4 border-t border-slate-200/80 text-center text-sm text-slate-500 bg-white/70">
                    © {{ date('Y') }} CollegeCare • Counselling Booking System
                </footer>
            </section>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const slide = document.getElementById('session-slide');
                const slideImage = document.getElementById('session-slide-image');
                const slideTitle = document.getElementById('session-slide-title');
                const slideSubtitle = document.getElementById('session-slide-subtitle');
                const slideTag = document.getElementById('session-slide-tag');
                const items = @json($announcements ?? []);
                const fallbackSlides = [{
                        title: 'Counselling slots for this week are now open. Book early to secure your preferred time.',
                        subtitle: 'Pick your preferred date and counsellor from the calendar.',
                        tag: 'Weekly Updates',
                        image: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1400&q=80',
                    },
                    {
                        title: 'Need to change time? Use Booking History to reschedule your active appointment.',
                        subtitle: 'Keep your session on track with quick, guided rescheduling.',
                        tag: 'Booking Tips',
                        image: 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=1400&q=80',
                    },
                    {
                        title: 'Check your inbox regularly for OTP and reminder notifications before your session.',
                        subtitle: 'Stay informed and never miss important counselling updates.',
                        tag: 'Reminder',
                        image: 'https://images.unsplash.com/photo-1517022812141-23620dba5c23?auto=format&fit=crop&w=1400&q=80',
                    }
                ];
                const slides = Array.isArray(items) && items.length > 0 ?
                    items.map((item, index) => ({
                        title: typeof item === 'string' ? item : (item.message || ''),
                        subtitle: fallbackSlides[index % fallbackSlides.length].subtitle,
                        tag: fallbackSlides[index % fallbackSlides.length].tag,
                        image: (typeof item === 'object' && item && item.image) ? item.image : fallbackSlides[
                            index % fallbackSlides.length].image,
                    })) :
                    fallbackSlides;

                if (slide && slideImage && slideTitle && slideSubtitle && slideTag && slides.length > 0) {
                    let idx = 0;
                    const renderSlide = (item) => {
                        slide.classList.remove('slide-fade');
                        void slide.offsetWidth;
                        slideImage.src = item.image;
                        slideTitle.textContent = item.title;
                        slideSubtitle.textContent = item.subtitle;
                        slideTag.textContent = item.tag;
                        slide.classList.add('slide-fade');
                    };

                    renderSlide(slides[idx]);
                    window.setInterval(() => {
                        idx = (idx + 1) % slides.length;
                        renderSlide(slides[idx]);
                    }, 6000);
                }
                const upcomingSessionSlider = document.getElementById('upcoming-session-slider');
                if (upcomingSessionSlider) {
                    const dateNode = document.getElementById('upcoming-session-date');
                    const statusNode = document.getElementById('upcoming-session-status');
                    const timeNode = document.getElementById('upcoming-session-time');
                    const counsellorNode = document.getElementById('upcoming-session-counsellor');
                    const progressBar = document.getElementById('upcoming-slide-progress-bar');
                    let entries = [];

                    try {
                        entries = JSON.parse(upcomingSessionSlider.dataset.entries || '[]');
                    } catch (_error) {
                        entries = [];
                    }

                    if (Array.isArray(entries) && entries.length > 1 && dateNode && statusNode && timeNode &&
                        counsellorNode) {
                        let upcomingIdx = 0;
                        const themes = ['upcoming-theme-a', 'upcoming-theme-b', 'upcoming-theme-c'];
                        const renderUpcoming = (entry, idx = 0) => {
                            upcomingSessionSlider.classList.remove(...themes, 'upcoming-theme-emergency');
                            const themeClass = entry.is_emergency ? 'upcoming-theme-emergency' : themes[idx % themes
                                .length];
                            upcomingSessionSlider.classList.add(themeClass);
                            upcomingSessionSlider.classList.remove('is-animating');
                            void upcomingSessionSlider.offsetWidth;
                            upcomingSessionSlider.classList.add('is-animating');
                            if (progressBar) {
                                progressBar.classList.remove('is-running');
                                void progressBar.offsetWidth;
                                progressBar.classList.add('is-running');
                            }

                            dateNode.textContent = entry.date || 'Date pending';
                            statusNode.textContent = `Status: ${entry.status || 'Pending'}`;

                            if (entry.time) {
                                timeNode.textContent = `Time: ${entry.time}`;
                                timeNode.classList.remove('hidden');
                            } else {
                                timeNode.classList.add('hidden');
                            }

                            if (entry.counsellor) {
                                counsellorNode.textContent = `Counsellor: ${entry.counsellor}`;
                                counsellorNode.classList.remove('hidden');
                            } else {
                                counsellorNode.classList.add('hidden');
                            }
                        };

                        renderUpcoming(entries[upcomingIdx], upcomingIdx);
                        window.setInterval(() => {
                            upcomingIdx = (upcomingIdx + 1) % entries.length;
                            renderUpcoming(entries[upcomingIdx], upcomingIdx);
                        }, 4500);
                    }
                }


                const malaysiaTimeFormatter = new Intl.DateTimeFormat('en-US', {
                    timeZone: 'Asia/Kuala_Lumpur',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true,
                });

                const currentTimeDisplay = document.getElementById('current-time-display');
                const statusUpdatedTime = document.getElementById('status-updated-time');

                const syncMalaysiaTime = () => {
                    const formattedTime = malaysiaTimeFormatter.format(new Date());
                    if (currentTimeDisplay) currentTimeDisplay.textContent = formattedTime;
                    if (statusUpdatedTime) statusUpdatedTime.textContent = formattedTime;
                };

                syncMalaysiaTime();
                window.setInterval(syncMalaysiaTime, 1000);

                const calendarGrid = document.getElementById('calendar-grid');
                const calendarTitle = document.getElementById('calendar-title');
                const prevBtn = document.getElementById('calendar-prev');
                const nextBtn = document.getElementById('calendar-next');
                const calendarContent = document.getElementById('calendar-content');
                const calendarSizeToggleBtn = document.getElementById('calendar-toggle-size');
                const sidebar = document.getElementById('home-sidebar');
                const sidebarToggle = document.getElementById('sidebar-toggle');
                const sidebarClose = document.getElementById('sidebar-close');
                const sidebarBackdrop = document.getElementById('sidebar-backdrop');

                const modal = document.getElementById('schedule-modal');
                const modalTitle = document.getElementById('schedule-modal-title');
                const modalBody = document.getElementById('schedule-modal-body');
                const modalClose = document.getElementById('schedule-modal-close');
                const logoutForm = document.getElementById('logout-form');
                const logoutModal = document.getElementById('logout-modal');
                const logoutCancel = document.getElementById('logout-cancel');
                const logoutConfirm = document.getElementById('logout-confirm');

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

                    if (logoutCancel) {
                        logoutCancel.addEventListener('click', closeLogoutModal);
                    }

                    if (logoutConfirm) {
                        logoutConfirm.addEventListener('click', () => logoutForm.submit());
                    }

                    logoutModal.addEventListener('click', (event) => {
                        if (event.target === logoutModal) closeLogoutModal();
                    });

                    document.addEventListener('keydown', (event) => {
                        if (event.key === 'Escape') closeLogoutModal();
                    });
                }

                if (!calendarGrid || !calendarTitle || !prevBtn || !nextBtn) {
                    return;
                }
                const setCalendarContentState = (isExpanded) => {
                    if (!calendarContent || !calendarSizeToggleBtn) return;
                    calendarContent.classList.toggle('is-collapsed', !isExpanded);
                    calendarSizeToggleBtn.textContent = isExpanded ? '−' : '+';
                    calendarSizeToggleBtn.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                    calendarSizeToggleBtn.setAttribute('aria-label', isExpanded ? 'Minimize calendar content' :
                        'Maximize calendar content');
                };

                if (calendarContent && calendarSizeToggleBtn) {
                    setCalendarContentState(false);
                    calendarSizeToggleBtn.addEventListener('click', () => {
                        const isExpanded = calendarSizeToggleBtn.getAttribute('aria-expanded') !== 'true';
                        setCalendarContentState(isExpanded);
                    });
                }


                const closeSidebar = () => {
                    if (!sidebar || !sidebarBackdrop) return;
                    sidebar.classList.remove('is-open');
                    sidebarBackdrop.classList.remove('is-open');
                };


                const openSidebar = () => {
                    if (!sidebar || !sidebarBackdrop) return;
                    sidebar.classList.add('is-open');
                    sidebarBackdrop.classList.add('is-open');
                };

                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', openSidebar);
                }
                if (sidebarClose) {
                    sidebarClose.addEventListener('click', closeSidebar);
                }
                if (sidebarBackdrop) {
                    sidebarBackdrop.addEventListener('click', closeSidebar);
                }

                const rawCounsellorNames = @json($counsellorNames ?? []);
                const rawBookingSlots = @json($bookingSlots ?? []);
                const rawUserActiveBookings = @json($userActiveBookings ?? []);
                const counsellors = Array.isArray(rawCounsellorNames) ? rawCounsellorNames : Object.values(
                    rawCounsellorNames || {});
                const bookingSlots = Array.isArray(rawBookingSlots) ? rawBookingSlots : Object.values(
                    rawBookingSlots || {});
                const userActiveBookings = Array.isArray(rawUserActiveBookings) ? rawUserActiveBookings : Object.values(
                    rawUserActiveBookings || {});
                const availableCounsellors = counsellors.filter(Boolean).length ? counsellors.filter(Boolean) : [
                    'Counsellor'
                ];
                const bookingPageUrl = @json(route('booking.index'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const statusClass = {
                    Available: 'text-emerald-700 bg-emerald-50 border-emerald-200',
                    Booked: 'text-sky-700 bg-sky-50 border-sky-200',
                    Pending: 'text-amber-700 bg-amber-50 border-amber-200',
                    Full: 'text-rose-700 bg-rose-50 border-rose-200',
                };

                let activeDate = new Date();
                let selectedScheduleDate = null;

                const buildHourlySlots = (startHour, endHour) => {
                    const slots = [];
                    for (let hour = startHour; hour < endHour; hour++) {
                        const from = String(hour).padStart(2, '0');
                        const to = String(hour + 1).padStart(2, '0');
                        slots.push(`${from}:00 - ${to}:00`);
                    }
                    return slots;
                };

                const getSlotTimesForDate = (date) => {
                    const day = date.getDay();
                    if (day === 5) return buildHourlySlots(8, 12);
                    if (day >= 1 && day <= 4) return buildHourlySlots(8, 17);
                    return [];
                };

                const bookedSlotsByKey = new Map(
                    bookingSlots.map((slot) => [
                        `${slot.date}|${slot.time}|${slot.counsellor}`,
                        slot.status === 'pending' ? 'Pending' : 'Booked'
                    ])
                );
                const formatDateForApi = (date) => {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');

                    return `${year}-${month}-${day}`;
                };

                const slotKey = (date, time, counsellor) =>
                    `${formatDateForApi(date)}|${time}|${counsellor}`;
                const computedStatus = (date, time, counsellor) => bookedSlotsByKey.get(slotKey(date, time,
                        counsellor)) ??
                    'Available';

                const getSlotStatusMeta = (date, time) => {
                    const occupied = availableCounsellors
                        .map((counsellor) => ({
                            counsellor,
                            status: computedStatus(date, time, counsellor),
                        }))
                        .filter((item) => item.status !== 'Available');

                    if (occupied.length === 0) {
                        return {
                            counsellorLabel: '-',
                            status: 'Available',
                        };
                    }

                    const allPending = occupied.every((item) => item.status === 'Pending');

                    return {
                        counsellorLabel: occupied.map((item) => item.counsellor).join(', '),
                        status: allPending ? 'Pending' : 'Booked',
                    };
                };

                const getDailyStatus = (date) => {
                    if (date.getDay() === 0 || date.getDay() === 6) return null;

                    const slotTimes = getSlotTimesForDate(date);
                    const slotStatuses = slotTimes.map((time) => getSlotStatusMeta(date, time).status);

                    if (slotStatuses.some((status) => status === 'Available')) return 'Available';
                    if (slotStatuses.some((status) => status === 'Pending')) return 'Pending';
                    return 'Full';
                };

                const renderScheduleRows = (date) => {
                    modalBody.innerHTML = '';
                    const slotTimes = getSlotTimesForDate(date);

                    if (!slotTimes.length) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">
                                Tiada slot kaunseling pada hujung minggu.
                            </td>
                        `;
                        modalBody.appendChild(tr);
                        return;
                    }

                    slotTimes.forEach((time) => {
                        const {
                            counsellorLabel,
                            status
                        } = getSlotStatusMeta(date, time);
                        const actionMarkup =
                            `<span class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-500">View only</span>`;
                        const tr = document.createElement('tr');
                        tr.className = 'group';
                        tr.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-slate-700 bg-white border-y border-l border-slate-200 rounded-l-xl group-hover:border-sky-200 transition">${time}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700 bg-white border-y border-slate-200 group-hover:border-sky-200 transition">${counsellorLabel}</td>
                            <td class="px-6 py-4 text-center bg-white border-y border-slate-200 group-hover:border-sky-200 transition">
                                <span class="inline-flex min-w-[104px] justify-center rounded-full border px-3 py-1 text-sm font-semibold ${statusClass[status]}">${status}</span>
                            </td>
                            <td class="px-6 py-4 text-center bg-white border-y border-r border-slate-200 rounded-r-xl group-hover:border-sky-200 transition">${actionMarkup}</td>
                        `;
                        modalBody.appendChild(tr);
                    });
                };

                const openModal = (date) => {
                    if (!modal || !modalTitle || !modalBody) {
                        return;
                    }
                    selectedScheduleDate = new Date(date);
                    modalTitle.textContent =
                        `Jadual Kaunselor • ${date.toLocaleDateString('en-GB', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })}`;
                    renderScheduleRows(date);
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                };

                const closeModal = () => {
                    if (!modal) {
                        return;
                    }
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                };

                if (modal && modalClose) {
                    modalClose.addEventListener('click', closeModal);
                    modal.addEventListener('click', (event) => {
                        if (event.target === modal) closeModal();
                    });
                }

                const renderCalendar = () => {
                    const year = activeDate.getFullYear();
                    const month = activeDate.getMonth();
                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);
                    const startOffset = firstDay.getDay();

                    calendarTitle.textContent = firstDay.toLocaleDateString('en-GB', {
                        month: 'long',
                        year: 'numeric'
                    });
                    calendarGrid.innerHTML = '';

                    for (let i = 0; i < startOffset; i++) {
                        const pad = document.createElement('div');
                        pad.className =
                            'min-h-14 sm:min-h-20 bg-slate-50 rounded-lg sm:rounded-xl border border-slate-200/70';
                        calendarGrid.appendChild(pad);
                    }

                    const today = new Date();
                    const isCurrentMonth = today.getFullYear() === year && today.getMonth() === month;

                    for (let day = 1; day <= lastDay.getDate(); day++) {
                        const cellDate = new Date(year, month, day);
                        const status = getDailyStatus(cellDate);
                        const isWeekend = cellDate.getDay() === 0 || cellDate.getDay() === 6;
                        const isToday = isCurrentMonth && today.getDate() === day;
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className =
                            `min-h-20 sm:min-h-28 p-1.5 sm:p-2.5 text-left border border-slate-200 rounded-lg sm:rounded-xl transition flex flex-col justify-between ${
                                isWeekend ? 'bg-slate-50 cursor-default' : 'bg-white hover:bg-sky-50'
                            }`;
                        button.innerHTML = `
                         <p class="font-semibold text-xs sm:text-sm leading-none ${isToday ? 'text-sky-700' : 'text-slate-700'}">${day}</p>
                            ${status ? `<span class="mt-1 inline-flex rounded-full border px-1.5 sm:px-2 py-0.5 text-[9px] sm:text-[10px] ${statusClass[status]}">${status}</span>` : '<span class="mt-1 text-[9px] sm:text-[10px] text-slate-400">Weekend</span>'}
                        `;
                        if (isToday) {
                            button.classList.add('ring-2', 'ring-sky-200', 'ring-inset');
                        }
                        if (!isWeekend) {
                            button.addEventListener('click', () => openModal(cellDate));
                        }
                        calendarGrid.appendChild(button);
                    }

                    const totalCells = startOffset + lastDay.getDate();
                    const trailingPads = (7 - (totalCells % 7)) % 7;
                    for (let i = 0; i < trailingPads; i++) {
                        const pad = document.createElement('div');
                        pad.className =
                            'min-h-20 sm:min-h-28 bg-slate-50 rounded-lg sm:rounded-xl border border-slate-200/70';
                        calendarGrid.appendChild(pad);
                    }
                };

                prevBtn.addEventListener('click', () => {
                    activeDate = new Date(activeDate.getFullYear(), activeDate.getMonth() - 1, 1);
                    renderCalendar();
                });

                nextBtn.addEventListener('click', () => {
                    activeDate = new Date(activeDate.getFullYear(), activeDate.getMonth() + 1, 1);
                    renderCalendar();
                });

                renderCalendar();
            });
        </script>

    </div>

    <div id="schedule-modal"
        class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-[70] p-3 sm:p-6">
        <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
            <div
                class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
                <h3 id="schedule-modal-title" class="text-lg font-semibold text-slate-800">Jadual Kaunselor</h3>
                <button id="schedule-modal-close"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm hover:border-sky-200 hover:text-sky-700">
                    Tutup
                </button>
            </div>
            <div class="max-h-[65vh] overflow-auto">
                <table class="w-full border-separate [border-spacing:0_10px] text-base">
                    <thead class="sticky top-0 z-10 bg-white/95 text-slate-600 backdrop-blur">
                        <tr>
                            <th
                                class="w-[20%] px-6 py-3 text-left border-b border-slate-200 text-[12px] font-bold uppercase tracking-[0.12em]">
                                Masa
                            </th>
                            <th
                                class="w-[30%] px-6 py-3 text-left border-b border-slate-200 text-[12px] font-bold uppercase tracking-[0.12em]">
                                Kaunselor
                            </th>
                            <th
                                class="w-[20%] px-6 py-3 text-center border-b border-slate-200 text-[12px] font-bold uppercase tracking-[0.12em]">
                                Status
                            </th>
                            <th
                                class="w-[30%] px-6 py-3 text-center border-b border-slate-200 text-[12px] font-bold uppercase tracking-[0.12em]">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody id="schedule-modal-body" class="text-slate-700"></tbody>
                </table>
            </div>
        </div>
    </div>


    <div id="logout-modal" class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-[80] p-4">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
            <h3 class="text-lg font-semibold text-slate-800">Confirm logout</h3>
            <p class="mt-2 text-sm text-slate-600">Are you sure you want to logout?</p>
            <div class="mt-5 flex justify-end gap-2">
                <button id="logout-cancel" type="button"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:border-sky-200 hover:text-sky-700 transition">
                    Cancel
                </button>
                <button id="logout-confirm" type="button"
                    class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">
                    Yes, logout
                </button>
            </div>
        </div>
    </div>
</body>

</html>
