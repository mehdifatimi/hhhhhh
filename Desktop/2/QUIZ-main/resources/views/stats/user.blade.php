@extends('layouts.app')

@section('title', 'Mes Statistiques')

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
                        <a href="{{ route('stats') }}" class="active">
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
                        <h2>Mes Statistiques</h2>
                    </div>
                    
                    <div class="stats-summary mb-5">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="stat-card bg-primary">
                                    <div class="stat-icon">
                                        <i class="fas fa-award"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>{{ number_format($averageScore, 1) }}%</h3>
                                        <p>Score moyen</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card bg-success">
                                    <div class="stat-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>{{ $completedAttempts }}</h3>
                                        <p>Quiz Complétés</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card bg-info">
                                    <div class="stat-icon">
                                        <i class="fas fa-brain"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>{{ $passedAttempts }}</h3>
                                        <p>Quiz Réussis</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card bg-warning">
                                    <div class="stat-icon">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>{{ number_format($bestScore, 1) }}%</h3>
                                        <p>Meilleur Score</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(count($topCategories) > 0)
                    <div class="stats-charts mb-5">
                        <h3 class="section-title">Performance par Catégorie</h3>
                        <div class="chart-container">
                            <canvas id="categoryChart" height="300"></canvas>
                        </div>
                    </div>
                    @endif
                    
                    @if(count($monthlyProgress) > 0)
                    <div class="stats-charts mb-5">
                        <h3 class="section-title">Progression Mensuelle</h3>
                        <div class="chart-container">
                            <canvas id="progressChart" height="300"></canvas>
                        </div>
                    </div>
                    @endif
                    
                    @if(count($topCategories) > 0)
                    <div class="stats-strengths-weaknesses">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="stats-box">
                                    <h3 class="section-title">Catégories les plus jouées</h3>
                                    <ul class="stats-list">
                                        @foreach($topCategories as $index => $category)
                                        <li>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>{{ $category->name }}</span>
                                                <span class="badge bg-primary">{{ $category->total }} quiz</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                    style="width: {{ ($category->total / $topCategories->max('total')) * 100 }}%" 
                                                    aria-valuenow="{{ $category->total }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="{{ $topCategories->max('total') }}"></div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stats-box">
                                    <h3 class="section-title">Mes Performances</h3>
                                    <div class="performance-stats">
                                        <div class="stat-item">
                                            <div class="stat-label">Taux de réussite</div>
                                            <div class="stat-value">
                                                {{ $completedAttempts > 0 ? number_format(($passedAttempts / $completedAttempts) * 100, 1) : 0 }}%
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar 
                                                    {{ $completedAttempts > 0 ? ($passedAttempts / $completedAttempts) * 100 >= 80 ? 'bg-success' : (($passedAttempts / $completedAttempts) * 100 >= 50 ? 'bg-warning' : 'bg-danger') : 'bg-secondary' }}" 
                                                    role="progressbar" 
                                                    style="width: {{ $completedAttempts > 0 ? ($passedAttempts / $completedAttempts) * 100 : 0 }}%" 
                                                    aria-valuenow="{{ $completedAttempts > 0 ? ($passedAttempts / $completedAttempts) * 100 : 0 }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="stat-item">
                                            <div class="stat-label">Progression</div>
                                            <div class="stat-value">
                                                {{ $totalAttempts }} quiz tentés
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <p>Vous n'avez pas encore complété de quiz. Commencez à jouer pour voir vos statistiques!</p>
                        <a href="{{ route('browse') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-play"></i> Découvrir les Quiz
                        </a>
                    </div>
                    @endif
                    
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
                            
                            @if(count($badges) > 3)
                            <div class="text-center mt-3">
                                <a href="{{ route('badges') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-award"></i> Voir tous mes badges
                                </a>
                            </div>
                            @endif
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart for categories performance
        @if(count($topCategories) > 0)
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($topCategories as $category)
                    '{{ $category->name }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Nombre de quiz',
                    data: [
                        @foreach($topCategories as $category)
                        {{ $category->total }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        @endif
        
        // Chart for monthly progress
        @if(count($monthlyProgress) > 0)
        const progressCtx = document.getElementById('progressChart').getContext('2d');
        const progressChart = new Chart(progressCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($monthlyProgress as $progress)
                    '{{ \Carbon\Carbon::createFromFormat('Y-m', $progress->month)->format('M Y') }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Nombre de quiz',
                    data: [
                        @foreach($monthlyProgress as $progress)
                        {{ $progress->count }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3
                }, {
                    label: 'Score moyen (%)',
                    data: [
                        @foreach($monthlyProgress as $progress)
                        {{ $progress->average_score }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        @endif
    });
</script>
@endsection
