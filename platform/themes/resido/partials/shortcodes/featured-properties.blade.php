<section class="bg-light2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10 text-center">
                <div class="sec-heading center">
                    <h2>{!! clean($title) !!}</h2>
                    <p>{!! clean($description) !!}</p>
                </div>
            </div>
        </div>

        <div class="row list-layout" style="display: none;">
            @foreach($properties as $property)
            <!-- Single Property -->
            @if($style == 1)
            <div class="col-lg-6 col-sm-12">
                {!! Theme::partial('real-estate.properties.item-list', compact('property')) !!}
			</div>
            @else
            <div class="col-lg-4 col-md-6 col-sm-12">
                {!! Theme::partial('real-estate.properties.item-grid', compact('property')) !!}
			</div>
            @endif
            <!-- End Single Property -->
            @endforeach
        </div>
		
		<div class="row" style="display: block;">
            <div class="col-lg-12 col-md-12">
                @php
                    $count = 1
                @endphp

                @foreach ($properties as $property)
                    @if ($count % 4 == 1) 
                    <div class="property-slide" style="max-height: 554px;">
                    @endif
                        <!-- Single Property -->
                        <div class="single-items">
                            {!! Theme::partial('real-estate.properties.item-grid', ['property' => $property, 'class_extend' => 'shadow-none border']) !!}
                        </div>
                    @if ($count % 4 == 0) 
                    </div>
                    @endif
                    @php
                        $count++;
                     @endphp
                @endforeach

                @if ($count % 4 != 1) 
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                <a href="{{ route('public.featuredProperty') }}" class="btn btn-theme-light-2 rounded">{{ __('Browse More Properties') }}</a>
            </div>
        </div>
	</div>
</section>
