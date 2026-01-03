@extends('layouts.app')

@section('title', 'Dashboard MUA - WINWIN Makeup')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Dashboard MUA</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->name }}!</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Pending</h5>
                <h2 class="text-warning">{{ $pendingBookings }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Confirmed</h5>
                <h2 class="text-info">{{ $confirmedBookings }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">On Progress</h5>
                <h2 class="text-primary">{{ $onProgressBookings }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Completed</h5>
                <h2 class="text-success">{{ $completedBookings }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5>Profil MUA</h5>
                <p><strong>Rating:</strong> {{ number_format($muaProfile->rating, 1) }} ⭐ ({{ $muaProfile->total_reviews }} reviews)</p>
                <p><strong>Status:</strong> 
                    @if($muaProfile->is_verified)
                        <span class="badge bg-success">Verified</span>
                    @else
                        <span class="badge bg-warning">Belum Verified</span>
                    @endif
                </p>
                <a href="{{ route('profile.mua.edit') }}" class="btn btn-primary">Edit Profil</a>
                <a href="{{ route('profile.mua.portfolio') }}" class="btn btn-outline-primary">Kelola Portfolio</a>
                <a href="{{ route('profile.mua.packages') }}" class="btn btn-outline-primary">Kelola Paket</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Booking Terbaru</h5>
                <a href="{{ route('booking.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @forelse($bookings as $booking)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>{{ $booking->customer->name }}</h6>
                                <p class="mb-1"><strong>Paket:</strong> {{ $booking->package->name }}</p>
                                <p class="mb-1"><strong>Tanggal:</strong> {{ $booking->booking_date->format('d M Y') }} - {{ $booking->booking_time }}</p>
                                <p class="mb-1"><strong>Status:</strong> 
                                    <span class="badge bg-{{ $booking->status == 'pending' ? 'warning' : ($booking->status == 'confirmed' ? 'info' : ($booking->status == 'on_progress' ? 'primary' : ($booking->status == 'completed' ? 'success' : 'secondary'))) }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Belum ada booking.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

