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
                            <img src="{{ $user->avatar ?? '/images/default-avatar.png' }}" alt="Avatar">
                        </div>
                        <h3>{{ $user->name }}</h3>
                        <p class="user-email">{{ $user->email }}</p>
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
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="profile-stats mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stat-card bg-primary">
                                    <div class="stat-icon">
                                        <i class="fas fa-question-circle"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>{{ $quizCount }}</h3>
                                        <p>Quiz créés</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card bg-success">
                                    <div class="stat-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>{{ $completedQuizCount }}</h3>
                                        <p>Quiz complétés</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card bg-info">
                                    <div class="stat-icon">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>{{ $attemptsCount }}</h3>
                                        <p>Tentatives totales</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-form">
                        <h3 class="section-title">Informations Personnelles</h3>
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom d'utilisateur</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="avatar" class="form-label">Avatar</label>
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar">
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Taille maximale: 2 MB. Formats acceptés: JPEG, PNG, JPG, GIF.
                                </div>
                            </div>
                            
                            <h3 class="section-title">Changer de Mot de Passe</h3>
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mot de passe actuel</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Doit contenir au moins 8 caractères.
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer les Modifications
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    @if(count($badges) > 0)
                    <div class="recent-badges mt-5">
                        <h3 class="section-title">Badges Récents</h3>
                        <div class="badges-container">
                            <div class="row">
                                @foreach($badges->take(3) as $badge)
                                <div class="col-md-4">
                                    <div class="badge-item unlocked">
                                        <div class="badge-icon">
                                            <i class="fas {{ $badge->icon }}"></i>
                                        </div>
                                        <div class="badge-info">
                                            <h4>{{ $badge->name }}</h4>
                                            <p>{{ $badge->description }}</p>
                                            <small class="badge-date">Obtenu le {{ $badge->pivot->earned_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="text-center mt-3">
                                <a href="{{ route('badges') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-award"></i> Voir tous mes badges
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profile/profile.css') }}">
@endsection
