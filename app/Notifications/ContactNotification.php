<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactNotification extends Notification
{
    use Queueable;
    private $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
       
        return (new MailMessage)
        ->subject($this->details['subject'])
        ->greeting($this->details['greeting'])
        ->line($this->details['body'])
        ->line('======= Contact Details ========')
        ->line('Name: '.$this->details['contact']->first_name.' '.$this->details['contact']->last_name)
        ->line('Number: '.$this->details['contact']->phone ?? '')
        ->line('Email: '.$this->details['contact']->email ?? '')
        ->line('Message: '.$this->details['contact']->message ?? '')
        ->action($this->details['action_title'], $this->details['action_url'])
        ->line($this->details['thanks']);
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
