<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chat Box • CollegeCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-700 overflow-x-hidden">
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_#e0f2fe_0%,_#f8fafc_35%,_#f1f5f9_100%)]"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
        <div class="absolute inset-0 bg-noise-layer opacity-15"></div>
    </div>

    <main class="min-h-screen p-4 sm:p-8">
        <section class="max-w-6xl mx-auto rounded-[2rem] border border-slate-200/80 bg-white/75 backdrop-blur-xl shadow-2xl overflow-hidden">
            <header class="px-5 sm:px-7 py-4 border-b border-slate-200/80 bg-white/80 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">CollegeCare</p>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Chat Box</h1>
                    <p class="text-sm text-slate-500 mt-1">{{ $user->full_name ?: $user->name }} • {{ ucfirst($role) }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('home.session') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 hover:text-sky-700 hover:border-sky-200 transition">Home Session</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-xl bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">Logout</button>
                    </form>
                </div>
            </header>

            <div class="p-5 sm:p-7 space-y-4">
                @if (session('status'))
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="grid lg:grid-cols-[240px_1fr] gap-5">
                    <aside class="rounded-2xl border border-slate-200 bg-white/85 p-4 shadow-sm space-y-4">
                        <p class="text-xs uppercase tracking-[0.12em] text-slate-500">Menu</p>
                        <nav class="space-y-2 text-sm">
                            <a href="{{ route('inbox') }}" class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Inbox</a>
                            <a href="{{ route('chat.index') }}" class="block rounded-xl bg-sky-600 text-white px-3 py-2 font-medium">Chat Box</a>
                            <a href="{{ route('profile.edit') }}" class="block rounded-xl border border-slate-200 bg-white px-3 py-2 hover:border-sky-200 hover:text-sky-700 transition">Edit Profile</a>
                        </nav>

                        <form method="GET" action="{{ route('chat.index') }}" class="space-y-2">
                            <label for="search" class="text-xs uppercase tracking-[0.12em] text-slate-500">Search user</label>
                            <input id="search" type="text" name="search" value="{{ $search }}" placeholder="Name / email"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-400 focus:ring-sky-200" />
                            <button type="submit" class="w-full rounded-xl border border-sky-200 bg-sky-50 px-3 py-2 text-sm font-semibold text-sky-700 hover:bg-sky-100 transition">Search</button>
                        </form>

                        <div class="space-y-2 max-h-80 overflow-auto pr-1">
                            @forelse ($users as $listedUser)
                                <a href="{{ route('chat.index', ['user_id' => $listedUser->id, 'search' => $search]) }}"
                                    class="block rounded-xl border px-3 py-2 text-sm transition {{ $selectedUser?->id === $listedUser->id ? 'border-sky-300 bg-sky-50 text-sky-700' : 'border-slate-200 bg-white hover:border-sky-200' }}">
                                    <p class="font-semibold">{{ $listedUser->full_name ?: $listedUser->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $listedUser->email }}</p>
                                </a>
                            @empty
                                <p class="text-sm text-slate-500">No users found.</p>
                            @endforelse
                        </div>
                    </aside>

                    <section class="rounded-2xl border border-slate-200 bg-white/90 p-4 sm:p-6 shadow-sm min-h-[28rem] flex flex-col">
                        @if (!$selectedUser)
                            <div class="flex-1 grid place-items-center text-center">
                                <div>
                                    <div class="text-4xl">💬</div>
                                    <h2 class="mt-3 text-lg font-semibold text-slate-800">Select a user to start chat</h2>
                                    <p class="text-sm text-slate-500 mt-1">Search for a student or teacher, then send your message.</p>
                                </div>
                            </div>
                        @else
                            <div class="pb-3 border-b border-slate-200">
                                <h2 class="text-lg font-semibold text-slate-800">Chat with {{ $selectedUser->full_name ?: $selectedUser->name }}</h2>
                                <p class="text-sm text-slate-500">{{ $selectedUser->email }}</p>
                            </div>

                            <div class="flex-1 my-4 space-y-3 overflow-auto pr-1">
                                @forelse ($messages as $message)
                                    <div class="flex {{ (int) $message->sender_id === (int) $user->id ? 'justify-end' : 'justify-start' }}">
                                        <article class="max-w-[80%] rounded-2xl px-4 py-2 {{ (int) $message->sender_id === (int) $user->id ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-700' }}">
                                            <p class="text-sm">{{ $message->message }}</p>
                                            <p class="mt-1 text-[11px] {{ (int) $message->sender_id === (int) $user->id ? 'text-sky-100' : 'text-slate-500' }}">
                                                {{ $message->created_at?->format('Y-m-d H:i') }}
                                            </p>
                                        </article>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No messages yet. Start the conversation below.</p>
                                @endforelse
                            </div>

                            <form method="POST" action="{{ route('chat.store') }}" class="space-y-2 border-t border-slate-200 pt-3">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}" />
                                <textarea name="message" rows="3" maxlength="3000" required
                                    placeholder="Type your message..."
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-400 focus:ring-sky-200">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                                <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700 transition">Send</button>
                            </form>
                        @endif
                    </section>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
