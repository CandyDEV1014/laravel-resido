<a data-fancybox data-type="ajax" data-src="{{ route('simple-slider-item.edit', $item->id) }}" href="javascript:;"
   title="{{ $item->title }}">
    <img src="{{ RvMedia::getImageUrl($item->image, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $item->title }}" width="80">
</a>
