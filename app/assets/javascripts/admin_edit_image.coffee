$(document).ready () ->
  $('#image-edit-controls button').on "click", (e) ->
    edit_mode = $(e.target).data('edit-control')
    image_id = $(e.target).data('image-id')

    # Disable editing controls, dim image, and display 'Please wait' text while image is manipulated ...
    $('#image-edit-controls-text').css('visibility', 'visible')
    $('#image-edit-controls button').prop("disabled", true)
    $('img.preview:first').css('opacity', '0.35')

    $.get( Routes.image_manipulate_path(image_id) + "?edit_mode=" + edit_mode, () =>
      # Refresh the image
      $img = $('img.preview:first')
      img_src = $img.attr('src')
      $img[0].src = img_src

      $('#image-edit-controls-text').css('visibility', 'hidden')
      $('#image-edit-controls button').prop("disabled", false)
      $('img.preview:first').css('opacity', '1')

      # Unfortunately S3 updates aren't always available right away, so it sometimes
      # appears as though the image wasn't manipulated when we really just need to wait
      # a few seconds. Here's a quick hack until we fix our design ...
      setTimeout( () =>
        $img[0].src = img_src
      , 3500)
      setTimeout( () =>
        $img[0].src = img_src
      , 7000)
      setTimeout( () =>
        $img[0].src = img_src
      , 10500)
    ).fail( () =>
      $('#image-edit-controls-text').css('color', '#f00').html('An error occurred. Please try again or contact IT.')
    )

    false
