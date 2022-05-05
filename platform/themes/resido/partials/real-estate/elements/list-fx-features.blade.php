<div class="list-fx-features">
    @if ($property->number_bedroom)
    <div class="listing-card-info-icon">
        <div class="inc-fleat-icon"><img src="{{ Theme::asset()->url('img/bed.svg') }}" width="13" alt="" title="<?php echo e(__('Bedroom')); ?>"></div>
        {{ number_format($property->number_bedroom) }} {{ __('Beds') }}
    </div>
    @endif
    @if ($property->number_bathroom)
    <div class="listing-card-info-icon">
        <div class="inc-fleat-icon"><img src="{{ Theme::asset()->url('img/bathtub.svg') }}" width="13" alt="" title="<?php echo e(__('Bathroom')); ?>">
        </div>{{ number_format($property->number_bathroom) }} {{ __('Bath') }}
    </div>
    @endif
    @if ($property->square)
    <div class="listing-card-info-icon">
        <div class="inc-fleat-icon"><img src="{{ Theme::asset()->url('img/move.svg') }}" width="13" alt="" title="<?php echo e(__('Bathroom')); ?>"></div>
        {{ $property->square_text }}
    </div>
    @endif
</div>