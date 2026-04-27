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
            background: linear-gradient(180deg, rgb(14 116 144 / 0.22) 0%, rgb(14 165 233 / 0.12) 55%, rgb(240 249 255 / 0.95) 100%);
            backdrop-filter: blur(10px);
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

        .status-card {
            border: 1px solid rgb(226 232 240);
            background: linear-gradient(180deg, rgb(248 250 252) 0%, rgb(255 255 255) 100%);
        }

        @media (min-width: 1280px) {
            .home-shell {
                flex-direction: row;
                align-items: flex-start;
            }

            .home-sidebar {
                width: 16rem;
                flex: 0 0 16rem;
                position: sticky;
                top: 1rem;
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
