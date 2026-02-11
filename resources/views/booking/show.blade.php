@extends('layouts.app')

@section('title', 'Detail Booking - WINWIN Makeup')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Detail Booking</h4>
            </div>
            <div class="card-body">
                <p><strong>Kode Booking:</strong> {{ $booking->booking_code }}</p>
                <p><strong>MUA:</strong> {{ $muaProfile->name }}</p>
                <p><strong>Paket:</strong> {{ $booking->package->name }}</p>
                <p><strong>Tanggal:</strong> {{ $booking->booking_date->format('d M Y') }}</p>
                <p><strong>Waktu:</strong> {{ $booking->booking_time }}</p>
                <p><strong>Lokasi:</strong> {{ $booking->event_location }}</p>
                @if($booking->event_type)
                    <p><strong>Jenis Acara:</strong> {{ $booking->event_type }}</p>
                @endif
                @if($booking->notes)
                    <p><strong>Catatan:</strong> {{ $booking->notes }}</p>
                @endif
                <p><strong>Total Harga:</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-{{ $booking->status == 'pending' ? 'warning' : ($booking->status == 'confirmed' ? 'info' : ($booking->status == 'on_progress' ? 'primary' : ($booking->status == 'completed' ? 'success' : 'secondary'))) }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </p>

                @if(Auth::user()->isAdmin() && in_array($booking->status, ['pending', 'confirmed', 'on_progress']))
                    <hr>
                    <form method="POST" action="{{ route('booking.updateStatus', $booking->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Update Status</label>
                            <select name="status" class="form-select" required>
                                @if($booking->status == 'pending')
                                    <option value="confirmed">Terima</option>
                                    <option value="rejected">Tolak</option>
                                @elseif($booking->status == 'confirmed')
                                    <option value="on_progress">Mulai Pekerjaan</option>
                                @elseif($booking->status == 'on_progress')
                                    <option value="completed">Selesaikan Pekerjaan</option>
                                @endif
                            </select>
                        </div>
                        @if($booking->status == 'pending')
                            <div class="mb-3">
                                <label class="form-label">Alasan (jika ditolak)</label>
                                <textarea name="rejection_reason" class="form-control" rows="3"></textarea>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if($booking->payment)
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Pembayaran</h5>
                </div>
                <div class="card-body">
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $booking->payment->status == 'verified' ? 'success' : ($booking->payment->status == 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($booking->payment->status) }}
                        </span>
                    </p>
                    <a href="{{ route('payment.show', $booking->payment->id) }}" class="btn btn-sm btn-primary">Lihat Detail</a>
                    @if($booking->payment->payment_proof || in_array($booking->payment->status, ['paid', 'verified']))
                        <hr>
                        @if($booking->payment->payment_proof)
                            <p class="mb-2"><strong>Bukti Pembayaran:</strong></p>
                            <img src="{{ asset('storage/' . $booking->payment->payment_proof) }}" class="img-fluid mb-2 border rounded" alt="Bukti Pembayaran">
                        @else
                            <p class="mb-2 text-success"><i class="fas fa-check-circle"></i> Pembayaran Terverifikasi</p>
                        @endif
                        <a href="{{ route('payment.download', $booking->payment->id) }}" class="btn btn-sm btn-outline-success w-100">
                            <i class="fas fa-download"></i> {{ $booking->payment->payment_proof ? 'Unduh Bukti' : 'Cetak Invoice' }}
                        </a>
                    @endif
                </div>
            </div>
        @elseif(Auth::user()->isCustomer() && $booking->customer_id == Auth::id())
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pembayaran</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('payment.create', $booking->id) }}" class="btn btn-primary w-100">Upload Bukti Pembayaran</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
