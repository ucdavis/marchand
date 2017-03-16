<?php
	require_once '/html/connect.inc';

	if(isset($_POST['lid']) && isset($_POST['action'])) {
		$db = mysql_connect("localhost", $connect["username"], $connect["password"]);
		mysql_select_db("lessons", $db);

		if($_POST['action'] == "delete") {
			// Delete text block from database, reordering in the process
			$result = mysql_query("delete from text_blocks where text_block_num='".mysql_real_escape_string($_POST['tbid'])."' and lesson_id='".mysql_real_escape_string($_POST['lid'])."' limit 1", $db);

			// Now reorder the database
			$result = mysql_query("update text_blocks set text_block_num = text_block_num - 1 where text_block_num > '".mysql_real_escape_string($_POST['tbid'])."' and lesson_id='".mysql_real_escape_string($_POST['lid'])."'", $db);
		}
	}
