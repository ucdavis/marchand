<?php
	require_once '/html/connect.inc';

	if(isset($_POST['id'])) {
		// Add to database
		$db = mysql_connect("localhost", $connect["username"], $connect["password"]);
		mysql_select_db("image_archive", $db);

		$result = mysql_query("delete from topic_assignments where sid='".mysql_real_escape_string($_POST['id'])."' and tid='".mysql_real_escape_string($_POST['topic_id'])."' limit 1", $db);
	}
