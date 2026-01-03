@extends('layouts.app')

@section('title', 'Profil - WINWIN Makeup')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Profil Saya</h4>
            </div>
            <div class="card-body">
                <p><strong>Nama:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                @if($user->phone)
                    <p><strong>Telepon:</strong> {{ $user->phone }}</p>
                @endif
                @if($user->address)
                    <p><strong>Alamat:</strong> {{ $user->address }}</p>
                @endif
                <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
            </div>
        </div>
    </div>
</div>
@endsection

