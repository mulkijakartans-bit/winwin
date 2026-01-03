@extends('layouts.app')

@section('title', 'Login - WINWIN Makeup')

@push('styles')
<style>
    .auth-page {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 100px 20px 60px;
        background: #f5f5f5;
    }

    .auth-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('{{
            optional(\App\MuaProfile::getWinwinProfile())->login_background
                ? asset('storage/' . \App\MuaProfile::getWinwinProfile()->login_background)
                : (optional(\App\MuaProfile::getWinwinProfile())->hero_image
                    ? asset('storage/' . \App\MuaProfile::getWinwinProfile()->hero_image)
                    : 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=1920&q=80')
          }}');
        background-size: cover;
        background-position: center;
        z-index: 0;
    }

    .auth-background::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
    }

    .auth-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 450px;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 50px;
        color: white;
    }

    .auth-header h1 {
        font-size: clamp(2rem, 5vw, 2.5rem);
        font-weight: 300;
        letter-spacing: -1px;
        margin-bottom: 15px;
        color: white;
    }

    .auth-header p {
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 300;
        color: rgba(255, 255, 255, 0.9);
    }

    .auth-form {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 50px 40px;
        border-radius: 0;
        box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
    }

    .form-group {
        margin-bottom: 30px;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #333;
        margin-bottom: 10px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 14px 0;
        border: none;
        border-bottom: 1px solid #e0e0e0;
        background: transparent;
        font-size: 1rem;
        color: #1a1a1a;
        transition: all 0.3s ease;
        border-radius: 0;
    }

    .form-control:focus {
        outline: none;
        border-bottom-color: #1a1a1a;
        box-shadow: none;
    }

    .form-control::placeholder {
        color: #999;
        font-weight: 300;
    }

    .form-check {
        margin-bottom: 30px;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        border: 1px solid #ccc;
        border-radius: 0;
        margin-top: 0.25rem;
    }

    .form-check-input:checked {
        background-color: #1a1a1a;
        border-color: #1a1a1a;
    }

    .form-check-label {
        font-size: 0.9rem;
        color: #666;
        margin-left: 8px;
        font-weight: 400;
    }

    .btn-submit {
        width: 100%;
        padding: 16px;
        background: #1a1a1a;
        color: white;
        border: 1px solid #1a1a1a;
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: all 0.3s ease;
        border-radius: 0;
        margin-bottom: 30px;
    }

    .btn-submit:hover {
        background: transparent;
        color: #1a1a1a;
    }

    .auth-footer {
        text-align: center;
        padding-top: 30px;
        border-top: 1px solid #e0e0e0;
    }

    .auth-footer p {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
    }

    .auth-footer a {
        color: #1a1a1a;
        text-decoration: none;
        font-weight: 500;
        transition: opacity 0.3s ease;
    }

    .auth-footer a:hover {
        opacity: 0.7;
    }

    .text-danger {
        font-size: 0.85rem;
        margin-top: 8px;
        display: block;
    }

    .invalid-feedback {
        display: block;
        font-size: 0.85rem;
        margin-top: 8px;
    }

    @media (max-width: 768px) {
        .auth-page {
            padding: 80px 20px 40px;
        }

        .auth-form {
            padding: 40px 30px;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-background"></div>
<div class="auth-container">
                    <div class="auth-header">
            <h1>Login</h1>
            <p>Selamat datang kembali</p>
                    </div>
        <div class="auth-form">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Masukkan email Anda"
                                           required 
                                           autofocus>
                                @error('email')
                        <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Masukkan password Anda"
                                           required>
                                @error('password')
                        <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>

                <button type="submit" class="btn btn-submit">
                    Login
                            </button>
                        </form>

            <div class="auth-footer">
                <p>Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
