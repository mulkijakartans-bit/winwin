<div>
    <div style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 1px solid #e0e0e0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h3 style="font-size: 1.2rem; font-weight: 500; margin: 0;">Kelola Global Add-Ons</h3>
                <p style="color: #666; font-size: 0.9rem; margin-top: 5px;">Add-on ini akan muncul pada pilihan booking customer untuk semua paket.</p>
            </div>
            <button type="button" class="btn-submit" onclick="openCreateAddOnModal()">Tambah Add-On</button>
        </div>
    </div>

    <div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            @forelse($addOns as $addOn)
                <div style="border: none; padding: 25px; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <h4 style="font-size: 1.1rem; font-weight: 500; margin-bottom: 10px;">{{ $addOn->name }}</h4>
                    <p style="font-size: 0.9rem; color: #666; margin-bottom: 15px;">{{ $addOn->description }}</p>
                    <p style="font-size: 1.2rem; font-weight: 400; margin-bottom: 10px;">Rp {{ number_format($addOn->default_price, 0, ',', '.') }}</p>
                    @if($addOn->is_active)
                        <span class="status-badge completed" style="margin-bottom: 15px; display: inline-block;">Aktif</span>
                    @else
                        <span class="status-badge pending" style="margin-bottom: 15px; display: inline-block;">Tidak Aktif</span>
                    @endif
                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <button type="button" class="btn-action" onclick="openEditAddOnModal({{ $addOn->id }}, this)"
                            data-addon-id="{{ $addOn->id }}"
                            data-addon-name="{{ htmlspecialchars($addOn->name, ENT_QUOTES, 'UTF-8') }}"
                            data-addon-description="{{ htmlspecialchars($addOn->description ?? '', ENT_QUOTES, 'UTF-8') }}"
                            data-addon-price="{{ $addOn->default_price }}"
                            data-addon-active="{{ $addOn->is_active ? '1' : '0' }}">Edit</button>
                        <form method="POST" action="{{ route('admin.global-addons.delete', $addOn->id) }}" onsubmit="return confirm('Yakin ingin menghapus?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action" style="background: transparent; color: #ef4444; border-color: #ef4444;">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1;">Belum ada add-on.</div>
            @endforelse
        </div>
    </div>
</div>

@push('modals')
<!-- Create Modal -->
<div id="createAddOnModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <button class="modal-close" onclick="closeCreateAddOnModal()">&times;</button>
        <div class="modal-header">
            <h2 class="modal-title">Tambah Add-On Baru</h2>
        </div>
        <form method="POST" action="{{ route('admin.global-addons.store') }}" id="createAddOnForm">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">Nama Add-On</label>
                <input type="text" class="form-input" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-input" id="description" name="description" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="price" class="form-label">Harga</label>
                <input type="number" class="form-input" id="price" name="default_price" min="0" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked style="width: 18px; height: 18px;">
                    <span style="font-size: 0.9rem; color: #333;">Aktif</span>
                </label>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn-modal btn-modal-primary">Simpan</button>
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeCreateAddOnModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editAddOnModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <button class="modal-close" onclick="closeEditAddOnModal()">&times;</button>
        <div class="modal-header">
            <h2 class="modal-title">Edit Add-On</h2>
        </div>
        <div id="editAddOnContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>
@endpush

<script>
function openCreateAddOnModal() {
    document.getElementById('createAddOnModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeCreateAddOnModal() {
    document.getElementById('createAddOnModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    document.getElementById('createAddOnForm').reset();
}

function closeEditAddOnModal() {
    document.getElementById('editAddOnModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

function openEditAddOnModal(addOnId, buttonElement) {
    const modal = document.getElementById('editAddOnModal');
    const content = document.getElementById('editAddOnContent');
    const button = buttonElement;
    
    const addOnData = {
        id: button.getAttribute('data-addon-id'),
        name: button.getAttribute('data-addon-name'),
        description: button.getAttribute('data-addon-description'),
        price: button.getAttribute('data-addon-price'),
        is_active: button.getAttribute('data-addon-active') === '1'
    };
    
    // Helper to escape HTML special chars
    const escapeHtml = (unsafe) => {
        return unsafe
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
    }

    // Helper to unescape for textarea value
    // (since we are injecting into template literal but need proper value)
    // Actually for value attribute, standard HTML escaping is fine.
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const html = `
        <form method="POST" action="/admin/global-addons/${addOnId}">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="PUT">
            
            <div class="form-group">
                <label class="form-label">Nama Add-On</label>
                <input type="text" class="form-input" name="name" value="${addOnData.name}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-input" name="description" rows="3">${addOnData.description}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Harga</label>
                <input type="number" class="form-input" name="default_price" value="${addOnData.price}" min="0" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" ${addOnData.is_active ? 'checked' : ''} style="width: 18px; height: 18px;">
                    <span style="font-size: 0.9rem; color: #333;">Aktif</span>
                </label>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn-modal btn-modal-primary">Update</button>
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeEditAddOnModal()">Batal</button>
            </div>
        </form>
    `;
    
    content.innerHTML = html;
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

// Close modals when clicking outside
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    });
});

// Close with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.show').forEach(modal => {
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        });
    }
});
</script>
