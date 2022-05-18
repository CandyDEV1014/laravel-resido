@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')
@php
$user = auth('account')->user();

$getMyReview = \Botble\RealEstate\Models\Review::select('re_reviews.*')
->where('account_id', $user->id)
->orderBy('id', 'DESC')
->paginate(10);
@endphp
{{-- ->where('expire_date', '>', now()->toDateTimeString())
->where('never_expired', false) --}}
@section('content')

    <div class="row">
        <div>
            <div class="bg-white br2 pa3" style="box-shadow: rgb(217, 217, 217) 0px 1px 1px;">
                <ul role="tablist" class="nav" style="border-bottom: 1px solid rgb(217, 217, 217);">
                    <li class="nav-item gray-text">
                        <a data-bs-toggle="tab" href="#activity-logs" class="no-underline mr2 black-50 hover-black-70 pv1 ph2 db show active b" style="text-decoration: none; line-height: 32px;">My Review</a>
                    </li>
                </ul> 
                <div class="tab-content">
                    <div id="activity-logs" class="tab-pane fade show active"> 
                        <div class="pv3 ph3-ns">
                            
                            <div id="" class="panel-collapse collapse show">
                                <div class="block-body">
                                    <div class="author-review">
                                        <div class="comment-list">
                                            <ul>
                                                @if($getMyReview->count())
                                                @foreach($getMyReview as $value)
                                                @if($value->expireDate > now()->toDateTimeString() || $value->expireDate == '')
                                                <li class="article_comments_wrap">
                                                    <article>
                                                        <div class="article_comments_thumb">
                                                            <a href="{{$value->reviewable->url}}">
                                                                @php
                                                                $src = '';
                                                                if (isset($value->reviewable->images)) {
                                                                    $src = count($value->reviewable->images) > 0 ? RvMedia::getImageUrl(array_values($value->reviewable->images)[0], 'thumb') : RvMedia::getImageUrl('img-loading.jpg', 'thumb');
                                                                } else if (isset($value->reviewable->image)) {
                                                                    $src = $value->reviewable->image != '' ? RvMedia::getImageUrl($value->reviewable->image, 'thumb') : RvMedia::getImageUrl('img-loading.jpg', 'thumb');
                                                                } else {
                                                                    $src = RvMedia::getImageUrl('img-loading.jpg', 'thumb');
                                                                }
                                                                @endphp
                                                                <img src="{{ $src }}" style="height: 80px;" />
                                                            </a>
                                                        </div>
                                                        <div style="display: flex; align-items: center; justify-content: space-between;">
                                                            <div class="comment-details">
                                                                {{-- <div style="font-family: Jost, sans-serif">{{ $value->account->name }}</div> --}}
                                                                <div class="rating_wrap">
                                                                    <div class="rating">
                                                                        <div class="product_rate" style="width: {{ $value->star * 18.5 }}%"></div>
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
                                                                <a href="{{ route('public.account.editMyReview', $value->id) }}"><i class="fa fa-edit" style="color: #898b8d;"></i></a>&nbsp;&nbsp;
                                                                <a href="{{ route('public.account.deleteMyReview', $value->id) }}" onclick="return confirm('Are you sure you want to delete this review?');"><i class="fa fa-trash" style="color: #898b8d;"></i></a>
                                                            </div>
                                                        </div>
                                                    </article>
                                                </li>
                                                @endif
                                                @endforeach
                                                @else
                                                    <li>
                                                        <div align="center">No Review.</div>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                        {!! $getMyReview->links() !!}
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
