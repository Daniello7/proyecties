<aside class="w-64 bg-gray-800 text-white h-screen p-4">
    <nav>
        <ul>
            @foreach ($links as $link)
                <li class="p-2 hover:bg-gray-700">
                    <a href="{{ $link['url'] }}">{{ $link['name'] }}</a>
                </li>
            @endforeach
        </ul>
    </nav>
</aside>
