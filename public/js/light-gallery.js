(function($) {
  'use strict';
  if ($("#lightgallery").length) {
    $("#lightgallery").lightGallery();
  }

  /*if ($("#lightgallery1").length) {
    $("#lightgallery1").lightGallery();
  }*/

  if ($("#lightgallery-without-thumb").length) {
    $("#lightgallery-without-thumb").lightGallery();
  }
  /*if ($("#lightgallery-without-thumb").length) {
    $("#lightgallery-without-thumb").lightGallery({
      thumbnail: true,
      animateThumb: false,
      showThumbByDefault: false
    });
  }*/

  if ($("#video-gallery").length) {
    $("#video-gallery").lightGallery();
  }
})(jQuery);