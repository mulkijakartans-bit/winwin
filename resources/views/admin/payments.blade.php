@extends('layouts.app')

@section('title', 'Kelola Payments - WINWIN Makeup')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Kelola Payments</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.payments') }}">
                    <div class="row">
                        <div class="col-md-9">
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode Pembayaran</th>
                                <th>Kode Booking</th>
                                <th>Customer</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_code }}</td>
                                    <td>{{ $payment->booking->booking_code }}</td>
                                    <td>{{ $payment->booking->customer->name }}</td>
                                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status == 'verified' ? 'success' : ($payment->status == 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('payment.show', $payment->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                        @if($payment->payment_proof || in_array($payment->status, ['paid', 'verified']))
                                            <a href="{{ route('payment.download', $payment->id) }}" class="btn btn-sm btn-success" title="{{ $payment->payment_proof ? 'Download Bukti' : 'Cetak Invoice' }}">
                                                <i class="fas fa-{{ $payment->payment_proof ? 'download' : 'print' }}"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada payment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
