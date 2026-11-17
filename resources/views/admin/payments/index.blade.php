@extends('layouts.app')

@section('title', 'Antrean Pembayaran - UEMKAEM Admin')

@section('content')
    <section>
        <div class="section-head">
            <small class="eyebrow">ADMIN DASHBOARD</small>
            <h2>Antrean Verifikasi Pembayaran</h2>
        </div>

        @if (session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
            @forelse ($pendingPayments as $payment)
                <div class="card" style="padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <h3>Pesanan #{{ $payment->order_id }}</h3>
                            <span class="badge badge-light">{{ $payment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="muted" style="margin-bottom: 5px;">Pembeli: <strong>{{ $payment->order->buyer->name }}</strong></p>
                        <h3 style="margin-top: 5px;">Rp {{ number_format($payment->order->total_amount, 0, ',', '.') }}</h3>
                        
                        <div style="margin: 15px 0;">
                            <a href="{{ asset('storage/' . $payment->proof_path) }}" target="_blank" class="btn btn-outline" style="display: inline-block; width: 100%; text-align: center;">
                                🔍 Lihat Bukti Transfer
                            </a>
                        </div>
                    </div>

                    {{-- Action Form (Approve / Reject) --}}
                    <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" style="margin-top: 15px; background: #f8f9fa; padding: 15px; border-radius: 8px;">
                        @csrf
                        <div style="margin-bottom: 10px;">
                            <label style="font-size: 0.85em; font-weight: bold;">Alasan Tolak (Wajib jika menolak):</label>
                            <input type="text" name="rejection_reason" placeholder="Ketik alasan penolakan..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;">
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button type="submit" name="action" value="approve" class="btn btn-primary" style="flex: 1; background-color: #28a745; border-color: #28a745;">
                                Setujui
                            </button>
                            <button type="submit" name="action" value="reject" class="btn btn-outline" style="flex: 1; color: #dc3545; border-color: #dc3545;">
                                Tolak
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="card" style="padding: 40px; text-align: center; grid-column: 1 / -1;">
                    <span class="stat-icon" style="margin: 0 auto 15px auto;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span>
                    <h3>Antrean Kosong</h3>
                    <p class="muted">Bagus! Tidak ada pembayaran yang menunggu verifikasi saat ini.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection