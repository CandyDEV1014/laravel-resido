<select id="ptypes" data-placeholder="{{ __('Category') }}" name="category_id"
        data-url="{{ route('public.ajax.sub-categories') }}" class="form-control has-sub-category">
    <option value="">&nbsp;</option>
    @foreach (get_re_categories() as $category)
        <option value="{{ $category->id }}" @if (request()->input('category_id') == $category->id) selected @endif>
            {{ $category->name }}
        </option>
    @endforeach
</select>
