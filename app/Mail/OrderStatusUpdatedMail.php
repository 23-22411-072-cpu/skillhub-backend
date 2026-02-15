<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; 
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable implements ShouldQueue 
{
    use Queueable, SerializesModels;

    
    public $order;
    public $userRole; 

    /**
     * Create a new message instance.
     */
    public function __construct($order, $userRole) 
    {
        $this->order = $order;
        $this->userRole = $userRole;
    }

    /**
     * Get the message envelope (Email Subject).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            
            subject: 'SkillHub: Order #' . $this->order->id . ' Status Updated to ' . strtoupper($this->order->status),
        );
    }

    /**
     * Get the message content definition (Email View).
     */
    public function content(): Content
    {
        return new Content(
            
            markdown: 'emails.orders.status-updated',
            with: [
                'order' => $this->order,
                'userRole' => $this->userRole,
            ],
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