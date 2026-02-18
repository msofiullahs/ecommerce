<?php

namespace App\Mail;

use App\Enums\OrderStatus;
use App\Models\EmailTemplate;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusChangedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public OrderStatus $oldStatus,
        public OrderStatus $newStatus,
    ) {}

    public function envelope(): Envelope
    {
        $template = EmailTemplate::where('slug', 'order_status_changed')->first();
        $subject = $template
            ? $this->replacePlaceholders($template->subject, $this->getVariables())
            : "Order #{$this->order->order_number} Status Update";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        $template = EmailTemplate::where('slug', 'order_status_changed')->first();
        $body = $template
            ? $this->replacePlaceholders($template->body, $this->getVariables())
            : '<p>Your order status has been updated.</p>';

        return new Content(view: 'emails.template', with: ['body' => $body]);
    }

    private function getVariables(): array
    {
        return [
            'order_number' => $this->order->order_number,
            'customer_name' => $this->order->user?->name ?? $this->order->shipping_name,
            'old_status' => $this->oldStatus->label(),
            'new_status' => $this->newStatus->label(),
        ];
    }

    private function replacePlaceholders(string $text, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $text = str_replace("{{$key}}", $value, $text);
        }
        return $text;
    }
}
