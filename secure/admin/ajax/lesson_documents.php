<?php
	require_once '/html/connect.inc';

	if(isset($_POST['lid']) && isset($_POST['action'])) {
		$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
		mysqli_select_db($db, "lessons");

		if($_POST['action'] == "delete") {
			// Delete text block from database, reordering in the process
			$result = mysqli_query($db, "delete from documents where document_num='".mysqli_real_escape_string($db, $_POST['did'])."' and lesson_id='".mysqli_real_escape_string($db, $_POST['lid'])."' limit 1");

			// Now reorder the database
			$result = mysqli_query($db, "update documents set document_num = document_num - 1 where document_num > '".mysqli_real_escape_string($db, $_POST['did'])."' and lesson_id='".mysqli_real_escape_string($db, $_POST['lid'])."'");
		}
	}
