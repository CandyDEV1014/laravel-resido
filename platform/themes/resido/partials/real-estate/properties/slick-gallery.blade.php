<div class="featured_slick_gallery-slide" style="height:400px">
    @foreach ($property->images as $index => $image)
        <div class="featured_slick_padd">
            <a href="{{ RvMedia::getImageUrl($image, null, false, RvMedia::getDefaultImage()) }}" class="mfp-gallery">
                <img src="{{ RvMedia::getImageUrl($image, 'property_large', false, RvMedia::getDefaultImage()) }}"
                    class="img-fluid w-100" alt="{{ $property->name }}-{{ $index }}" />
            </a>
        </div>
    @endforeach
</div>
