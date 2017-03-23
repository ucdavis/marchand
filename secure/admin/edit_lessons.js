function add_text_block() {
	// count the text blocks
	num_text_blocks = $("#text_blocks > tbody > tr").length;
	
	new_num = num_text_blocks + 1;
	
	// add the new text block
	$('#text_blocks > tbody:last').append('<tr><td><br /><div style="float: left; width: 120px;"> \
		<h2>Text Block #' + new_num + '</h2> \
	</div> \
	<div style="margin-left: 120px;"> \
		<input type="text" name="text_block_' + new_num + '_title" value="Title" style="width: 400px;" /><br /> \
		<textarea name="text_block_' + new_num + '" rows="10" cols="65"></textarea><br /> \
	</div></td></tr>');
}

function add_document() {
	// count the text blocks
	num_documents = $("#documents > tbody > tr").length;
	
	new_num = num_documents + 1;
	
	// add the new text block
	$('#documents > tbody:last').append('<tr><td><h2>Document #' + new_num + ':</h2> \
						<textarea name="document_' + new_num + '" rows="3" cols="80">Text here</textarea><br /> \
	\
						<h2 style="display: inline;">Image ID: </h2> <input type="text" value="" name="document_' + new_num + '_sid" /> <input type="button" value="Add" /><br /> \
						\
						<div style="margin-left: 80px;"> \
							<br /> \
							<div style="background-color: #999; width: 125px; height: 125px;"></div> \
							<br /> \
							<h2>Image Caption/Text</h2> <input type="text" name="document_' + new_num + '_sid_caption" value="" /> \
						</div></td></tr>');
}

function lesson_assign_region(id, region_id) {
	$.post("ajax/lesson_region.php", { id: id, region_id: region_id, action: 'assign' }, function(data) {
		window.location.reload(true);
	});
}

function lesson_remove_region(id, region_id) {
	$.post("ajax/lesson_region.php", { id: id, region_id: region_id, action: 'remove' }, function(data) {
		window.location.reload(true);
	});
}

function lesson_assign_topic(id, topic_id) {
	$.post("ajax/lesson_topic.php", { id: id, topic_id: topic_id, action: 'assign' }, function(data) {
		window.location.reload(true);
	});
}

function lesson_remove_topic(id, topic_id) {
	$.post("ajax/lesson_topic.php", { id: id, topic_id: topic_id, action: 'remove' }, function(data) {
		window.location.reload(true);
	});
}

function lesson_assign_standard(id, standard_id, stype) {
	$.post("ajax/lesson_standard.php", { id: id, standard_id: standard_id, stype: stype, action: 'assign' }, function(data) {
		window.location.reload(true);
	});
}

function lesson_remove_standard(id, standard_id, stype) {
	$.post("ajax/lesson_standard.php", { id: id, standard_id: standard_id, stype: stype, action: 'remove' }, function(data) {
		window.location.reload(true);
	});
}

// delete a text block by lesson id and text block id
function delete_block(lid, tbid) {
	$.post("ajax/lesson_text_blocks.php", { lid: lid, tbid: tbid, action: 'delete' }, function(data) {
		window.location.reload(true);
	});
}

// delete a document by lesson id and document id
function delete_document(lid, did) {
	$.post("ajax/lesson_documents.php", { lid: lid, did: did, action: 'delete' }, function(data) {
		window.location.reload(true);
	});
}
