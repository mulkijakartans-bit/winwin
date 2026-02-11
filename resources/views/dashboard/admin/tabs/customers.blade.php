<div>
    <div style="margin-bottom: 30px;">
        <div style="display: flex; gap: 15px;">
            <input type="text" class="form-input" id="customerSearch" placeholder="Cari nama atau email..." style="flex: 1;">
            <button type="button" class="btn-submit" onclick="searchCustomers(document.getElementById('customerSearch').value)">Cari</button>
        </div>
    </div>

    <div style="overflow-x: auto;" id="customersTableContainer">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Tanggal Daftar</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody id="customersTableBody">
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus customer ini? Semua data booking terkait juga akan dihapus.')" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action" style="background: #ef4444; color: white; border-color: #ef4444;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">Tidak ada customer.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function searchCustomers(query) {
    const tbody = document.getElementById('customersTableBody');
    tbody.innerHTML = '<tr><td colspan="5" class="empty-state">Memuat...</td></tr>';
    
    fetch(`/admin/users?search=${encodeURIComponent(query)}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTbody = doc.querySelector('tbody');
            if (newTbody) {
                tbody.innerHTML = newTbody.innerHTML;
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="empty-state">Tidak ada customer.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr><td colspan="5" class="empty-state">Terjadi kesalahan.</td></tr>';
        });
}
</script>

