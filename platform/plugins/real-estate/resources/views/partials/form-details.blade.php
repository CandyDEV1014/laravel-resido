@if (count($details))
<div class="row">
    @foreach($details as $key => $detail)
    <div class="form-group col-md-3">
        <label for="details[{{ $detail->id }}][value]" class="control-label">{{ $detail->name }}</label>
        @switch($detail->type)
            @case('text')
                <input type="text" 
                    name="details[{{ $detail->id }}][value]" 
                    class="form-control" 
                    placeholder="{{ $detail->name }}" 
                    value="{{ isset($selectedDetails[$detail->id]) ? $selectedDetails[$detail->id] : '' }}" 
                />
                @break

            @case('number')
                <input type="number" 
                    name="details[{{ $detail->id }}][value]" 
                    class="form-control" 
                    placeholder="{{ $detail->name }}" 
                    value="{{ isset($selectedDetails[$detail->id]) ? $selectedDetails[$detail->id] : '' }}" 
                />
                @break
            
            @case('date')
                <input 
                    type="text" 
                    name="details[{{ $detail->id }}][value]" 
                    class="form-control datepicker" 
                    date-format="yyyy-mm-dd" 
                    placeholder="{{ $detail->name }}" 
                    value="{{ isset($selectedDetails[$detail->id]) ? $selectedDetails[$detail->id] : '' }}" 
                />
                @break

            @case('selectbox')
                <div class="ui-select-wrapper form-group">
                    <select class="form-control ui-select" name="details[{{ $detail->id }}][value]">
                        @if ($detail->features)
                            @foreach (json_decode($detail->features, true) as $feature)
                                @if (count($feature) > 0)
                                    @if ($value = Arr::get($feature, '0.value') != '')
                                    <option value="{{ $value }}" 
                                        {{ isset($selectedDetails[$detail->id]) && $selectedDetails[$detail->id] == $value ? "selected" : "" }}>
                                        {{ Arr::get($feature, '0.value') }}
                                    </option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                @break

            @default
                <input type="text" 
                    name="details[{{ $detail->id }}][value]" 
                    class="form-control" 
                    placeholder="{{ $detail->name }}" 
                    value="{{ isset($selectedDetails[$detail->id]) ? $selectedDetails[$detail->id] : '' }}" 
                />
        @endswitch
    </div>
    @endforeach
</div>

<script>
    $(document).ready(function() {
        
    });
</script>

@endif