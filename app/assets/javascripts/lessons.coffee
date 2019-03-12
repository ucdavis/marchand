# Changes the data in a modal
# @param el - div containing information of the picture
setModalLessonDetails = (el) ->
  lessonId = $(el).data('id')
  lessonTitle = $(el).data('title')

  $("input[name=lesson_id]", $("#requestLessonModal")).val(lessonId)
  $("#modal_lesson_title", $("#requestLessonModal")).html(lessonTitle)

$(document).ready () ->
  # Fill email modal content
  $(".request-lesson[data-toggle=modal]").on "click", (e) ->
    setModalLessonDetails(this)
