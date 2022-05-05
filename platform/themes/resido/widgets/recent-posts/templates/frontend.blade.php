<div class="single-widgets widget_thumb_post">
    <h4 class="title">{{ $config['name'] }}</h4>
        @foreach (get_recent_posts($config['number_display']) as $post)
            <div class="col-sm-12">
                <div class="blog-wrap-grid">
                    <div class="blog-thumb">
                        <a href="{{ $post->url }}">
                            <img src="{{ RvMedia::getImageUrl($post->image, null, false, RvMedia::getDefaultImage()) }}"
                                class="img-fluid" alt="{{ $post->name }}" />
                        </a>
                        <div class="blog-category">
                            @php $color = ['red', 'yellow', 'green', 'blue']; @endphp
                            @foreach($post->categories as $category)
                                <span class="{{ $color[(int)$category->id % 4]}}">{{ $category->name }}</span>
                            @endforeach
                        </div>
                        
                    </div>
                    <div class="blog-body">
                        <p class="blog-date">
                            <span>{{ $post->created_at->format('m') }}</span>
                            <span>{{ $post->created_at->format('d') }}</span>
                            <span>{{ $post->created_at->format('Y') }}</span>
                        </p>
                        <span class="blog-comment"><i class="fa fa-comment-dots" aria-hidden="true"></i> {{ $post->reviews_count }} </span>
                        <div class="blog-header">
                            <div class="blog-header-images">
                                <img src="{{ $post->author->avatar_url }}" alt="{{ $post->author->name }}" class="img-fluid img-thumbnail">
                                <span>{{ $post->author->name }}</span>
                            </div>
                        </div>

                        <h4 class="blog-title">
                            <a href="{{ $post->url }}">{{ $post->name }}</a>
                        </h4>
                        <p>{{ $post->description }}</p>
                    </div>
                </div>
            </div>
        @endforeach
</div>
