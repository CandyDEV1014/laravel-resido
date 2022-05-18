<ul class="no-ul-list {{ $class ?? 'third-row' }}">
    @foreach (get_feature_all() as $key => $feature)
        <li>
            <input id="features-{{ $key }}" class="input-filter checkbox-custom" name="features[]"
                type="checkbox" @if (in_array($feature['id'], request()->input('features', []))) checked @endif value="{{ $feature['id'] }}">
            <label for="features-{{ $key }}" class="checkbox-custom-label" style="display: unset;">{{ $feature['translations'][0]['name'] ?? $feature['name'] }}</label>
        </li>
    @endforeach
</ul>