@extends('layouts.app')

@section('title', 'À propos')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">À propos de Quiz Master</h2>
                </div>
                <div class="card-body">
                    <p>
                        Quiz Master est une plateforme éducative interactive qui permet aux utilisateurs de tester leurs connaissances 
                        dans différents domaines grâce à des quiz variés.
                    </p>
                    
                    <h4>Notre mission</h4>
                    <p>
                        Rendre l'apprentissage ludique et accessible à tous en proposant une expérience interactive
                        et engageante à travers des quiz personnalisés.
                    </p>
                    
                    <h4>Nos fonctionnalités</h4>
                    <ul>
                        <li>Création de quiz personnalisés</li>
                        <li>Catégories variées pour tous les centres d'intérêt</li>
                        <li>Statistiques détaillées de performance</li>
                        <li>Système de badges pour récompenser la progression</li>
                        <li>Mode invité pour jouer sans compte</li>
                    </ul>
                    
                    <h4>Notre équipe</h4>
                    <p>
                        Quiz Master a été développé par une équipe passionnée d'éducateurs et de développeurs
                        qui croient au pouvoir de l'apprentissage interactif.
                    </p>
                    
                    <h4>Contactez-nous</h4>
                    <p>
                        Si vous avez des questions ou des suggestions, n'hésitez pas à nous contacter via notre 
                        <a href="{{ route('contact') }}">formulaire de contact</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
