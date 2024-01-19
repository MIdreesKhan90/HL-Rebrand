<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MagicLink extends Notification
{
    use Queueable;

    protected $magicLink;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($magicLink)
    {
        $this->magicLink = $magicLink;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Use your custom email view and pass the magic link to it
        return (new MailMessage)
            ->subject("Here's a link to help you log in instantly External.")
            ->markdown('mail.magicLink', ['magicLink' => $this->magicLink]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
