<?php
	require_once '/html/connect.inc';

	if(isset($_POST['id'])) {
		// Remove from database
		$db = mysql_connect("localhost", $connect["username"], $connect["password"]);
		mysql_select_db("image_archive", $db);

		$result = mysql_query("delete from region_assignments where sid='".mysql_real_escape_string($_POST['id'])."' and rid='".mysql_real_escape_string($_POST['region_id'])."' limit 1", $db);
	}
