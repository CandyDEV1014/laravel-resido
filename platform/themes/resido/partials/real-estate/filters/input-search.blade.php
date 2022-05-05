<div class="input-with-icon">
    <input type="text" class="form-control" name="k" value="{{ request()->input('k') }}" placeholder="{{ __('Search for a location') }}">
    <img src="{{ Theme::asset()->url('img/pin.svg') }}" width="20">
</div>
