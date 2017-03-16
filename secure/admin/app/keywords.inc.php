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
	function fetch_keywords_by_sid($sid) {
		mysql_select_db("image_archive");
	
		$result = mysql_query("select kid from keyword_assignments where sid='".mysql_real_escape_string($sid)."'");
		
		$keywords = array();
		
		while($row = mysql_fetch_assoc($result)) {
			$kresult = mysql_query("select title from keywords where id='".mysql_real_escape_string($row['kid'])."'");
			
			$krow = mysql_fetch_assoc($kresult);
			$keywords[] = $krow['title'];
		}
		
		return $keywords;
	}
	
	// $lid = lesson ID. fetches all keywords for a given $lid. expects database to already be connected
	function fetch_keywords_by_lid($lid) {
		mysql_select_db("lessons");
	
		$result = mysql_query("select lid from keyword_assignments where lid='".mysql_real_escape_string($lid)."'");
		
		$keywords = array();
		
		while($row = mysql_fetch_assoc($result)) {
			$kresult = mysql_query("select title from keywords where id='".mysql_real_escape_string($row['kid'])."'");
			
			$krow = mysql_fetch_assoc($kresult);
			$keywords[] = $krow['title'];
		}
		
		return $keywords;
	}