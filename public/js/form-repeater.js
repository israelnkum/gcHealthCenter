$(document).ready(function () {
  $(".selectMedicine").select2({
    placeholder: "Nothing Selected",
    allowClear: true,
  });
});
(function($) {
  'use strict';
  $('.repeater').repeater({

    show: function () {
      $(this).slideDown();

      $(this).find('.selectMedicine').select2({

        placeholder: "Nothing Selected",

        allowClear: true,

      });


    },
    hide: function (deleteElement) {
      if (confirm('Are you sure you want to delete this drug?')) {
        $(this).slideUp(deleteElement);
      }
    },
    isFirstItemUndeletable: true
  });
})(jQuery);


(function($) {
  'use strict';
  $('.other-repeater').repeater({

    show: function () {
      $(this).slideDown();
    },
    hide: function (deleteElement) {
      if (confirm('Are you sure you want to delete this element?')) {
        $(this).slideUp(deleteElement);
      }
    },
    isFirstItemUndeletable: true
  });
})(jQuery);









