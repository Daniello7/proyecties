@component('mail::message')
    # Token API Control 77

    {{ __('Hello!') . ' ' . $user->name}}.
    Your token is: {{ $token }}.

    @component('mail::button', ['url' => ''])
        Button Text
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
