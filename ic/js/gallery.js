// Global variables
// User settings (set by the dropdowns)
var collection_id = -1;
var region_id = -1;
var standard_nat_id = -1;
var standard_cal_id = -1;
var topic_id = -1;
var keywords = "";
var query = "";

// Internal settings (for tracking pagination)
var index = 0;
var per_page = 24;
var known_total = 0; // changes depending on the search terms

$(document).ready(function() {
	$("select#slides_per_page").change(function() {
		per_page = $("select#slides_per_page").val();
	});
});

// Functions
function search_gallery(reset_pagination) {
	collection_id = $("select[name=search_term_collection]").val();
	topic_id = $("select[name=search_term_topic]").val();
	region_id = $("select[name=search_term_region]").val();
	standard_nat_id = $("select[name=search_term_standard_nat]").val();
	standard_cal_id = $("select[name=search_term_standard_cal]").val();
	keywords = $("input[name=search_term_keywords]").val();
	query = $("input[name=search_term_query]").val();

	if(reset_pagination) {
		index = 0;
		known_total = 0;
	}

	refresh_gallery();
}

function first_page() {
	index = 0;

	redirect_to_search(false, index);
}

function last_page() {
	index = (Math.ceil(parseInt(known_total) / parseInt(per_page)) - 1) * parseInt(per_page);

	redirect_to_search(false, index);
}

function prev_page() {
	index = parseInt(index) - parseInt(per_page);

	if(index < 0) index = 0;

	// pre-emptively set the last_location to the upcoming search page
	//$.cookie('last_location', build_search_url(index));

	//search_gallery(false);
	redirect_to_search(false, index);
}

function next_page() {
	index = parseInt(index) + parseInt(per_page);

	if(index >= parseInt(known_total)) index = (Math.ceil(parseInt(known_total) / parseInt(per_page)) - 1) * parseInt(per_page);

	// pre-emptively set the last_location to the upcoming search page
	//$.cookie('last_location', build_search_url(index));

	//search_gallery(false);
	redirect_to_search(false, index);
}

// type = 0 (default) is thumbnail sheet
// type = 1 is list view
// stored in a cookie so as to not frustrate the user
function switch_view(type) {
	if(type == 0) {
		// switch to thumbnail view
		$.cookie("ic_view", 0);
		$("span#view_options").html("Thumbnail or <a href=\"javascript:switch_view(1);\">List</a>");
	} else if(type == 1) {
		// switch to list view
		$.cookie("ic_view", 1);
		$("span#view_options").html("<a href=\"javascript:switch_view(0);\">Thumbnail</a> or List");
	}

	refresh_gallery();
}

function refresh_gallery() {
	$.post("ajax/fetch_gallery.ajax.php", { collection: collection_id, region: region_id, standard_nat: standard_nat_id, standard_cal: standard_cal_id, topic: topic_id, keywords: keywords, index: index, per_page: per_page, query: query }, function(data) {

		total = data['total'];
		images = data['images'];

		known_total = total;

		// Clear out the current gallery
		$("div#gallery").empty();

		if(total > 0) {
			// Build a new table based on the results
			through_num = (parseInt(index) + parseInt(per_page));

			if(through_num > total) through_num = parseInt(total);

			html_str = "<h4>Displaying " + (parseInt(index) + 1) + " through " + through_num + " out of " + total + " results</h4>";

			// which view (table or list) does the user want?
			if(($.cookie("ic_view") == 0) || ($.cookie("ic_view") == null)) {
				// table view
				html_str = html_str + "<table><tr style=\"vertical-align: top;\">";

				row_count = 0;
				for(i = 0; i < images.length; i++) {
					html_str = html_str + "<td style=\"text-align: center;\"><div id=\"" + images[i]['id'] + "\" style=\"background-color: #fff; width: 125px; height: 125px; text-align: center;\"><a name=\"" + images[i]['id'] + "\"></a><a href=\"/ic/image_details.php?id=" + images[i]['id'] + "\"><img class=\"gallery-thumbnail\" src=\"http://historyproject.ucdavis.edu/ic/get_image.php?id=" + images[i]['id'] + "&thumb\" style=\"float: none; margin: 0;\" /></a></div><div style=\"width: 125px; clear: both; overflow: hidden;\">" + images[i]['title'];

					if(editor) {
						html_str = html_str + "<br /><input type=\"button\" value=\"Edit\" onClick=\"window.location.href='http://historyproject.ucdavis.edu/secure/admin/edit_slides.php?id=" + images[i]['id'] + "'\" />";
					}

					html_str = html_str + "</div></td><td width=\"25\">&nbsp;&nbsp;</td>";

					row_count = row_count + 1;

					if(row_count == 4) {
						html_str = html_str + "</tr><tr><td colspan=\"8\">&nbsp;</td></tr><tr style=\"vertical-align: top;\">";
						row_count = 0;
					}
				}

				html_str = html_str + "</tr></table>";
			} else {
				// list view
				html_str = html_str + "<table>";

				for(i = 0; i < images.length; i++) {
					html_str = html_str + "<tr valign=\"top\"><td style=\"text-align: center;\"><div id=\"" + images[i]['id'] + "\" style=\"background-color: #e6e7e8; width: 125px; height: 125px; text-align: center;\"><a name=\"" + images[i]['id'] + "\"></a><a href=\"/ic/image_details.php?id=" + images[i]['id'] + "\"><img class=\"gallery-thumbnail\" src=\"http://historyproject.ucdavis.edu/ic/get_image.php?id=" + images[i]['id'] + "&thumb\" /></a>";

					if(editor) {
						html_str = html_str + "<br /><input type=\"button\" value=\"Edit\" onClick=\"window.location.href='http://historyproject.ucdavis.edu/secure/admin/edit_slides.php?id=" + images[i]['id'] + "'\" /><br /><br />";
					}

					html_str = html_str + "</div></td><td><h4>" + images[i]['title'] + "</h4><p>" + images[i]['card'] + "</p><p>" + images[i]['citation'] + "</p></td></tr><tr><td colspan=\"2\">&nbsp;</td></tr>";
				}

				html_str = html_str + "</table>";
			}

			page_num = (index / per_page) + 1;
			page_num = Math.round(page_num);
			total_pages = Math.ceil(known_total / per_page);

			html_str = html_str + "<div><center><h4><big>Page " + page_num + " of " + total_pages + "</big></h4></center></div>";

			html_str = html_str + "<div style=\"float: right;\">";

			// do we need pagination?
			if(total > per_page) {
				html_str = html_str + "<p><br />";

				if(index > 0) {
					html_str = html_str + "<a href=\"javascript:first_page();\">First</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:prev_page();\">Prev</a>";
				} else {
					html_str = html_str + "First&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prev";
				}

				html_str = html_str + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

				if(index + per_page < total) {
					html_str = html_str + "<a href=\"javascript:next_page();\">Next</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:last_page();\">Last</a>";
				} else {
					html_str = html_str + "Next&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Last";
				}

				html_str = html_str + "</p></div>";
			}
		} else {
			html_str = "<h4>No results match your criteria.</h4>";
		}

		$("div#gallery").html(html_str);
		$("span#intro").hide();

		// update the last location (we always want to return to the search results we're at)
		$.cookie('last_location', build_search_url(index), { path: '/' });

		// scroll, if necessary
		o = document.getElementById($.cookie('last_edit_id'));
		if(o) o.scrollIntoView(true);
	});
}

function redirect_to_search(remember_page, specified_index) {
	if(remember_page) $.cookie('last_location', window.location.href, { path: '/' });

	// the following line implents a form of default parameters for JS
	specified_index = typeof(specified_index) != 'undefined' ? specified_index : 0;

	if(($("input[name=search_term_id]").val() != undefined) && ($("input[name=search_term_id]").val() > 0)) {
		window.location.href = "http://historyproject.ucdavis.edu/ic/image_details.php?id=" + $("input[name=search_term_id]").val();
	} else {
		var newPath = build_search_url(specified_index);
		window.location.href = newPath;
	}
}

// returns the search url for current page variables
function build_search_url(specified_index, specified_per_page) {
	region = $("select[name=search_term_region]").val();
	topic = $("select[name=search_term_topic]").val();
	standard_cal = $("select[name=search_term_standard_cal]").val();
	collection = $("select[name=search_term_collection]").val();
	query = $("input[name=search_term_query]").val();

	// the following lines implent a form of default parameters for JS
	specified_index = typeof(specified_index) != 'undefined' ? specified_index : 0;
	specified_per_page = typeof(specified_per_page) != 'undefined' ? specified_per_page : per_page;

	return "http://" + window.location.host + window.location.pathname + 'index.php?region=' + region + '&topic=' + topic + '&standard_cal=' + standard_cal + '&collection=' + collection + "&index=" + specified_index + "&per_page=" + specified_per_page + "&query=" + escape(query);
	// return "http://google.com";
}
