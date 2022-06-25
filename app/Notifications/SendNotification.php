<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendNotification extends Notification
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
                ->line('Donation Amount: $'.$this->details['donation']->donation_amount)
                ->line('Donation Type: '.$this->details['donation']->recurrence)
                ->line('Processing Fees: '.$this->details['donation']->processing_fees)
                ->line('On Behalf of: '.$this->details['donation']->behalf_of)
                ->line('Message: '.$this->details['donation']->message ?? '')
                ->line('======= Donor Details ========')
                ->line('Name: '.$this->details['donation']->first_name.' '.$this->details['donation']->last_name)
                ->line('Email: '.$this->details['donation']->email ?? '')
                ->line('Address: '.$this->details['donation']->address ?? '')
                ->line('Postal Code: '.$this->details['donation']->postal_code ?? '')
                ->line('Payment Method: '.$this->details['donation']->payment_method ?? '')
                ->line('Check Details: '.$this->details['donation']->check_details ?? '')
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

    public function toDatabase($notifiable)
    {
        return [
           'data' => $this->details['body']
        ];
    }
}
