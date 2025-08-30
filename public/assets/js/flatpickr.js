// npm package: flatpickr
// github link: https://github.com/flatpickr/flatpickr

$(function () {
  'use strict';

  // date picker 
  if ($('#flatpickr-date').length) {
    flatpickr("#flatpickr-date", {
      wrap: true,
      dateFormat: "Y-m-d",
    });
  }
  if ($('#flatpickr-date1').length) {
    flatpickr("#flatpickr-date1", {
      wrap: true,
      dateFormat: "Y-m-d",
    });
  }
  if ($('#flatpickr-date2').length) {
    flatpickr("#flatpickr-date2", {
      wrap: true,
      dateFormat: "Y-m-d",
    });
  }


  // time picker
  if ($('#flatpickr-time').length) {
    flatpickr("#flatpickr-time", {
      wrap: true,
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
    });
  }

});