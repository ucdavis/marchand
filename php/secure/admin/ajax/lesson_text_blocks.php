<?php
	require_once '/html/connect.inc';

	if(isset($_POST['lid']) && isset($_POST['action'])) {
		$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
		mysqli_select_db($db, "lessons");

		if($_POST['action'] == "delete") {
			// Delete text block from database, reordering in the process
			$result = mysqli_query($db, "delete from text_blocks where text_block_num='".mysqli_real_escape_string($db,$_POST['tbid'])."' and lesson_id='".mysqli_real_escape_string($db,$_POST['lid'])."' limit 1");

			// Now reorder the database
			$result = mysqli_query($db,"update text_blocks set text_block_num = text_block_num - 1 where text_block_num > '".mysqli_real_escape_string($db,$_POST['tbid'])."' and lesson_id='".mysqli_real_escape_string($db,$_POST['lid'])."'");
		}
	}
