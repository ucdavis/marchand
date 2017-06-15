$(document).ready () ->
    # $(".container.admin .panel .panel-body")
    $("[data-type=search-box]").each () ->
        $(this).on "keyup", (e) ->
            area = $(this).closest(".panel-body")
            area = $(".filter-row-container", area)
            target = $(this).data("target")
            text = $(this).val()
            searchFilter(area, target, text)

# Filters a given ul based on information in the search-box
# @param{jQuery} menu - Container that consists of a search-box and a list
# @param{String} target - Inner most wrapper for the text to search through.
searchFilter = (searchArea, target, input) ->
    filter = input.toLowerCase()

    $("#{target}", searchArea).each (index, item) ->
        text = $(this).html();
        if ( text.toLowerCase().indexOf(filter) > -1 )
            $(item).parent().css("display", "")
        else
            $(item).parent().css("display", "none")
