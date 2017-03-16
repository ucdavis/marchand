<?php
	require_once '/html/connect.inc';

	if(isset($_POST['id'])) {
		// Add to database
		$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
		mysqli_select_db($db,"image_archive");

		$result = mysqli_query($db, "insert into topic_assignments (sid, tid) values('".mysqli_real_escape_string($db, $_POST['id'])."', '".mysqli_real_escape_string($db, $_POST['topic_id'])."')");
	}
