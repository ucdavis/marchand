# Place all the behaviors and hooks related to the matching controller here.
# All this logic will automatically be available in application.js.
# You can use CoffeeScript in this file: http://coffeescript.org/

$(document).ready ->
	$(".image-card").on "click", (e) ->
		fillModal(this)


fillModal = (el) ->
	delim = "@delim@"
	imgSrc = $(el).data("src")
	imgTitle = $(".title", $(el)).html()
	imgCollection = "From #{$(el).data("collection")} collection"
	imgCard = $(el).data("card")
	imgTopics = $(el).data("topics").split(delim)
	imgRegions = $(el).data("regions").split(delim)
	imgCalStandards = $(el).data("cal-standards").split(delim)
	imgNatStandards = $(el).data("nat-standards").split(delim)
	imgCitations = $(el).data("citations").split(delim)

	$("img", $("#myModal .modal-header")).attr("src", imgSrc)
	$(".title", $("#myModal .image-title")).html(imgTitle)
	$(".collection", $("#myModal .image-title")).html(imgCollection)
	$(".card", $("#myModal .list-section")).html(imgCard)

	$(".topics", $("#myModal .list-section")).html("")
	imgTopics.forEach (topic) ->
		$(".topics", $("#myModal .list-section")).append("<li>#{topic}</li>")

	$(".regions", $("#myModal .list-section")).html("")
	imgRegions.forEach (region) ->
		$(".regions", $("#myModal .list-section")).append("<li>#{region}</li>")

	$(".cal-standards", $("#myModal .list-section")).html("")
	imgCalStandards.forEach (calStandard) ->
		$(".cal-standards", $("#myModal .list-section")).append("<li>#{calStandard}</li>")

	$(".nat-standards", $("#myModal .list-section")).html("")
	imgNatStandards.forEach (natStandard) ->
		$(".nat-standards", $("#myModal .list-section")).append("<li>#{natStandard}</li>")

	$(".citations", $("#myModal .list-section")).html("")
	imgCitations.forEach (citation) ->
		$(".citations", $("#myModal .list-section")).append("<li>#{citation}</li>")
