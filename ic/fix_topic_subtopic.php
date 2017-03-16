<?php
	include '../app/ic.inc.php';
	
	$db = mysql_connect("localhost", "hc", "admin");
	mysql_select_db("image_archive", $db);
	
	die;
	
	// query for all the topic_assignments
	$result = mysql_query("select * from standards_cal", $db);
	if($result == false) {
		echo "There was an error in the query: ".mysql_error()."<br />";
		die;
	}
	
	echo "All selected.<br /><br />";
	
	$heads = array();
	
	// find the heads!
	while($row = mysql_fetch_assoc($result)) {
		$id = $row['id'];
		$grade_id = $row['grade_id'];
		$standard_id = $row['standard_id'];
		$sub_standard_num = $row['sub_standard_num'];
		
		if((int)$sub_standard_num == 1) $heads[$grade_id][$standard_id] = $id;
	}
	
	// rewind!
	$ret = mysql_data_seek($result, 0);
	assert($ret);
	
	// re-associate any non-standard heads
	while($row = mysql_fetch_assoc($result)) {
		$id = $row['id'];
		$grade_id = $row['grade_id'];
		$standard_id = $row['standard_id'];
		$sub_standard_num = $row['sub_standard_num'];
		
		if((int)$sub_standard_num != 1) {
			// find all rows in standards_data that uses this id and change it to the head id
			$result2 = mysql_query("select id from standards_data where sid='".mysql_real_escape_string($id)."'", $db);
			while($row2 = mysql_fetch_assoc($result2)) {
				// update each row
				$result3 = mysql_query("update standards_data set sid='".mysql_real_escape_string($heads[$grade_id][$standard_id])."' where id='".mysql_real_escape_string($row2['id'])."'", $db);
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