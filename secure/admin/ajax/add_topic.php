<?php
	require_once '/html/connect.inc';

	if(isset($_POST['title'])) {
		// Add to database
		$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
		mysqli_select_db($db,"image_archive");

		$query = "insert into topics (title, code, collection) values('".mysqli_real_escape_string($db,$_POST['title'])."', 'NA', 'US')";

		$result = mysqli_query($db,$query);

		echo mysqli_insert_id($db);
	}
