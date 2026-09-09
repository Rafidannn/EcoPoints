<?php

namespace App\Notifications;

use App\Models\RewardRedemption;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RewardRedeemedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public RewardRedemption $redemption
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Penukaran Reward Berhasil!',
            'message' => "Anda telah menukarkan reward {$this->redemption->reward?->name} senilai ".number_format($this->redemption->points_used).' EcoPoints.',
            'url' => route('rewards.history'),
            'type' => 'reward_redeemed',
            'redemption_id' => $this->redemption->id,
        ];
    }
}
