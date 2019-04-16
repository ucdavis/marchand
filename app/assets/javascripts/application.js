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
  var wrapper = $(".append_input_fields");

  $(wrapper).on("click", ".remove_field", function(e){
    e.preventDefault();
    $(this).parent('div').remove();
  })

  wrapper = $(".appended_input_fields");

  $(wrapper).on("click", ".remove_field", function(e){
    e.preventDefault();
    $(this).parent('div').remove();
  })
});

function add_document(image_id) {
  console.log(image_id);

  $('#image-find-controls-text').css('visibility', 'visible');

  $.get( Routes.image_path(image_id), () =>
     $('.append_input_fields').append('<div><input id="business_loc" multiple="multiple" name="lesson[image_ids][]" size="40" value="'+ image_id +'"><a href="#" class="remove_field"> Remove</a></div>')
  ).fail( () =>
    $('#image-find-controls-text').css('color', '#f00').html('Invalid image id. Please try again.')
  )
}
