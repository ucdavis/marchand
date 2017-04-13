<?php
	include '../app/ic.inc.php';

	$db = mysqli_connect("localhost", "hc", "admin");
	mysqli_select_db($db, "image_archive");

	die;

	// query for all the topic_assignments
	$result = mysqli_query($db, "select * from standards_cal");
	if($result == false) {
		echo "There was an error in the query: ".mysqli_error($db)."<br />";
		die;
	}

	echo "All selected.<br /><br />";

	$heads = array();

	// find the heads!
	while($row = mysqli_fetch_assoc($result)) {
		$id = $row['id'];
		$grade_id = $row['grade_id'];
		$standard_id = $row['standard_id'];
		$sub_standard_num = $row['sub_standard_num'];

		if((int)$sub_standard_num == 1) $heads[$grade_id][$standard_id] = $id;
	}

	// rewind!
	$ret = mysqli_data_seek($result, 0);
	assert($ret);

	// re-associate any non-standard heads
	while($row = mysqli_fetch_assoc($result)) {
		$id = $row['id'];
		$grade_id = $row['grade_id'];
		$standard_id = $row['standard_id'];
		$sub_standard_num = $row['sub_standard_num'];

		if((int)$sub_standard_num != 1) {
			// find all rows in standards_data that uses this id and change it to the head id
			$result2 = mysqli_query($db, "select id from standards_data where sid='".mysqli_real_escape_string($db, $id)."'");
			while($row2 = mysqli_fetch_assoc($result2)) {
				// update each row
				$result3 = mysqli_query($db, "update standards_data set sid='".mysqli_real_escape_string($db, $heads[$grade_id][$standard_id])."' where id='".mysqli_real_escape_string($db, $row2['id'])."'");
				if($result3 == false) {
					echo "Error in update query!<br />";
				}
			}
		}
	}

	echo "<br /><br />Done!<br />";

	/*
		process:

		--select all standards_cal
		--determine which is "head" standard for a given standard, e.g. 4.1.2's head is 4.1.1
		--for each non-head standard, find out if it's used in standards_data. if it is, alter standards_data to use the head
		delete all non-head standards
		rename the head to 4.1 and ensure the text is correct
		hope it works

	*/
