@auth
    <!-- Settings Dropdown -->
    <div class="hidden sm:flex sm:items-center sm:ms-6">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex custom-gradient-text hover:font-black transition shadow rounded-lg hover:shadow-md p-2">
                    <div>{{ Auth::user()->name }}</div>
                    <x-svg.dropdown-icon class="fill-current text-emerald-600 dark:text-pink-500 h-4 w-4 m-1"/>
                </button>
            </x-slot>
            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
@endauth
@guest
    <div class="hidden sm:flex sm:items-center sm:ms-6">
        <x-dropdown>
            <x-slot name="trigger">
                <button class="inline-flex custom-gradient-text hover:font-black transition">
                    <div>{{ __('Account') }}</div>
                    <div class="p-1">
                        <svg class="fill-current text-emerald-600 dark:text-pink-500 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </button>
            </x-slot>
            <x-slot name="content">
                <x-dropdown-link :href="route('login')">
                    {{ __('Log in') }}
                </x-dropdown-link>
                <x-dropdown-link :href="route('register')">
                    {{ __('Register') }}
                </x-dropdown-link>
            </x-slot>
        </x-dropdown>
    </div>
@endguest