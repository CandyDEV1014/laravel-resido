<div class="form-group">
    <label for="description" class="control-label required">{{ trans('plugins/real-estate::property.form.type') }}</label>
    <div class="ui-select-wrapper form-group">
        <select class="form-control ui-select is-valid" name="type_id" id="type_id">
            @foreach($types as $type)
                <option value="{{ $type->id }}" data-code="{{ $type->code }}" @if($type_id == $type->id) selected @endif>{{ $type->name }}</option>
            @endforeach
        </select>
    </div>
    
</div>