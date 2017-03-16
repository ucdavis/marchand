<?php
	require_once '/html/connect.inc';

	if(isset($_POST['id']) && isset($_POST['action'])) {
		$db = mysql_connect("localhost", $connect["username"], $connect["password"]);
		mysql_select_db("lessons", $db);

		if($_POST['action'] == "assign") {
			// Add to database
			$result = mysql_query("insert into lessons.topic_assignments (lid, tid) values('".mysql_real_escape_string($_POST['id'])."', '".mysql_real_escape_string($_POST['topic_id'])."')", $db);
		} else if($_POST['action'] == "remove") {
			// Remove from database
			$result = mysql_query("delete from lessons.topic_assignments where lid='".mysql_real_escape_string($_POST['id'])."' and tid='".mysql_real_escape_string($_POST['topic_id'])."' limit 1", $db);
		}
	}
