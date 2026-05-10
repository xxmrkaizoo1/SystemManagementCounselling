<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{

    public function messagesHub(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $hasAllowedRole = $user->roles()
            ->whereIn(DB::raw('LOWER(TRIM(name))'), ['student', 'teacher', 'lecturer', 'counsellor'])
            ->exists();

        abort_unless($hasAllowedRole, 403);

        $conversationMessages = ChatMessage::query()
            ->selectRaw('receiver_id as other_user_id, id as message_id')
            ->where('sender_id', $user->id)
            ->unionAll(
                ChatMessage::query()
                    ->selectRaw('sender_id as other_user_id, id as message_id')
                    ->where('receiver_id', $user->id)
            );

        $latestMessages = ChatMessage::query()
            ->select('chat_messages.*')
            ->joinSub(
                DB::query()
                    ->fromSub($conversationMessages, 'conversation_messages')
                    ->selectRaw('other_user_id, MAX(message_id) as latest_message_id')
                    ->groupBy('other_user_id'),
                'latest_conversations',
                'latest_conversations.latest_message_id',
                '=',
                'chat_messages.id'
            )
            ->with([
                'sender:id,name,full_name',
                'receiver:id,name,full_name',
            ])
            ->latest('chat_messages.id')
            ->limit(40)
            ->get();

        $messageCards = $latestMessages->map(function (ChatMessage $message) use ($user): array {
            $otherUser = $message->sender_id === $user->id ? $message->receiver : $message->sender;
            $name = trim((string) ($otherUser?->full_name ?: $otherUser?->name ?: 'Unknown user'));
            $topic = str($message->message)->limit(40, '…')->toString();
            $category = $message->created_at && $message->created_at->gt(now()->subDays(14)) ? 'active' : 'archived';

            return [
                'user_id' => $otherUser?->id,
                'name' => $name,
                'initial' => strtoupper(substr($name, 0, 1)),
                'topic' => $topic,
                'preview' => str($message->message)->limit(90, '…')->toString(),
                'time_ago' => $message->created_at?->diffForHumans() ?? 'Recently',
                'category' => $category,
                'search' => strtolower($name . ' ' . $message->message),
            ];
        })->filter(fn(array $card): bool => ! empty($card['user_id']))->values();

        $role = strtolower(trim((string) $user->roles()->value('name')));
        $userDisplayName = trim((string) ($user->full_name ?: $user->name));

        $requestCards = BookingRequest::query()
            ->with('user:id,name,full_name')
            ->when($role === 'counsellor', function ($query) use ($userDisplayName) {
                $query->where('status', 'pending')
                    ->where('counsellor_name', $userDisplayName);
            }, function ($query) use ($user) {
                $query->where('status', 'pending')
                    ->where('user_id', $user->id);
            })
            ->latest('created_at')
            ->limit(40)
            ->get()
            ->map(function (BookingRequest $request): array {
                $studentName = trim((string) ($request->user?->full_name ?: $request->user?->name ?: 'Student'));
                $topic = trim((string) ($request->topic ?: 'General support'));

                return [
                    'user_id' => $request->user_id,
                    'name' => $studentName,
                    'initial' => strtoupper(substr($studentName, 0, 1)),
                    'topic' => 'Session request: ' . $topic,
                    'preview' => $request->note
                        ? str($request->note)->limit(90, '…')->toString()
                        : 'New booking request for ' . ($request->booking_date ?: 'upcoming date') . ' at ' . ($request->booking_time ?: 'scheduled time') . '.',
                    'time_ago' => $request->created_at?->diffForHumans() ?? 'Recently',
                    'category' => 'request',
                    'search' => strtolower($studentName . ' ' . $topic . ' ' . ($request->note ?? '')),
                    'action_label' => 'Review Request',
                    'action_url' => route('counsellor.pending-requests'),
                ];
            });

        $combinedCards = $messageCards->concat($requestCards)->values();

        $counts = [
            'active' => $messageCards->where('category', 'active')->count(),
            'request' => $requestCards->count(),
            'archived' => $messageCards->where('category', 'archived')->count(),
        ];

        $newNotifications = $user->inboxNotifications()
            ->where('title', 'New chat message')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return view('messages', [
            'messageCards' => $combinedCards,
            'counts' => $counts,
            'newNotifications' => $newNotifications,
        ]);
    }
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $role = $user->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher', 'lecturer', 'counsellor'], true), 403);

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

        $conversationMessages = ChatMessage::query()
            ->selectRaw('receiver_id as other_user_id, id as message_id')
            ->where('sender_id', $user->id)
            ->unionAll(
                ChatMessage::query()
                    ->selectRaw('sender_id as other_user_id, id as message_id')
                    ->where('receiver_id', $user->id)
            );

        $conversationUsers = User::query()
            ->select('users.*')
            ->joinSub(
                DB::query()
                    ->fromSub($conversationMessages, 'conversation_messages')
                    ->selectRaw('other_user_id, MAX(message_id) as latest_message_id')
                    ->groupBy('other_user_id'),
                'latest_conversations',
                'latest_conversations.other_user_id',
                '=',
                'users.id'
            )
            ->orderByDesc('latest_conversations.latest_message_id')
            ->limit(10)
            ->get();

        $selectedUser = null;
        $messages = collect();
        $bookingRecords = collect();
        $currentBookingNotes = collect();

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

                if ($role === 'counsellor') {
                    $counsellorName = trim((string) ($user->full_name ?: $user->name));
                    $selectedUserBookings = BookingRequest::query()
                        ->where('user_id', $selectedUser->id)
                        ->where('counsellor_name', $counsellorName)
                        ->latest('booking_date')
                        ->latest('booking_time')
                        ->limit(10)
                        ->get();

                    $bookingRecords = $selectedUserBookings->map(static function (BookingRequest $booking): array {
                        return [
                            'date' => (string) $booking->booking_date,
                            'time' => (string) $booking->booking_time,
                            'topic' => (string) ($booking->topic ?: 'General support'),
                            'status' => ucfirst((string) ($booking->status ?: 'pending')),
                        ];
                    });

                    $currentBookingNotes = $selectedUserBookings
                        ->where('status', 'pending')
                        ->take(5)
                        ->map(static fn(BookingRequest $booking): array => [
                            'date' => (string) $booking->booking_date,
                            'time' => (string) $booking->booking_time,
                            'note' => (string) ($booking->note ?: 'No note provided.'),
                        ])
                        ->values();
                }
            }
        }

        return view('chat', [
            'user' => $user,
            'role' => $role,
            'users' => $users,
            'search' => $search,
            'conversationUsers' => $conversationUsers,
            'selectedUser' => $selectedUser,
            'messages' => $messages,
            'bookingRecords' => $bookingRecords,
            'currentBookingNotes' => $currentBookingNotes,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $role = $user->roles()->value('name');

        abort_unless(in_array($role, ['student', 'teacher', 'lecturer', 'counsellor'], true), 403);

        $validated = $request->validate([
            'receiver_id' => ['required', 'integer', 'exists:users,id', 'not_in:' . $user->id],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        ChatMessage::create([
            'sender_id' => $user->id,
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'],
        ]);

        $receiver = User::find($validated['receiver_id']);

        if ($receiver) {
            $senderName = $user->full_name ?: $user->name;

            $receiver->inboxNotifications()->create([
                'title' => 'New chat message',
                'message' => "{$senderName} sent you a new message in Chat Box.",
            ]);

            $staleNotificationIds = $receiver->inboxNotifications()
                ->latest('id')
                ->get(['id'])
                ->slice(20)
                ->pluck('id');

            if ($staleNotificationIds->isNotEmpty()) {
                $receiver->inboxNotifications()->whereIn('id', $staleNotificationIds)->delete();
            }
        }

        return redirect()->route('chat.index', [
            'user_id' => $validated['receiver_id'],
        ])->with('status', 'Message sent successfully.');
    }
}
