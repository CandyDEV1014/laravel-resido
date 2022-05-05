<div class="col-lg-12 col-md-12">
    <div class="item-sorting-box">
        <div class="item-sorting clearfix">
            <div class="left-column pull-left">
                <h4 class="m-0">
                    @if($properties->total() == 0)
                        {{ __('0 results') }}
                    @else
                        {{ __('Found :from - :to Of :total Results', [
                            'from' => $properties->firstItem(),
                            'to' => $properties->lastItem(),
                            'total' => $properties->total(),
                        ]) }}
                    @endif
                </h4>
            </div>
        </div>
        <div class="item-sorting-box-right">
            <div class="sorting-by">
                <select id="sort_by" name="sort_by" class="form-control" data-placeholder="{{ __('Sort by') }}">
                    <option value="">{{ __('Sort by') }}</option>
                    <option value=""
                            @if (request()->input('sort_by') == 'default_sorting') selected @endif>{{ __('Default') }}</option>
                    <option value="featured"
                            @if (request()->input('sort_by') == 'featured') selected @endif>{{ __('Featured') }}</option>
                    <option value="top_property"
                            @if (request()->input('sort_by') == 'top_property') selected @endif>{{ __('Top Property') }}</option>
					<option value="urgent"
                            @if (request()->input('sort_by') == 'urgent') selected @endif>{{ __('Urgent') }}</option>
                    <option value="date_asc"
                            @if (request()->input('sort_by') == 'date_asc') selected @endif>{{ __('Oldest') }}</option>
                    <option value="date_desc"
                            @if (request()->input('sort_by') == 'date_desc') selected @endif>{{ __('Newest') }}</option>
                    <option value="price_asc"
                            @if (request()->input('sort_by') == 'price_asc') selected @endif>{{ __('Price: Low to high') }}</option>
                    <option value="price_desc"
                            @if (request()->input('sort_by') == 'price_desc') selected @endif>{{ __('Price: High to low') }}</option>
                    <option value="name_asc"
                            @if (request()->input('sort_by') == 'name_asc') selected @endif>{{ __('Name: A-Z') }}</option>
                    <option value="name_desc"
                            @if (request()->input('sort_by') == 'name_desc') selected @endif>{{ __('Name: Z-A') }}</option>
                </select>
            </div>
            <ul class="shorting-list">
                <li>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}"
                       class="{{ strpos($viewType, 'grid') !== false ? 'active' : '' }}"><i
                            class="ti-layout-grid2"></i></a></li>
                <li>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
                       class="{{ strpos($viewType, 'list') !== false ? 'active' : '' }}"><i
                            class="ti-view-list"></i></a>
                </li>
            </ul>
        </div>
    </div>
</div>
