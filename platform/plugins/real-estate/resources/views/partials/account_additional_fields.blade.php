<div class="form-group">
    <label for="description" class="control-label required">{{ trans('core/base::forms.description') }}</label>
    {!! Form::textarea('description', $description, ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('core/base::forms.description'), 'data-counter' => 400]) !!}
</div>