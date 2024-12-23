<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Generic Email Notification 
 * 
 **/

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $view_path;
    public $object;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $view_path, $object)
    {
        //initialize variables
        $this->subject = $subject;
        $this->view_path = $view_path;
        $this->object = $object;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->view_path,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
