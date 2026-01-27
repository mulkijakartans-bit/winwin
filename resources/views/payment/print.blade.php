<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $payment->payment_code }} - WINWIN Makeup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #1a1a1a;
            font-weight: 300;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 0.9rem;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .info-group h3 {
            font-size: 0.9rem;
            text-transform: uppercase;
            color: #999;
            margin: 0 0 10px;
        }
        .info-group p {
            margin: 0;
            font-weight: 500;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .table th, .table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        .table th {
            text-transform: uppercase;
            font-size: 0.85rem;
            color: #999;
            font-weight: 500;
        }
        .total-section {
            text-align: right;
            border-top: 2px solid #1a1a1a;
            padding-top: 20px;
        }
        .total-label {
            font-size: 0.9rem;
            color: #666;
            margin-right: 20px;
        }
        .total-amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1a1a1a;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border: 1px solid #1a1a1a;
            text-transform: uppercase;
            font-size: 0.8rem;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            color: #999;
            font-size: 0.8rem;
        }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>WINWIN MAKEUP</h1>
        <p>Professional MUA Service</p>
    </div>

    <div class="invoice-info">
        <div class="info-group">
            <h3>Ditagihkan Kepada</h3>
            <p>{{ $payment->booking->customer->name }}</p>
            <p>{{ $payment->booking->customer->email }}</p>
            @if($payment->booking->customer->phone)
                <p>{{ $payment->booking->customer->phone }}</p>
            @endif
        </div>
        <div class="info-group" style="text-align: right;">
            <h3>Invoice Details</h3>
            <p>No: {{ $payment->payment_code }}</p>
            <p>Tanggal: {{ $payment->created_at->format('d M Y') }}</p>
            @if($payment->status === 'verified' || $payment->status === 'paid')
                <div class="status-badge" style="border-color: #10b981; color: #10b981;">LUNAS</div>
            @else
                <div class="status-badge" style="border-color: #f59e0b; color: #f59e0b;">{{ strtoupper($payment->status) }}</div>
            @endif
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th style="text-align: right;">Harga</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $payment->booking->package->name }}</strong><br>
                    <small style="color: #666;">Booking Code: {{ $payment->booking->booking_code }}</small><br>
                    <small style="color: #666;">Jadwal: {{ $payment->booking->booking_date->format('d M Y') }} - {{ $payment->booking->booking_time }}</small>
                </td>
                <td style="text-align: right;">Rp {{ number_format($payment->booking->package->price, 0, ',', '.') }}</td>
            </tr>
            @if($payment->booking->selected_add_ons)
                @foreach($payment->booking->selected_add_ons as $addon)
                <tr>
                    <td>
                        Add-On: {{ $addon['name'] }}
                    </td>
                    <td style="text-align: right;">Rp {{ number_format($addon['price'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="total-section">
        <span class="total-label">TOTAL PEMBAYARAN</span>
        <span class="total-amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
    </div>

    @if($payment->payment_method)
    <div style="margin-top: 40px;">
        <p><strong>Metode Pembayaran:</strong> {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</p>
        @if($payment->verified_at)
            <p><strong>Diverifikasi pada:</strong> {{ $payment->verified_at->format('d M Y H:i') }}</p>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>Terima kasih telah mempercayakan momen spesial Anda kepada WINWIN Makeup.</p>
        <p class="no-print" style="margin-top: 20px;">
            <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #1a1a1a; color: white; border: none;">Cetak / Simpan PDF</button>
        </p>
    </div>
</body>
</html>
