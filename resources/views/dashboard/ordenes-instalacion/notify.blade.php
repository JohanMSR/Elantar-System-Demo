<style>
    .notification-item {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .notification-item:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }

    .notification-item.unread {
        border-left: 4px solid #3B82F6;
        background: #f8fafc;
    }

    .notification-item.read {
        border-left: 4px solid #e5e7eb;
        opacity: 0.8;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }

    .notification-title {
        font-weight: 600;
        color: #1f2937;
        margin: 0;
        font-size: 0.95rem;
    }

    .notification-time {
        color: #6b7280;
        font-size: 0.8rem;
        white-space: nowrap;
        margin-left: 1rem;
    }

    .notification-message {
        color: #4b5563;
        margin-bottom: 0.5rem;
        line-height: 1.4;
        font-size: 0.9rem;
    }

    .notification-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.5rem;
    }

    .notification-source {
        color: #9ca3af;
        font-size: 0.75rem;
        font-style: italic;
    }

    .notification-status {
        padding: 0.15rem 0.5rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .status-unread {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .status-read {
        background-color: #f3f4f6;
        color: #6b7280;
    }

    .notifications-header {
        background: linear-gradient(135deg, #3B82F6 0%, #1d4ed8 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 8px 8px 0 0;
        margin-bottom: 1.5rem;
    }

    .notifications-header h5 {
        color: white;
        margin: 0;
        font-weight: 600;
    }

    .notifications-header .badge {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        font-weight: 500;
    }

    .notifications-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0 0.5rem;
    }

    .notifications-controls small {
        color: #6b7280;
        font-size: 0.85rem;
    }

    .btn-mark-read {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-mark-read:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-mark-read:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .empty-notifications {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }

    .empty-notifications i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #d1d5db;
    }

    .empty-notifications h6 {
        color: #4b5563;
        margin-bottom: 0.5rem;
    }

    .empty-notifications p {
        color: #9ca3af;
        margin: 0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .notification-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .notification-time {
            margin-left: 0;
            margin-top: 0.25rem;
        }

        .notifications-controls {
            flex-direction: column;
            gap: 0.5rem;
            align-items: stretch;
        }

        .btn-mark-read {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="container-fluid">
    <div class="notifications-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-bell me-2"></i>Notificaciones de la Orden #{{ $co_aplicacion }}</h5>
            <span class="badge">{{ $notifications->count() }} {{ $notifications->count() == 1 ? 'notificación' : 'notificaciones' }}</span>
        </div>
    </div>

    @if($notifications->count() > 0)
        <div class="notifications-controls">
            <small>
                @if($notification_type == 'unread')
                    Mostrando notificaciones no leídas
                @else
                    Mostrando todas las notificaciones
                @endif
            </small>
            @if($notification_type == 'unread' && $notifications->count() > 0)
                <button class="btn btn-mark-read" onclick="markAllAsRead({{ $co_aplicacion }})">
                    <i class="fas fa-check me-1"></i>Marcar todas como leídas
                </button>
            @endif
        </div>

        <div class="notifications-list">
            @foreach($notifications as $notification)
                <div class="notification-item {{ $notification->bo_visto ? 'read' : 'unread' }}">
                    <div class="notification-header">
                        <h6 class="notification-title">
                            @switch($notification->co_tiponoti)
                                @case(9)
                                    <i class="fas fa-info-circle me-2"></i>Actualización de la orden
                                    @break
                                @default
                                    <i class="fas fa-bell me-2"></i>Notificación
                            @endswitch
                        </h6>
                        <span class="notification-time">{{ $notification->fe_registro_formatted ?? 'Fecha no disponible' }}</span>
                    </div>
                    
                    <div class="notification-message">
                        {{ $notification->tx_mensaje ?? 'Nueva notificación para la orden de instalación' }}
                    </div>
                    
                    <div class="notification-footer">
                        <span class="notification-source">
                            <i class="fas fa-cog me-1"></i>Sistema de órdenes
                        </span>
                        <span class="notification-status {{ $notification->bo_visto ? 'status-read' : 'status-unread' }}">
                            {{ $notification->bo_visto ? 'Leída' : 'No leída' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-notifications">
            <i class="fas fa-bell-slash"></i>
            <h6>No hay notificaciones</h6>
            <p>
                @if($notification_type == 'unread')
                    No tienes notificaciones no leídas para esta orden
                @else
                    No hay notificaciones disponibles para esta orden
                @endif
            </p>
        </div>
    @endif
</div>

<script>
    function markAllAsRead(coAplicacion) {
        const button = document.querySelector('.btn-mark-read');
        const originalText = button.innerHTML;
        
        // Deshabilitar botón y mostrar carga
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Marcando...';
        
        fetch(`/installation/notifications/${coAplicacion}/mark-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                co_aplicacion: coAplicacion
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar las notificaciones visualmente
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                    item.classList.add('read');
                    const status = item.querySelector('.notification-status');
                    if (status) {
                        status.textContent = 'Leída';
                        status.classList.remove('status-unread');
                        status.classList.add('status-read');
                    }
                });
                
                // Ocultar el botón después de marcarlo como leído
                button.style.display = 'none';
                
                // Actualizar el badge en la página principal si existe
                if (window.updateNotificationBadge) {
                    window.updateNotificationBadge(coAplicacion);
                }
            } else {
                throw new Error(data.message || 'Error al marcar como leídas');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Restaurar botón en caso de error
            button.disabled = false;
            button.innerHTML = originalText;
            
            // Mostrar mensaje de error
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger mt-2';
            errorDiv.textContent = 'Error al marcar las notificaciones como leídas. Inténtalo nuevamente.';
            button.parentNode.insertBefore(errorDiv, button.nextSibling);
            
            // Remover mensaje de error después de 3 segundos
            setTimeout(() => {
                errorDiv.remove();
            }, 3000);
        });
    }
</script> 