<div>
    <div style="margin-bottom: 30px;">
        <div style="display: flex; gap: 15px;">
            <select class="form-input" id="paymentStatusFilter" style="flex: 1;">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="verified">Verified</option>
                <option value="rejected">Rejected</option>
            </select>
            <button type="button" class="btn-submit" onclick="filterPayments(document.getElementById('paymentStatusFilter').value)">Filter</button>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
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
            <tbody id="paymentsTableBody">
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_code }}</td>
                        <td>{{ $payment->booking->booking_code }}</td>
                        <td>{{ $payment->booking->customer->name }}</td>
                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                        <td>
                            <span class="status-badge {{ $payment->status === 'verified' ? 'verified' : ($payment->status === 'rejected' ? 'rejected' : 'pending') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn-action" onclick="openPaymentDetailModal({{ $payment->id }})">Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">Tidak ada payment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function filterPayments(status) {
    const tbody = document.getElementById('paymentsTableBody');
    tbody.innerHTML = '<tr><td colspan="7" class="empty-state">Memuat...</td></tr>';
    
    const url = status ? `/admin/payments?status=${encodeURIComponent(status)}` : '/admin/payments';
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTbody = doc.querySelector('tbody');
            if (newTbody) {
                // Replace links with buttons and fix status badges
                let html = newTbody.innerHTML;
                html = html.replace(/<a[^>]*href="[^"]*\/payment\/(\d+)"[^>]*class="[^"]*btn-action[^"]*"[^>]*>([^<]*)<\/a>/g, 
                    '<button type="button" class="btn-action" onclick="openPaymentDetailModal($1)">$2</button>');
                // Fix status badge - convert Bootstrap badge to our status-badge
                html = html.replace(/<span[^>]*class="badge[^"]*bg-([^"]*)"[^>]*>([^<]*)<\/span>/g, function(match, bgClass, text) {
                    let statusClass = 'pending';
                    if (bgClass === 'success') statusClass = 'verified';
                    else if (bgClass === 'danger') statusClass = 'rejected';
                    else if (bgClass === 'warning') statusClass = 'pending';
                    return `<span class="status-badge ${statusClass}">${text}</span>`;
                });
                tbody.innerHTML = html;
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="empty-state">Tidak ada payment.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr><td colspan="7" class="empty-state">Terjadi kesalahan.</td></tr>';
        });
}
</script>

