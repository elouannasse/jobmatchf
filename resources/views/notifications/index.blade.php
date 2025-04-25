@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mes notifications</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Notifications</li>
        </ol>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Toutes les notifications</h6>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-secondary">Marquer tout comme lu</button>
                </form>
            @endif
        </div>
        <div class="card-body">
            <div class="list-group">
                @forelse($notifications as $notification)
                    <div class="list-group-item d-flex justify-content-between align-items-center {{ $notification->read_at ? 'bg-light' : '' }}">
                        <div class="flex-grow-1">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">
                                    @if(isset($notification->data['type']) && $notification->data['type'] == 'pending_offer')
                                        Nouvelle offre à approuver
                                    @elseif(isset($notification->data['titre']) && isset($notification->data['status']))
                                        Offre "{{ $notification->data['titre'] }}" {{ $notification->data['status'] }}
                                    @else
                                        {{ $notification->data['titre'] ?? 'Notification' }}
                                    @endif
                                </h5>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">
                                @if(isset($notification->data['type']) && $notification->data['type'] == 'pending_offer')
                                    {{ $notification->data['data']['offer_title'] ?? '' }} (Publié par {{ $notification->data['data']['recruteur_name'] ?? '' }})
                                @else
                                    {{ $notification->data['message'] ?? '' }}
                                @endif
                            </p>
                            <div class="mt-2">
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary">Marquer comme lu</button>
                                    </form>
                                @endif
                                
                                @if(isset($notification->data['offre_id']))
                                    <a href="{{ route('offres.show', $notification->data['offre_id']) }}" class="btn btn-sm btn-primary">Voir l'offre</a>
                                @endif
                            </div>
                        </div>
                        <div>
                            @if(!$notification->read_at)
                                <span class="badge bg-primary">Non lu</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <p>Aucune notification</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection