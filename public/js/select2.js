$(document).ready(function () {
  $(".js-example-basic-single").select2({
    placeholder: "Nothing Selected",
    allowClear: true,
  });
  (function($) {
    'use strict';


    if ($(".js-example-basic-multiple").length) {
      $(".js-example-basic-multiple").select2({
        placeholder: "Nothing Selected",
        allowClear: true,
      });
    }
  })(jQuery);
});

