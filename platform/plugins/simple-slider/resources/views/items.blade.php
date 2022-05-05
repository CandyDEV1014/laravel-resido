<div class="float-left">
    <a data-fancybox data-type="ajax"
       data-src="{{ route('simple-slider-item.create') }}?simple_slider_id={{ request()->route('simple_slider') }}"
       href="javascript:void(0);" class="btn btn-info"><i class="fa fa-plus-circle"></i> {{ trans('plugins/simple-slider::simple-slider.add_new') }}</a>
    <button class="btn-success btn btn-save-sort-order" style="display: none;"><i
                class="fa fa-save"></i> {{ trans('plugins/simple-slider::simple-slider.save_sorting') }}</button>
</div>
<div class="clearfix"></div>
<br>

@include('core/table::simple-table')
