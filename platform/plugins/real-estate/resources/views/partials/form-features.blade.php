@foreach ($features as $feature)
    <label class="checkbox-inline">
        <input name="features[]" class="features" type="checkbox" value="{{ $feature->id }}" @if (in_array($feature->id, $selectedFeatures)) checked @endif>{{ $feature->name }}
    </label>&nbsp;
@endforeach

<script>
    $(document).ready(function() {
        var limit = parseInt("{{ isset($limit_features) ? $limit_features : -1 }}");
        var $checkboxes = $("input.features");
        
        $checkboxes.change(function () {
            if (this.checked) {
                if (limit != -1 && $checkboxes.filter(':checked').length == limit) {
                    $checkboxes.not(':checked').prop('disabled', true);
                    toastr.info("Features limit count is " + limit);
                }
            } else {
                $checkboxes.prop('disabled', false);
            }
        });
    });
</script>
