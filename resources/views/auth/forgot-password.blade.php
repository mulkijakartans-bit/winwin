@extends('layouts.app')

@section('title', 'Lupa Sandi - WINWIN Makeup')

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

    .auth-form {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 50px 40px;
        border-radius: 0;
        box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .auth-form p {
        color: #666;
        margin-bottom: 30px;
        font-weight: 300;
        line-height: 1.6;
    }

    .btn-submit {
        display: inline-block;
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
        text-decoration: none;
    }

    .btn-submit:hover {
        background: transparent;
        color: #1a1a1a;
    }

    .auth-footer {
        text-align: center;
        padding-top: 30px;
        border-top: 1px solid #e0e0e0;
        margin-top: 30px;
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
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-background"></div>
    <div class="auth-container">
        <div class="auth-header">
            <h1>Lupa Sandi</h1>
        </div>
        <div class="auth-form">
            <p>Fitur reset sandi otomatis sedang dalam pengembangan. Silakan hubungi admin WINWIN Makeup melalui WhatsApp untuk mereset sandi Anda.</p>
            
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', optional(\App\MuaProfile::getWinwinProfile())->phone ?? '628123456789') }}" target="_blank" class="btn-submit">
                Hubungi Admin via WhatsApp
            </a>

            <div class="auth-footer">
                <p><a href="{{ route('login') }}">Kembali ke Login</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
