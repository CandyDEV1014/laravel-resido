<div class="form-group">
    <label for="widget-name">{{ trans('core/base::forms.name') }}</label>
    <input type="text" class="form-control" name="name" value="{{ $config['name'] }}">
</div>
<div class="form-group">
    <label for="widget-name">{{ __('Sub name') }}</label>
    <input type="text" class="form-control" name="subname" value="{{ $config['subname'] }}">
</div>