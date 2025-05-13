@extends('layouts.app')

@section('title', 'Mes Tentatives')

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
                        <a href="{{ route('quizzes.index') }}">
                            <i class="fas fa-list"></i> Mes Quiz
                        </a>
                        <a href="{{ route('user.attempts') }}" class="active">
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
                        <h2>Mes Tentatives de Quiz</h2>
                        <div class="filters">
                            <select class="form-select" id="attempts-filter">
                                <option value="all" selected>Tous les quiz</option>
                                <option value="completed">Complétés uniquement</option>
                                <option value="in-progress">En cours uniquement</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="attempts-search mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Rechercher par nom de quiz..." id="search-attempts">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="attempts-list">
                        <!-- Tentative complétée avec bon score -->
                        <div class="attempt-item completed success">
                            <div class="attempt-header">
                                <div class="attempt-quiz">
                                    <h3>Introduction à HTML/CSS</h3>
                                    <div class="attempt-category">
                                        <span><i class="fas fa-layer-group"></i> Développement Web Frontend</span>
                                    </div>
                                </div>
                                <div class="attempt-score">
                                    <div class="score-circle">
                                        <span>90%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="attempt-details">
                                <div class="attempt-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>15 mai 2025</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-clock"></i>
                                        <span>12 minutes</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>9/10 questions</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-certificate"></i>
                                        <span>45 XP gagnés</span>
                                    </div>
                                </div>
                                
                                <div class="attempt-actions">
                                    <a href="{{ route('quizzes.result', 1) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Voir le détail
                                    </a>
                                    <a href="{{ route('quizzes.play', 1) }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-redo"></i> Réessayer
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tentative complétée avec score moyen -->
                        <div class="attempt-item completed warning">
                            <div class="attempt-header">
                                <div class="attempt-quiz">
                                    <h3>JavaScript Avancé</h3>
                                    <div class="attempt-category">
                                        <span><i class="fas fa-layer-group"></i> JavaScript & Frameworks</span>
                                    </div>
                                </div>
                                <div class="attempt-score">
                                    <div class="score-circle">
                                        <span>75%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="attempt-details">
                                <div class="attempt-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>10 mai 2025</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-clock"></i>
                                        <span>25 minutes</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>15/20 questions</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-certificate"></i>
                                        <span>37 XP gagnés</span>
                                    </div>
                                </div>
                                
                                <div class="attempt-actions">
                                    <a href="{{ route('quizzes.result', 2) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Voir le détail
                                    </a>
                                    <a href="{{ route('quizzes.play', 2) }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-redo"></i> Réessayer
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tentative complétée avec faible score -->
                        <div class="attempt-item completed danger">
                            <div class="attempt-header">
                                <div class="attempt-quiz">
                                    <h3>Base de données avancées</h3>
                                    <div class="attempt-category">
                                        <span><i class="fas fa-layer-group"></i> Base de données</span>
                                    </div>
                                </div>
                                <div class="attempt-score">
                                    <div class="score-circle">
                                        <span>45%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="attempt-details">
                                <div class="attempt-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>5 mai 2025</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-clock"></i>
                                        <span>18 minutes</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>9/20 questions</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-certificate"></i>
                                        <span>22 XP gagnés</span>
                                    </div>
                                </div>
                                
                                <div class="attempt-actions">
                                    <a href="{{ route('quizzes.result', 3) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Voir le détail
                                    </a>
                                    <a href="{{ route('quizzes.play', 3) }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-redo"></i> Réessayer
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tentative en cours -->
                        <div class="attempt-item in-progress">
                            <div class="attempt-header">
                                <div class="attempt-quiz">
                                    <h3>Laravel: Les bases</h3>
                                    <div class="attempt-category">
                                        <span><i class="fas fa-layer-group"></i> Frameworks PHP</span>
                                    </div>
                                </div>
                                <div class="attempt-status">
                                    <span class="badge bg-info">En cours</span>
                                </div>
                            </div>
                            
                            <div class="attempt-details">
                                <div class="attempt-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Aujourd'hui</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-clock"></i>
                                        <span>5 minutes</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>4/15 questions</span>
                                    </div>
                                </div>
                                
                                <div class="attempt-actions">
                                    <a href="{{ route('quizzes.continue', 4) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-play"></i> Continuer
                                    </a>
                                    <form action="{{ route('quizzes.abandon', 4) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir abandonner ce quiz?')">
                                            <i class="fas fa-times"></i> Abandonner
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pagination-container d-flex justify-content-center mt-4">
                        <nav aria-label="Attempts pagination">
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterSelect = document.getElementById('attempts-filter');
        const searchInput = document.getElementById('search-attempts');
        const attemptItems = document.querySelectorAll('.attempt-item');
        
        // Filter functionality
        filterSelect.addEventListener('change', function() {
            const filterValue = this.value;
            
            attemptItems.forEach(item => {
                if (filterValue === 'all') {
                    item.style.display = 'block';
                } else if (filterValue === 'completed' && item.classList.contains('completed')) {
                    item.style.display = 'block';
                } else if (filterValue === 'in-progress' && item.classList.contains('in-progress')) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Search functionality
        searchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            
            attemptItems.forEach(item => {
                const quizTitle = item.querySelector('.attempt-quiz h3').textContent.toLowerCase();
                const categoryText = item.querySelector('.attempt-category').textContent.toLowerCase();
                
                if (quizTitle.includes(searchValue) || categoryText.includes(searchValue)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
