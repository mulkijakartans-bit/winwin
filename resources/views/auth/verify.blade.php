@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h4 class="mb-0 text-center">{{ __('Verifikasi Alamat Email Anda') }}</h4>
                </div>

                <div class="card-body p-5">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </div>
                    @endif

                    <p class="text-center mb-4">
                        {{ __('Sebelum melanjutkan, silakan periksa email Anda untuk tautan verifikasi.') }}
                        {{ __('Jika Anda tidak menerima email tersebut') }},
                    </p>

                    <form class="d-inline text-center w-100 d-block" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('klik di sini untuk meminta lagi') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
