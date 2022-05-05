<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="form-group">
            <div class="simple-input">
                <input type="text" class="form-control" placeholder="{{ __('Min Area') }}"
                    value="{{ request()->input('min_square') }}">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="form-group">
            <div class="simple-input">
                <input type="text" class="form-control" placeholder="{{ __('Max Area') }}"
                    value="{{ request()->input('max_square') }}">
            </div>
        </div>
    </div>
</div>
