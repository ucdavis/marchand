<?php
	// most functions here expect mysql to already be set up

	define("GRADE_UNIVERSITY", 3);
	define("GRADE_HIGH_SCHOOL", 2);
	define("GRADE_MIDDLE_SCHOOL", 1);
	define("GRADE_ELEMENTARY", 0);

	// returns all lessons
	function fetch_lessons($topic_id = -1, $grade_id = -1, $standard_ca_id = -1, $standard_nat_id = -1) {
		$select_args = array();
		$where_args = array();
		$table_args = array();

		mysql_select_db("lessons");
		mysql_query("SET NAMES 'utf8'");

		// Searching for a specific topic?
		if($topic_id != -1) {
			$select_args[] = "image_archive.topics.title as topic";
			$where_args[] = "topic_assignments.lid = lessons.id";
			$where_args[] = "topic_assignments.tid = image_archive.topics.id";
			$where_args[] = "topic_assignments.tid = ".mysql_real_escape_string($topic_id);
			$table_args[] = "image_archive.topics";
			$table_args[] = "topic_assignments";
		}
		// Searching for a specific grade level?
		if($grade_id != -1) {
			switch($grade_id) {
				case GRADE_UNIVERSITY:
					$where_args[] = "lessons.is_university = 1";
				break;
				case GRADE_HIGH_SCHOOL:
					$where_args[] = "lessons.is_high_school = 1";
				break;
				case GRADE_MIDDLE_SCHOOL:
					$where_args[] = "lessons.is_middle_school = 1";
				break;
				case GRADE_ELEMENTARY:
					$where_args[] = "lessons.is_elementary = 1";
				break;
				default:
				break;
			}
		}
		// Searching for a california standard?
		if($standard_ca_id != -1) {
			$where_args[] = "standards_data.sid = ".mysql_real_escape_string($standard_ca_id);
			$where_args[] = "standards_data.stype = 0";
			$where_args[] = "standards_data.lesson_id = lessons.id";
			$table_args[] = "standards_data";
		}
		// Searching for a national standard?
		if($standard_nat_id != -1) {
			$where_args[] = "standards_data.sid = ".mysql_real_escape_string($standard_nat_id);
			$where_args[] = "standards_data.stype = 1";
			$where_args[] = "standards_data.lesson_id = lessons.id";
			$table_args[] = "standards_data";
		}

		// Ensure tables weren't selected more than once
		$table_args = array_unique($table_args);

		// Build the needed strings
		$select_str = "";
		if(count($select_args)) {
			foreach($select_args as $arg) {
				$select_str .= ", ".$arg;
			}
		}
		
		$where_str = "";
		if(count($where_args)) {
			$where_str = "where ";
			$started = false;
			foreach($where_args as $arg) {
				if($started) $where_str .= " and ";
				$where_str .= $arg;
				$started = true;
			}
		}

		$table_str = "";
		if(count($table_args)) {
			$table_str = ", ";
			$started = false;
			foreach($table_args as $arg) {
				if($started) $table_str .= ", ";
				$table_str .= $arg;
				$started = true;
			}
		}
		
		$query = "select lessons.id as id, lessons.title as title, lessons.creator as creator, is_university, is_middle_school, is_high_school, is_elementary $select_str from lessons $table_str $where_str order by lessons.title asc";
		
		//echo "query is $query<br />";
		
		$result = mysql_query($query);
		
		$lessons = array();
		
		while($row = mysql_fetch_assoc($result)) {
			if(trim($row['title']) == "") $row['title'] = "No Title Available";
			if(trim($row['creator']) == "") $row['creator'] = "Unknown";

			// add some attributes to make the rest of the code nicer
			$row['url'] = "/lessons/view_lesson.php?id=".$row['id'];
		
			$lessons[] = $row;
		}
		
		return $lessons;
	}
	
	// Fetches all topics for a specific lesson
	function fetch_lesson_topics($lesson_id) {
		mysql_select_db("lessons");
		mysql_query("SET NAMES 'utf8'");
		
		$topics = array();
	
		$result = mysql_query("select topic_assignments.tid as tid, image_archive.topics.title as title from image_archive.topics, topic_assignments where topic_assignments.lid='".mysql_real_escape_string($lesson_id)."' and topic_assignments.tid = topics.id");
		
		while($row = mysql_fetch_assoc($result)) $topics[] = $row;
		
		return $topics;
	}
	
	// Fetches only topics used by any lesson
	function fetch_lesson_unique_topics($db) {
		mysql_select_db("lessons", $db);
		
		$result = mysql_query("select distinct topics.id as id, topics.title as title from image_archive.topics, topic_assignments where topic_assignments.tid=image_archive.topics.id order by title asc", $db);
		
		$topics = array();
		while($topic = mysql_fetch_assoc($result)) {
			$topics[] = $topic;
		}
		
		return $topics;
	}
	
	function fetch_lesson($id) {
		mysql_select_db("lessons");
			
		$result = mysql_query("select * from lessons where id='".mysql_real_escape_string($id)."'");
		
		$lesson = mysql_fetch_assoc($result);

		if(trim($lesson['title']) == "") $lesson['title'] = "No Title Available";
		if(trim($lesson['creator']) == "") $lesson['creator'] = "Unknown";

		// add some attributes to make the rest of the code nicer
		$lesson['url'] = "/lessons/view_lesson.php?id=".$lesson['id'];
		
		return $lesson;
	}
