@extends('layouts.app')

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Tableau de bord JobMatch</h3>
        </div>
        <div class="block-content">
            <p>Bienvenue, {{ Auth::user()->name }} {{ Auth::user()->prenom }}!</p>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="block block-rounded block-bordered">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Mon profil</h3>
                        </div>
                        <div class="block-content">
                            <p><strong>Nom:</strong> {{ Auth::user()->name }}</p>
                            <p><strong>Prénom:</strong> {{ Auth::user()->prenom }}</p>
                            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            <p><strong>Rôle:</strong> {{ Auth::user()->role->name }}</p>
                            
                            <a href="#" class="btn btn-sm btn-alt-primary">Modifier mon profil</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="block block-rounded block-bordered">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Activités récentes</h3>
                        </div>
                        <div class="block-content">
                            <p>Aucune activité récente.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection