@component('mail::message')
    # Welcome

    WELCOME NEW USER!

    @component('mail::button', ['url' => ''])
        Button Text
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
