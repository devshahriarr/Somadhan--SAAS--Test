<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserLimitReached extends Notification
{
    use Queueable;

    protected $company;

    /**
     * Create a new notification instance.
     */
    public function __construct($company)
    {
        $this->company = $company;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('User Limit Reached for Your Company')
            ->line('Dear '.$notifiable->name.',')
            ->line('Your company, '.$this->company->name.', has reached its user limit of '.$this->company->userLimit->user_limit.' users.')
            ->line('To add more users, please consider upgrading your package.')
            ->action('Upgrade Now', url('/pricing'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'company_id' => $this->company->id,
            'company_name' => $this->company->name,
            'message' => 'User limit reached',
        ];
    }
}
