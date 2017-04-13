<?php
	require_once '/html/connect.inc';

	if(isset($_POST['id']) && isset($_POST['action']) && isset($_POST['stype'])) {
		$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
		mysqli_select_db($db, "lessons");

		if($_POST['action'] == "assign") {
			// Add to database
			$result = mysqli_query($db, "insert into standards_data (lesson_id, sid, stype) values('".mysqli_real_escape_string($db, $_POST['id'])."', '".mysqli_real_escape_string($db, $_POST['standard_id'])."', '".mysqli_real_escape_string($db,$_POST['stype'])."')");
		} else if($_POST['action'] == "remove") {
			// Remove from database
			$query = "delete from standards_data where lesson_id='".mysqli_real_escape_string($db,$_POST['id'])."' and id='".mysqli_real_escape_string($db,$_POST['standard_id'])."' and stype='".mysqli_real_escape_string($db,$_POST['stype'])."' limit 1";
			$result = mysqli_query($db,$query);
		}
	}
