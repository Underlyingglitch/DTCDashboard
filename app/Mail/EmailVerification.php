<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $verifyUrl, protected $user)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->to($this->user->email)
            ->subject('Verifieer uw e-mail')->view('emails.verify', ['button' => ["Verifieer emailadres", $this->verifyUrl], 'user' => $this->user]);;
    }
}
