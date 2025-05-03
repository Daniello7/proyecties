@component('mail::message')
    # {{ strtoupper(__('New Visit')) }}

    {{ $message }}

    @component('mail::button', ['url' => ''])
        Button Text
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
