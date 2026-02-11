<div>
    <div style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 1px solid #e0e0e0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 1.2rem; font-weight: 500; margin: 0;">Paket WINWIN Makeup</h3>
            <button type="button" class="btn-submit" onclick="openAddPackageModal()">Tambah Paket</button>
        </div>
    </div>

    <div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            @forelse($packages as $package)
                <div style="border: none; padding: 25px;">
                    <h4 style="font-size: 1.1rem; font-weight: 500; margin-bottom: 10px;">{{ $package->name }}</h4>
                    @if($package->images && count($package->images) > 0)
                        <div style="margin-bottom: 15px;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 10px;">
                                @foreach($package->images as $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $package->name }}" style="width: 100%; height: 80px; object-fit: cover; border: 1px solid #e0e0e0;">
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($package->description)
                        <p style="font-size: 0.9rem; color: #666; margin-bottom: 15px;">{{ $package->description }}</p>
                    @endif
                    <p style="font-size: 1.2rem; font-weight: 400; margin-bottom: 10px;">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                    <p style="font-size: 0.85rem; color: #999; margin-bottom: 15px;">Durasi: {{ $package->duration }} menit</p>
                    @if($package->is_active)
                        <span class="status-badge completed" style="margin-bottom: 15px; display: inline-block;">Aktif</span>
                    @else
                        <span class="status-badge pending" style="margin-bottom: 15px; display: inline-block;">Tidak Aktif</span>
                    @endif
                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <button type="button" class="btn-action" onclick="openEditPackageModal({{ $package->id }}, this)" 
                            data-package-id="{{ $package->id }}"
                            data-package-name="{{ htmlspecialchars($package->name, ENT_QUOTES, 'UTF-8') }}"
                            data-package-description="{{ htmlspecialchars($package->description ?? '', ENT_QUOTES, 'UTF-8') }}"
                            data-package-price="{{ $package->price }}"
                            data-package-duration="{{ $package->duration }}"
                            data-package-includes="{{ htmlspecialchars($package->includes ?? '', ENT_QUOTES, 'UTF-8') }}"
                            data-package-active="{{ $package->is_active ? '1' : '0' }}">Edit</button>
                        <form method="POST" action="{{ route('admin.packages.delete', $package->id) }}" onsubmit="return confirm('Yakin ingin menghapus?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action" style="background: transparent; color: #ef4444; border-color: #ef4444;">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1;">Belum ada paket.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Add Package Modal -->
@push('modals')
<div id="addPackageModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <button class="modal-close" onclick="closeAddPackageModal()">&times;</button>
        <div class="modal-header">
            <h2 class="modal-title">Tambah Paket</h2>
        </div>
        <form method="POST" action="{{ route('admin.packages.store') }}" enctype="multipart/form-data" id="addPackageForm">
            @csrf
            <div class="form-group">
                <label for="add_name" class="form-label">Nama Paket</label>
                <input type="text" class="form-input" id="add_name" name="name" required>
            </div>
            <div class="form-group">
                <label for="add_description" class="form-label">Deskripsi</label>
                <textarea class="form-input" id="add_description" name="description" rows="3"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="add_price" class="form-label">Harga</label>
                    <input type="number" class="form-input" id="add_price" name="price" min="0" required 
                           oninvalid="this.setCustomValidity('tentukan harga!')" 
                           oninput="this.setCustomValidity('')">
                </div>
                <div class="form-group">
                    <label for="add_duration" class="form-label">Durasi (menit)</label>
                    <input type="number" class="form-input" id="add_duration" name="duration" min="1" required 
                           oninvalid="this.setCustomValidity('tentukan durasi!')" 
                           oninput="this.setCustomValidity('')">
                </div>
            </div>
            <div class="form-group">
                <label for="add_includes" class="form-label">Include</label>
                <textarea class="form-input" id="add_includes" name="includes" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="add_images" class="form-label">Gambar (Bisa pilih multiple)</label>
                <input type="file" class="form-input" id="add_images" name="images[]" accept="image/*" multiple>
                <small style="color: #999; font-size: 0.85rem; margin-top: 5px; display: block;">Pilih satu atau lebih gambar untuk paket ini</small>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="add_is_active" name="is_active" value="1" checked style="width: 18px; height: 18px;">
                    <span style="font-size: 0.9rem; color: #333;">Aktif</span>
                </label>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-modal btn-modal-primary">Tambah Paket</button>
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeAddPackageModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Package Modal -->
<div id="editPackageModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <button class="modal-close" onclick="closeEditPackageModal()">&times;</button>
        <div class="modal-header">
            <h2 class="modal-title">Edit Paket</h2>
        </div>
        <div id="editPackageContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>
@endpush

<script>
async function openEditPackageModal(packageId, buttonElement) {
    const modal = document.getElementById('editPackageModal');
    const content = document.getElementById('editPackageContent');
    
    // Get the package data from the button's data attributes
    const button = buttonElement || event.target;
    const packageData = {
        id: button.getAttribute('data-package-id'),
        name: button.getAttribute('data-package-name') || '',
        description: button.getAttribute('data-package-description') || '',
        price: button.getAttribute('data-package-price') || '0',
        duration: button.getAttribute('data-package-duration') || '60',
        includes: button.getAttribute('data-package-includes') || '',
        is_active: button.getAttribute('data-package-active') === '1'
    };
    
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    // Escape untuk HTML attribute
    function escapeForHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Escape untuk template literal (handle backtick, $, backslash)
    function escapeForTemplate(text) {
        if (!text) return '';
        return String(text)
            .replace(/\\/g, '\\\\')
            .replace(/`/g, '\\`')
            .replace(/\${/g, '\\${')
            .replace(/\n/g, '\\n')
            .replace(/\r/g, '\\r');
    }
    
    const nameEscaped = escapeForHtml(packageData.name);
    const descEscaped = escapeForTemplate(packageData.description);
    const includesEscaped = escapeForTemplate(packageData.includes);
    
    content.innerHTML = `
        <form method="POST" action="/admin/packages/${packageId}" enctype="multipart/form-data" id="editPackageForm">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="PUT">
            
            <div class="form-group">
                <label class="form-label">Nama Paket</label>
                <input type="text" class="form-input" name="name" value="${nameEscaped}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-input" name="description" rows="3">${descEscaped}</textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Harga</label>
                    <input type="number" class="form-input" name="price" value="${packageData.price || 0}" min="0" required 
                           oninvalid="this.setCustomValidity('tentukan harga!')" 
                           oninput="this.setCustomValidity('')">
                </div>
                <div class="form-group">
                    <label class="form-label">Durasi (menit)</label>
                    <input type="number" class="form-input" name="duration" value="${packageData.duration || 60}" min="1" required 
                           oninvalid="this.setCustomValidity('tentukan durasi!')" 
                           oninput="this.setCustomValidity('')">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Include</label>
                <textarea class="form-input" name="includes" rows="3">${includesEscaped}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Gambar (Bisa pilih multiple)</label>
                <input type="file" class="form-input" name="images[]" accept="image/*" multiple>
                <small style="color: #999; font-size: 0.85rem; margin-top: 5px; display: block;">Pilih satu atau lebih gambar untuk paket ini</small>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" ${(packageData.is_active === true || packageData.is_active === 1) ? 'checked' : ''} style="width: 18px; height: 18px;">
                    <span style="font-size: 0.9rem; color: #333;">Aktif</span>
                </label>
            </div>
            
            <div class="modal-actions">
                <button type="submit" class="btn-modal btn-modal-primary">Update</button>
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeEditPackageModal()">Batal</button>
            </div>
        </form>
    `;
    
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Add form submit handler
    setTimeout(() => {
        const form = document.getElementById('editPackageForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Memproses...';
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.ok) {
                        closeEditPackageModal();
                        window.location.href = '/dashboard?tab=packages';
                    } else {
                        alert('Terjadi kesalahan saat mengupdate paket.');
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengupdate paket.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
            });
        }
    }, 100);
}

function closeEditPackageModal() {
    document.getElementById('editPackageModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

document.getElementById('editPackageModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditPackageModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditPackageModal();
        closeAddPackageModal();
    }
});

function openAddPackageModal() {
    document.getElementById('addPackageModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeAddPackageModal() {
    document.getElementById('addPackageModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    document.getElementById('addPackageForm').reset();
}

document.getElementById('addPackageModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddPackageModal();
    }
});

document.getElementById('addPackageForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Menambahkan...';
    
    fetch('{{ route("admin.packages.store") }}', {
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
            closeAddPackageModal();
            window.location.href = '/dashboard?tab=packages';
        } else {
            alert('Terjadi kesalahan saat menambahkan paket.');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan paket.');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});

</script>
