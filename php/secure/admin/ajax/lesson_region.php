<?php
	require_once '/html/connect.inc/';

	if(isset($_POST['id']) && isset($_POST['action'])) {
		$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
		mysqli_select_db($db, "lessons");

		if($_POST['action'] == "assign") {
			// Add to database
			$result = mysqli_query($db, "insert into lessons.region_assignments (lid, rid) values('".mysqli_real_escape_string($db, $_POST['id'])."', '".mysqli_real_escape_string($db, $_POST['region_id'])."')");
		} else if($_POST['action'] == "remove") {
			// Remove from database
			$result = mysqli_query($db, "delete from lessons.region_assignments where lid='".mysqli_real_escape_string($db, $_POST['id'])."' and rid='".mysqli_real_escape_string($db,$_POST['region_id'])."' limit 1");
		}
	}
