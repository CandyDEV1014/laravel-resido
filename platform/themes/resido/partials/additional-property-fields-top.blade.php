<div class="form-group mb-3">
    <label for="header_layout" class="control-label">{{ __('Header layout') }}</label>
    {!! Form::customSelect('header_layout', get_single_header_layout(), $headerLayout, ['class' => 'form-control', 'id' => 'header_layout']) !!}
</div>
