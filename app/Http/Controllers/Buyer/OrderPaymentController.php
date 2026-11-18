<?php

namespace App\Http\Controllers\Buyer;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderPaymentController extends Controller
{
    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403, 'Unauthorized action.');

        $order->load('payments');

        return view('buyer.payment.show', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        // Security check
        abort_if($order->user_id !== auth()->id(), 403, 'Unauthorized action.');
        
        abort_if(in_array($order->status, [OrderStatus::PAID, OrderStatus::PAYMENT_UNDER_REVIEW]), 400, 'Invalid order status for payment upload.');

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $order->payments()->create([
            'proof_path' => $path,
            'status' => PaymentStatus::PENDING,
        ]);

        $order->update(['status' => OrderStatus::PAYMENT_UNDER_REVIEW]);

        return back()->with('success', 'Payment proof uploaded successfully. Awaiting admin verification.');
    }
}