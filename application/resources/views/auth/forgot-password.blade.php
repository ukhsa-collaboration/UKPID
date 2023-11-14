<x-auth-layout :title="__('Recover your account')">
    <h1 class="auth-title">{{ __('Recover your account') }}</h1>

    <!-- Session Status -->
    <x-message.success :message="session('status')"/>

    @if(!session('status'))
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <x-forms.group>
                <fluent-text-field id="email" type="email" name="email" value="{{old('email')}}" required autofocus
                                   autocomplete="username" appearance="outline" placeholder="user@email.com"
                                   class="full-width" dusk="email">{{ __('Email') }}</fluent-text-field>
                <x-input-error :messages="$errors->get('email')"/>
            </x-forms.group>

            <div class="button-group button-group--align-right">
                <fluent-button appearance="neutral"
                               type="button" dusk="back"
                               onclick="window.location.href = '{{ route('login') }}'">{{ __('Back') }}</fluent-button>
                <fluent-button appearance="accent"
                               type="submit" dusk="submit">{{ __('Next') }}</fluent-button>
            </div>
        </form>
    @else
        <form action="{{ route('login') }}">
            <fluent-button appearance="accent" class="full-width" type="submit">{{ __('Log in') }}</fluent-button>
        </form>
    @endif
</x-auth-layout>
