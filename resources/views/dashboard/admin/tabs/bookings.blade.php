<div>
    <div style="margin-bottom: 30px;">
        <div style="display: flex; gap: 15px;">
            <select class="form-input" id="bookingStatusFilter" style="flex: 1;">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="on_progress">On Progress</option>
                <option value="completed">Completed</option>
            </select>
            <button type="button" class="btn-submit" onclick="filterBookings(document.getElementById('bookingStatusFilter').value)">Filter</button>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Customer</th>
                    <th>Paket</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Aksi</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody id="bookingsTableBody">
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->booking_code }}</td>
                        <td>{{ $booking->customer->name }}</td>
                        <td>{{ $booking->package->name }}</td>
                        <td>{{ $booking->booking_date->format('d M Y') }}</td>
                        <td>
                            <span class="status-badge {{ $booking->status }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                        <td>
                            <button type="button" class="btn-action" onclick="openBookingDetailModal({{ $booking->id }})">Detail</button>
                        </td>
                        <td>
                            @if(in_array($booking->status, ['pending', 'confirmed', 'on_progress']))
                                <select class="form-input" style="min-width: 150px; padding: 6px 10px; font-size: 0.85rem; border: 1px solid #e0e0e0; border-radius: 4px; background: white; cursor: pointer;" onchange="updateBookingStatus({{ $booking->id }}, this.value, this)" id="status_{{ $booking->id }}">
                                    <option value="">Pilih Status</option>
                                    @if($booking->status == 'pending')
                                        <option value="confirmed">Terima</option>
                                        <option value="rejected">Tolak</option>
                                    @elseif($booking->status == 'confirmed')
                                        <option value="on_progress">Mulai Pekerjaan</option>
                                    @elseif($booking->status == 'on_progress')
                                        <option value="completed">Selesaikan</option>
                                    @endif
                                </select>
                            @else
                                <span style="color: #999; font-size: 0.85rem;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-state">Tidak ada booking.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function filterBookings(status) {
    const tbody = document.getElementById('bookingsTableBody');
    tbody.innerHTML = '<tr><td colspan="8" class="empty-state">Memuat...</td></tr>';
    
    const url = status ? `/admin/bookings?status=${encodeURIComponent(status)}` : '/admin/bookings';
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTbody = doc.querySelector('tbody');
            if (newTbody) {
                // Replace links with buttons and fix status badges
                let html = newTbody.innerHTML;
                html = html.replace(/<a[^>]*href="[^"]*\/booking\/(\d+)"[^>]*class="[^"]*btn-action[^"]*"[^>]*>([^<]*)<\/a>/g, 
                    '<button type="button" class="btn-action" onclick="openBookingDetailModal($1)">$2</button>');
                // Fix status badge - convert Bootstrap badge to our status-badge
                html = html.replace(/<span[^>]*class="badge[^"]*bg-([^"]*)"[^>]*>([^<]*)<\/span>/g, function(match, bgClass, text) {
                    let statusClass = 'pending';
                    if (bgClass === 'warning') statusClass = 'pending';
                    else if (bgClass === 'info') statusClass = 'confirmed';
                    else if (bgClass === 'primary') statusClass = 'on_progress';
                    else if (bgClass === 'success') statusClass = 'completed';
                    return `<span class="status-badge ${statusClass}">${text}</span>`;
                });
                tbody.innerHTML = html;
            } else {
                tbody.innerHTML = '<tr><td colspan="8" class="empty-state">Tidak ada booking.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr><td colspan="8" class="empty-state">Terjadi kesalahan.</td></tr>';
        });
}

</script>

