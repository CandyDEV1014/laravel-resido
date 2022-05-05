$(document).ready(function () {
    $(document).on('change', '#type_id', event => {
        if ($('#type_id').children('option:selected').data('code') == 'rent') {
            $('#period').closest('.period-form-group').removeClass('hidden').fadeIn();
        } else {
            $('#period').closest('.period-form-group').addClass('hidden').fadeOut();
        }
    });

    $(document).on('change', '#never_expired', event => {
        if ($(event.currentTarget).is(':checked') === true) {
            $('#auto_renew').closest('.auto-renew-form-group').addClass('hidden').fadeOut();
        } else {
            $('#auto_renew').closest('.auto-renew-form-group').removeClass('hidden').fadeIn();
        }
    });

    $(document).on('change', '#auto_renew', event => {
        if ($(event.currentTarget).is(':checked') === true) {
            $(".renew_cost").removeClass('hidden').fadeIn();
        } else {
            $('.renew_cost').addClass('hidden').fadeOut();
        }
    });

    $(document).on('click', '.button-renew', event => {
        
        event.preventDefault();
        let _self = $(event.currentTarget);
        Botble.showError("asdfasdfsadf");
        $('.button-confirm-renew').data('section', _self.data('section')).data('parent-table', _self.closest('.table').prop('id'));
        $('.modal-confirm-renew').modal('show');
    });

    $('.button-confirm-renew').on('click', event => {
        event.preventDefault();
        let _self = $(event.currentTarget);
        let url = _self.data('section');
        _self.addClass('button-loading');

        $.ajax({
            url: url,
            type: 'POST',
            success: data => {
                if (data.error) {
                    Botble.showError(data.message);
                } else {
                    window.LaravelDataTables[_self.data('parent-table')].row($('a[data-section="' + url + '"]').closest('tr')).remove().draw();
                    Botble.showSuccess(data.message);
                }

                _self.closest('.modal').modal('hide');
                _self.removeClass('button-loading');
            },
            error: data => {
                Botble.handleError(data);
                _self.removeClass('button-loading');
            }
        });
    });

    $('body')
        .on('change', '#property-category', function () {
            let _this = $(this);
            if ($('#property-sub-category').length < 1) {
                return;
            }

            $.ajax({
                url: _this.data('url'),
                data: {
                    id: _this.val()
                },
                beforeSend: () => {
                    $('#property-sub-category').html('<option value="">' + ($('#property-sub-category').data('placeholder')) + '</option>');
                },
                success: data => {
                    let option = '<option value="">' + ($('#property-sub-category').data('placeholder')) + '</option>';
                    $.each(data.data, (index, item) => {
                        option += '<option value="' + item.id + '">' + item.name + '</option>';
                    });

                    $('#property-sub-category').html(option).select2();
                }
            });
        })
        .on('change', 'select#filter_country_id', function () {
            let _this = $(this);

            $.ajax({
                url: $('#filter_state_id').data('url'),
                data: {
                    id: _this.val()
                },
                beforeSend: () => {
                    $('#filter_state_id').html('<option value="">' + ($('#filter_state_id').data('placeholder')) + '</option>');
                    $('#filter_city_id').html('<option value="">' + ($('#filter_city_id').data('placeholder')) + '</option>');
                },
                success: data => {
                    let option = '<option value="">' + ($('#filter_state_id').data('placeholder')) + '</option>';
                    $.each(data.data, (index, item) => {
                        option += '<option value="' + item.id + '">' + item.name + '</option>';
                    });

                    $('#filter_state_id').html(option).select2();
                }
            });
        })
        .on('change', 'select#filter_state_id', function () {
            let _this = $(this);

            $.ajax({
                url: $('#filter_city_id').data('url'),
                data: {
                    id: _this.val()
                },
                beforeSend: () => {
                    $('#filter_city_id').html('<option value="">' + ($('#filter_city_id').data('placeholder')) + '</option>');
                },
                success: data => {
                    let option = '<option value="">' + ($('#filter_city_id').data('placeholder')) + '</option>';
                    $.each(data.data, (index, item) => {
                        option += '<option value="' + item.id + '">' + item.name + '</option>';
                    });

                    $('#filter_city_id').html(option).select2();
                }
            });
        })
        .on('change', 'select#country_id', function () {
            let _this = $(this);

            $.ajax({
                url: $('#state_id').data('url'),
                data: {
                    id: _this.val()
                },
                beforeSend: () => {
                    $('#state_id').html('<option value="">' + ($('#state_id').data('placeholder')) + '</option>');
                    $('#city_id').html('<option value="">' + ($('#city_id').data('placeholder')) + '</option>');
                },
                success: data => {
                    let option = '<option value="">' + ($('#state_id').data('placeholder')) + '</option>';
                    $.each(data.data, (index, item) => {
                        option += '<option value="' + item.id + '">' + item.name + '</option>';
                    });

                    $('#state_id').html(option).select2();
                }
            });
        })
        .on('change', 'select#state_id', function () {
            let _this = $(this);

            $.ajax({
                url: $('#city_id').data('url'),
                data: {
                    id: _this.val()
                },
                beforeSend: () => {
                    $('#city_id').html('<option value="">' + ($('#city_id').data('placeholder')) + '</option>');
                },
                success: data => {
                    let option = '<option value="">' + ($('#city_id').data('placeholder')) + '</option>';
                    $.each(data.data, (index, item) => {
                        option += '<option value="' + item.id + '">' + item.name + '</option>';
                    });

                    $('#city_id').html(option).select2();
                }
            });
        })
        .on('change', 'select#category_id', function () {
            let _this = $(this);
            if ($('#subcategory_id').length < 1) {
                return;
            }

            $.ajax({
                url: $('#subcategory_id').data('url'),
                data: {
                    id: _this.val()
                },
                beforeSend: () => {
                    $('#subcategory_id').html('<option value="">' + ($('#subcategory_id').data('placeholder')) + '</option>');
                },
                success: data => {
                    let option = '<option value="">' + ($('#subcategory_id').data('placeholder')) + '</option>';
                    $.each(data.data, (index, item) => {
                        option += '<option value="' + item.id + '">' + item.name + '</option>';
                    });

                    $('#subcategory_id').html(option).select2();
                }
            });
        });
});
