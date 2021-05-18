@if ($menu_items && $menu_items->count())
    @foreach ($menu_items as $menu_item)
        @if ($menu_item->children->count())
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-list"></i> {{ $menu_item->name }}</a>
                <ul class="nav-dropdown-items">
                    @foreach ($menu_item->children as $child)
                        <li class="nav-item {{ ($child->url == Request::url()) ? 'active' : '' }}">
                            @if ($child->children->count())
                                @includeIf('dynamic_view::sub_sidebar')
                            @else
                                @if($child->url === 'belumtersedia')
                                    <a class='nav-link' href="#" onclick="alert('Menu belum tersedia')">
                                @else
                                    <a class='nav-link' href="{{ starmoozie_url($child->url) }}">
                                @endif
                            	    <i class="nav-icon la la-circle"></i>
                            		<span>{{ $child->name }}</span>
                            	</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </li>
        @else
            <li class="navitem {{ ($menu_item->url == Request::url()) ? ' active' : '' }}">
                @if($menu_item->url === 'belumtersedia')
                    <a class='nav-link' href="#" onclick="alert('Menu belum tersedia')">
                @else
                    <a class='nav-link' href="{{ starmoozie_url($menu_item->url) }}">
                @endif
                	<i class="nav-icon la la-list"></i> {{ $menu_item->name }}
                </a>
            </li>
        @endif
    @endforeach
@endif