<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification
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
        // dd($this->details);
        return (new MailMessage)
        ->subject($this->details['subject'])
        ->greeting($this->details['greeting'])
        ->line($this->details['body'])
        ->line('Total Amount: $'.$this->details['Order']->total)
        ->line('======= Customer Details ========')
        ->line('Name: '.$this->details['Order']->firstName.' '.$this->details['Order']->lastName)
        ->line('Email: '.$this->details['Order']->email ?? '')
        ->line('Address: '.$this->details['Order']->address ?? '')
        ->line('Postal Code: '.$this->details['Order']->zip ?? '')
        ->line('Mobile: '.$this->details['Order']->mobile ?? '')
        ->line('City: '.$this->details['Order']->city ?? '')
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
