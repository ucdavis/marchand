<?php
	require_once '/html/connect.inc/';

	if(isset($_POST['id']) && isset($_POST['action'])) {
		$db = mysql_connect("localhost", $connect["username"], $connect["password"]);
		mysql_select_db("lessons", $db);

		if($_POST['action'] == "assign") {
			// Add to database
			$result = mysql_query("insert into lessons.region_assignments (lid, rid) values('".mysql_real_escape_string($_POST['id'])."', '".mysql_real_escape_string($_POST['region_id'])."')", $db);
		} else if($_POST['action'] == "remove") {
			// Remove from database
			$result = mysql_query("delete from lessons.region_assignments where lid='".mysql_real_escape_string($_POST['id'])."' and rid='".mysql_real_escape_string($_POST['region_id'])."' limit 1", $db);
		}
	}
