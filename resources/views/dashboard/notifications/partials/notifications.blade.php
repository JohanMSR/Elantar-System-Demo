@php 
    use Illuminate\Support\Facades\Auth;
@endphp

@foreach($notifications as $notification)
    @php
        $icon = match ($notification->co_tiponoti) {
            1 => 'message-square',
            2 => 'file-text',
            3 => 'calendar',
            4 => 'book-open',
            5 => 'video',
            default => 'bell'
        };

        $type = match ($notification->co_tiponoti) {
            1 => 'Mensaje',
            2 => 'Documento',
            3 => 'Evento',
            4 => 'Blog',
            5 => 'Video',
            default => 'Notificación'
        };
        
        // Extraer el número de aplicación del texto entre corchetes si es una notificación de tipo mensaje o default
        $applicationId = null;
        if (($notification->co_tiponoti == 1 || !in_array($notification->co_tiponoti, [1,2,3,4,5])) && 
            preg_match('/\[(\d+)\]/', $notification->tx_info_general, $matches)) {
            $applicationId = $matches[1];
        }
        
        // Determinar la URL de destino según el tipo de notificación
        $notificationUrl = '';
        $isClickable = false;
        
        if (($notification->co_tiponoti == 1 || !in_array($notification->co_tiponoti, [1,2,3,4,5])) && $applicationId) {
            $isClickable = true;
            if (Auth::check() && Auth::user()->co_tipo_usuario == 3) {
                // Si es usuario administrativo, redirigir a la vista de team-details
                $notificationUrl = '/dashboard/team-details?co_aplicacion=' . $applicationId . '&from_notification=1';
            } else {
                // Si no es usuario administrativo, redirigir a la vista de projects
                $notificationUrl = route('project_Search', ['type' => 'teamprojects', 'open_chat' => $applicationId]);
            }
        } 
        // Para otros tipos de notificaciones
        elseif ($notification->co_tiponoti == 2) {
            $isClickable = true;
            $notificationUrl = route('u_documents');
        }
        elseif ($notification->co_tiponoti == 3) {
            $isClickable = true;
            $notificationUrl = route('event');
        }
        elseif ($notification->co_tiponoti == 4) {
            $isClickable = true;
            $notificationUrl = route('u_blog');
        }
        elseif ($notification->co_tiponoti == 5) {
            $isClickable = true;
            $notificationUrl = route('u_videos');
        }
    @endphp
    
    <div class="notification-item {{ $notification->highlight ? 'notification-unread' : '' }} {{ $isClickable ? 'clickable-notification' : '' }}">
        @if($isClickable)
            <a href="{{ $notificationUrl }}" class="notification-link">
        @endif
        <div class="d-flex">
            <div class="notification-icon-wrapper">
                <i data-feather="{{ $icon }}" class="notification-icon"></i>
            </div>
            <div class="notification-content">
                <h6 class="notification-title">
                    {{ $type }}
                    @if($notification->highlight)
                        <span class="badge bg-primary"
                            style="font-size: 0.7rem; vertical-align: middle;">Nueva</span>
                    @endif
                    @if($isClickable)
                        <span class="ms-1 notification-indicator">
                            <i data-feather="external-link" style="width: 14px; height: 14px;"></i>
                        </span>
                    @endif
                </h6>
                <p class="notification-message">
                    {{ $notification->tx_info_general }}
                </p>

                <div class="notification-meta">
                    <span class="notification-time">
                        <i data-feather="clock"></i>
                        <span data-time="{{ $notification->fe_registro }}">
                            {{-- El contenido de este span será reemplazado por Javascript --}}
                        </span>
                    </span>
                    @if(!$notification->bo_visto)
                        @if($isClickable)
                            </a>
                        @endif
                        <form
                            action="{{ route('notifications.markAsRead', $notification->co_usrnotificahis) }}"
                            method="POST" class="d-inline ms-auto mark-read-form">
                            @csrf
                            <button type="submit" class="mark-read-btn">
                                <i data-feather="check" style="width: 16px; height: 16px;"></i>
                                Marcar como leída
                            </button>
                        </form>
                        @if($isClickable)
                            <a href="{{ $notificationUrl }}" class="d-none"></a>
                        @endif
                    @else
                        @if($isClickable)
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @if($isClickable && $notification->bo_visto)
            </a>
        @endif
    </div>
@endforeach

<style>
.clickable-notification {
    cursor: pointer;
    transition: var(--transition-fast);
    position: relative;
}

.clickable-notification:hover {
    background-color: rgba(19, 192, 230, 0.05);
}

.notification-link {
    color: inherit;
    text-decoration: none;
    display: block;
}

.mark-read-form {
    position: relative;
    z-index: 10;
}

.mark-read-btn {
    color: var(--color-primary);
    font-size: 0.875rem;
    padding: 0.625rem 1.25rem;
    border: 1.5px solid var(--color-primary);
    border-radius: var(--radius-md);
    transition: var(--transition-normal);
    background: transparent;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    font-weight: 500;
    box-shadow: var(--shadow-btn);
}

.mark-read-btn:hover {
    color: #fff;
    background: var(--color-primary);
    box-shadow: 0 4px 12px rgba(19, 192, 230, 0.2);
    transform: translateY(-1px);
}

.notification-icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(19, 192, 230, 0.1);
    margin-right: 1.25rem;
    box-shadow: 0 2px 8px rgba(19, 192, 230, 0.15);
    flex-shrink: 0;
}

.notification-icon {
    width: 22px;
    height: 22px;
    color: var(--color-primary);
    stroke-width: 2;
}

.notification-indicator {
    color: var(--color-secondary);
}

.badge.bg-primary {
    background-color: var(--color-primary) !important;
    color: white;
}

.notification-unread {
    background-color: rgba(19, 192, 230, 0.05);
}

.notification-unread::before {
    background: var(--color-primary);
    box-shadow: 0 0 8px rgba(19, 192, 230, 0.3);
}
</style>
