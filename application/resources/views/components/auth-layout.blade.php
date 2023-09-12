<x-layout :title="$title ?? null">
    <x-container>
        <div class="auth-container">
            <x-logo/>
            {{ $slot }}
        </div>
    </x-container>
</x-layout>
