@props(['role' => ''])
<div>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <!-- Email Address -->
        <x-text-input id="email" type="hidden" name="email" :value="$role.'@mail.es'" required autofocus autocomplete="username"/>
        <!-- Password -->
        <x-text-input id="password" type="hidden" name="password" :value="$role.'123'" required autocomplete="current-password"/>
        <x-primary-button>Login as {{ ucfirst($role) }}</x-primary-button>
    </form>
</div>
