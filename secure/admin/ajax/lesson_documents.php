<?php
	require_once '/html/connect.inc';

	if(isset($_POST['lid']) && isset($_POST['action'])) {
		$db = mysql_connect("localhost", $connect["username"], $connect["password"]);
		mysql_select_db("lessons", $db);

		if($_POST['action'] == "delete") {
			// Delete text block from database, reordering in the process
			$result = mysql_query("delete from documents where document_num='".mysql_real_escape_string($_POST['did'])."' and lesson_id='".mysql_real_escape_string($_POST['lid'])."' limit 1", $db);

			// Now reorder the database
			$result = mysql_query("update documents set document_num = document_num - 1 where document_num > '".mysql_real_escape_string($_POST['did'])."' and lesson_id='".mysql_real_escape_string($_POST['lid'])."'", $db);
		}
	}
