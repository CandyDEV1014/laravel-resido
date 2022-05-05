<ul {!! $options !!}>
    @foreach ($menu_nodes as $key => $row)
    <li class="nav-item {{ $row->css_class }}">
        <a class="nav-link @if ($row->active) active text-orange @endif" href="{{ $row->url }}" target="{{ $row->target }}">
            @if ($row->icon_font)<i class='{{ trim($row->icon_font) }}'></i> @endif{{ $row->title }}
            @if ($row->active) <span class="sr-only">(current)</span> @endif
        </a>
    </li>
    @endforeach
</ul>
