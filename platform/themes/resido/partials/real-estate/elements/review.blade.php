@if ($property->reviews_count > 0)
    <real-estate-reviews-component api-get-reviews="{{ route('public.ajax.real-estate-reviews', $property->id) }}" 
        api-get-rating="{{ route('public.ajax.real-estate-rating', $property->id) }}" :review-fields="{{ json_encode(get_review_fields()) }}">
    </real-estate-reviews-component>
@endif

@if (!auth('account')->check() || !check_if_reviewed($property->id))
<div class="property_block_wrap style-2">

    <div class="property_block_wrap_header">
        <a data-bs-toggle="collapse" data-parent="#comment" data-bs-target="#clTen" aria-controls="clTen"
            href="javascript:void(0);" aria-expanded="true">
            <h4 class="property_block_title">{{ __('Write a Review') }}</h4>
            
        </a>
        @if (!auth('account')->check())
            <p class="text-danger">{{ __('Please') }} <a class="text-danger" href="{{ route('public.account.login') }}">{{ __('login') }}</a> {{ __('to write review!') }}</p>
        @endif
    </div>

    <div id="clTen" class="panel-collapse collapse show">
        <div class="block-body">
            {!! Form::open(['route' => 'public.reviews.create', 'method' => 'post', 'class' => 'form--review-product']) !!}
            <input type="hidden" name="reviewable_id" value="{{ $property->id }}">
            <input type="hidden" name="reviewable_type" value="{{ get_class($property) }}">
            <div class="row py-3">
                <div class="col-md-8">
                    <div class="row">
                        @foreach (get_review_fields() as $item)
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label for="select-star">{{ __($item['field']) }}</label>
                            <select class="rating" name="meta[{{ $item['field'] }}]" id="select-star-{{ $item['field'] }}" data-read-only="false" @if (!auth('account')->check()) disabled @endif>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5" selected>5</option>
                            </select>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="avg-total-pilx">
                        <input type="hidden" name="star" value="5">
                        <h4 class="high user_commnet_avg_rate">5</h4>
                        <span>{{ __('Average Rating') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        <textarea name="comment" class="form-control ht-80" placeholder="{{ __('Messages') }}" @if (!auth('account')->check()) disabled @endif></textarea>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        <button class="btn btn-theme-light-2 rounded" type="submit" @if (!auth('account')->check()) disabled @endif>{{ __('Submit Review') }}</button>
                    </div>
                </div>

            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endif
