$(function () {
  $('.edit-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    var day = $(this).attr('day');
    var part = $(this).attr('part');
    var modal = $(this);
    $('.modal-inner-day input').val(day);
    $('.modal-inner-day span').text(day);
    $('.modal-inner-part input').val(part);
    $('.modal-inner-part span').text(part);
    return false;
  });
  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });

});
