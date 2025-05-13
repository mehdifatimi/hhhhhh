@extends('layouts.app')

@section('title', 'Mes Quiz')

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
                        <a href="{{ route('profile') }}">
                            <i class="fas fa-user"></i> Mon Profil
                        </a>
                        <a href="{{ route('quizzes.index') }}" class="active">
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
                    <div class="content-header d-flex justify-content-between align-items-center">
                        <h2>Mes Quiz</h2>
                        <a href="{{ route('quizzes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Créer un Quiz
                        </a>
                    </div>
                    
                    <div class="quiz-filters mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <select class="form-select" id="category-filter">
                                    <option value="">Toutes les catégories</option>
                                    <option value="1">Développement Web Frontend</option>
                                    <option value="2">Développement Web Backend</option>
                                    <option value="3">DevOps & Cloud</option>
                                    <option value="4">Base de données</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="visibility-filter">
                                    <option value="">Toutes les visibilités</option>
                                    <option value="public">Public</option>
                                    <option value="private">Privé</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Rechercher un quiz" id="search-quiz">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="my-quizzes">
                        <!-- Exemple de quiz 1 -->
                        <div class="quiz-item">
                            <div class="quiz-header">
                                <h3>Introduction à HTML/CSS</h3>
                                <div class="quiz-badges">
                                    <span class="badge bg-success">Public</span>
                                    <span class="badge bg-info">Facile</span>
                                </div>
                            </div>
                            <div class="quiz-details">
                                <div class="quiz-meta">
                                    <span><i class="fas fa-layer-group"></i> Développement Web Frontend</span>
                                    <span><i class="fas fa-question-circle"></i> 10 questions</span>
                                    <span><i class="fas fa-clock"></i> 15 min</span>
                                    <span><i class="fas fa-users"></i> 125 participants</span>
                                </div>
                                <div class="quiz-description">
                                    <p>Ce quiz couvre les bases du HTML et CSS pour les débutants en développement web. Testez vos connaissances sur les balises, les sélecteurs et le modèle de boîte.</p>
                                </div>
                            </div>
                            <div class="quiz-actions">
                                <a href="{{ route('quizzes.show', 1) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                <a href="{{ route('quizzes.edit', 1) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <a href="{{ route('quizzes.questions.index', 1) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-question-circle"></i> Questions
                                </a>
                                <a href="{{ route('quizzes.play', 1) }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-play"></i> Jouer
                                </a>
                                <form action="{{ route('quizzes.destroy', 1) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Exemple de quiz 2 -->
                        <div class="quiz-item">
                            <div class="quiz-header">
                                <h3>JavaScript Avancé</h3>
                                <div class="quiz-badges">
                                    <span class="badge bg-danger">Privé</span>
                                    <span class="badge bg-warning">Difficile</span>
                                </div>
                            </div>
                            <div class="quiz-details">
                                <div class="quiz-meta">
                                    <span><i class="fas fa-layer-group"></i> JavaScript & Frameworks</span>
                                    <span><i class="fas fa-question-circle"></i> 15 questions</span>
                                    <span><i class="fas fa-clock"></i> 30 min</span>
                                    <span><i class="fas fa-users"></i> 43 participants</span>
                                </div>
                                <div class="quiz-description">
                                    <p>Un quiz avancé sur JavaScript couvrant les closures, le prototypage, les promesses, async/await et les dernières fonctionnalités ES2021. Pour développeurs expérimentés.</p>
                                </div>
                            </div>
                            <div class="quiz-actions">
                                <a href="{{ route('quizzes.show', 2) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                <a href="{{ route('quizzes.edit', 2) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <a href="{{ route('quizzes.questions.index', 2) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-question-circle"></i> Questions
                                </a>
                                <a href="{{ route('quizzes.play', 2) }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-play"></i> Jouer
                                </a>
                                <form action="{{ route('quizzes.destroy', 2) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Exemple de quiz 3 -->
                        <div class="quiz-item">
                            <div class="quiz-header">
                                <h3>Laravel: Les bases</h3>
                                <div class="quiz-badges">
                                    <span class="badge bg-success">Public</span>
                                    <span class="badge bg-primary">Moyen</span>
                                </div>
                            </div>
                            <div class="quiz-details">
                                <div class="quiz-meta">
                                    <span><i class="fas fa-layer-group"></i> Frameworks PHP</span>
                                    <span><i class="fas fa-question-circle"></i> 12 questions</span>
                                    <span><i class="fas fa-clock"></i> 20 min</span>
                                    <span><i class="fas fa-users"></i> 78 participants</span>
                                </div>
                                <div class="quiz-description">
                                    <p>Ce quiz couvre les fondamentaux du framework Laravel, les routes, les contrôleurs, les modèles Eloquent et le système de blade templates.</p>
                                </div>
                            </div>
                            <div class="quiz-actions">
                                <a href="{{ route('quizzes.show', 3) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                <a href="{{ route('quizzes.edit', 3) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <a href="{{ route('quizzes.questions.index', 3) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-question-circle"></i> Questions
                                </a>
                                <a href="{{ route('quizzes.play', 3) }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-play"></i> Jouer
                                </a>
                                <form action="{{ route('quizzes.destroy', 3) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pagination-container d-flex justify-content-center mt-4">
                        <nav aria-label="Quiz pagination">
                            <ul class="pagination">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                                </li>
                                <li class="page-item active" aria-current="page">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Suivant</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profile/profile.css') }}">
@endsection
