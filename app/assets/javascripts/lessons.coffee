$(document).ready () ->
    $(".lesson-content.new").on "click", (e) ->
        window.location.href = "/lessons/new"

    $('input[name="lesson[pdf]"]').on "change", (e) ->
        $(".container.edit-lesson .pdf-path").html($(this).val())
