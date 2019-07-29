(function($) {
  'use strict';
  if ($("#lightgallery").length) {
    $("#lightgallery").lightGallery();
  }

  for (let i=1; i<500; i++){
    if ($("#scan"+i).length) {
      $("#scan"+i).lightGallery();
    }
  }

  for (let i=1; i<500; i++){
    if ($("#lab"+i).length) {
      $("#lab"+i).lightGallery();
    }
  }

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