<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
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

        <div class="aurora-band aurora-band--one"></div>
        <div class="aurora-band aurora-band--two"></div>
    </div>

    <main class="min-h-screen flex items-center justify-center p-4 sm:p-8">
        <section
            class="w-full max-w-5xl rounded-[2rem] border border-slate-200/80 bg-white/75 backdrop-blur-xl shadow-2xl overflow-hidden">
            <div class="p-5 sm:p-7 border-b border-slate-200/80 flex items-center justify-between gap-3 bg-white/80">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Create account</h1>
                </div>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">
                    <span>←</span>
                    <span>Back</span>
                </a>
            </div>

            <div class="p-6 sm:p-8"
                x-data="{ role: '{{ old('role', 'student') }}', showPassword: false, showConfirm: false, profilePreview: null, profileFileName: '' }">
                <div class="mx-auto max-w-3xl rounded-3xl border border-slate-200 bg-white/90 p-6 sm:p-8 shadow-sm">
                    <form class="mt-1 grid gap-4" action="{{ route('signup.store') }}" method="POST"
                        enctype="multipart/form-data"> @csrf
                        <div class="flex flex-col items-center gap-2.5">
                            <input id="profile_pic" name="profile_pic" type="file" accept=".jpg,.jpeg,.png,.webp" class="hidden"
                                x-ref="profilePicInput"
                                x-on:change="const file = $event.target.files?.[0]; profileFileName = file ? file.name : ''; if (!file) { profilePreview = null; return; } const reader = new FileReader(); reader.onload = e => profilePreview = e.target?.result; reader.readAsDataURL(file);" />

                            <button type="button"
                                class="group relative mx-auto w-24 h-24 rounded-full border-2 border-sky-200 bg-sky-50 grid place-items-center text-sky-700 font-bold text-2xl overflow-hidden transition hover:border-sky-300 hover:bg-sky-100"
                                x-on:click="$refs.profilePicInput.click()" aria-label="Upload profile photo">
                                <img x-show="profilePreview" x-bind:src="profilePreview" alt="Profile picture preview"
                                    class="absolute inset-0 w-full h-full object-cover" />
                                <span x-show="!profilePreview" class="leading-none">+</span>
                            </button>

                            <p class="text-center text-xs text-slate-500">Profile photo (optional)</p>



                            @error('profile_pic')
                                <p class="text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-slate-700 mb-1.5">Full
                                    Name</label>
                                <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}"
                                    placeholder="Enter your full name"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                                @error('full_name')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">No.
                                    Phone</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                                    placeholder="01X-XXXXXXX"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                                @error('phone')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}"
                                    placeholder="name@college.edu"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                                @error('email')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="role"
                                    class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                                <select id="role" name="role" x-model="role"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition bg-white">
                                    <option value="student">Student</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="counsellor">Counsellor</option>
                                </select>
                                @error('role')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4" x-show="role === 'student'" x-transition>
                            <div>
                                <label for="years"
                                    class="block text-sm font-medium text-slate-700 mb-1.5">Years</label>
                                <select id="years" name="years" x-bind:disabled="role !== 'student'"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition bg-white disabled:bg-slate-100 disabled:text-slate-400">
                                    <option value="">Select years</option>
                                    <option value="1SVM SEM1" @selected(old('years') === '1SVM SEM1')>1SVM SEM1</option>
                                    <option value="1SVM SEM2" @selected(old('years') === '1SVM SEM2')>1SVM SEM2</option>
                                    <option value="2SVM SEM3" @selected(old('years') === '2SVM SEM3')>2SVM SEM3</option>
                                    <option value="2SVM SEM4" @selected(old('years') === '2SVM SEM4')>2SVM SEM4</option>
                                    <option value="1DVM SEM1" @selected(old('years') === '1DVM SEM1')>1DVM SEM1</option>
                                    <option value="1DVM SEM2" @selected(old('years') === '1DVM SEM2')>1DVM SEM2</option>
                                    <option value="2DVM SEM3" @selected(old('years') === '2DVM SEM3')>2DVM SEM3</option>
                                    <option value="2DVM SEM4" @selected(old('years') === '2DVM SEM4')>2DVM SEM4</option>
                                </select>
                                @error('years')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="programme"
                                    class="block text-sm font-medium text-slate-700 mb-1.5">Programme</label>
                                <select id="programme" name="programme" x-bind:disabled="role !== 'student'"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition bg-white disabled:bg-slate-100 disabled:text-slate-400">
                                    <option value="">Select programme</option>
                                    <option value="IPD" @selected(old('programme') === 'IPD')>IPD</option>
                                    <option value="ISK" @selected(old('programme') === 'ISK')>ISK</option>
                                    <option value="MTK 1" @selected(old('programme') === 'MTK 1')>MTK 1</option>
                                    <option value="MTK 2" @selected(old('programme') === 'MTK 2')>MTK 2</option>
                                    <option value="MPI 1" @selected(old('programme') === 'MPI 1')>MPI 1</option>
                                    <option value="MPI 2" @selected(old('programme') === 'MPI 2')>MPI 2</option>
                                </select>
                                @error('programme')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password"
                                    class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                                <div class="relative">
                                    <input id="password" name="password"
                                        x-bind:type="showPassword ? 'text' : 'password'" placeholder="••••••••"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 pr-12 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                                    <button type="button" x-on:click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-2 my-auto h-8 px-2 rounded-lg text-xs font-medium text-slate-500 hover:text-sky-700 hover:bg-sky-50 transition"
                                        x-text="showPassword ? 'Hide' : 'Show'"></button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-slate-700 mb-1.5">Re-Enter Password</label>
                                <div class="relative">
                                    <input id="password_confirmation" name="password_confirmation"
                                        x-bind:type="showConfirm ? 'text' : 'password'" placeholder="••••••••"
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2.5 pr-12 text-sm outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition" />
                                    <button type="button" x-on:click="showConfirm = !showConfirm"
                                        class="absolute inset-y-0 right-2 my-auto h-8 px-2 rounded-lg text-xs font-medium text-slate-500 hover:text-sky-700 hover:bg-sky-50 transition"
                                        x-text="showConfirm ? 'Hide' : 'Show'"></button>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="mt-2 w-full rounded-xl bg-sky-600 text-white font-semibold py-2.5 hover:bg-sky-700 transition shadow-sm">
                            Sign Up
                        </button>
                    </form>

                    <p class="mt-4 text-sm text-center text-slate-500">Already have an account?
                        <a href="{{ route('login') }}" class="text-sky-700 hover:text-sky-800 font-medium">Login</a>
                    </p>
                </div>
            </div>

            <footer
                class="px-6 sm:px-8 py-4 border-t border-slate-200/80 text-center text-sm text-slate-500 bg-white/70">
                © {{ date('Y') }} CollegeCare • Counselling Booking System
            </footer>
        </section>
    </main>
</body>

</html>
