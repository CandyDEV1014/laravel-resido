/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************************************************!*\
  !*** ./platform/plugins/real-estate/resources/assets/js/form.js ***!
  \******************************************************************/
$(document).ready(function () {
  $('.custom-select-image').on('click', function (event) {
    event.preventDefault();
    $(this).closest('.image-box').find('.image_input').trigger('click');
  });

  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $(input).closest('.image-box').find('.preview_image').prop('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  $('.image_input').on('change', function () {
    readURL(this);
  });
});
/******/ })()
;