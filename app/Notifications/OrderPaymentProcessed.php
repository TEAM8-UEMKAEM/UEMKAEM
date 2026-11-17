<?php

namespace App\Notifications;

use App\Models\Payment;
use App\Enums\PaymentStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaymentProcessed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Payment $payment)
    {
    }
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }
    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->payment->status === PaymentStatus::APPROVED ? 'Approved' : 'Rejected';
        
        $message = (new MailMessage)
            ->subject("Payment {$status} - Order #{$this->payment->order_id}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your recent payment proof for Order #{$this->payment->order_id} has been {$status}.");

        if ($this->payment->status === PaymentStatus::REJECTED) {
            $message->line("Reason: " . $this->payment->rejection_reason)
                    ->line("Please review the feedback and upload a new payment proof.");
        } else {
            $message->line("Your order is now being processed for shipping.");
        }

        return $message->action('View Order Details', route('buyer.orders.payment.show', $this->payment->order_id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->payment->order_id,
            'status' => $this->payment->status->value,
            'message' => $this->payment->status === PaymentStatus::APPROVED 
                ? "Payment for Order #{$this->payment->order_id} was approved." 
                : "Payment for Order #{$this->payment->order_id} was rejected: " . $this->payment->rejection_reason,
        ];
    }
}