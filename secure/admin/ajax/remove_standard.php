<?php
	require_once '/html/connect.inc';

	if(isset($_POST['id']) && isset($_POST['stype'])) {
		// Add to database
		$db = mysql_connect("localhost", $connect["username"], $connect["password"]);
		mysql_select_db("image_archive", $db);

		$query = "delete from standards_data where image_id='".mysql_real_escape_string($_POST['id'])."' and id='".mysql_real_escape_string($_POST['standard_id'])."' and stype='".mysql_real_escape_string($_POST['stype'])."' limit 1";
		$result = mysql_query($query, $db);
	}
