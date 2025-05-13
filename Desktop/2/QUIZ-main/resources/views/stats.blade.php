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
                                        <h3>85%</h3>
                                        <p>Taux de réussite</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card bg-success">
                                    <div class="stat-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>28</h3>
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
                                        <h3>264</h3>
                                        <p>Questions Répondues</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card bg-warning">
                                    <div class="stat-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>8.5h</h3>
                                        <p>Temps Total</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stats-charts mb-5">
                        <h3 class="section-title">Performance par Catégorie</h3>
                        <div class="chart-container">
                            <canvas id="categoryChart" height="300"></canvas>
                        </div>
                    </div>
                    
                    <div class="stats-charts mb-5">
                        <h3 class="section-title">Progression Mensuelle</h3>
                        <div class="chart-container">
                            <canvas id="progressChart" height="300"></canvas>
                        </div>
                    </div>
                    
                    <div class="stats-strengths-weaknesses">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="stats-box">
                                    <h3 class="section-title">Points Forts</h3>
                                    <ul class="stats-list">
                                        <li>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>HTML/CSS</span>
                                                <span class="badge bg-success">95%</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>JavaScript</span>
                                                <span class="badge bg-success">90%</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>Laravel</span>
                                                <span class="badge bg-success">88%</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 88%" aria-valuenow="88" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stats-box">
                                    <h3 class="section-title">Points à Améliorer</h3>
                                    <ul class="stats-list">
                                        <li>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>DevOps</span>
                                                <span class="badge bg-warning">65%</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>Sécurité Web</span>
                                                <span class="badge bg-warning">60%</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>Tests Unitaires</span>
                                                <span class="badge bg-danger">45%</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stats-recent mt-5">
                        <h3 class="section-title">Activité Récente</h3>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Quiz</th>
                                        <th>Date</th>
                                        <th>Score</th>
                                        <th>Temps</th>
                                        <th>Résultat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>JavaScript Avancé</td>
                                        <td>13 mai 2025</td>
                                        <td>85%</td>
                                        <td>18 min</td>
                                        <td><span class="badge bg-success">Réussi</span></td>
                                    </tr>
                                    <tr>
                                        <td>Laravel: Les bases</td>
                                        <td>10 mai 2025</td>
                                        <td>90%</td>
                                        <td>15 min</td>
                                        <td><span class="badge bg-success">Réussi</span></td>
                                    </tr>
                                    <tr>
                                        <td>DevOps & Cloud</td>
                                        <td>5 mai 2025</td>
                                        <td>65%</td>
                                        <td>25 min</td>
                                        <td><span class="badge bg-warning">Passable</span></td>
                                    </tr>
                                    <tr>
                                        <td>Sécurité Web</td>
                                        <td>1 mai 2025</td>
                                        <td>45%</td>
                                        <td>22 min</td>
                                        <td><span class="badge bg-danger">Échoué</span></td>
                                    </tr>
                                    <tr>
                                        <td>Base de données SQL</td>
                                        <td>25 avril 2025</td>
                                        <td>78%</td>
                                        <td>20 min</td>
                                        <td><span class="badge bg-success">Réussi</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
    
    .section-title {
        font-size: 1.25rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .stat-card {
        border-radius: 10px;
        padding: 20px;
        color: white;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        transition: all 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-icon {
        font-size: 2.5rem;
        margin-right: 15px;
    }
    
    .stat-content h3 {
        font-size: 1.75rem;
        margin: 0;
    }
    
    .stat-content p {
        margin: 0;
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .chart-container {
        margin-bottom: 30px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
    }
    
    .stats-box {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .stats-list {
        list-style: none;
        padding: 0;
    }
    
    .stats-list li {
        margin-bottom: 15px;
    }
    
    .progress {
        height: 10px;
        margin-top: 5px;
    }
    
    .table {
        margin-bottom: 0;
    }
    
    @media (max-width: 992px) {
        .profile-sidebar {
            margin-bottom: 30px;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart for categories performance
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: ['HTML/CSS', 'JavaScript', 'PHP', 'Laravel', 'Base de données', 'DevOps', 'Sécurité'],
                datasets: [{
                    label: 'Taux de réussite (%)',
                    data: [95, 90, 82, 88, 78, 65, 60],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(199, 199, 199, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(199, 199, 199, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
        
        // Chart for monthly progress
        const progressCtx = document.getElementById('progressChart').getContext('2d');
        const progressChart = new Chart(progressCtx, {
            type: 'line',
            data: {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai'],
                datasets: [{
                    label: 'Quiz Complétés',
                    data: [4, 6, 5, 8, 5],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3
                }, {
                    label: 'Score Moyen (%)',
                    data: [65, 70, 75, 80, 85],
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
    });
</script>
@endsection
