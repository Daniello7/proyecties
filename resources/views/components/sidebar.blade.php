<aside class="w-52 bg-white dark:bg-gray-800 min-h-screen shadow-lg transition-colors duration-500">
    <nav class="py-4 pr-4">
        <ul>
            @foreach ($links as $link)
                <li class="p-2 my-2 font-semibold transition-all duration-500 {{ request()->routeIs($link['url'].'*') ? 'active-link' : 'not-active-link' }}">
                    <a href="{{ route($link['url']) }}" class="block">
                        {{ __($link['name']) }}
                    </a>
                </li>
                <hr class="mx-2 border-blue-600 dark:border-pink-600 text- opacity-20">
            @endforeach
        </ul>
    </nav>
</aside>
