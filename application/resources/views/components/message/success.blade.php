@props(['message'])

@if ($message)
    <div {{ $attributes->merge(['class' => 'message message--success message-with-icon']) }}>
        <x-icons.checkmark_filled_12/>{{ $message }}
    </div>
@endif
