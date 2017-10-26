<?php

namespace App\Notifications;

use App\FilmsHistory;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Http\Request;

class SendEmailSubscription extends Notification implements ShouldQueue
{

    use Queueable;


    public function __construct()
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('films/unsubscribe?email=' . $notifiable->email . '&code=' . hashids()->encode($notifiable->id));

        return (new MailMessage)
            ->subject('Updates for ' . Carbon::now()->formatLocalized('%A %d %B %Y'))
            ->line('The introduction to the notification.')
            ->line('List of films')
            ->line('If you do not want to receive emails for this subscription you can unsubscribe here:')
            ->action('Unsubscribe me', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
