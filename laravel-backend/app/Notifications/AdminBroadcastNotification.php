<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

/**
 * System-wide push notification sent by an administrator to every app user.
 * Delivered over FCM only (no in-app inbox persistence).
 */
final class AdminBroadcastNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $title,
        public readonly string $body,
        public readonly ?string $imageUrl = null
    ) {
    }

    /**
     * @return array<int, string>
     */
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
                'type' => 'admin_broadcast',
            ]);
    }
}
