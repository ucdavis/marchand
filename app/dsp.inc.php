<?php
	// if $id is passed, we look up the standards of that particular lesson id, else we return all national standards
	function dsp_fetch_standards_nat($db, $id = -1) {
		$standards = array();

		if($id == -1) {
			$result = mysqli_query($db, "select id, era, us_world, title from image_archive.standards_nat order by us_world, era asc");

			while($standard = mysqli_fetch_assoc($result)) {
				if($standard['us_world'] == 0) $label = "US"; else $label = "World";
				$standard['label'] = $label.' Era '.$standard['era'].' - '.$standard['title'];

				$standards[] = $standard;
			}
		} else {
			$result = mysqli_query($db, "select lessons.standards_data.id as id, image_archive.standards_nat.era as era, image_archive.standards_nat.us_world as us_world, image_archive.standards_nat.title as title from image_archive.standards_nat, lessons.standards_data where image_archive.standards_nat.id = lessons.standards_data.sid and lessons.standards_data.stype = 1 and lessons.standards_data.lesson_id = '".mysqli_real_escape_string($db, $id)."'");

			while($standard = mysqli_fetch_assoc($result)) {
				if($standard['us_world'] == 0) $label = "US"; else $label = "World";
				$standard['label'] = $label.' Era '.$standard['era'].' - '.$standard['title'];

				$standards[] = $standard;
			}
		}

		return $standards;
	}
