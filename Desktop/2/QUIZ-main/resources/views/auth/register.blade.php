@extends('layouts.app')

@section('title', 'Inscription')

@push('styles')
<link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2>Inscription</h2>
        
        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label"><i class="fas fa-user"></i> Nom</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <span class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label"><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <span class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation" class="form-label"><i class="fas fa-check-circle"></i> Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-auth btn-primary-auth">S'inscrire</button>
            </div>
            
            <div class="auth-links">
                <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Déjà inscrit ? Se connecter</a>
            </div>
            
            <div class="social-auth">
                <p>Ou inscrivez-vous avec</p>
                <div class="social-buttons">
                    <a href="#" class="btn-social btn-github"><i class="fab fa-github"></i></a>
                    <a href="#" class="btn-social btn-google"><i class="fab fa-google"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
