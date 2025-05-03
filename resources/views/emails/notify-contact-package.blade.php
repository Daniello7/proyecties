@component('mail::message')
    # {{ strtoupper(__('New Package')) }}

    {{ $message }}

    @component('mail::button', ['url' => ''])
        Button Text
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
