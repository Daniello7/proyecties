<div class="hidden sm:flex sm:items-center sm:ms-6">
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="inline-flex custom-gradient-text hover:font-black transition shadow rounded-lg hover:shadow-md p-2">
                <div>{{ __('Language') }} {{ session('lang') ? '('.strtoupper(session('lang')).')' : '' }}</div>
                <div class="p-1">
                    <svg class="fill-current text-emerald-600 dark:text-pink-500 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
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