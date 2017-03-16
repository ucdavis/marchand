<?php
	require_once '/html/connect.inc';

	if(isset($_POST['id']) && isset($_POST['stype'])) {
		// Add to database
		$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
		mysqli_select_db($db,"image_archive");

		$result = mysqli_query($db,"insert into standards_data (image_id, sid, stype) values('".mysqli_real_escape_string($db,$_POST['id'])."', '".mysqli_real_escape_string($db,$_POST['standard_id'])."', '".mysqli_real_escape_string($db,$_POST['stype'])."')");
	}
