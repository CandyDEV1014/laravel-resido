@php
$defaultLanguage = app(Botble\Language\Repositories\Interfaces\LanguageInterface::class)->getDefaultLanguage();
$default_locale = $defaultLanguage->lang_locale;
$current_locale = App::getLocale();

$details = $property->details()
    ->where('status', Botble\Base\Enums\BaseStatusEnum::PUBLISHED)
    ->orderBy('order', 'DESC')
    ->get();

@endphp
<div class="property_block_wrap style-2">

    <div class="property_block_wrap_header">
        <a data-bs-toggle="collapse" data-parent="#features" data-bs-target="#clOne" aria-controls="clOne" href="javascript:void(0);" aria-expanded="false">
            <h4 class="property_block_title">{{ __('Detail & Features') }}</h4>
        </a>
    </div>
    <div id="clOne" class="panel-collapse collapse show" aria-labelledby="clOne">
        <div class="block-body">
            <ul class="detail_features">
                @foreach($details as $detail)
                    @if ($detail->type == 'selectbox')
                    <li>
                        <strong>{{ $detail->title }}: </strong>
                        <span>{!! clean($current_locale == $default_locale ? $detail->pivot->value : $detail->pivot->value2) !!} </span>
                        {{ $detail->type == Botble\RealEstate\Enums\DetailTypeEnum::SQUARE ? setting('real_estate_square_unit', 'm²') : $detail->alt }}
                    </li>
                    @else
                    <li>
                        <strong>{{ $detail->title }}: </strong>
                        <span>{!! clean($detail->pivot->value) !!} </span>
                        {{ $detail->type == Botble\RealEstate\Enums\DetailTypeEnum::SQUARE ? setting('real_estate_square_unit', 'm²') : $detail->alt }}
                    </li>
                    @endif
                
                @endforeach
                
                @if ($property->category)
                <li><strong>{{ __('Property Type:') }}</strong>{{ $property->category_name }} {{ !empty($property->subcategory_id) ? ' , ' . $property->subcategory->name : '' }}</li>
                @endif
            </ul>
        </div>
    </div>

</div>
