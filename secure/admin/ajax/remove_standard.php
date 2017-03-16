<?php
	require_once '/html/connect.inc';

	if(isset($_POST['id']) && isset($_POST['stype'])) {
		// Add to database
		$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
		mysqli_select_db($db,"image_archive");

		$query = "delete from standards_data where image_id='".mysqli_real_escape_string($db,$_POST['id'])."' and id='".mysqli_real_escape_string($db,$_POST['standard_id'])."' and stype='".mysqli_real_escape_string($db,$_POST['stype'])."' limit 1";
		$result = mysqli_query($db, $query);
	}
