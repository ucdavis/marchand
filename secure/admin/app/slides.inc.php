<?php
	// Slide editing functions

	function standardinfo($db, $sid) {
		$result_standards = mysqli_query($db,"select * from standards_cal where id = '$sid' limit 1");
		while ($myrow_standards = mysqli_fetch_row($result_standards)) {
			$new = $myrow_standards[1]."|".$myrow_standards[2]."|".$myrow_standards[3]."|".$myrow_standards[4];
		}
		return $new;
	}

	// Get the Major Title based on ID
	function majortitle($db, $major_id) {
		$result = mysqli_query($db,"select title, code from topics where id = '$major_id'");
		while($myrow = mysqli_fetch_row($result)) {
			$new = $myrow[0]."|".$myrow[1];
			return $new;
		}
	}

	// Get the Minor Title based on ID
	function minortitle($db, $minor_id) {
		$result = mysqli_query($db,"select title, code from subtopics where id = '$minor_id'");
		while($myrow = mysqli_fetch_assoc($result)) {
			$new = $myrow['title']."|".$myrow['code'];
			return $new;
		}
	}
