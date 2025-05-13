@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="profile-container">
    <div class="container py-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="profile-sidebar">
                    <div class="profile-avatar">
                        <div class="avatar-circle">
                            <img src="{{ Auth::user()->avatar ?? '/images/default-avatar.png' }}" alt="Avatar">
                        </div>
                        <h3>{{ Auth::user()->name }}</h3>
                        <p class="user-email">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <div class="profile-navigation">
                        <a href="{{ route('profile') }}" class="active">
                            <i class="fas fa-user"></i> Mon Profil
                        </a>
                        <a href="{{ route('quizzes.index') }}">
                            <i class="fas fa-list"></i> Mes Quiz
                        </a>
                        <a href="{{ route('user.attempts') }}">
                            <i class="fas fa-history"></i> Mes Tentatives
                        </a>
                        <a href="{{ route('stats') }}">
                            <i class="fas fa-chart-bar"></i> Mes Statistiques
                        </a>
                        <a href="{{ route('badges') }}">
                            <i class="fas fa-award"></i> Mes Badges
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="profile-content">
                    <div class="content-header">
                        <h2>Mon Profil</h2>
                    </div>
                    
                    <div class="profile-form">
                        <form action="{{ route('profile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            <div class="form-group mb-4">
                                <label for="name">Nom</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ Auth::user()->name }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ Auth::user()->email }}" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="avatar">Photo de profil</label>
                                <div class="custom-file-upload">
                                    <input type="file" name="avatar" id="avatar" class="form-control-file">
                                    <small class="form-text text-muted">Format accepté : JPG, PNG. Taille max : 2MB</small>
                                </div>
                                @error('avatar')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="form-group mb-4">
                                <label for="current_password">Mot de passe actuel (laissez vide pour ne pas changer)</label>
                                <input type="password" name="current_password" id="current_password" class="form-control">
                                @error('current_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="password">Nouveau mot de passe</label>
                                <input type="password" name="password" id="password" class="form-control">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Mettre à jour le profil</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .profile-container {
        background-color: #f8f9fa;
        min-height: calc(100vh - 80px);
    }
    
    .profile-sidebar {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }
    
    .profile-avatar {
        text-align: center;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
        margin-bottom: 20px;
    }
    
    .avatar-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 15px;
        border: 3px solid #007bff;
    }
    
    .avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-navigation a {
        display: block;
        padding: 12px 15px;
        margin-bottom: 5px;
        border-radius: 5px;
        color: #343a40;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .profile-navigation a:hover {
        background-color: #e9ecef;
    }
    
    .profile-navigation a.active {
        background-color: #007bff;
        color: white;
    }
    
    .profile-navigation i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }
    
    .profile-content {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 25px;
    }
    
    .content-header {
        border-bottom: 1px solid #eee;
        margin-bottom: 25px;
        padding-bottom: 15px;
    }
    
    .form-actions {
        margin-top: 30px;
    }
    
    .custom-file-upload {
        position: relative;
        overflow: hidden;
    }
    
    @media (max-width: 992px) {
        .profile-sidebar {
            margin-bottom: 30px;
        }
    }
</style>
@endsection
