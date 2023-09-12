@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'input-errors']) }}>
        @foreach ((array) $messages as $message)
            <li class="message-with-icon">
                <x-icons.warning_12_filled/>
                <span>{{ $message }}</span></li>
        @endforeach
    </ul>
@endif
