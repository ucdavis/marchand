<?php
	require_once '/html/connect.inc';

	if(isset($_POST['id'])) {
		// Add to database
		$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
		mysqli_select_db($db, "image_archive");

		$result = mysqli_query($db, "delete from topic_assignments where sid='".mysqli_real_escape_string($db,$_POST['id'])."' and tid='".mysqli_real_escape_string($db,$_POST['topic_id'])."' limit 1");
	}
