@component('mail::message')
    # Welcome to Control 77!

    Welcome {{ $user->name }}!

    @component('mail::button', ['url' => ''])
        Button Text
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
