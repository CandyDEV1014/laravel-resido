<div class="row property-details">
    @foreach($details as $key => $detail)
    <div class="form-group col-md-3">
        <label for="details[{{ $detail->id }}][value]" class="control-label {{ $detail->is_required ? 'required' : '' }}">{{ $detail->name }}</label>
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
            @case('year')
                <div class="ui-select-wrapper form-group">
                    <select class="form-control ui-select" name="details[{{ $detail->id }}][value]">
                        @php $current_year = date('Y'); @endphp
                        @php $start_year = 1940; @endphp
                        @for($year = $current_year; $year >= $start_year; $year--)
                        <option value="{{ $year }}" {{isset($selectedDetails[$detail->id]) && $selectedDetails[$detail->id] == $year ? "selected" : ""}}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                @break;
            @case('selectbox')
                <div class="ui-select-wrapper form-group">
                    <select class="form-control ui-select" name="details[{{ $detail->id }}][value]">
                        @if ($detail->features)
                            @foreach (json_decode($detail->features, true) as $feature)
                                @if (count($feature) > 0)
                                    @if (Arr::get($feature, '0.value') != '')
                                    <option value="{{ Arr::get($feature, '0.value') }}" 
                                        {{ isset($selectedDetails[$detail->id]) && $selectedDetails[$detail->id] == Arr::get($feature, '0.value') ? "selected" : "" }}>
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