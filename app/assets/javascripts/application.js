// Library includes

//= require jquery
//= require jquery_ujs
//= require bootstrap-sprockets
//= require bootstrap2-toggle.js
//= require bootstrap-slider.js
//= require sticky.js
//= require js-routes

// Local includes

//= require admin
//= require images

$(document).ready(function () {
  var append = $(".append-input-fields");

  $(append).on("click", ".remove_field", function(e) {
    e.preventDefault();
    $(this).parent('div').remove();
  })

  var wrapper = $(".appended-input-fields");

  $(wrapper).on("click", ".remove_field", function(e) {
    e.preventDefault();
    $(this).parent('div').remove();
  })
});

function add_image(image_id) {
  $('#image-find-controls-text').css('visibility', 'hidden');

  $.get( Routes.image_path(image_id), () =>
     $('.append-input-fields').append('<div><input id="added-input-field" multiple="multiple" name="lesson[image_ids][]" size="40" style="margin-right: 15px;" value="'+ image_id +'"><a href="#" class="remove_field">Remove</a></div>')
  ).fail( () =>
    $('#image-find-controls-text').css('color', '#f00').html('Invalid image id. Please try again.').css('visibility', 'visible')
  )
}
