@extends('layouts.app')

@section('title', 'Kelola Portfolio - WINWIN Makeup')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Kelola Portfolio</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tambah Portfolio</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.portfolio.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                        @error('image')
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

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1">
                        <label class="form-check-label" for="is_featured">Featured</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Tambah Portfolio</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h4>Portfolio WINWIN Makeup</h4>
        <div class="row">
            @forelse($portfolios as $portfolio)
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <img src="{{ asset('storage/' . $portfolio->image) }}" class="card-img-top" alt="Portfolio">
                        <div class="card-body">
                            @if($portfolio->description)
                                <p class="card-text">{{ \Illuminate\Support\Str::limit($portfolio->description, 50) }}</p>
                            @endif
                            @if($portfolio->is_featured)
                                <span class="badge bg-success">Featured</span>
                            @endif
                            <form method="POST" action="{{ route('admin.portfolio.delete', $portfolio->id) }}" class="mt-2" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">Belum ada portfolio.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

