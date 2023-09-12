@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'auth-session-status message-with-icon']) }}>
        <x-icons.checkmark_filled_12/>{{ $status }}
    </div>
@endif
