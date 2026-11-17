@extends('layouts.app')

@section('title', 'Pembayaran Pesanan #' . $order->id . ' - UEMKAEM Kriya Nusantara')

@section('content')
    <section>
        <div class="section-head">
            <small class="eyebrow">PEMBAYARAN PESANAN</small>
            <h2>Detail Pesanan #{{ $order->id }}</h2>
        </div>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">
            {{-- Order Summary Card --}}
            <div class="card" style="padding: 20px;">
                <h3>Ringkasan Pesanan</h3>
                <p class="muted">Status Saat Ini: 
                    <span class="badge badge-gray">{{ strtoupper(str_replace('_', ' ', $order->status->value)) }}</span>
                </p>
                <h2 style="margin-top: 10px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h2>
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                
                @if ($order->status === \App\Enums\OrderStatus::PENDING_PAYMENT)
                    <form action="{{ route('buyer.orders.payment.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 8px;">Upload Bukti Pembayaran (JPG/PNG)</label>
                            <input type="file" name="payment_proof" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%; text-align: center;">Kirim Bukti Pembayaran</button>
                    </form>
                @else
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; text-align: center;">
                        <p class="muted" style="margin: 0;">Pesanan Anda sedang diproses atau sudah lunas. Tidak perlu upload bukti lagi.</p>
                    </div>
                @endif
            </div>

            {{-- Verification History (PBI #39) --}}
            <div class="card" style="padding: 20px;">
                <h3>Riwayat Verifikasi</h3>
                @if($order->payments->isEmpty())
                    <p class="muted">Belum ada riwayat pembayaran.</p>
                @else
                    <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 15px;">
                        @foreach ($order->payments as $payment)
                            <div style="border-left: 4px solid {{ $payment->status->value === 'rejected' ? '#dc3545' : ($payment->status->value === 'approved' ? '#28a745' : '#ffc107') }}; padding-left: 15px;">
                                <strong>{{ strtoupper($payment->status->value) }}</strong>
                                <small class="muted" style="display: block;">{{ $payment->created_at->format('d M Y, H:i') }}</small>
                                @if ($payment->status->value === 'rejected' && $payment->rejection_reason)
                                    <p style="margin-top: 5px; color: #dc3545; font-size: 0.9em;">
                                        <strong>Alasan Tolak:</strong> {{ $payment->rejection_reason }}
                                    </p>
                                @endif
                                <a href="{{ asset('storage/' . $payment->proof_path) }}" target="_blank" style="font-size: 0.85em; text-decoration: underline;">Lihat Bukti yang Diupload</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection