function search_archive() {
	query = $("input#search_query").val();
	
	if(query == "") return; // bad input from user
	
	$.post("ajax/search.php", { query: query }, function(data) {
		
	});
}
