<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

final class WorkspaceBroadcastNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $title,
        public readonly string $body,
        public readonly ?string $imageUrl,
        public readonly string $workspaceId
    ) {
    }

    public function via($notifiable): array
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable): FcmMessage
    {
        $notification = FcmNotification::create()
            ->title($this->title)
            ->body($this->body);

        if ($this->imageUrl) {
            $notification = $notification->image($this->imageUrl);
        }

        return FcmMessage::create()
            ->notification($notification)
            ->data([
                'type' => 'workspace_broadcast',
                'workspace_id' => $this->workspaceId,
            ]);
    }
}
