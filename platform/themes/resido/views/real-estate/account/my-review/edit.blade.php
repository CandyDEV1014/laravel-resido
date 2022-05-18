@php
    Theme::asset()->usePath()->add('leaflet-css', 'plugins/leaflet.css');
    Theme::asset()->usePath()->add('jquery-bar-rating', 'plugins/jquery-bar-rating/themes/fontawesome-stars.css');
    Theme::asset()->container('footer')->usePath()->add('leaflet-js', 'plugins/leaflet.js');
    Theme::asset()->usePath()->add('magnific-css', 'plugins/magnific-popup.css');
    Theme::asset()->container('footer')->usePath()->add('magnific-js', 'plugins/jquery.magnific-popup.min.js');
    Theme::asset()->container('footer')->usePath()->add('property-js', 'js/property.js');
    Theme::asset()->container('footer')->usePath()->add('jquery-bar-rating-js', 'plugins/jquery-bar-rating/jquery.barrating.min.js');
    Theme::asset()->container('footer')->usePath()->add('wishlist', 'js/wishlist.js', [], []);
@endphp

@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')

@php
    $user = auth('account')->user();

    $getMyReview = \Botble\RealEstate\Models\Review::where('id', $reviewId)->first();

    $getReviewMeta = \Botble\RealEstate\Models\ReviewMeta::where('review_id', $getMyReview->id)->get();

    $slug = \Botble\Slug\Models\Slug::where('reference_id', $getMyReview->reviewable_id)->where('prefix', 'properties')->first();

    $property = app(\Botble\RealEstate\Repositories\Interfaces\PropertyInterface::class)->advancedGet([
        'condition' => [
            're_properties.id' => $getMyReview->reviewable_id,
        ],
    ]);
@endphp

@section('content')

    <div class="row">
        <div>
            <div class="bg-white br2 pa3" style="box-shadow: rgb(217, 217, 217) 0px 1px 1px;">
                <ul role="tablist" class="nav" style="border-bottom: 1px solid rgb(217, 217, 217);">
                    <li class="nav-item gray-text">
                        <a data-bs-toggle="tab" href="#activity-logs" class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db show active b" style="text-decoration: none; line-height: 32px;">Edit Review</a>
                    </li>
                </ul> 
                <div class="tab-content">
                    <div id="activity-logs" class="tab-pane fade show active"> 
                        <div class="pv3 ph3-ns">
                            <div class="property_block_wrap style-2">

                                <div id="clTen" class="panel-collapse collapse show">
                                    <div class="block-body">
                                        {!! Form::open(['route' => 'public.reviews.create', 'method' => 'post', 'class' => 'form--review-product']) !!}
                                        <input type="hidden" name="reviewable_id" value="{{ $property[0]->id }}">
                                        <input type="hidden" name="reviewable_type" value="{{ get_class($property[0]) }}">
                                        <input type="hidden" name="review_id" value="{{ $getMyReview->id }}">
                                        <input type="hidden" name="review_comment" value="{{ $getMyReview->comment }}">
                                        <input type="hidden" name="edit" value="yes">
                                        @foreach($getReviewMeta as $key => $value)
                                        <input type="hidden" class="jkRate select-star-{{ $value->key }}" value="{{ $value->value }}" data-rating="{{ $value->value }}" review-id="select-star-{{ $value->key }}">
                                        <script>
                                            $(function() {
                                                $('.jkRate').each(function() {
                                                    var jk = $(this).attr('data-rating');
                                                    // console.log(jk);
                                                    var review_id = $(this).attr('review-id');
                                                    // console.log(review_id);
                                                    $('#' +review_id).barrating({
                                                        theme: 'fontawesome-stars',
                                                        showSelectedRating: true,
                                                        initialRating: jk,
                                                        readonly: false,
                                                        onSelect: function (value, text) {
                                                            console.log(value);
                                                            calculateRating()
                                                        }
                                                    });
                                                });
                                            }); 
                                            function calculateRating() {
                                                let sum = 0;
                                                let avg_rate = 5;
                                                $(document).find('select.rating').each(function () {
                                                    sum += parseFloat($(this).val());
                                                });
                                                avg_rate = sum/($(document).find('select.rating').length);
                                                $('input[name="star"]').val(avg_rate);
                                                $('.user_commnet_avg_rate').html(avg_rate);
                                            }
                                        </script>
                                        @endforeach
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
                                                    <input type="hidden" name="star" value="{{$getMyReview->star}}">
                                                    <h4 class="high user_commnet_avg_rate">{{$getMyReview->star}}</h4>
                                                    <span>{{ __('Average Rating') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    <textarea name="comment" class="form-control ht-80" placeholder="{{ __('Messages') }}" @if (!auth('account')->check()) disabled @endif>{{ $getMyReview->comment }}</textarea>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div id="app-real-estate">
            <activity-log-component url="{{ route('public.account.ajax.myreview-activity-logs') }}" default-active-tab="activity-logs"></activity-log-component>
        </div>
    </div>
@endsection
