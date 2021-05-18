<a href="{{ $child->url }}" class="dropdown-toggle" data-toggle="dropdown">
    {{ $child->name }} <span class="caret"></span>
</a>
<ul class="dropdown-menu">
    @foreach ($child->children as $child)
        <li class="{{ ($child->url == Request::url()) ? 'active' : '' }}">
            @if ($child->children->count())
                @includeIf('dynamic_view::sub_sidebar')
            @else
                <a href="{{ $child->url }}">{{ $child->name }}</a>
            @endif
        </li>
    @endforeach
</ul>