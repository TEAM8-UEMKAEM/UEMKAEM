<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentVerificationController extends Controller
{
    public function index()
    {
        $pendingPayments = Payment::with(['order.buyer'])
            ->where('status', PaymentStatus::PENDING)
            ->oldest()
            ->get();

        return view('admin.payments.index', compact('pendingPayments'));
    }

    public function verify(Request $request, Payment $payment)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:255',
        ]);

        $order = $payment->order;

        if ($request->action === 'approve') {
            $payment->update([
                'status' => PaymentStatus::APPROVED,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);
            $order->update(['status' => OrderStatus::PAID]);
            $message = 'Payment approved successfully.';
        } else {
            $payment->update([
                'status' => PaymentStatus::REJECTED,
                'rejection_reason' => $request->rejection_reason,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);
            $order->update(['status' => OrderStatus::PENDING_PAYMENT]);
            $message = 'Payment rejected.';
        }
        
        $order->buyer->notify(new \App\Notifications\OrderPaymentProcessed($payment));

        return back()->with('success', $message);
    }
}