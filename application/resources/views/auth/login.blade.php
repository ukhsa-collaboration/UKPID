<x-auth-layout :title="__('Log in')">
    <h1 class="auth-title">{{ __('Log in') }}</h1>

    <!-- Session Status -->
    <x-auth-session-status :status="session('status')"/>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <x-forms.group>
            <fluent-text-field type="email" name="email" value="{{old('email')}}" required autofocus
                               autocomplete="username" appearance="outline" placeholder="user@email.com"
                               class="full-width" dusk="email">{{ __('Email') }}</fluent-text-field>
            <x-input-error :messages="$errors->get('email')"/>
        </x-forms.group>

        <!-- Password -->
        <x-forms.group>
            <fluent-text-field type="password" name="password" required autocomplete="current-password"
                               appearance="outline" class="full-width"
                               dusk="password">{{ __('Password') }}</fluent-text-field>
            <x-input-error :messages="$errors->get('password')"/>
        </x-forms.group>

        @if (Route::has('password.request'))
            <p><a href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a></p>
        @endif

        <div>
            <fluent-button appearance="accent" class="full-width" type="submit"
                           dusk="submit">{{ __('Log in') }}</fluent-button>
        </div>
    </form>
</x-auth-layout>
