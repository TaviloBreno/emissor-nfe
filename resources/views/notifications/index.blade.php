@extends('layouts.dashboard')

@section('title', 'Notificações - Emissor NFe')

@section('page-title', 'Notificações')
@section('page-description', 'Gerencie suas notificações do sistema')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div class="flex items-center space-x-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Notificações</h2>
                @if($unreadCount > 0)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300">
                        {{ $unreadCount }} não lidas
                    </span>
                @endif
            </div>
            
            <div class="flex space-x-2">
                <button onclick="markAllAsRead()" 
                        class="btn-secondary text-sm">
                    <i class="fas fa-check-double mr-2"></i>Marcar todas como lidas
                </button>
                <button onclick="deleteReadNotifications()" 
                        class="btn-secondary text-sm">
                    <i class="fas fa-trash mr-2"></i>Excluir lidas
                </button>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="space-y-3">
            @forelse($notifications as $notification)
                <div class="notification-item bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow {{ $notification->isUnread() ? 'ring-2 ring-blue-500/20' : '' }}"
                     data-notification-id="{{ $notification->id }}">
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center
                                    @if(($notification->data['color'] ?? 'blue') == 'green') bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400
                                    @elseif(($notification->data['color'] ?? 'blue') == 'red') bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400
                                    @elseif(($notification->data['color'] ?? 'blue') == 'yellow') bg-yellow-100 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400
                                    @else bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 @endif">
                                    <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }}"></i>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white 
                                            {{ is_null($notification->read_at) ? 'font-bold' : '' }}">
                                            {{ $notification->data['title'] ?? 'Notificação' }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $notification->data['message'] ?? '' }}
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                            <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2 ml-4">
                                        @if(isset($notification->data['url']) && $notification->data['url'])
                                            <a href="{{ $notification->data['url'] }}" 
                                               onclick="markAsRead('{{ $notification->id }}')"
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-500 text-sm">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                        
                                        @if(is_null($notification->read_at))
                                            <button onclick="markAsRead('{{ $notification->id }}')" 
                                                    class="text-green-600 dark:text-green-400 hover:text-green-500"
                                                    title="Marcar como lida">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @else
                                            <button onclick="markAsUnread('{{ $notification->id }}')" 
                                                    class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-500"
                                                    title="Marcar como não lida">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif
                                        
                                        <button onclick="deleteNotification('{{ $notification->id }}')" 
                                                class="text-red-600 dark:text-red-400 hover:text-red-500"
                                                title="Excluir notificação">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Unread Indicator -->
                                @if(is_null($notification->read_at))
                                    <div class="absolute top-4 left-4 w-3 h-3 bg-blue-500 rounded-full"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bell-slash text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma notificação</h3>
                    <p class="text-gray-500 dark:text-gray-400">Você não possui notificações no momento.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function markAsUnread(notificationId) {
    fetch(`/notifications/${notificationId}/mark-unread`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deleteNotification(notificationId) {
    if (confirm('Tem certeza que deseja excluir esta notificação?')) {
        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

function deleteReadNotifications() {
    if (confirm('Tem certeza que deseja excluir todas as notificações lidas?')) {
        fetch('/notifications/delete-read', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endsection