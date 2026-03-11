<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProfileAccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $accessToken;
    public $userEmail;

    /**
     * Create a new message instance.
     */
    public function __construct($accessToken, $userEmail)
    {
        $this->accessToken = $accessToken;
        $this->userEmail = $userEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Comptafriq - 🔑 Réinitialisation de votre mot de passe',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'accessToken' => $this->accessToken,
                'userEmail' => $this->userEmail,
                'resetUrl' => url('/reset-password?token=' . $this->accessToken . '&email=' . $this->userEmail),
            ]
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
