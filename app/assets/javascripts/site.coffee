# Place all the behaviors and hooks related to the matching controller here.
# All this logic will automatically be available in application.js.
# You can use CoffeeScript in this file: http://coffeescript.org/

$(document).ready ->
	$(".image-card").on "click", (e) ->
		fillModal(this)


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

	console.log imgRegions

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
