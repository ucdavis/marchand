previewImage = (file) ->
  file = file.files[0]
  reader = new FileReader()
  reader.addEventListener "load", ( ->
    $(".preview").attr("src", reader.result);
  ), false

  if (file)
    reader.readAsDataURL(file)

setNewImageValues = (form) ->
  title = $("input[name=q][type=text]").val()
  regions = []
  topics = []
  calStandards = []
  $("input[type=checkbox]", $("form[name=filter]")).each (i, item) ->
    if !item.checked
      return true

    param = item.getAttribute("name")
    value = item.getAttribute("value")
    switch(param)
      when 'region'
        regions.push value
        break
      when 'collection'
        collections.push value
        break
      when 'topic'
        topics.push value
        break
      when 'calstandard'
        calStandards.push value
        break

  regions = "#{regions.join('","')}"
  topics = "#{topics.join('","')}"
  calStandards = "#{calStandards.join('","')}"

  $('[name="image[title]"]',form).val(title)
  $('[name="image[topic_ids][]"]', form).val(topics)
  $('[name="image[cal_standard_ids][]"]', form).val(calStandards)
  $('[name="image[region_ids][]"]', form).val(regions)

# Builds the url on advanced search
buildUrl = (form) ->
  page = $(form).data("target")
  query = "q=#{$("input[name=q][type=text]").val()}"
  start_year = "start_year=#{$("input[name=start_year]",form).val()}"
  end_year = "end_year=#{$("input[name=end_year]",form).val()}"
  regions = []
  collections = []
  topics = []
  calStandards = []
  $("input[type=checkbox]", form).each (i, item) ->
    if !item.checked
      return true

    param = item.getAttribute("name")
    value = item.getAttribute("value")
    switch(param)
      when 'region'
        regions.push value
        break
      when 'collection'
        collections.push value
        break
      when 'topic'
        topics.push value
        break
      when 'calstandard'
        calStandards.push value
        break

  regions = "region=" + regions.join ","
  collections  = "collection=" + collections.join ","
  topics = "topic=" + topics.join ","
  calStandards = "calstandard=" + calStandards.join ","

  return "#{page}?#{regions}&#{collections}&#{topics}&#{calStandards}&#{start_year}&#{end_year}&#{query}"


# Adds / Remove the tag in the tag area
# @param checkbox - clicked checkbox
toggleTag = (checkbox) ->
  targetId = $(checkbox).data("target-id")
  text = $("span", $(checkbox).parent()).html()
  if $(checkbox).is(":checked")
    createTag(checkbox, text, targetId)
  else
    # Remove tag from target area
    removeTag(text, targetId)

createTag = (checkbox, text, targetId) ->
  # Initialize close icon for tag
  closeIcon = $("<i></i>",
    "class": "glyphicon glyphicon-remove"
    on:
      click: (e) ->
        $(checkbox).prop("checked", false);
        toggleTag(checkbox, text, targetId)
  )

  # Add tag to target area
  $("<span></span>",
    "class": "label label-default filter-label"
  ).html("<span class='text-short'>#{text}</span>").append(closeIcon).appendTo("##{targetId}")

removeTag = (text, targetId) ->
  $("span.text-short", "##{targetId}").each (i, el) ->
    content = el.innerHTML
    # content = content.substring 0, content.indexOf("<")
    if content == text
      $(el).parent().remove()

# Filters a given ul based on information in the search-box
# @param{jQuery} menu - Container that consists of a search-box and a list
# @param{String} target - Inner most wrapper for the text to search through.
searchFilter = (menu, target) ->
  input = $("[data-type='search-box']", menu)
  filter = $(input).val().toLowerCase()
  searchArea = $("[data-type=list]", menu);

  $('li', searchArea).each (index, item) ->
    text = if target == "input" then $(target, item).val() else $(target,item).html()
    console.log text
    if ( text.toLowerCase().indexOf(filter) > -1 )
      $(item).css("display", "")
    else
      $(item).css("display", "none")

# Changes the data in a modal
# @param el - div containing information of the picture
setModalImageDetails = (el) ->
  view = $(el).data("view")
  imgSrc = $(el).data("src")
  imgTitle = $(".title", $(el)).html()
  imgAuthors = $(el).data("authors")
  imgCollection = "From #{$(el).data('collection')} collection"
  imgCard = $(el).data("card")
  imgCitation = $(el).data("citation")
  imgTopics = $(el).data("topics")
  imgRegions = $(el).data("regions")
  imgCalStandards = $(el).data("cal-standards")
  imgNatStandards = $(el).data("nat-standards")
  imgId = $(el).data("id")

  # Edit Button
  $(".btn-edit", $("##{view}-modal .modal-header")).attr("href", "/images/#{imgId}/edit")

  # Firefox chooses to only swap the images once the picture is fully loaded
  # So we clear it first to avoid having the wrong picutre in a modal
  $("img", $("##{view}-modal .modal-header")).attr("src", "")

  $("img", $("##{view}-modal .modal-header")).attr("src", imgSrc)
  $(".title", $("##{view}-modal .image-title")).html(imgTitle)
  $(".collection", $("##{view}-modal .image-title")).html(imgCollection)
  $(".authors", $("##{view}-modal .image-title")).html("Uploaded by #{imgAuthors.join(", ")}")
  $(".card", $("##{view}-modal .list-section")).html(imgCard)
  $(".citation", $("##{view}-modal .list-section")).html(imgCitation)

  $(".topics", $("##{view}-modal .list-section")).html("")
  imgTopics.forEach (topic) ->
    if topic.length > 0
      $(".topics", $("##{view}-modal .list-section")).append("<li>#{topic}</li>")

  $(".regions", $("##{view}-modal .list-section")).html("")
  imgRegions.forEach (region) ->
    if region.length > 0
      $(".regions", $("##{view}-modal .list-section")).append("<li>#{region}</li>")

  $(".cal-standards", $("##{view}-modal .list-section")).html("")
  imgCalStandards.forEach (calStandard) ->
    if calStandard.length > 0
      $(".cal-standards", $("##{view}-modal .list-section")).append("<li>#{calStandard}</li>")

  $(".nat-standards", $("##{view}-modal .list-section")).html("")
  imgNatStandards.forEach (natStandard) ->
    if natStandard.length > 0
      $(".nat-standards", $("##{view}-modal .list-section")).append("<li>#{natStandard}</li>")

# Sets up event handlers for all future image modals
setupModalEvents = () ->
  # Download Button
  $('#download-image').on 'click', (e) ->
    imgSrc = $(window.modalImage).data('src')
    key = imgSrc.split('/').pop()
    tmp = window.open "download/#{key}"

$(document).ready () ->
  setupModalEvents()

  $('.upload-image-btn').on 'change', (e) ->
      previewImage(this)
    
  # Persist filters / tags from previous search
  $("input:checked").each (i, item) ->
    targetId = $(this).data("target-id")
    text = $("span", $(this).parent()).html()
    createTag(this, text, targetId)

  # Fill modal content
  $(".image-card").on "click", (e) ->
    window.modalImage = this
    setModalImageDetails(this)

  # Add filter search
  $("[data-type=menu]").on "keyup", (e) ->
    target = $("[data-target]", this).data("target")
    searchFilter(this, target)

  # Prevent checkbox's default event in favor of clicking the div
  $("input[type=checkbox]", $("[data-type=list]")).on "click", (e) ->
    e.stopPropagation()

  # Prevent bootstrap's default hide-on-click for dropdowns
  $(".dropdown-menu").on "click", (e) ->
    event.stopPropagation()

  # Add / Remove tags for filters
  $("input[type=checkbox]", $("[data-type=list]")).change (e) ->
    toggleTag(this)

  # Toggle checkbox on item click
  $(".customized-checkbox").on "click", (e) ->
    cb = $("input[type=checkbox]", this)[0]
    if $(cb).is(":checked")
      $(cb).prop("checked", false);
    else
      $(cb).prop("checked", true);
    $(cb).trigger("change")

  # Manually build url on submit
  $("form[name=filter]").on "submit", (e) ->
    e.preventDefault()
    url = buildUrl(this)
    window.location.href = url

  # Create new card with filter as initial params
  $("form[name=new-image]").on "click", () ->
    setNewImageValues($("form[name=new-image]"))
    this.submit()
