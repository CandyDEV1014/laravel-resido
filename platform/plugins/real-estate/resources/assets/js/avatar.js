/**
 * Created by Sang Nguyen on 06/09/2015.
 */

require('./cropper');

import Botble from './utils';

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else {
        factory(jQuery);
    }
})(function ($) {

    function CropAvatar($element) {
        this.$container = $element;

        this.$avatarView = this.$container.find('.avatar-view');
        this.$avatar = this.$avatarView.find('img');
        this.$avatarModal = this.$container.find('#avatar-modal');
        this.$loading = this.$container.find('.loading');

        this.$avatarForm = this.$avatarModal.find('.avatar-form');
        this.$avatarSrc = this.$avatarForm.find('.avatar-src');
        this.$avatarData = this.$avatarForm.find('.avatar-data');
        this.$avatarInput = this.$avatarForm.find('.avatar-input');
        this.$avatarSave = this.$avatarForm.find('.avatar-save');

        this.$avatarWrapper = this.$avatarModal.find('.avatar-wrapper');
        this.$avatarPreview = this.$avatarModal.find('.avatar-preview');

        this.init();
    }

    CropAvatar.prototype = {
        constructor: CropAvatar,

        support: {
            fileList: !!$('<input type="file">').prop('files'),
            fileReader: !!window.FileReader,
            formData: !!window.FormData
        },

        init: function () {
            this.support.datauri = this.support.fileList && this.support.fileReader;

            if (!this.support.formData) {
                this.initIframe();
            }

            this.initTooltip();
            this.initModal();
            this.addListener();
        },

        addListener: function () {
            this.$avatarView.on('click', $.proxy(this.click, this));
            this.$avatarInput.on('change', $.proxy(this.change, this));
            this.$avatarForm.on('submit', $.proxy(this.submit, this));
        },

        initTooltip: function () {
            this.$avatarView.tooltip({
                placement: 'bottom'
            });
        },

        initModal: function () {
            this.$avatarModal.modal('hide');
            this.initPreview();
        },

        initPreview: function () {
            var url = this.$avatar.prop('src');

            this.$avatarPreview.empty().html('<img src="' + url + '">');
        },

        initIframe: function () {
            var iframeName = 'avatar-iframe-' + Math.random().toString().replace('.', ''),
                $iframe = $('<iframe name="' + iframeName + '" style="display:none;"></iframe>'),
                firstLoad = true,
                _this = this;

            this.$iframe = $iframe;
            this.$avatarForm.attr('target', iframeName).after($iframe);

            this.$iframe.on('load', function () {
                var data,
                    win,
                    doc;

                try {
                    win = this.contentWindow;
                    doc = this.contentDocument;

                    doc = doc ? doc : win.document;
                    data = doc ? doc.body.innerText : null;
                } catch (e) {
                }

                if (data) {
                    _this.submitDone(data);
                } else {
                    if (firstLoad) {
                        firstLoad = false;
                    } else {
                        Botble.showError('Image upload failed!');
                    }
                }

                _this.submitEnd();
            });
        },

        click: function () {
            this.$avatarModal.modal('show');
        },

        change: function () {
            var files,
                file;

            if (this.support.datauri) {
                files = this.$avatarInput.prop('files');

                if (files.length > 0) {
                    file = files[0];

                    if (this.isImageFile(file)) {
                        this.read(file);
                    }
                }
            } else {
                file = this.$avatarInput.val();

                if (this.isImageFile(file)) {
                    this.syncUpload();
                }
            }
        },

        submit: function () {
            if (!this.$avatarSrc.val() && !this.$avatarInput.val()) {
                alert('Please select image!');
                return false;
            }

            if (this.support.formData) {
                this.ajaxUpload();
                return false;
            }
        },

        isImageFile: function (file) {
            if (file.type) {
                return /^image\/\w+$/.test(file.type);
            } else {
                return /\.(jpg|jpeg|png|gif)$/.test(file);
            }
        },

        read: function (file) {
            var _this = this,
                fileReader = new FileReader();

            fileReader.readAsDataURL(file);

            fileReader.onload = function () {
                _this.url = this.result;
                _this.startCropper();
            };
        },

        startCropper: function () {
            var _this = this;

            if (this.active) {
                this.$img.cropper('replace', this.url);
            } else {
                this.$img = $('<img src="' + this.url + '">');
                this.$avatarWrapper.empty().html(this.$img);
                this.$img.cropper({
                    aspectRatio: 1,
                    rotatable: true,
                    preview: this.$avatarPreview.selector,
                    done: function (data) {
                        var json = [
                            '{"x":' + data.x,
                            '"y":' + data.y,
                            '"height":' + data.height,
                            '"width":' + data.width + '}'
                        ].join();

                        _this.$avatarData.val(json);
                    }
                });

                this.active = true;
            }
        },

        stopCropper: function () {
            if (this.active) {
                this.$img.cropper('destroy');
                this.$img.remove();
                this.active = false;
            }
        },

        ajaxUpload: function () {
            var url = this.$avatarForm.attr('action'),
                data = new FormData(this.$avatarForm[0]),
                _this = this;

            $.ajax(url, {
                type: 'post',
                data: data,
                processData: false,
                contentType: false,

                beforeSend: function () {
                    _this.submitStart();
                },

                success: function (data) {
                    _this.submitDone(data);
                },

                error: function (data) {
                    Botble.handleError(data);
                },

                complete: function () {
                    _this.submitEnd();
                }
            });
        },

        syncUpload: function () {
            this.$avatarSave.click();
        },

        submitStart: function () {
            this.$loading.fadeIn();
            this.$avatarSave.attr('disabled', true).text('Saving...');
        },

        submitDone: function (data) {

            try {
                data = $.parseJSON(data);
            } catch (e) {
            }

            if (data && !data.error) {
                if (!data.error) {
                    this.url = data.data.url;

                    if (this.support.datauri || this.uploaded) {
                        this.uploaded = false;
                        this.cropDone();
                    } else {
                        this.uploaded = true;
                        this.$avatarSrc.val(this.url);
                        this.startCropper();
                    }

                    this.$avatarInput.val('');
                    Botble.showSuccess(data.message);
                } else {
                    Botble.showError(data.message);
                }
            } else {
                Botble.showError(data.message);
            }
        },

        submitEnd: function () {
            this.$loading.fadeOut();
            this.$avatarSave.removeAttr('disabled').text('Save');
        },

        cropDone: function () {
            this.$avatarSrc.val('');
            this.$avatarData.val('');
            this.$avatar.prop('src', this.url);
            $('.user-menu img').prop('src', this.url);
            $('.user.dropdown img').prop('src', this.url);
            this.stopCropper();
            this.initModal();
        },
    };

    $(function () {
        new CropAvatar($('.crop-avatar'));
    });
});
