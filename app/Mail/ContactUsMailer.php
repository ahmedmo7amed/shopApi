<?php

namespace App\Mail;

use App\Models\ContactUs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactUsMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */


    public $contactUs;
    public function __construct(ContactUs $contactUs)
    {

        $this->contactUs = $contactUs;
    }

    public function build()
    {
//        return $this->view('emails.contact')
//            ->with([
//                'name' => $this->name,
//                'email' => $this->email,
//                'message' => $this->message,
//            ]);
        return $this->subject('Contact Us Submission')
            ->text('emails.contact-message') // Use a plain text email template
            ->with([
                'name' => $this->contactUs->name,
                'email' => $this->contactUs->email,
                'phone' => $this->contactUs->phone,
                'subject' => $this->contactUs->subject,
                'message' => $this->contactUs->message,
            ]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contact Us Mailer',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-message',
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
