@if(count($notifications) > 0)
    @foreach($notifications as $notification)
        <a class="dropdown-item d-flex align-items-center" href="{{ isset($notification->data['offre_id']) ? route('offres.show', $notification->data['offre_id']) : route('notifications.index') }}">
            <div class="me-3">
                @if(isset($notification->data['type']) && $notification->data['type'] == 'pending_offer')
                    <div class="icon-circle bg-warning">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                @elseif(isset($notification->data['status']) && $notification->data['status'] == 'approuvée')
                    <div class="icon-circle bg-success">
                        <i class="fas fa-check text-white"></i>
                    </div>
                @elseif(isset($notification->data['status']) && $notification->data['status'] == 'rejetée')
                    <div class="icon-circle bg-danger">
                        <i class="fas fa-times text-white"></i>
                    </div>
                @else
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-bell text-white"></i>
                    </div>
                @endif
            </div>
            <div>
                <div class="small text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                <span class="font-weight-bold">
                    @if(isset($notification->data['type']) && $notification->data['type'] == 'pending_offer')
                        Nouvelle offre à approuver: {{ $notification->data['data']['offer_title'] ?? '' }}
                    @elseif(isset($notification->data['titre']) && isset($notification->data['status']))
                        Offre "{{ $notification->data['titre'] }}" {{ $notification->data['status'] }}
                    @else
                        {{ $notification->data['message'] ?? 'Notification' }}
                    @endif
                </span>
            </div>
        </a>
    @endforeach
    
    <div class="dropdown-item text-center">
        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-secondary">Marquer tout comme lu</button>
        </form>
    </div>
@else
    <a class="dropdown-item d-flex align-items-center" href="#">
        <div>
            <span class="font-weight-bold">Aucune notification non lue</span>
        </div>
    </a>
@endif

<a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications.index') }}">Voir toutes les notifications</a>