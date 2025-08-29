@props(['notifications' => []])

<div id="notification-bell" class="fixed top-4 right-4 z-50">
    <div class="relative">
        <!-- Bell Icon with Badge -->
        <button id="notification-toggle" class="relative p-2 bg-gray-800 hover:bg-gray-700 border border-gray-600 rounded-lg shadow-lg transition-all duration-200">
            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.5 3.75a6 6 0 0 1 6 6v3.75l1.5 1.5H3l1.5-1.5V9.75a6 6 0 0 1 6-6z"></path>
            </svg>
            @if(count($notifications) > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-black text-xs rounded-full h-4 w-4 flex items-center justify-center">
                    {{ count($notifications) > 9 ? '9+' : count($notifications) }}
                </span>
            @endif
        </button>

        <!-- Dropdown Notifications -->
        <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-blue-900 border border-blue-700 rounded-lg shadow-xl max-h-96 overflow-y-auto transform transition-all duration-200">
            <div class="p-4 border-b border-blue-700 flex justify-between items-center">
                <h3 class="text-sm font-medium text-black">New notifications</h3>
                @if(count($notifications) > 0)
                    <button id="mark-all-read" class="text-xs text-blue-200 hover:text-black font-medium">Mark all as read</button>
                @endif
            </div>
            
            <div class="py-2">
                @forelse($notifications as $notification)
                <div class="notification-item px-4 py-3 hover:bg-blue-800 border-b border-blue-700 last:border-b-0 transition-colors duration-150" 
                     data-notification-id="{{ $notification['id'] ?? $loop->index }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            @if(($notification['type'] ?? '') === 'overdue')
                                <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                            @else
                                <div class="w-2 h-2 bg-blue-300 rounded-full"></div>
                            @endif
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-black truncate">{{ $notification['title'] ?? 'Reminder' }}</p>
                            <p class="text-sm text-blue-200 truncate">{{ $notification['message'] ?? '' }}</p>
                            @if(isset($notification['time']))
                            <p class="text-xs text-blue-300 mt-1">{{ $notification['time'] }}</p>
                            @endif
                        </div>
                        <div class="ml-2 flex-shrink-0">
                            <button type="button" class="notification-close text-blue-300 hover:text-black transition-colors duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.5 3.75a6 6 0 0 1 6 6v3.75l1.5 1.5H3l1.5-1.5V9.75a6 6 0 0 1 6-6z"></path>
                    </svg>
                    <p class="text-sm text-blue-200 mt-2">- No new notifications -</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function() {
    const $toggle = $('#notification-toggle');
    const $dropdown = $('#notification-dropdown');
    const $markAllRead = $('#mark-all-read');
    
    // Toggle dropdown with animation
    $toggle.on('click', function(e) {
        e.stopPropagation();
        $dropdown.toggleClass('hidden');
        
        if (!$dropdown.hasClass('hidden')) {
            $dropdown.addClass('scale-95 opacity-0');
            setTimeout(() => {
                $dropdown.removeClass('scale-95 opacity-0');
            }, 10);
        }
    });
    
    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#notification-bell').length) {
            $dropdown.addClass('hidden');
        }
    });
    
    // Handle individual notification close
    $(document).on('click', '.notification-close', function(e) {
        e.stopPropagation();
        const $item = $(this).closest('.notification-item');
        const notificationId = $item.data('notification-id');
        
        // Mark as read via AJAX
        $.post('{{ route("notifications.mark-as-read") }}', {
            notification_id: notificationId,
            _token: '{{ csrf_token() }}'
        }).done(function() {
            $item.fadeOut(300, function() {
                $item.remove();
                updateNotificationCount();
            });
        });
    });
    
    // Mark all as read
    $markAllRead.on('click', function(e) {
        e.stopPropagation();
        
        // Mark all as read via AJAX
        $.post('{{ route("notifications.mark-all-as-read") }}', {
            _token: '{{ csrf_token() }}'
        }).done(function() {
            $('.notification-item').fadeOut(300, function() {
                $(this).remove();
                updateNotificationCount();
            });
        });
    });
    
    // Update notification count
    function updateNotificationCount() {
        const count = $('.notification-item').length;
        const $badge = $('.bg-red-500');
        
        if (count === 0) {
            $badge.fadeOut(300, function() {
                $(this).remove();
            });
            $markAllRead.hide();
        } else {
            $badge.text(count > 9 ? '9+' : count);
        }
    }
    
    // Auto-hide notifications after 30 seconds (optional)
    $('.notification-item').each(function() {
        const $item = $(this);
        setTimeout(() => {
            if ($item.length) {
                const notificationId = $item.data('notification-id');
                
                // Mark as read via AJAX before hiding
                $.post('{{ route("notifications.mark-as-read") }}', {
                    notification_id: notificationId,
                    _token: '{{ csrf_token() }}'
                }).done(function() {
                    $item.fadeOut(300, function() {
                        $item.remove();
                        updateNotificationCount();
                    });
                });
            }
        }, 30000);
    });
});
</script>
@endpush
