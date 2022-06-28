<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $content;
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        $subject = 'New Query/Message from '.@$this->content['first_name'].' '.@$this->content['last_name'] ?? '';
        return $this->subject($subject)->markdown('Mail.mails')->with('content',$this->content);
    }
}
