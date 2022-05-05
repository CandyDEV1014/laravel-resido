$(document).ready(() => {
  $(document).on('click', '#is_allow_featured', (event) => {
    if ($(event.currentTarget).is(':checked')) {
      $('#number_of_featured').closest('.form-group').removeClass('hidden').fadeIn();
    } else {
      $('#number_of_featured').closest('.form-group').addClass('hidden').fadeOut();
    }
  });
  $(document).on('click', '#is_allow_top', (event) => {
    if ($(event.currentTarget).is(':checked')) {
      $('#number_of_top').closest('.form-group').removeClass('hidden').fadeIn();
    } else {
      $('#number_of_top').closest('.form-group').addClass('hidden').fadeOut();
    }
  });
  $(document).on('click', '#is_allow_urgent', (event) => {
    if ($(event.currentTarget).is(':checked')) {
      $('#number_of_urgent').closest('.form-group').removeClass('hidden').fadeIn();
    } else {
      $('#number_of_urgent').closest('.form-group').addClass('hidden').fadeOut();
    }
  });
  $(document).on('click', '#is_promotion', (event) => {
    if ($(event.currentTarget).is(':checked')) {
      $('.promotion').removeClass('hidden').fadeIn();
    } else {
      $('.promotion').addClass('hidden').fadeOut();
    }
  });
});
