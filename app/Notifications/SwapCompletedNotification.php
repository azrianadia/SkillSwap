<?php

namespace App\Notifications;

use App\Models\Swap;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SwapCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(public Swap $swap)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $actor = $this->swap->sender_id === auth()->id() ? $this->swap->sender : $this->swap->receiver;
        
        return [
            'swap_id' => $this->swap->id,
            'actor_name' => $actor->name,
            'message' => $actor->name . ' menandai swap selesai. Anda bisa memberikan review.',
            'action_url' => route('swaps.index'),
        ];
    }
}