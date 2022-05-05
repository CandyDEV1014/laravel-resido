@if ($post->reviews_count > 0)
    <post-reviews-component api-get-reviews="{{ route('public.ajax.post-reviews', $post->id) }}" >
    </post-reviews-component>
@endif

@if (!auth('account')->check() || !check_if_post_reviewed($post->id))
    <div class="property_block_wrap style-2">

        <div class="property_block_wrap_header">
            <a data-bs-toggle="collapse" data-parent="#comment" data-bs-target="#clTen" aria-controls="clTen"
                href="javascript:void(0);" aria-expanded="true">
                <h4 class="property_block_title">{{ __('Write a Comments') }}</h4>
                
            </a>
            @if (!auth('account')->check())
                <p class="text-danger">{{ __('Please') }} <a class="text-danger" href="{{ route('public.account.login') }}">{{ __('login') }}</a> {{ __('to write comment!') }}</p>
            @endif
        </div>

        <div id="clTen" class="panel-collapse collapse show">
            <div class="block-body">
                {!! Form::open(['route' => 'public.post-reviews.create', 'method' => 'post', 'class' => 'form--review-post']) !!}
                <input type="hidden" name="reviewable_id" value="{{ $post->id }}">
                <input type="hidden" name="reviewable_type" value="{{ get_class($post) }}">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <textarea name="comment" class="form-control ht-80" placeholder="{{ __('Messages') }}" @if (!auth('account')->check()) disabled @endif></textarea>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <button class="btn btn-theme-light-2 rounded" type="submit" @if (!auth('account')->check()) disabled @endif>{{ __('Submit Comment') }}</button>
                        </div>
                    </div>

                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endif