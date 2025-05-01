@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-lg w-100" style="max-width: 800px;">
            <div class="card-header text-center bg-primary text-white">
                <h4>Statistiques</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Total Utilisateurs</h5>
                                <p class="card-text display-4">{{ $stats['total_utilisateurs'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Total Recruteurs</h5>
                                <p class="card-text display-4">{{ $stats['total_recruteurs'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Total Candidats</h5>
                                <p class="card-text display-4">{{ $stats['total_candidats'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Total Offres</h5>
                                <p class="card-text display-4">{{ $stats['total_offres'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection