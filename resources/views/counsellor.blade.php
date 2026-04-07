<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>E-Health Dashboard • Counsellor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#bfe8e2] text-slate-700">
    <main class="mx-auto w-full max-w-[1280px] px-4 py-6 sm:px-8 sm:py-8">
        <nav class="rounded-[2.25rem] bg-white/95 px-6 py-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-6">
                    <div>
                        <p class="text-xs font-bold leading-tight text-[#2f3b72]">KOLEJ</p>
                        <p class="-mt-0.5 text-3xl font-black leading-none text-[#2f3b72]">VOKASIONAL</p>
                        <p class="text-[10px] tracking-wide text-[#dd4e4e]">KOLEJ VOKASIONAL GERIK</p>
                    </div>
                    <div class="hidden h-9 w-px bg-slate-200 sm:block"></div>
                    <div>
                        <p class="text-3xl font-black italic leading-none text-[#2f6f98]">E-HEALTH</p>
                        <p class="text-[11px] text-slate-500">Health is Wealth</p>
                    </div>
                </div>

                <ul class="flex flex-wrap items-center gap-x-6 gap-y-3 text-xl font-semibold text-slate-900">
                    <li class="flex items-center gap-2">🏠 <span>Utama</span></li>
                    <li class="flex items-center gap-2">📅 <span>Jadual Guru Bertugas</span></li>
                    <li class="flex items-center gap-2">☑️ <span>Borang Ke Hospital</span></li>
                    <li class="flex items-center gap-2">🔎 <span>Semak Status</span></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="font-semibold text-rose-500 hover:text-rose-600">↪ Log Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <section class="mx-auto mt-8 max-w-4xl rounded-3xl bg-white/95 px-6 py-10 text-center shadow-sm">
            <h1 class="text-5xl font-black uppercase tracking-wide text-[#22aaa6]">🪪 E-Health Dashboard</h1>
            <p class="mt-4 text-2xl text-slate-600">Selamat datang ke sistem E-Health. Sila pilih menu di bawah untuk tindakan lanjut.</p>
        </section>

        <section class="mx-auto mt-7 max-w-5xl rounded-lg bg-[#f3e4b3] px-6 py-5 text-[#8f6a00] shadow-sm">
            <p class="text-3xl leading-relaxed">
                <span class="font-black">ℹ️ NOTIS PEMBERITAHUAN:</span>
                Pelajar hanya dibenarkan berurusan di Hospital Gerik, Klinik Komuniti, Klinik Kesihatan dan Hospital Taiping.
                Pelajar hanya boleh dibenarkan pergi ke hospital hanya pada 2 waktu sahaja iaitu pada waktu <strong>9.00 pagi</strong> dan
                <strong>3.00 petang</strong>. Pihak asrama dan warden hanya boleh membawa pelajar ke hospital selepas waktu persekolahan dan
                hanya untuk kes kecemasan sahaja.
            </p>
        </section>

        <section class="mx-auto mt-8 grid max-w-5xl gap-6 lg:grid-cols-3">
            <article class="rounded-3xl bg-white/95 px-6 py-9 text-center shadow-sm">
                <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-sky-100 text-4xl text-[#22aaa6]">✏️</div>
                <div class="mx-auto mt-5 grid h-24 w-24 place-items-center rounded-full bg-slate-100 text-5xl">👤</div>
                <h2 class="mt-5 text-4xl font-bold text-[#20aaa5]">Kemaskini Profile</h2>
                <p class="mt-3 text-3xl text-slate-500">Sila masukkan maklumat dengan betul</p>
                <button class="mt-6 rounded-full bg-[#34b9b2] px-10 py-3 text-2xl font-extrabold tracking-wide text-white">PROFILE</button>
            </article>

            <article class="rounded-3xl bg-white/95 px-6 py-9 text-center shadow-sm">
                <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-sky-100 text-4xl text-[#22aaa6]">✅</div>
                <div class="mx-auto mt-5 grid h-24 w-24 place-items-center rounded-full bg-slate-100 text-5xl">📋</div>
                <h2 class="mt-5 text-4xl font-bold text-[#20aaa5]">Semak Status</h2>
                <p class="mt-3 text-3xl text-slate-500">Proses pengesahan disahkan sebelum masa yang ditetapkan untuk ke hospital</p>
                <button class="mt-6 rounded-full bg-[#34b9b2] px-10 py-3 text-2xl font-extrabold tracking-wide text-white">SEMAK STATUS/EDIT</button>
            </article>

            <article class="rounded-3xl bg-white/95 px-6 py-9 text-center shadow-sm">
                <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-sky-100 text-4xl text-[#22aaa6]">📅</div>
                <div class="mx-auto mt-5 grid h-24 w-24 place-items-center rounded-full bg-slate-100 text-5xl">🏥</div>
                <h2 class="mt-5 text-4xl font-bold text-[#20aaa5]">Hospital</h2>
                <p class="mt-3 text-3xl text-slate-500">Sila masukkan maklumat yang sah dan betul</p>
                <button class="mt-6 rounded-full bg-[#34b9b2] px-10 py-3 text-2xl font-extrabold tracking-wide text-white">BORANG KE HOSPITAL</button>
            </article>
        </section>
    </main>
</body>

</html>
