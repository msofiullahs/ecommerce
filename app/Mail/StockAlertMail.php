<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Models\ProductVariant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ProductVariant $variant) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Low Stock Alert: {$this->variant->product->name}");
    }

    public function content(): Content
    {
        $template = EmailTemplate::where('slug', 'low_stock_alert')->first();
        $variables = [
            'product_name' => $this->variant->product->name,
            'variant_name' => $this->variant->name,
            'current_stock' => (string) $this->variant->stock,
            'threshold' => (string) $this->variant->low_stock_threshold,
        ];

        $body = $template
            ? $this->replacePlaceholders($template->body, $variables)
            : "<p>Low stock: {$this->variant->product->name} - {$this->variant->name} ({$this->variant->stock} remaining)</p>";

        return new Content(view: 'emails.template', with: ['body' => $body]);
    }

    private function replacePlaceholders(string $text, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $text = str_replace("{{$key}}", $value, $text);
        }
        return $text;
    }
}
