<x-auth-layout :title="__('Reset Password')">
    <h1 class="auth-title">{{ __('Reset Password') }}</h1>

    <!-- Session Status -->
    <x-message.success :message="session('status')"/>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <input type="hidden" name="email" value="{{ $request->email }}">
        <x-input-error :messages="$errors->get('email')"/>

        <!-- Password -->
        <x-forms.group>
            <fluent-text-field type="password" name="password" required autocomplete="new-password"
                               appearance="outline" class="full-width"
                               dusk="password">{{ __('Password') }}</fluent-text-field>
            <x-input-error :messages="$errors->get('password')"/>
        </x-forms.group>

        <!-- Confirm Password -->
        <x-forms.group>
            <fluent-text-field type="password" name="password_confirmation" required autocomplete="new-password"
                               appearance="outline" class="full-width"
                               dusk="password_confirmation">{{ __('Confirm Password') }}</fluent-text-field>
            <x-input-error :messages="$errors->get('password_confirmation')"/>
        </x-forms.group>

        <div>
            <fluent-button appearance="accent" class="full-width"
                           type="submit" dusk="submit">{{ __('Reset Password') }}</fluent-button>
        </div>
    </form>
</x-auth-layout>
