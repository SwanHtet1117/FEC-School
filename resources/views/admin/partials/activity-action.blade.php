@php
    $normalizedAction = strtolower($activity->action);
    $actionColor = match (true) {
        in_array($normalizedAction, ['login', 'logout'], true) => 'authentication',
        str_contains($normalizedAction, 'added') => 'added',
        str_contains($normalizedAction, 'updated') => 'updated',
        str_contains($normalizedAction, 'deleted') => 'deleted',
        str_contains($normalizedAction, 'approved') => 'approved',
        str_contains($normalizedAction, 'enabled'),
        str_contains($normalizedAction, 'disabled') => 'availability',
        default => 'other',
    };
@endphp
<span class="activity-action activity-action--{{ $actionColor }}">{{ $activity->action }}</span>
