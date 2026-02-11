@extends('layouts.app')

@section('title', 'Kelola Paket - WINWIN Makeup')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Kelola Paket Makeup</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tambah Paket</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.packages.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Paket</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" required 
                                       oninvalid="this.setCustomValidity('tentukan harga!')" 
                                       oninput="this.setCustomValidity('')">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
 industrial
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duration" class="form-label">Durasi (menit)</label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration') }}" min="1" required 
                                       oninvalid="this.setCustomValidity('tentukan durasi!')" 
                                       oninput="this.setCustomValidity('')">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
 industrial
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="includes" class="form-label">Include (Apa saja yang termasuk)</label>
                        <textarea class="form-control @error('includes') is-invalid @enderror" id="includes" name="includes" rows="3">{{ old('includes') }}</textarea>
                        @error('includes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Tambah Paket</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h4>Paket WINWIN Makeup</h4>
        <div class="row">
            @forelse($packages as $package)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $package->name }}</h5>
                            <p class="card-text">{{ $package->description }}</p>
                            <p class="card-text"><strong>Rp {{ number_format($package->price, 0, ',', '.') }}</strong></p>
                            <p class="card-text"><small class="text-muted">Durasi: {{ $package->duration }} menit</small></p>
                            @if($package->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $package->id }}">Edit</button>
                                <form method="POST" action="{{ route('admin.packages.delete', $package->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $package->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Paket</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.packages.update', $package->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Paket</label>
                                        <input type="text" class="form-control" name="name" value="{{ $package->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="description" rows="3">{{ $package->description }}</textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Harga</label>
                                                <input type="number" class="form-control" name="price" value="{{ $package->price }}" min="0" required 
                                                       oninvalid="this.setCustomValidity('tentukan harga!')" 
                                                       oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Durasi (menit)</label>
                                                <input type="number" class="form-control" name="duration" value="{{ $package->duration }}" min="1" required 
                                                       oninvalid="this.setCustomValidity('tentukan durasi!')" 
                                                       oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Include</label>
                                        <textarea class="form-control" name="includes" rows="3">{{ $package->includes }}</textarea>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_active" value="1" {{ $package->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label">Aktif</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">Belum ada paket.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

