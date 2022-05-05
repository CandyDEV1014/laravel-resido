<!-- Categories -->
<div class="single-widgets widget_category">
    <h4 class="title">{{ $config['name'] }}</h4>
    <ul>
        @foreach(get_categories(['select' => ['categories.id', 'categories.name']]) as $category)
            <li>
                <a href="{{ $category->url }}">
                    {{ $category->name }}
                    @php $category = $category->load('slugable')->loadCount('posts') @endphp
                    <span>{{ $category->posts_count }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>

