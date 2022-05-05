<div class="post-meta">
    <p class="d-inline-block">
        <i class="fa fa-calendar"></i> {{ $post->created_at->format('Y/m/d') }} {{ __('in') }} @foreach($post->categories as $category)
            <a href="{{ $category->url }}">{{ $category->name }}</a>
            @if (!$loop->last)
                ,
            @endif
        @endforeach
        - <i class="fa fa-eye"></i>{{ number_format($post->views) }}
    </p>
</div>
