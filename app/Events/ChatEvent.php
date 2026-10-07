<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Chat $chat, public int $creatorId) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];
        foreach ($this->chat->users as $user) {
            if ($user->id !== $this->creatorId) {
                $channels[] = new PrivateChannel('user.'.$user->id);
            }
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'chat.created';
    }

    public function broadcastWith(): array
    {
        return ['chat' => [
            'id' => $this->chat->id,
            'name' => $this->chat->name,
            'is_group' => $this->chat->is_group,
            'users' => $this->chat->users->map(fn ($user) => [
                'id' => $user->id,
                'username' => $user->username,
            ])->all(),
        ]];
    }
}
