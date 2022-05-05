<div class="row">
    <div class="col-md-6 col-12">
        <h3>{{ trans('plugins/real-estate::review.add_review') }}</h3>
        {!! Form::open(['route' => 'public.reviews.create', 'method' => 'post']) !!}

            <div class="form-group {{ $errors->has('star') ? ' has-error' : '' }}">
                <p>{{ trans('plugins/real-estate::review.please_select_rating') }}</p>

                <input type="hidden" name="star" id="rating" @if (!auth('customer')->check() || ($isReviewed = check_if_reviewed_product($productId))) disabled value="{{ get_customer_reviewed_value($productId) }}" @endif/>
            </div>

            @if (!isset($isReviewed) || !$isReviewed)
                @if (auth('customer')->check())
                    <div class="form-group ">
                        <label for="comment">{{ trans('plugins/real-estate::review.comment') }}</label>
                        {!! Form::textarea('comment', old('comment'), ['class' => 'form-control', 'placeholder' => trans('plugins/real-estate::review.comment'), 'rows' => 4]) !!}
                        {!! Form::error('comment', $errors) !!}
                    </div>

                    <input type="hidden" name="product_id" value="{{ $productId }}"/>

                    {!! Form::submit(trans('plugins/real-estate::review.create_review'), ['class' => 'btn btn-primary btn-md btn-color']) !!}
                @else
                    <div class="alert alert-success">
                        <span>{!! trans('plugins/real-estate::review.require_login', ['login' => Html::link(route('customer.login'), trans('plugins/real-estate::review.login'))]) !!}</span>
                    </div>
                @endif
            @endif
        {!! Form::close() !!}
    </div>
    <div class="col-md-6 col-12">
        <h3>{{ trans('plugins/real-estate::review.list_review') }}</h3>
        <div id="list-reviews">
            <ul>
                @foreach ($reviews as $review)
                    @if (!empty($review->user) && !empty($review->product))
                        <li>
                            <p>
                                <span class="review-author">
                                    {{ $review->user_name }}
                                </span>
                                -
                                <span class="review-time">{{ BaseHelper::formatDate($review->created_at, 'Y-m-d H:i:s') }}</span>
                                <span class="review-rating">
                                    {{ $review->star }} <i class="fa fa-star" aria-hidden="true"></i>
                                </span>
                            </p>

                            <p>
                                <span class="review-comment">
                                    {{ $review->comment }}
                                </span>
                            </p>

                            @if (auth('customer')->check() && auth('customer')->id() == $review->customer_id)
                                <span class="delete-review-btn"><a href="{{ route('public.reviews.destroy', $review->id) }}" title="{{ trans('plugins/real-estate::review.delete_review') }}"><i class="glyphicon glyphicon-remove"></i></a></span>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>
