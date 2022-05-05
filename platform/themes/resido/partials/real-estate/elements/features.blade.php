<div class="property_block_wrap style-2">

    <div class="property_block_wrap_header">
        <a data-bs-toggle="collapse" data-parent="#features" data-bs-target="#clOne" aria-controls="clOne" href="javascript:void(0);" aria-expanded="false">
            <h4 class="property_block_title">{{ __('Detail & Features') }}</h4>
        </a>
    </div>
    <div id="clOne" class="panel-collapse collapse show" aria-labelledby="clOne">
        <div class="block-body">
            <ul class="detail_features">
                @if ($property->number_bedroom)
                <li>
                    <strong>{{ __('Bedrooms:') }}</strong>
                    {{ number_format($property->number_bedroom) }} {{ __('Beds') }}
                </li>
                @endif
                @if ($property->number_bathroom)
                <li>
                    <strong>{{ __('Bathrooms:') }}</strong>
                    {{ number_format($property->number_bathroom) }} {{ __('Bath') }}
                </li>
                @endif
                @if ($property->square)
                <li>
                    <strong>{{ __('Square:') }}</strong>{{ $property->square_text }}
                </li>
                @endif
                @if ($property->number_floor)
                <li>
                    <strong>{{ __('Floors:') }}</strong>{{ number_format($property->number_floor) }}
                </li>
                @endif
                @if ($property->category)
                <li><strong>{{ __('Property Type:') }}</strong>{{ $property->category_name }} {{ !empty($property->subcategory_id) ? ' , ' . $property->subcategory->name : '' }}</li>
                @endif
            </ul>
        </div>
    </div>

</div>
