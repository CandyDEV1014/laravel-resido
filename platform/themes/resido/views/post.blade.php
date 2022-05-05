<!-- ============================ Page Title Start================================== -->
<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h1 class="ipt-title">{{ $post->name }}</h1>
                <span class="ipn-subtitle"></span>
            </div>
        </div>
    </div>
</div>
<!-- ============================ Page Title End ================================== -->

<!-- ============================ Agency List Start ================================== -->
<section class="blog-page gray-simple">

    <div class="container">

        <!-- <div class="row">
            <div class="col text-center">
                <div class="sec-heading center">
                    {!! Theme::partial('breadcrumb') !!}
                </div>
            </div>
        </div> -->

        <!-- row Start -->
        <div class="row">
            <!-- Blog Detail -->
            <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="single-post-item format-standard">
                    <div class="blog-details">
                        <div class="post-featured-img">
                            <img src="{{ RvMedia::getImageUrl($post->image, null, false, RvMedia::getDefaultImage()) }}"
                                class="img-fluid" alt="{{ $post->name }}" />
                            <p>
                                <span><i class="fa fa-user-clock" aria-hidden="true"></i> {{ $post->author->name }}</span>
                                <span><i class="fa fa-comment-alt-dots" aria-hidden="true"></i> {{ $post->reviews_count }} Comments</span>
                                <span><i class="fa fa-eye" aria-hidden="true"></i>{{ number_format($post->views) }} {{ __('Views') }}</span>
                            </p>
                        </div>

                        <h2 class="post-title">{{ $post->name }}</h2>

                        <div>
                            {!! clean($post->content) !!}
                        </div>

                        <div class="post-bottom-meta">
                            <div class="post-tags">
                                <h4 class="pbm-title">{{ __('Tags') }}</h4>
                                @if ($post->tags->count())
                                    <ul class="list">
                                        @foreach ($post->tags as $tag)
                                            <li>
                                                <a href="{{ $tag->url }}">{{ $tag->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div class="post-share">
                                {!! Theme::partial('share', ['title' => __('Share this post'), 'description' => $post->description]) !!}
                            </div>
                        </div>

                    </div>

                    <!-- Single Review -->
                    @if(is_review_enabled())
                    <div class="blog-review">
                        {!! Theme::partial('real-estate.elements.post-review', compact('post')) !!}
                    </div>
                    @endif

                    @php $relatedPosts = get_related_posts($post->id, 2); @endphp

                    @if ($relatedPosts->count())
                        <div class="related-blog">
                            <h4>{{ __('Related posts') }}:</h4>
                            <div class="blog-container">
                                <div class="row">
                                    @foreach ($relatedPosts as $relatedItem)
                                        <div class="col-md-6 col-sm-6 container-grid">
                                            <div class="blog-wrap-grid">
                                                <div class="blog-thumb">
                                                    <a href="{{ $relatedItem->url }}">
                                                        <img src="{{ RvMedia::getImageUrl($relatedItem->image, null, false, RvMedia::getDefaultImage()) }}"
                                                            class="img-fluid" alt="{{ $relatedItem->name }}" />
                                                    </a>
                                                    <div class="blog-category">
                                                        @php $color = ['red', 'yellow', 'green', 'blue']; @endphp
                                                        @foreach($relatedItem->categories as $category)
                                                            <span class="{{ $color[(int)$category->id % 4]}}">{{ $category->name }}</span>
                                                        @endforeach
                                                    </div>
                                                    
                                                </div>
                                                <div class="blog-body">
                                                    <p class="blog-date">
                                                        <span>{{ $relatedItem->created_at->format('m') }}</span>
                                                        <span>{{ $relatedItem->created_at->format('d') }}</span>
                                                        <span>{{ $relatedItem->created_at->format('Y') }}</span>
                                                    </p>
                                                    <span class="blog-comment"><i class="fa fa-comment-dots" aria-hidden="true"></i> {{ $relatedItem->reviews_count }}</span>
                                                    <div class="blog-header">
                                                        <div class="blog-header-images">
                                                            <img src="{{ $relatedItem->author->avatar_url }}" alt="{{ $relatedItem->author->name }}" class="img-fluid img-thumbnail">
                                                            <span>{{ $relatedItem->author->name }}</span>
                                                        </div>
                                                    </div>

                                                    <h4 class="blog-title">
                                                        <a href="{{ $relatedItem->url }}">{{ $relatedItem->name }}</a>
                                                    </h4>
                                                    <p>{{ $relatedItem->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                
                

                

            </div>

            <!-- Single blog Grid -->
            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                <div class="blog-details single-post-item format-standard">
                    {!! dynamic_sidebar('primary_sidebar') !!}
                </div>
            </div>
        </div>
    </div>
</section>
