$(document).ready(function() {
	// clear out default form fields when focused
	$("input#add_region_input").focus(function() {
		if($("input#add_region_input").val() == "New Region Name") $("input#add_region_input").val("");
	});
	$("input#add_topic_input").focus(function() {
		if($("input#add_topic_input").val() == "New Topic Name") $("input#add_topic_input").val("");
	});
});

function rotate(filename, dir) {
	// figure out the file to rotate

	data = {};
	data.filename = filename;
	data.dir = dir;

	$.post('rotate.php', data, function(data) {
		window.location.reload(true);
	});
}

function flip(filename) {
	// figure out the file to rotate

	data = {};
	data.filename = filename;

	$.post('flip.php', data, function() {
		window.location.reload(true);
	});
}

function add_region() {
	region = $("input#add_region_input").val();
	
	$.post("ajax/add_region.php", { title: region }, function(data) {
		// data = mysql_insert_id
		// (Assume data added, no json_encode() on this server ...)
		
		// Item is in DB, but add it to region dropdown and select as default
		$("select[name=additional_region]").addOption( data, region, true );
		
		// Ensure it is in ascending alphabetical order
		$("select[name=additional_region]").sortOptions(true);
		
		// Clear out the box to give more visual confirmation that something has happened
		$("input#add_region_input").val("");
	});
}

function add_topic() {
	topic = $("input#add_topic_input").val();
	
	if(topic == "Topic") return; // bad input from user
	
	$.post("ajax/add_topic.php", { title: topic }, function(data) {
		// data = mysql_insert_id
		// (Assume data added, no json_encode() on this server ...)
		
		new_topic_id = parseInt(data);
		
		// Item is in DB, but add it to region dropdown and select as default
		$("select[name=topic]").addOption( new_topic_id, topic, true );
		
		// Ensure it is in ascending alphabetical order
		$("select[name=topic]").sortOptions(true);
		
		// Clear out the box to give more visual confirmation that something has happened
		$("input#add_topic_input").val("");
	});
}

function assign_standard(id, standard_id, stype) {
	$.post("ajax/assign_standard.php", { id: id, standard_id: standard_id, stype: stype }, function(data) {
		window.location.reload(true);
	});
}

function remove_standard(id, standard_id, stype) {
	$.post("ajax/remove_standard.php", { id: id, standard_id: standard_id, stype: stype }, function(data) {
		window.location.reload(true);
	});
}

function assign_topic(id, topic_id) {
	$.post("ajax/assign_topic.php", { id: id, topic_id: topic_id }, function(data) {
		window.location.reload(true);
	});
}

function remove_topic(id, topic_id) {
	$.post("ajax/remove_topic.php", { id: id, topic_id: topic_id }, function(data) {
		window.location.reload(true);
	});
}

function assign_region(id, region_id) {
	$.post("ajax/assign_region.php", { id: id, region_id: region_id }, function(data) {
		window.location.reload(true);
	});
}

function remove_region(id, region_id) {
	$.post("ajax/remove_region.php", { id: id, region_id: region_id }, function(data) {
		window.location.reload(true);
	});
}
