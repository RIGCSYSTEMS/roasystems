@props(['links'])

<nav>
    <ul class="nav justify-content-end">
        @foreach ($links as $text => $url)
            <li class="nav-item">
                <a class="nav-link" href="{{ $url }}">{{ $text }}</a>
            </li>
        @endforeach
    </ul>
</nav>