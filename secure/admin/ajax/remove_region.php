<?php
	require_once '/html/connect.inc';

	if(isset($_POST['id'])) {
		// Remove from database
		$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
		mysqli_select_db($db,"image_archive");

		$result = mysqli_query($db,"delete from region_assignments where sid='".mysqli_real_escape_string($db,$_POST['id'])."' and rid='".mysqli_real_escape_string($db,$_POST['region_id'])."' limit 1");
	}
