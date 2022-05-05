@if(count($property->facilities))
<div class="property_block_wrap style-2">

    <div class="property_block_wrap_header">
        <a data-bs-toggle="collapse" data-parent="#nearby" data-bs-target="#clNine" aria-controls="clNine"
            href="javascript:void(0);" aria-expanded="true">
            <h4 class="property_block_title">{{ __('Nearby') }}</h4>
        </a>
    </div>

    <div id="clNine" class="panel-collapse collapse show">
        <div class="block-body">
            <div class="nearby-wrap">
                <div class="neary_section_list">
                    @foreach ($property->facilities as $facility)
                        <div class="neary_section">
                            <div class="neary_section_first">
                                <h4 class="nearby_place_title"><i class="@if ($facility->icon) {{ $facility->icon }} @else fas fa-check @endif"></i> {{ $facility->name }}</h4>
                            </div>
                            <div class="neary_section_last">
                                <small class="reviews-count">{{ $facility->pivot->distance }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endif
