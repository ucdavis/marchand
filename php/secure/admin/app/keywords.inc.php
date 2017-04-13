<?php
	// parses a string ("people, gifts, love") and returns a keyword array (e.g. arr[0] => "people", arr[1] => "gifts")
	function split_keyword_string($list) {
		$list = trim($list);

		if(strlen($list) == 0) return array();

		$keywords = split(",", $list);

		for($i = 0; $i < count($keywords); $i++) {
			$keywords[$i] = trim($keywords[$i]);
		}

		return $keywords;
	}

	// $sid = slide ID or image id. fetches all keywords for a given $sid. expects database to already be connected
	function fetch_keywords_by_sid($db, $sid) {
		mysqli_select_db($db, "image_archive");

		$result = mysqli_query($db,"select kid from keyword_assignments where sid='".mysqli_real_escape_string($db,$sid)."'");

		$keywords = array();

		while($row = mysqli_fetch_assoc($result)) {
			$kresult = mysqli_query($db,"select title from keywords where id='".mysqli_real_escape_string($db,$row['kid'])."'");

			$krow = mysqli_fetch_assoc($kresult);
			$keywords[] = $krow['title'];
		}

		return $keywords;
	}

	// $lid = lesson ID. fetches all keywords for a given $lid. expects database to already be connected
	function fetch_keywords_by_lid($db, $lid) {
		mysqli_select_db($db, "lessons");

		$result = mysqli_query($db,"select lid from keyword_assignments where lid='".mysqli_real_escape_string($db,$lid)."'");

		$keywords = array();

		while($row = mysqli_fetch_assoc($result)) {
			$kresult = mysqli_query($db,"select title from keywords where id='".mysqli_real_escape_string($db,$row['kid'])."'");

			$krow = mysqli_fetch_assoc($kresult);
			$keywords[] = $krow['title'];
		}

		return $keywords;
	}
