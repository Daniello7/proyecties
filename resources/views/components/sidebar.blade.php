<aside class="w-52 bg-white dark:bg-gray-800 min-h-screen shadow-lg transition-colors">
    <nav class="py-4 pr-4">
        <ul>
            @foreach ($links as $link)
                <li class="my-2 font-semibold transition-all duration-200 {{ request()->is($link['url'].'*') || ($link['url'] == 'person-entries' && request()->routeIs('person.*')) ? 'active-link' : 'not-active-link' }}">
                    <a href="{{ route($link['url']) }}" class="block p-2">
                        {{ __($link['name']) }}
                    </a>
                </li>
                <hr class="mx-2 border-blue-600 dark:border-pink-600 text- opacity-20">
            @endforeach
        </ul>
    </nav>
</aside>
