<x-auth-layout :title="__('Confirm your password')">
    <h1 class="auth-title">{{ __('Confirm your password') }}</h1>

    <p>{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <x-forms.group>
            <fluent-text-field type="password" name="password" required autocomplete="current-password"
                               appearance="outline" class="full-width">{{ __('Password') }}</fluent-text-field>
            <x-input-error :messages="$errors->get('password')"/>
        </x-forms.group>

        <div>
            <fluent-button appearance="accent" class="full-width" type="submit">{{ __('Confirm') }}</fluent-button>
        </div>
    </form>
</x-auth-layout>
