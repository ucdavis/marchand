<?php
	require_once '/html/connect.inc';

	if(isset($_POST['id']) && isset($_POST['stype'])) {
		// Add to database
		$db = mysql_connect("localhost", $connect["username"], $connect["password"]);
		mysql_select_db("image_archive", $db);

		$result = mysql_query("insert into standards_data (image_id, sid, stype) values('".mysql_real_escape_string($_POST['id'])."', '".mysql_real_escape_string($_POST['standard_id'])."', '".mysql_real_escape_string($_POST['stype'])."')", $db);
	}
