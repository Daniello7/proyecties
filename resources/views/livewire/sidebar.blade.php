<aside class="w-52 bg-white dark:bg-gray-800 min-h-screen shadow-lg transition-colors">
    @if(auth()->check() && auth()->user()->hasRole('admin'))
        <select id="select-links" class="custom-gradient-text max-w-max *:text-blue-600 cursor-pointer border-none outline-none hover:font-black rounded-md ml-4 transition-all">
            <option value="admin-nav">Admin</option>
            <option value="rrhh-nav">RRHH</option>
            <option value="porter-nav">Porter</option>
        </select>
        @foreach($links as $role => $value)
            <nav class="py-4 pr-4 hidden" id="{{ $role.'-nav' }}">
                <ul>
                    @foreach ($value as $link)
                        <li class="my-2 font-semibold transition-all duration-200 {{ request()->is($link['url'].'*') || ($link['url'] == 'person-entries' && request()->routeIs('person.*')) ? 'active-link' : 'not-active-link' }}">
                            <a href="{{ route($link['url']) }}" class="block p-2">
                                {{ __($link['name']) }}
                            </a>
                        </li>
                        <hr class="mx-2 border-blue-600 dark:border-pink-600 text- opacity-20">
                    @endforeach
                </ul>
            </nav>
        @endforeach
    @else
        <nav class="py-4 pr-4">
            <ul>
                @foreach ($links as $link)
                    <li class="my-2 font-semibold transition-all duration-200 {{ request()->is($link['url'].'*') || ($link['url'] == 'person-entries' && request()->routeIs('person.*')) ? 'active-link' : 'not-active-link' }}">
                        <a href="{{ route($link['url']) }}" class="block p-2">
                            {{ __($link['name']) }}
                            @if($link['url'] == 'pdf-exports.index' && $unreadPdfCount != null)
                                <span class="bg-red-600 text-white text-xs rounded-full p-1 shadow-sm ml-1">{{ $unreadPdfCount }}</span>
                            @endif
                        </a>
                    </li>
                    <hr class="mx-2 border-blue-600 dark:border-pink-600 text- opacity-20">
                @endforeach
            </ul>
        </nav>
    @endif
</aside>

@if(auth()->check() && auth()->user()->hasRole('admin'))
    <script>
        const selectLinks = document.querySelector('#select-links');
        const linkNavs = document.querySelectorAll('nav');

        linkNavs[0].classList.remove('hidden');

        selectLinks.addEventListener('change', updateLinks);

        function updateLinks() {
            linkNavs.forEach(nav => {
                if (!nav.classList.contains('hidden')) nav.classList.add('hidden');
                if (nav.id === this.value) nav.classList.remove('hidden');
            })
        }
    </script>
@endif
