@component('mail::message')
    # New Package

    NEW PACKAGE FOR YOU HAS ARRIVED!

    @component('mail::button', ['url' => ''])
        Button Text
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
