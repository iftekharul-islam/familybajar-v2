<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $reset_token;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reset_token, $user)
    {
        $this->reset_token = $reset_token;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function build()
    {
        return $this->subject('Password Reset')
            ->markdown('mails.resetLink', [
                'rem_token' => $this->reset_token,
                'user' => $this->user,
                'url' => env('APP_URL') . 'reset-password?token=' . $this->reset_token
            ]);
    }
}
