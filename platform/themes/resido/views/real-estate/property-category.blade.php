<div class="page-title">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h1 class="ipt-title">{{ $category->name }}</h1>
                <span class="ipn-subtitle">{{ $category->description }}</span>
            </div>
        </div>
    </div>
</div>

@php
    request()->request->set('category_id', $category->id);
@endphp

@include(Theme::getThemeNamespace('views.real-estate.includes.properties-list'))
