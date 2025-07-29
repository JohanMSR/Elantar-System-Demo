<div class="notification-container" id="notification-container">
    <div class="notification-list" id="notification-list">
        @include('dashboard.account.partials.notifications', ['notifications' => $notifications ?? []])
    </div>
</div>
<style>
    .notification-container {
            background: #fff;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            max-height: 180vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--color-primary) #f0f7ff;
            margin-bottom: 2rem;
        }

        .notification-container::-webkit-scrollbar {
            width: 8px;
        }

        .notification-container::-webkit-scrollbar-track {
            background: #f0f7ff;
            border-radius: 4px;
        }

        .notification-container::-webkit-scrollbar-thumb {
            background-color: var(--color-primary);
            border-radius: 4px;
            border: 2px solid #f0f7ff;
        }
        .notification-list {
            padding: 0.5rem;
        }
</style>    