<div>
    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-input @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $muaProfile->name) }}" required>
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="bio" class="form-label">Bio</label>
            <textarea class="form-input @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4">{{ old('bio', $muaProfile->bio) }}</textarea>
            @error('bio')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="experience_years" class="form-label">Tahun Pengalaman</label>
            <input type="number" class="form-input @error('experience_years') is-invalid @enderror" id="experience_years" name="experience_years" value="{{ old('experience_years', $muaProfile->experience_years) }}" min="0">
            @error('experience_years')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="specialization" class="form-label">Spesialisasi</label>
            <input type="text" class="form-input @error('specialization') is-invalid @enderror" id="specialization" name="specialization" value="{{ old('specialization', $muaProfile->specialization) }}" placeholder="Contoh: Wedding, Prewedding, dll">
            @error('specialization')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-input @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $muaProfile->email) }}">
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="phone" class="form-label">Telepon</label>
                <input type="text" class="form-input @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $muaProfile->phone) }}">
                @error('phone')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="whatsapp" class="form-label">WhatsApp</label>
            <input type="text" class="form-input @error('whatsapp') is-invalid @enderror" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $muaProfile->whatsapp) }}">
            @error('whatsapp')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="instagram" class="form-label">Instagram</label>
            <input type="text" class="form-input @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ old('instagram', $muaProfile->instagram) }}">
            @error('instagram')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="facebook" class="form-label">Facebook</label>
            <input type="text" class="form-input @error('facebook') is-invalid @enderror" id="facebook" name="facebook" value="{{ old('facebook', $muaProfile->facebook) }}">
            @error('facebook')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="cover_photo" class="form-label">Cover Photo</label>
            <input type="file" class="form-input @error('cover_photo') is-invalid @enderror" id="cover_photo" name="cover_photo" accept="image/*">
            @error('cover_photo')
                <span class="form-error">{{ $message }}</span>
            @enderror
            @if($muaProfile->cover_photo)
                <small style="color: #999; margin-top: 8px; display: block;">Cover photo saat ini: <img src="{{ asset('storage/' . $muaProfile->cover_photo) }}" alt="Cover" style="max-height: 100px; margin-left: 10px;"></small>
            @endif
        </div>

        <div class="form-group">
            <label for="hero_image" class="form-label">Hero Background (Halaman Beranda)</label>
            <input type="file" class="form-input @error('hero_image') is-invalid @enderror" id="hero_image" name="hero_image" accept="image/*">
            @error('hero_image')
                <span class="form-error">{{ $message }}</span>
            @enderror
            @if($muaProfile->hero_image)
                <small style="color: #999; margin-top: 8px; display: block;">Hero image saat ini: <img src="{{ asset('storage/' . $muaProfile->hero_image) }}" alt="Hero" style="max-height: 100px; margin-left: 10px;"></small>
            @else
                <small style="color: #999; margin-top: 8px; display: block;">Jika tidak diisi, akan memakai gambar default.</small>
            @endif
        </div>

        <div class="form-group">
            <label for="login_background" class="form-label">Login Background</label>
            <input type="file" class="form-input @error('login_background') is-invalid @enderror" id="login_background" name="login_background" accept="image/*">
            @error('login_background')
                <span class="form-error">{{ $message }}</span>
            @enderror
            @if($muaProfile->login_background)
                <small style="color: #999; margin-top: 8px; display: block;">Login background saat ini: <img src="{{ asset('storage/' . $muaProfile->login_background) }}" alt="Login Background" style="max-height: 100px; margin-left: 10px;"></small>
            @else
                <small style="color: #999; margin-top: 8px; display: block;">Jika tidak diisi, akan memakai hero image atau fallback default.</small>
            @endif
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
            <button type="submit" class="btn-submit">Update Profil</button>
        </div>
    </form>
</div>

