<select id="sub_category" data-placeholder="{{ __('Sub Category') }}" name="subcategory_id" class="form-control select2">
    <option value="">{{ __('Sub category') }}</option>
    @if(!empty(request()->input('category_id')))
        @foreach(get_re_categories(request()->input('category_id')) as $category)
            <option value="{{ $category->id }}" @if (request()->input('subcategory_id') == $category->id) selected @endif>
                {{ $category->name }}
            </option>
        @endforeach
    @endif
</select>
