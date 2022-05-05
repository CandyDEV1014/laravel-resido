@include('core/base::forms.partials.custom-select')

@once
    @push('footer')
        <script>
            "use strict";
            $('#' + '{{ Arr::get($selectAttributes, 'id') }}').select2({
                minimumInputLength: 2,
                ajax: {
                    url: '{{ Arr::get($selectAttributes, 'data-url') }}',
                    quietMillis: 500,
                    data: function (params) {
                        return {
                            q: params.term,
                        };
                    },
                    processResults: function (data) {
                        let results = data.data.map((item) => {
                            return {
                                id: item['id'],
                                text: item['name'],
                            };
                        });
                        return {
                            results: results
                        };
                    }
                }
            });
        </script>
    @endpush
@endonce
