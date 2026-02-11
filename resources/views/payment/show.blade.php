@extends('layouts.app')

@section('title', 'Detail Pembayaran - WINWIN Makeup')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Detail Pembayaran</h4>
            </div>
            <div class="card-body">
                <p><strong>Kode Pembayaran:</strong> {{ $payment->payment_code }}</p>
                <p><strong>Kode Booking:</strong> {{ $payment->booking->booking_code }}</p>
                <p><strong>Metode Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                <p><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-{{ $payment->status == 'verified' ? 'success' : ($payment->status == 'rejected' ? 'danger' : 'warning') }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </p>
                @if($payment->payment_proof)
                    <p><strong>Bukti Pembayaran:</strong></p>
                    <img src="{{ asset('storage/' . $payment->payment_proof) }}" class="img-fluid mb-3 d-block border rounded" alt="Bukti Pembayaran" style="max-height: 400px;">
                    <a href="{{ route('payment.download', $payment->id) }}" class="btn btn-outline-success mb-3">
                        <i class="fas fa-download"></i> Unduh Bukti Pembayaran
                    </a>
                @elseif(in_array($payment->status, ['paid', 'verified']))
                    <div class="alert alert-success mb-3">
                        <i class="fas fa-check-circle"></i> Pembayaran ini telah terverifikasi secara sistem.
                    </div>
                    <a href="{{ route('payment.download', $payment->id) }}" class="btn btn-outline-success mb-3">
                        <i class="fas fa-print"></i> Cetak Invoice / Bukti Pembayaran
                    </a>
                @endif
                @if($payment->notes)
                    <p><strong>Catatan:</strong> {{ $payment->notes }}</p>
                @endif
                @if($payment->rejection_reason)
                    <div class="alert alert-danger">
                        <strong>Alasan Ditolak:</strong> {{ $payment->rejection_reason }}
                    </div>
                @endif
                @if($payment->verified_at)
                    <p><strong>Diverifikasi pada:</strong> {{ $payment->verified_at->format('d M Y H:i') }}</p>
                    @if($payment->verifier)
                        <p><strong>Diverifikasi oleh:</strong> {{ $payment->verifier->name }}</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if(Auth::user()->isAdmin() && $payment->status == 'pending')
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Verifikasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('payment.verify', $payment->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="verified">Verifikasi</option>
                                <option value="rejected">Tolak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alasan (jika ditolak)</label>
                            <textarea name="rejection_reason" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
