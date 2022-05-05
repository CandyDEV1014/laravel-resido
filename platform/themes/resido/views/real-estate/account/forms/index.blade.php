@php $user = auth('account')->user(); @endphp
@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')

@push('footer')
{!! Assets::renderHeader(['core']) !!}
{!! Html::style('vendor/core/core/base/css/themes/default.css') !!}
<script src="{{ asset('vendor/core/plugins/real-estate/js/form.js') }}"></script>
<link href="{{ asset('vendor/core/plugins/real-estate/css/app_custom.css') }}" rel="stylesheet">
@endpush

@section('content')
    {!! $form->renderForm() !!}
@endsection
