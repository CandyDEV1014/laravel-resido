<div class="modal-box-container">
    <form action="{{ route('simple-slider-item.delete.post', $slider->id) }}" method="post" class="form-xs">
        @csrf
        @method('DELETE')
        <div class="modal-title">
            <i class="til_img"></i> <strong>{{ trans('core/base::tables.confirm_delete') }}</strong>
        </div>
        <div class="modal-body">
            <div class="form-body">
                <p>
                    {{ trans('core/base::tables.confirm_delete_msg') }}
                </p>
            </div>
        </div>
        <div class="modal-footer">
            <a href="javascript:;" class="btn btn-primary" data-fancybox-close>{{ trans('core/base::tables.cancel')  }}</a>
            <button type="submit" class="btn btn-info btn-delete-slider">{{ trans('core/base::tables.delete') }}</button>
        </div>
    </form>
</div>
