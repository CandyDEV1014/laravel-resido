/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import { toSafeInteger } from 'lodash';
import Botble from './utils';

// require('./bootstrap');
require('./form');
require('./avatar');

$(document).ready(() => {
    if (window.noticeMessages) {
        window.noticeMessages.forEach(message => {
            Botble.showNotice(message.type, message.message, message.type === 'error' ? 'Error' : 'Success');
        });
    }

    $(document).on('click', '.button-renew', event => {
        
        event.preventDefault();
        let _self = $(event.currentTarget);
        let url = _self.data("url");
        $.ajax({
            url: url,
            type: 'POST',
            success: data => {
                if (data.error) {
                    Botble.showError(data.message);
                } else {
                    $('.button-confirm-renew').data('section', _self.data('section')).data('parent-table', _self.closest('.table').prop('id'));
                    $('.modal-confirm-renew').modal('show');
                }
            },
            error: data => {
                Botble.handleError(data);
            }
        });
        console.log(url);
        
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
                    setTimeout(() => {location.reload();}, 1000);
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
});
