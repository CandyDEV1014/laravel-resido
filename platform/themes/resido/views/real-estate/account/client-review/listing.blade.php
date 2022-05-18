@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')
@php
$user = auth('account')->user();
$getClientReview = \Botble\RealEstate\Models\Review::join('re_properties', 're_properties.id', '=', 're_reviews.reviewable_id')->where('author_id', $user->id)->where('expire_date', '>', now()->toDateTimeString())->where('never_expired', false)->paginate();

@endphp

@section('content')
    <div class="row">
        <div>
            <div class="bg-white br2 pa3" style="box-shadow: rgb(217, 217, 217) 0px 1px 1px;">
                <ul role="tablist" class="nav" style="border-bottom: 1px solid rgb(217, 217, 217);">
                    <li class="nav-item gray-text">
                        <a data-bs-toggle="tab" href="#activity-logs" class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db show active b" style="text-decoration: none; line-height: 32px;">Client Review</a>
                    </li>
                </ul> 
                <div class="tab-content">
                    <div id="activity-logs" class="tab-pane fade show active"> 
                        <div class="pv3 ph3-ns">
                            <table style="display:none" class="table table-striped table-bordered" style="width:100%" id="example">
                                <thead>
                                    <th>{{ trans('core/base::tables.id') }}</th>
                                    <th>{{ trans('plugins/real-estate::review.product') }}</th>
                                    <th>{{ trans('plugins/real-estate::review.user') }}</th>
                                    <th>{{ trans('plugins/real-estate::review.star') }}</th>
                                    <th>{{ trans('plugins/real-estate::review.comment') }}</th>
                                    <th>{{ trans('plugins/real-estate::review.status') }}</th>
                                    <th>{{ trans('core/base::tables.created_at') }}</th>
                                </thead>
                                <tbody>
                                    @if($getClientReview->count())
                                    @foreach($getClientReview as $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td>{{ $value->reviewable->name }}</td>
                                        <td>{{ $value->account->name }}</td>
                                        <td>
                                            <div class="rating_wrap d-inline-block">
                                                <div class="rating">
                                                    <div class="product_rate" style="width: {{ $value->star * 20 }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $value->comment }}</td>
                                        <td>{!! $value->status->toHtml() !!}</td>
                                        <td>{{ date("Y/m/d",strtotime($value->created_at)) }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="6" align="center">No Review.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                            <div id="" class="panel-collapse collapse show">
                                <div class="block-body">
                                    <div class="author-review">
                                        <div class="comment-list">
                                            <ul>
                                                @if($getClientReview->count())
                                                @foreach($getClientReview as $value)
                                                <li class="article_comments_wrap">
                                                    <article>
                                                        <div class="article_comments_thumb">
                                                            <a href="{{ route('public.agent', ($value->account->username) ? $value->account->username : $value->account->name) }}">
                                                                {{-- <img src="@if(isset($value->reviewable->images)) {{RvMedia::getImageUrl($value->reviewable->images ? $value->reviewable->images[1] : 'img-loading.jpg', null, false, RvMedia::getDefaultImage())}} @endif" style="height: 80px;"> --}}
                                                                <img src="{{ $value->account->avatar->url ? RvMedia::getImageUrl($value->account->avatar->url, 'thumb') : $value->account->avatar_url }}" />
                                                            </a>
                                                        </div>
                                                        <div style="display: flex; align-items: center; justify-content: space-between;">
                                                            <div class="comment-details">
                                                                <div style="font-family: Jost, sans-serif"><a href="{{ route('public.agent', ($value->account->username) ? $value->account->username : $value->account->name) }}">{{ $value->account->name }}</a></div>
                                                                <div class="rating_wrap">
                                                                    <div class="rating">
                                                                        <div class="product_rate" style="width: {{ $value->star * 20 }}%"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="comment-meta">
                                                                    <div class="comment-left-meta">
                                                                        <h4 class="author-name">
                                                                            <a href="{{$value->reviewable->url}}">
                                                                                @if(isset($value->reviewable->name)) {{$value->reviewable->name}} @endif
                                                                            </a>
                                                                        </h4>
                                                                        <div class="comment-date">
                                                                            {{ date("Y/m/d",strtotime($value->created_at)) }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="comment-text">
                                                                    <p>{{ $value->comment }}</p>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <a href="{{$value->reviewable->url}}"><i class="fa fa-eye" style="color: #898b8d;"></i></a>
                                                            </div>
                                                        </div>
                                                    </article>
                                                </li>
                                                @endforeach
                                                @else
                                                    <li>
                                                        <div align="center">No Review.</div>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                        {!! $getClientReview->links() !!}
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
            <activity-log-component url="{{ route('public.account.ajax.clientreview-activity-logs') }}" default-active-tab="activity-logs"></activity-log-component>
        </div>
    </div>
@endsection
