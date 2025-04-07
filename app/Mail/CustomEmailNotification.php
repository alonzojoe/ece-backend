<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomEmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    // public $content;
    // public $subject;

    // public function __construct($subject, $content)
    // {
    //     $this->subject = $subject;
    //     $this->content = $content;
    // }


    // public function build()
    // {
    //     return $this->from('alertappnoreply@gmail.com')
    //         ->subject($this->subject)
    //         ->view('emails.custom_email')
    //         ->with(['content' => $this->content]);
    // }

    public $recipient;
    public $content;

    public function __construct($recipient, $content)
    {
        $this->recipient = $recipient;
        $this->content = $content;
    }

    public function build()
    {
        return $this->subject('System Alerts')
            ->view('emails.custom_email')
            ->with([
                'recipient' => $this->recipient,
                'content' => $this->content
            ]);
    }
}
