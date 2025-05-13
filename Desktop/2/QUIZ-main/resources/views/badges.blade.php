@extends('layouts.app')

@section('title', 'Mes Badges')

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
                        <a href="{{ route('user.attempts') }}">
                            <i class="fas fa-history"></i> Mes Tentatives
                        </a>
                        <a href="{{ route('stats') }}">
                            <i class="fas fa-chart-bar"></i> Mes Statistiques
                        </a>
                        <a href="{{ route('badges') }}" class="active">
                            <i class="fas fa-award"></i> Mes Badges
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="profile-content">
                    <div class="content-header">
                        <h2>Mes Badges</h2>
                    </div>
                    
                    <div class="badges-summary mb-4">
                        <div class="badge-progress-container">
                            <div class="badge-level">
                                <h3>Niveau Actuel: <span class="text-primary">Explorateur</span></h3>
                            </div>
                            <div class="progress-stats">
                                <div class="stat">
                                    <span class="stat-number">28</span>
                                    <span class="stat-label">Badges débloqués</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-number">65</span>
                                    <span class="stat-label">Total des badges</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-number">43%</span>
                                    <span class="stat-label">Progression</span>
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 43%" aria-valuenow="43" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="badges-categories mb-4">
                        <ul class="nav nav-pills mb-3" id="badges-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-badges-tab" data-bs-toggle="pill" data-bs-target="#all-badges" type="button" role="tab" aria-controls="all-badges" aria-selected="true">Tous</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="unlocked-badges-tab" data-bs-toggle="pill" data-bs-target="#unlocked-badges" type="button" role="tab" aria-controls="unlocked-badges" aria-selected="false">Débloqués</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="locked-badges-tab" data-bs-toggle="pill" data-bs-target="#locked-badges" type="button" role="tab" aria-controls="locked-badges" aria-selected="false">À débloquer</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="achievements-badges-tab" data-bs-toggle="pill" data-bs-target="#achievements-badges" type="button" role="tab" aria-controls="achievements-badges" aria-selected="false">Réussites</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="category-badges-tab" data-bs-toggle="pill" data-bs-target="#category-badges" type="button" role="tab" aria-controls="category-badges" aria-selected="false">Catégories</button>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="tab-content" id="badges-tabContent">
                        <div class="tab-pane fade show active" id="all-badges" role="tabpanel" aria-labelledby="all-badges-tab">
                            <div class="badges-grid">
                                <!-- Badges débloqués -->
                                <div class="badge-item unlocked">
                                    <div class="badge-icon">
                                        <i class="fas fa-rocket"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Premier Quiz</h4>
                                        <p>Compléter votre premier quiz</p>
                                        <small class="badge-date">Obtenu le 5 avril 2025</small>
                                    </div>
                                </div>
                                
                                <div class="badge-item unlocked">
                                    <div class="badge-icon">
                                        <i class="fas fa-fire"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Feu d'artifice</h4>
                                        <p>Compléter 5 quiz d'affilée avec un score > 80%</p>
                                        <small class="badge-date">Obtenu le 20 avril 2025</small>
                                    </div>
                                </div>
                                
                                <div class="badge-item unlocked">
                                    <div class="badge-icon">
                                        <i class="fas fa-bullseye"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Perfection</h4>
                                        <p>Obtenir un score de 100% sur un quiz</p>
                                        <small class="badge-date">Obtenu le 12 mai 2025</small>
                                    </div>
                                </div>
                                
                                <div class="badge-item unlocked">
                                    <div class="badge-icon">
                                        <i class="fas fa-award"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Spécialiste HTML</h4>
                                        <p>Réussir 3 quiz dans la catégorie HTML avec un score > 90%</p>
                                        <small class="badge-date">Obtenu le 2 mai 2025</small>
                                    </div>
                                </div>
                                
                                <!-- Badges à débloquer -->
                                <div class="badge-item locked">
                                    <div class="badge-icon">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Super Créateur</h4>
                                        <p>Créer 10 quiz avec au moins 10 questions chacun</p>
                                        <div class="badge-progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small>4/10 complétés</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="badge-item locked">
                                    <div class="badge-icon">
                                        <i class="fas fa-code"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Maître JavaScript</h4>
                                        <p>Réussir 5 quiz dans la catégorie JavaScript avec un score > 90%</p>
                                        <div class="badge-progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small>3/5 complétés</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="badge-item locked">
                                    <div class="badge-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Savant</h4>
                                        <p>Compléter 100 quiz avec un score moyen > 75%</p>
                                        <div class="badge-progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 28%" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small>28/100 complétés</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="badge-item locked">
                                    <div class="badge-icon">
                                        <i class="fas fa-database"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Expert SQL</h4>
                                        <p>Réussir 3 quiz dans la catégorie Base de données avec un score > 90%</p>
                                        <div class="badge-progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small>1/3 complétés</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="unlocked-badges" role="tabpanel" aria-labelledby="unlocked-badges-tab">
                            <div class="badges-grid">
                                <div class="badge-item unlocked">
                                    <div class="badge-icon">
                                        <i class="fas fa-rocket"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Premier Quiz</h4>
                                        <p>Compléter votre premier quiz</p>
                                        <small class="badge-date">Obtenu le 5 avril 2025</small>
                                    </div>
                                </div>
                                
                                <div class="badge-item unlocked">
                                    <div class="badge-icon">
                                        <i class="fas fa-fire"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Feu d'artifice</h4>
                                        <p>Compléter 5 quiz d'affilée avec un score > 80%</p>
                                        <small class="badge-date">Obtenu le 20 avril 2025</small>
                                    </div>
                                </div>
                                
                                <div class="badge-item unlocked">
                                    <div class="badge-icon">
                                        <i class="fas fa-bullseye"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Perfection</h4>
                                        <p>Obtenir un score de 100% sur un quiz</p>
                                        <small class="badge-date">Obtenu le 12 mai 2025</small>
                                    </div>
                                </div>
                                
                                <div class="badge-item unlocked">
                                    <div class="badge-icon">
                                        <i class="fas fa-award"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Spécialiste HTML</h4>
                                        <p>Réussir 3 quiz dans la catégorie HTML avec un score > 90%</p>
                                        <small class="badge-date">Obtenu le 2 mai 2025</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="locked-badges" role="tabpanel" aria-labelledby="locked-badges-tab">
                            <div class="badges-grid">
                                <div class="badge-item locked">
                                    <div class="badge-icon">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Super Créateur</h4>
                                        <p>Créer 10 quiz avec au moins 10 questions chacun</p>
                                        <div class="badge-progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small>4/10 complétés</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="badge-item locked">
                                    <div class="badge-icon">
                                        <i class="fas fa-code"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Maître JavaScript</h4>
                                        <p>Réussir 5 quiz dans la catégorie JavaScript avec un score > 90%</p>
                                        <div class="badge-progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small>3/5 complétés</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="badge-item locked">
                                    <div class="badge-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Savant</h4>
                                        <p>Compléter 100 quiz avec un score moyen > 75%</p>
                                        <div class="badge-progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 28%" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small>28/100 complétés</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="badge-item locked">
                                    <div class="badge-icon">
                                        <i class="fas fa-database"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Expert SQL</h4>
                                        <p>Réussir 3 quiz dans la catégorie Base de données avec un score > 90%</p>
                                        <div class="badge-progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small>1/3 complétés</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="achievements-badges" role="tabpanel" aria-labelledby="achievements-badges-tab">
                            <div class="badges-grid">
                                <div class="badge-item unlocked">
                                    <div class="badge-icon">
                                        <i class="fas fa-rocket"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Premier Quiz</h4>
                                        <p>Compléter votre premier quiz</p>
                                        <small class="badge-date">Obtenu le 5 avril 2025</small>
                                    </div>
                                </div>
                                
                                <div class="badge-item unlocked">
                                    <div class="badge-icon">
                                        <i class="fas fa-fire"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Feu d'artifice</h4>
                                        <p>Compléter 5 quiz d'affilée avec un score > 80%</p>
                                        <small class="badge-date">Obtenu le 20 avril 2025</small>
                                    </div>
                                </div>
                                
                                <div class="badge-item unlocked">
                                    <div class="badge-icon">
                                        <i class="fas fa-bullseye"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Perfection</h4>
                                        <p>Obtenir un score de 100% sur un quiz</p>
                                        <small class="badge-date">Obtenu le 12 mai 2025</small>
                                    </div>
                                </div>
                                
                                <div class="badge-item locked">
                                    <div class="badge-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Savant</h4>
                                        <p>Compléter 100 quiz avec un score moyen > 75%</p>
                                        <div class="badge-progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 28%" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small>28/100 complétés</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="category-badges" role="tabpanel" aria-labelledby="category-badges-tab">
                            <div class="badges-grid">
                                <div class="badge-item unlocked">
                                    <div class="badge-icon">
                                        <i class="fas fa-award"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Spécialiste HTML</h4>
                                        <p>Réussir 3 quiz dans la catégorie HTML avec un score > 90%</p>
                                        <small class="badge-date">Obtenu le 2 mai 2025</small>
                                    </div>
                                </div>
                                
                                <div class="badge-item locked">
                                    <div class="badge-icon">
                                        <i class="fas fa-code"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Maître JavaScript</h4>
                                        <p>Réussir 5 quiz dans la catégorie JavaScript avec un score > 90%</p>
                                        <div class="badge-progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small>3/5 complétés</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="badge-item locked">
                                    <div class="badge-icon">
                                        <i class="fas fa-database"></i>
                                    </div>
                                    <div class="badge-info">
                                        <h4>Expert SQL</h4>
                                        <p>Réussir 3 quiz dans la catégorie Base de données avec un score > 90%</p>
                                        <div class="badge-progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small>1/3 complétés</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        // Initialize Bootstrap tabs
        const triggerTabList = [].slice.call(document.querySelectorAll('#badges-tab button'));
        triggerTabList.forEach(function(triggerEl) {
            const tabTrigger = new bootstrap.Tab(triggerEl);
            
            triggerEl.addEventListener('click', function(event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });
    });
</script>
@endsection
