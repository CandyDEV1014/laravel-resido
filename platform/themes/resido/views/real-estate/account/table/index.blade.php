@php $user = auth('account')->user(); @endphp
@extends(Theme::getThemeNamespace() . '::views.real-estate.account.master')

@section('content')
    {!! $propertyTable->render(Theme::getThemeNamespace() . '::views.real-estate.account.table.base'); !!}
@endsection
