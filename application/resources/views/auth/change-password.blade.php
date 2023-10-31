<x-auth-layout :title="__('Change Password')">
    <h1 class="auth-title">{{ __('Change Password') }}</h1>

    <!-- Session Status -->
    <x-message.success :message="session('status')"/>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="redirect" value="{{ request('redirect') }}">

        @if($forcedChange)
            <x-message.warning :message="$forcedChangeReason"/>
        @else
            <x-forms.group>
                <fluent-text-field type="password" name="current_password" required autocomplete="password"
                                   appearance="outline" class="full-width"
                                   dusk="current_password">{{ __('Current Password') }}</fluent-text-field>
                <x-input-error :messages="$errors->get('current_password')"/>
            </x-forms.group>
        @endif

        <!-- Password -->
        <x-forms.group>
            <fluent-text-field type="password" name="password" required autocomplete="new-password"
                               appearance="outline" class="full-width"
                               dusk="new_password">{{ __('New Password') }}</fluent-text-field>
            <x-input-error :messages="$errors->get('password')"/>
        </x-forms.group>

        <!-- Confirm Password -->
        <x-forms.group>
            <fluent-text-field type="password" name="password_confirmation" required autocomplete="new-password"
                               appearance="outline" class="full-width"
                               dusk="new_password_confirmation">{{ __('Confirm New Password') }}</fluent-text-field>
            <x-input-error :messages="$errors->get('password_confirmation')"/>
        </x-forms.group>

        <div>
            <fluent-button appearance="accent" class="full-width"
                           type="submit" dusk="submit">{{ __('Change Password') }}</fluent-button>
        </div>
    </form>
</x-auth-layout>
