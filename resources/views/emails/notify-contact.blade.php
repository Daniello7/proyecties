@component('mail::message')
    # Visit Notify

    A NEW VISIT HAS ARRIVED!

    @component('mail::button', ['url' => ''])
        Button Text
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
