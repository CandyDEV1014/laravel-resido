<section>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10 text-center">
                <div class="sec-heading center">
                    <h2>{!! clean($title) !!}</h2>
                    <p>{!! clean($description) !!}</p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($posts as $post)
                <!-- Single blog Grid -->
                <div class="col-lg-4 col-md-6">
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
                            <span class="blog-comment"><i class="fa fa-comment-dots" aria-hidden="true"></i> {{ $post->reviews_count }}</span>
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

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 text-center mt-3">
                <a href="{{ url('/news') }}" class="btn btn-theme-light-2 rounded">{{ __('Browse More News') }}</a>
            </div>
        </div>
    </div>
</section>
<!-- ================= Blog Grid End ================= -->
