@extends('layouts.app')

@section('title', 'Connexion')

@push('styles')
<link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2>Connexion</h2>
        
        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <span class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group form-check">
                <input type="checkbox" id="remember" name="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="form-check-label">Se souvenir de moi</label>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-auth btn-primary-auth">Se connecter</button>
            </div>
            
            <div class="auth-links">
                <a href="{{ route('password.request') }}"><i class="fas fa-key"></i> Mot de passe oublié ?</a>
                <a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Pas encore inscrit ? Créer un compte</a>
            </div>
            
            <div class="social-auth">
                <p>Ou connectez-vous avec</p>
                <div class="social-buttons">
                    <a href="#" class="btn-social btn-github"><i class="fab fa-github"></i></a>
                    <a href="#" class="btn-social btn-google"><i class="fab fa-google"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
