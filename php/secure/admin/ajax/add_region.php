<?php
	require_once '/html/connect.inc';

	if(isset($_POST['title'])) {
		// Add to database
		$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
		mysqli_select_db($db,"image_archive");

		$result = mysqli_query($db,"insert into regions (title) values('".mysqli_real_escape_string($db,$_POST['title'])."')");

		echo mysqli_insert_id($db);
	}
