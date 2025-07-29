<div class="chat-container shadow-sm rounded-3" id="chat-{{ $applicationId }}">
    <div class="chat-header">
        <h5 class="fw-bold m-0 d-flex align-items-center"><i data-feather="message-circle" class="me-2"></i> Chat</h5>
        <span class="close-chat"></span>
    </div>
    
    <div class="chat-messages" id="messages-container">
    </div>
    
    <!-- Contenedor para el mensaje que se está respondiendo -->
    <div id="reply-container" class="reply-container d-none">
        <div class="reply-content">
            <div class="reply-text"></div>
            <button class="btn-close btn-sm" id="cancel-reply"></button>
        </div>
    </div>
    
    <div class="chat-input d-flex gap-2 p-2">
        <input type="text" class="form-control" id="message-input" placeholder="Escribe un mensaje...">
        <button id="send-message" class="modal-btn">Enviar</button>
    </div>
</div>