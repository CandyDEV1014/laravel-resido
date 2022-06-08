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
                    value="{{ isset($selectedDetails[$detail->id]['value']) ? $selectedDetails[$detail->id]['value'] : '' }}" 
                />
                @break

            @case('number')
                <input type="number" 
                    name="details[{{ $detail->id }}][value]" 
                    class="form-control" 
                    placeholder="{{ $detail->name }}" 
                    value="{{ isset($selectedDetails[$detail->id]['value']) ? $selectedDetails[$detail->id]['value'] : '' }}" 
                />
                @break

            @case('square')
                <input type="number" 
                    name="details[{{ $detail->id }}][value]"
                    class="form-control" 
                    placeholder="{{ $detail->name }}" 
                    value="{{ isset($selectedDetails[$detail->id]['value']) ? $selectedDetails[$detail->id]['value'] : '' }}" 
                />
                @break
            
            @case('date')
                <input 
                    type="text" 
                    name="details[{{ $detail->id }}][value]" 
                    class="form-control datepicker" 
                    date-format="yyyy-mm-dd" 
                    placeholder="{{ $detail->name }}" 
                    value="{{ isset($selectedDetails[$detail->id]['value']) ? $selectedDetails[$detail->id]['value'] : '' }}" 
                />
                @break
            @case('year')
                <div class="ui-select-wrapper form-group">
                    <select class="form-control ui-select" name="details[{{ $detail->id }}][value]">
                        @php $current_year = date('Y'); @endphp
                        @php $start_year = 1940; @endphp
                        @for($year = $current_year; $year >= $start_year; $year--)
                        <option value="{{ $year }}" {{isset($selectedDetails[$detail->id]['value']) && $selectedDetails[$detail->id]['value'] == $year ? "selected" : ""}}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                @break;
            @case('selectbox')
                <div class="ui-select-wrapper form-group">
                    <select class="form-control ui-select selectbox" name="details[{{ $detail->id }}][value]">
                        @if ($detail->features)
                            @foreach (json_decode($detail->features, true) as $feature_id => $feature)
                                @if (count($feature) > 0)
                                    @if (Arr::get($feature, '0.value') != '')
                                    <option value="{{ Arr::get($feature, '0.value') }}" data-detail-id="{{$key}}" data-feature-id="{{ $feature_id }}"
                                        {{ isset($selectedDetails[$detail->id]['value']) && $selectedDetails[$detail->id]['value'] == Arr::get($feature, '0.value') ? "selected" : "" }}>
                                        {{ Arr::get($feature, '0.value') }}
                                    </option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </select>
                    <input type="hidden" class="selectbox_hidden" name="details[{{ $detail->id }}][value2]" value="{{ isset($selectedDetails[$detail->id]['value2']) ? $selectedDetails[$detail->id]['value2'] : '' }}">
                </div>
                @break

            @default
                <input type="text" 
                    name="details[{{ $detail->id }}][value]" 
                    class="form-control" 
                    placeholder="{{ $detail->name }}" 
                    value="{{ isset($selectedDetails[$detail->id]['value']) ? $selectedDetails[$detail->id]['value'] : '' }}" 
                />
        @endswitch
    </div>
    @endforeach
</div>

<script type="text/javascript">
    var details = {!! json_encode($details, JSON_HEX_TAG) !!};
    
    $(document).ready(function () {
        $(".selectbox").each(function() {
            var detail_id = $(this).find('option:selected').data("detail-id");
            var feature_id = $(this).find('option:selected').data("feature-id");
            var detail = details[detail_id];
            var feature2 = JSON.parse(detail['features2']);
            var value = '';
            if (feature2[feature_id]) {
                value = feature2[feature_id][0]['value'];
            }
            $(this).parent(".form-group").find(".selectbox_hidden").val(value);
        });

        $(".selectbox").change(function() {
            var detail_id = $(this).find('option:selected').data("detail-id");
            var feature_id = $(this).find('option:selected').data("feature-id");
            var detail = details[detail_id];
            var feature2 = JSON.parse(detail['features2']);
            var value = '';
            if (feature2[feature_id]) {
                value = feature2[feature_id][0]['value'];
            }
            $(this).parent(".form-group").find(".selectbox_hidden").val(value);
        })

    });
</script>