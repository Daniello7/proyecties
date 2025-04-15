@component('mail::message')
    # Token API Proyecties.test

    Hello {{ $user->name }}.
    Your token is: {{ $token }}.

    @component('mail::button', ['url' => ''])
        Button Text
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
