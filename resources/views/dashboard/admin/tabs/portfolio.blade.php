<div>
    <div style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 1px solid #e0e0e0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 1.2rem; font-weight: 500; margin: 0;">Portfolio WINWIN Makeup</h3>
            <button type="button" class="btn-submit" onclick="openAddPortfolioModal()">Tambah Portfolio</button>
        </div>
    </div>

    <div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;" id="portfolioGrid">
            @forelse($portfolios as $portfolio)
                <div style="border: none; overflow: hidden;">
                    <img src="{{ asset('storage/' . $portfolio->image) }}" alt="Portfolio" style="width: 100%; height: 200px; object-fit: cover;">
                    <div style="padding: 15px;">
                        @if($portfolio->description)
                            <p style="font-size: 0.85rem; color: #666; margin-bottom: 10px;">{{ strlen($portfolio->description) > 50 ? substr($portfolio->description, 0, 50) . '...' : $portfolio->description }}</p>
                        @endif
                        @if($portfolio->is_featured)
                            <span class="status-badge completed" style="margin-bottom: 10px; display: inline-block;">Featured</span>
                        @endif
                        <div style="display: flex; gap: 8px; margin-top: 10px;">
                            <button type="button" class="btn-action" onclick="openEditPortfolioModal({{ $portfolio->id }}, '{{ asset('storage/' . $portfolio->image) }}', '{{ addslashes($portfolio->description ?? '') }}', {{ $portfolio->order }}, {{ $portfolio->is_featured ? 'true' : 'false' }})" style="flex: 1;">Edit</button>
                            <form method="POST" action="{{ route('admin.portfolio.delete', $portfolio->id) }}" onsubmit="return confirm('Yakin ingin menghapus?')" style="flex: 1; margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action" style="width: 100%; background: #ef4444;">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1;">Belum ada portfolio.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Edit Portfolio Modal -->
<div id="editPortfolioModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <button class="modal-close" onclick="closeEditPortfolioModal()">&times;</button>
        <div class="modal-header">
            <h2 class="modal-title">Edit Portfolio</h2>
        </div>
        <form method="POST" action="" enctype="multipart/form-data" id="editPortfolioForm">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="edit_modal_image" class="form-label">Gambar Baru (kosongkan jika tidak ingin mengganti)</label>
                <input type="file" class="form-input" id="edit_modal_image" name="image" accept="image/*">
                <div id="edit_current_image_preview" style="margin-top: 10px;"></div>
            </div>
            <div class="form-group">
                <label for="edit_modal_description" class="form-label">Deskripsi</label>
                <textarea class="form-input" id="edit_modal_description" name="description" rows="3"></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label for="edit_modal_order" class="form-label">Urutan (Order)</label>
                <input type="number" class="form-input" id="edit_modal_order" name="order" value="" min="0" placeholder="0">
                <small style="color: #666; font-size: 0.85rem;">Semakin kecil angka, semakin awal tampil</small>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="edit_modal_is_featured" name="is_featured" value="1" style="width: 18px; height: 18px;">
                    <span style="font-size: 0.9rem; color: #333;">Featured</span>
                </label>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-modal btn-modal-primary">Update Portfolio</button>
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeEditPortfolioModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Portfolio Modal -->
<div id="addPortfolioModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <button class="modal-close" onclick="closeAddPortfolioModal()">&times;</button>
        <div class="modal-header">
            <h2 class="modal-title">Tambah Portfolio</h2>
        </div>
        <form method="POST" action="{{ route('admin.portfolio.store') }}" enctype="multipart/form-data" id="addPortfolioForm">
            @csrf
            <div class="form-group">
                <label for="modal_image" class="form-label">Gambar</label>
                <input type="file" class="form-input" id="modal_image" name="image" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="modal_description" class="form-label">Deskripsi</label>
                <textarea class="form-input" id="modal_description" name="description" rows="3"></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label for="modal_order" class="form-label">Urutan (Order)</label>
                <input type="number" class="form-input" id="modal_order" name="order" value="" min="0" placeholder="0">
                <small style="color: #666; font-size: 0.85rem;">Semakin kecil angka, semakin awal tampil</small>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="modal_is_featured" name="is_featured" value="1" style="width: 18px; height: 18px;">
                    <span style="font-size: 0.9rem; color: #333;">Featured</span>
                </label>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-modal btn-modal-primary">Tambah Portfolio</button>
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeAddPortfolioModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddPortfolioModal() {
    document.getElementById('addPortfolioModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeAddPortfolioModal() {
    document.getElementById('addPortfolioModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    document.getElementById('addPortfolioForm').reset();
}

function openEditPortfolioModal(id, imageUrl, description, order, isFeatured) {
    const form = document.getElementById('editPortfolioForm');
    form.action = `/admin/portfolio/${id}`;
    document.getElementById('edit_modal_description').value = description;
    document.getElementById('edit_modal_order').value = order;
    document.getElementById('edit_modal_is_featured').checked = isFeatured;
    
    // Show current image
    const preview = document.getElementById('edit_current_image_preview');
    preview.innerHTML = `<img src="${imageUrl}" alt="Current" style="max-width: 100%; max-height: 200px; border-radius: 4px; border: 1px solid #e0e0e0;">`;
    
    document.getElementById('editPortfolioModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeEditPortfolioModal() {
    document.getElementById('editPortfolioModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    document.getElementById('editPortfolioForm').reset();
    document.getElementById('edit_current_image_preview').innerHTML = '';
}

document.getElementById('addPortfolioModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddPortfolioModal();
    }
});

document.getElementById('editPortfolioModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditPortfolioModal();
    }
});

document.getElementById('editPortfolioForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Updating...';
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditPortfolioModal();
            window.location.href = '/dashboard?tab=portfolio';
        } else {
            alert('Terjadi kesalahan saat mengupdate portfolio.');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate portfolio.');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});

document.getElementById('addPortfolioForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Menambahkan...';
    
    fetch('{{ route("admin.portfolio.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddPortfolioModal();
            window.location.href = '/dashboard?tab=portfolio';
        } else {
            alert('Terjadi kesalahan saat menambahkan portfolio.');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan portfolio.');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});
</script>

