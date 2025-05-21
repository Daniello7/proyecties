<div class="hidden sm:flex sm:items-center sm:ms-6">
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="inline-flex custom-gradient-text hover:font-black transition shadow rounded-lg hover:shadow-md p-2">
                <div>{{ __('Language') }} {{ session('lang') ? '('.strtoupper(session('lang')).')' : '' }}</div>
                <x-svg.dropdown-icon class="fill-current text-emerald-600 dark:text-pink-500 h-4 w-4 m-1"/>
            </button>
        </x-slot>
        <x-slot name="content">
            @foreach($langKeys as $key)
                <x-dropdown-link :href="route('languages', ['lang' => $key])">
                    {{__(explode(',', $languages[$key]['isoName'])[0])}}
                </x-dropdown-link>
            @endforeach
        </x-slot>
    </x-dropdown>
</div>