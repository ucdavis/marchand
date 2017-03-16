<?php
	require_once '/html/connect.inc';

	if(isset($_POST['title'])) {
		// Add to database
		$db = mysql_connect("localhost", $connect["username"], $connect["password"]);
		mysql_select_db("image_archive", $db);

		$query = "insert into topics (title, code, collection) values('".mysql_real_escape_string($_POST['title'])."', 'NA', 'US')";

		$result = mysql_query($query, $db);

		echo mysql_insert_id($db);
	}
