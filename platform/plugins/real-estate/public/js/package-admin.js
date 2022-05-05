/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************************************************!*\
  !*** ./platform/plugins/real-estate/resources/assets/js/package-admin.js ***!
  \***************************************************************************/
$(document).ready(function () {
  $(document).on('click', '#is_allow_featured', function (event) {
    if ($(event.currentTarget).is(':checked')) {
      $('#number_of_featured').closest('.form-group').removeClass('hidden').fadeIn();
    } else {
      $('#number_of_featured').closest('.form-group').addClass('hidden').fadeOut();
    }
  });
  $(document).on('click', '#is_allow_top', function (event) {
    if ($(event.currentTarget).is(':checked')) {
      $('#number_of_top').closest('.form-group').removeClass('hidden').fadeIn();
    } else {
      $('#number_of_top').closest('.form-group').addClass('hidden').fadeOut();
    }
  });
  $(document).on('click', '#is_allow_urgent', function (event) {
    if ($(event.currentTarget).is(':checked')) {
      $('#number_of_urgent').closest('.form-group').removeClass('hidden').fadeIn();
    } else {
      $('#number_of_urgent').closest('.form-group').addClass('hidden').fadeOut();
    }
  });
  $(document).on('click', '#is_promotion', function (event) {
    if ($(event.currentTarget).is(':checked')) {
      $('.promotion').removeClass('hidden').fadeIn();
    } else {
      $('.promotion').addClass('hidden').fadeOut();
    }
  });
});
/******/ })()
;