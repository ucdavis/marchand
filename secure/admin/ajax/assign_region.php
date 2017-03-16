<?php
	require_once '/html/connect.inc';

	if(isset($_POST['id'])) {
		// Add to database
		$db = mysql_connect("localhost", $connect["username"], $connect["password"]);
		mysql_select_db("image_archive", $db);

		$result = mysql_query("insert into region_assignments (sid, rid) values('".mysql_real_escape_string($_POST['id'])."', '".mysql_real_escape_string($_POST['region_id'])."')", $db);
	}
