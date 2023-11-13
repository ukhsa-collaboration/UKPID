@props(['message'])

@if ($message)
    <div {{ $attributes->merge(['class' => 'message message--warning message-with-icon']) }}>
        <x-icons.warning_12_filled/>{{ $message }}
    </div>
@endif
