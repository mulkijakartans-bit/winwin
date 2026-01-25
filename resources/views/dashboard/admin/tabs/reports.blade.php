<div class="card" id="printable-report" style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); padding: 20px;">
    
    <div class="no-print" style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
        <form action="{{ route('dashboard') }}" method="GET" style="display: flex; gap: 10px; align-items: flex-end;">
            <input type="hidden" name="tab" value="reports">
            
            <div style="flex: 1; max-width: 200px;">
                <label for="start_date" style="display: block; font-size: 0.85rem; font-weight: 500; color: #333; margin-bottom: 5px;">Dari Tanggal</label>
                <input type="date" id="start_date" name="start_date" class="form-input" value="{{ request('start_date') }}" style="padding: 10px;">
            </div>
            
            <div style="flex: 1; max-width: 200px;">
                <label for="end_date" style="display: block; font-size: 0.85rem; font-weight: 500; color: #333; margin-bottom: 5px;">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date" class="form-input" value="{{ request('end_date') }}" style="padding: 10px;">
            </div>
            
            <button type="submit" class="btn-action" style="background: #1a1a1a; color: white;">Filter</button>
            <a href="{{ route('dashboard', ['tab' => 'reports']) }}" class="btn-action" style="text-align: center;">Reset</a>
        </form>
    </div>

    <div class="report-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 500;">
            Laporan Pendapatan (Paid Orders)
            @if(request('start_date') && request('end_date'))
                <span class="no-print" style="font-size: 0.9rem; color: #666; font-weight: 400; margin-left: 10px;">
                    ({{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }})
                </span>
            @endif
        </h3>
        <div style="font-size: 0.9rem; color: #666;">
            <strong>Total Pendapatan:</strong> 
            <span style="color: #10b981; font-weight: 600;">Rp {{ number_format($paidBookings->sum('total_price'), 0, ',', '.') }}</span>
        </div>
    </div>

    @if($paidBookings->count() > 0)
    <div class="table-responsive">
        <table class="admin-table text-left">
            <thead>
                <tr>
                    <th>Waktu Pembayaran</th>
                    <th>Nama</th>
                    <th>Nomor Telfon</th>
                    <th>Email</th>
                    <th>Detail Pesanan</th>
                    <th>Kode Pesanan</th>
                    <th>Nominal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paidBookings as $booking)
                <tr>
                    <td>
                        {{ $booking->confirmed_at ? \Carbon\Carbon::parse($booking->confirmed_at)->format('d/m/Y H:i') : ($booking->payment && $booking->payment->verified_at ? \Carbon\Carbon::parse($booking->payment->verified_at)->format('d/m/Y H:i') : '-') }}
                    </td>
                    <td>{{ $booking->customer->name }}</td>
                    <td>{{ $booking->customer->phone ?? $booking->customer->mobile_number ?? '-' }}</td>
                    <td>{{ $booking->customer->email }}</td>
                    <td>{{ $booking->package->name }}</td>
                    <td>{{ $booking->booking_code }}</td>
                    <td style="font-weight: 600;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state">
        <p>Tidak ada data laporan transaksi yang lunas pada periode ini.</p>
    </div>
    @endif
</div>

<div class="no-print" style="margin-top: 20px; text-align: right;">
    <button onclick="window.print()" class="btn-action" style="cursor: pointer;">Cetak Laporan</button>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printable-report, #printable-report * {
        visibility: visible;
    }
    #printable-report {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 20px;
        box-shadow: none !important;
        border: none !important;
    }
    .no-print {
        display: none !important;
    }
    .report-header h3 {
        font-size: 1.5rem !important;
        color: black !important;
    }
    .admin-table th {
        color: black !important;
        border-bottom: 2px solid black !important;
        font-weight: bold !important;
    }
    .admin-table td {
        color: black !important;
        border-bottom: 1px solid #ddd !important;
    }
    .table-responsive {
        overflow: visible !important;
    }
}
</style>
