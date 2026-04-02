<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $role = $user->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);

        $search = trim((string) $request->query('search', ''));
        $selectedUserId = $request->integer('user_id');

        $usersQuery = User::query()
            ->whereKeyNot($user->id)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByRaw('COALESCE(full_name, name) asc')
            ->limit(20);

        $users = $usersQuery->get();

        $selectedUser = null;
        $messages = collect();

        if ($selectedUserId) {
            $selectedUser = User::query()
                ->whereKey($selectedUserId)
                ->whereKeyNot($user->id)
                ->first();

            if ($selectedUser) {
                $messages = ChatMessage::query()
                    ->where(function ($query) use ($user, $selectedUser) {
                        $query->where('sender_id', $user->id)
                            ->where('receiver_id', $selectedUser->id);
                    })
                    ->orWhere(function ($query) use ($user, $selectedUser) {
                        $query->where('sender_id', $selectedUser->id)
                            ->where('receiver_id', $user->id);
                    })
                    ->latest('id')
                    ->limit(100)
                    ->get()
                    ->reverse()
                    ->values();
            }
        }

        return view('chat', [
            'user' => $user,
            'role' => $role,
            'users' => $users,
            'search' => $search,
            'selectedUser' => $selectedUser,
            'messages' => $messages,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $role = $user->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher'], true), 403);

        $validated = $request->validate([
            'receiver_id' => ['required', 'integer', 'exists:users,id', 'not_in:'.$user->id],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        ChatMessage::create([
            'sender_id' => $user->id,
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'],
        ]);

        return redirect()->route('chat.index', [
            'user_id' => $validated['receiver_id'],
        ])->with('status', 'Message sent successfully.');
    }
}
