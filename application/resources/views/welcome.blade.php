<x-layout>
    <x-container>
        <x-logo/>

        <h1>{{ __('Welcome') }}</h1>

        @auth
            <p><strong>{{ __('Signed in as :name', ['name' => auth()->user()->name]) }}</strong></p>
            <x-logout/>
        @endauth

        @guest
            <a href="{{ route('login') }}">{{ __('Log in') }}</a>
        @endguest

    </x-container>
</x-layout>
