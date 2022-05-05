@php
    Theme::set('page', $page);
@endphp

@if ($page->template == 'default')
    <div class="page-title">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="ipt-title">{{ $page->name }}</h1>
                    <span class="ipn-subtitle">{{ $page->description }}</span>
                </div>
            </div>
        </div>
    </div>

    <section>
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="sec-heading center">
                        <h2>{{ $page->name }}</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="scontent">
                        {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, clean($page->content), $page) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@else
    {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, clean($page->content), $page) !!}
@endif
