<?php

namespace App\Mail;

use App\Models\QuoteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteRequestSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public QuoteRequest $quoteRequest) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New quote request from '.$this->quoteRequest->full_name,
            replyTo: [
                new Address($this->quoteRequest->email, $this->quoteRequest->full_name),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quote-request-submitted',
        );
    }
}
