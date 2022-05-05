<div class="form-group mb-3">
    <label class="control-label">{{ trans('plugins/payment::payment.payment_name') }}</label>
    {!! Form::input('text', 'name', Arr::get($attributes, 'name'), ['class' => 'form-control', 'placeholder' => trans('plugins/payment::payment.payment_name')]) !!}
</div>