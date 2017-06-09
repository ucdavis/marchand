# https://codepen.io/asommer70/post/rails-image-upload-preview
$(document).on 'turbolinks:load', () ->
    $('.upload-image-btn').on 'change', (e) ->
        previewImage(this)

previewImage = (file) ->
    console.log "previewing"
    file = file.files[0]
    reader = new FileReader()
    reader.addEventListener "load", ( ->
        $(".preview").attr("src", reader.result);
    ), false

    if (file)
        reader.readAsDataURL(file)
