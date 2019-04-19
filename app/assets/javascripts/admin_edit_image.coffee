$(document).ready () ->
  $('#image-edit-controls button').on "click", (e) ->
    edit_mode = $(e.target).data('edit-control')
    image_id = $(e.target).data('image-id')

    # Disable editing controls, dim image, and display 'Please wait' text while image is manipulated ...
    $('#image-edit-controls-text').css('visibility', 'visible')

    $.get( Routes.image_manipulate_path(image_id) + "?edit_mode=" + edit_mode, (data) =>
      # Refresh the image
       $('.image-thumbnail img').attr('src', data.url)
       $('#image-edit-controls-text').css('visibility', 'hidden')
    ).fail( () =>
      $('#image-edit-controls-text').css('color', '#f00').html('An error occurred. Please try again or contact IT.')
    )

    # Prevent form from submitting
    false
