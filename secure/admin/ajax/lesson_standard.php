<?php
	require_once '/html/connect.inc';

	if(isset($_POST['id']) && isset($_POST['action']) && isset($_POST['stype'])) {
		$db = mysql_connect("localhost", $connect["username"], $connect["password"]);
		mysql_select_db("lessons", $db);

		if($_POST['action'] == "assign") {
			// Add to database
			$result = mysql_query("insert into standards_data (lesson_id, sid, stype) values('".mysql_real_escape_string($_POST['id'])."', '".mysql_real_escape_string($_POST['standard_id'])."', '".mysql_real_escape_string($_POST['stype'])."')", $db);
		} else if($_POST['action'] == "remove") {
			// Remove from database
			$query = "delete from standards_data where lesson_id='".mysql_real_escape_string($_POST['id'])."' and id='".mysql_real_escape_string($_POST['standard_id'])."' and stype='".mysql_real_escape_string($_POST['stype'])."' limit 1";
			$result = mysql_query($query, $db);
		}
	}
