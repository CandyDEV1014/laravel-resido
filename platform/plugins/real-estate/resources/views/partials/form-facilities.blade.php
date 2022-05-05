@push('header')
    <script>
        "use strict";
        window.trans = JSON.parse('{!! addslashes(json_encode(trans('plugins/real-estate::dashboard'))) !!}');
    </script>
@endpush

<div id="app-real-estate">
    <facilities-component :selected_facilities="{{ json_encode($selectedFacilities) }}" :facilities="{{ json_encode($facilities) }}" limit={{ (int)(isset($limit_facilities) ? $limit_facilities : -1) }}></facilities-component>
</div>
