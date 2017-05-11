# Place all the behaviors and hooks related to the matching controller here.
# All this logic will automatically be available in application.js.
# You can use CoffeeScript in this file: http://coffeescript.org/

$(document).ready ->
	$(".image-card").on "click", (e) ->
		fillModal(this)

	$("[data-type=menu]").on "keyup", (e) ->
		target = $("[data-target]", this).data("target")
		searchFilter(this, target)

	$("input[type=checkbox]", $("[data-type=list]")).on "click", (e) ->
		target = $(this).closest("[data-tag-target]").data("tag-target")
		text = $(this).closest("span").html()
		toggleTag(this, text, targetId)

# Adds / Remove the tag in the tag area
toggleTag = (checkbox, text, targetId) ->
	if $(checkbox).is(":checked")
		# Add tag to target area
	else
		# Remove tag from target area

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
fillModal = (el) ->
	view = $(el).data("view")
	delim = "@delim@"
	imgSrc = $(el).data("src")
	imgTitle = $(".title", $(el)).html()
	imgCollection = "From #{$(el).data("collection")} collection"
	imgCard = $(el).data("card")
	imgCitation = $(el).data("citation")
	imgTopics = $(el).data("topics").split(delim)
	imgRegions = $(el).data("regions").split(delim)
	imgCalStandards = $(el).data("cal-standards").split(delim)
	imgNatStandards = $(el).data("nat-standards").split(delim)

	# Firefox chooses to only swap the images once the picture is fully loaded
	# So we clear it first to avoid having the wrong picutre in a modal
	$("img", $("##{view}-modal .modal-header")).attr("src", "")

	$("img", $("##{view}-modal .modal-header")).attr("src", imgSrc)
	$(".title", $("##{view}-modal .image-title")).html(imgTitle)
	$(".collection", $("##{view}-modal .image-title")).html(imgCollection)
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
