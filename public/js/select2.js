$(document).ready(function () {
  $(".js-example-basic-single").select2({
    placeholder: "Nothing Selected",
    allowClear: true,
  });


  (function($) {
    'use strict';
    $(".year_completed").select2({
      placeholder: "Nothing Selected",
      allowClear: true,
    });

    $(".js-example-basic-multiple").select2({
      placeholder: "Nothing Selected",
      allowClear: true,
    });
  })(jQuery);
});

