<?php

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/html/secure.inc.php');

function fetch_standards_ca($db, $id = -1) {
	$standards = array();

	if($id == -1) {
		$result = mysqli_query($db, "select id, grade_id, standard_id, description from standards_cal order by grade_id, standard_id");

		while($standard = mysqli_fetch_assoc($result)) {
			$standard['short_desc'] = substr($standard['description'], 0, 18);
			if($standard['grade_id'] == "0") $standard['grade_id'] = "K";
			$standard['label'] = $standard['grade_id'].'.'.$standard['standard_id']." - ".$standard['short_desc']."...";

			$standards[] = $standard;
		}
	} else {
		$result = mysqli_query($db, "select standards_data.id as id, standards_cal.grade_id as grade_id, standards_cal.standard_id as standard_id, standards_cal.description as description from standards_cal, standards_data where standards_cal.id = standards_data.sid and standards_data.stype = 0 and standards_data.image_id = '".mysqli_real_escape_string($db, $id)."'");

		while($standard = mysqli_fetch_assoc($result)) {
			$standard['label'] = $standard['grade_id'].'.'.$standard['standard_id'].' - '.$standard['description'];

			$standards[] = $standard;
		}
	}

	return $standards;
}

// if $id is passed, we look up the standards of that particular image id, else we return all national standards
function fetch_standards_nat($db, $id = -1) {
	$standards = array();

	if($id == -1) {
		$result = mysqli_query($db, "select id, era, us_world, title from standards_nat order by us_world, era asc");

		while($standard = mysqli_fetch_assoc($result)) {
			if($standard['us_world'] == 0) $label = "US"; else $label = "World";
			$standard['label'] = $label.' Era '.$standard['era'].' - '.$standard['title'];

			$standards[] = $standard;
		}
	} else {
		$result = mysqli_query($db, "select standards_data.id as id, standards_nat.era as era, standards_nat.us_world as us_world, standards_nat.title as title from standards_nat, standards_data where standards_nat.id = standards_data.sid and standards_data.stype = 1 and standards_data.image_id = '".mysqli_real_escape_string($db, $id)."'");

		while($standard = mysqli_fetch_assoc($result)) {
			if($standard['us_world'] == 0) $label = "US"; else $label = "World";
			$standard['label'] = $label.' Era '.$standard['era'].' - '.$standard['title'];

			$standards[] = $standard;
		}
	}

	return $standards;
}

// Retrieve information about a single slide
function fetch_image($db, $id) {
	$result = mysqli_query($db, "select images.id, images.file, images.citation, images.thumbnail, images.title, images.card, collections.name as collection

	from images, collections

	where images.id = '".mysqli_real_escape_string($db, (int)$id)."'
	and images.collection = collections.id

	limit 1");
	if($result == false) {
		echo "There was a database error:".mysqli_error($db)."<br />";
		return null;
	}

	$image = mysqli_fetch_assoc($result);

	return $image;
}

function fetch_image_topics($db, $id) {
	$result = mysqli_query($db, "select topics.id as tid, topics.title as title

	from topics, topic_assignments

	where topic_assignments.sid = '".mysqli_real_escape_string($db, (int)$id)."'
	and topic_assignments.tid = topics.id");

	if($result == false) {
		echo "There was a database error:".mysqli_error($db)."<br />";
		return null;
	}

	$topics = array();

	while($row = mysqli_fetch_assoc($result)) $topics[] = $row;

	return $topics;
}

function fetch_image_regions($db, $id) {
	$result = mysqli_query($db, "select regions.id as rid, regions.title as title

	from regions, region_assignments

	where region_assignments.sid = '".mysqli_real_escape_string($db, (int)$id)."'
	and region_assignments.rid = regions.id");

	if($result == false) {
		echo "There was a database error:".mysqli_error($db)."<br />";
		return null;
	}

	$regions = array();

	while($row = mysqli_fetch_assoc($result)) $regions[] = $row;

	return $regions;
}

function minor_title($db, $mid) {
	$result = mysqli_query($db, "select id, title, code from subtopics where title = '$mid'", $db);
	while ($myrow = mysqli_fetch_assoc($result)) {
		return $myrow['id'] . "|" . $myrow['title'] . "|" . $myrow['code'];
	}
}

function major_title($db, $mid) {
	$result = mysqli_query($db, "select id, title, code from topics where title = '$mid'", $db);
	while ($myrow = mysqli_fetch_assoc($result)) {
		return $myrow['id'] . "|" . $myrow['title'] . "|" . $myrow['code'];
	}
}

function major_info_by_code($db, $mid) {
	$result = mysqli_query($db, "select id, title, code from topics where id = '$mid'", $db);
	while($myrow = mysqli_fetch_assoc($result)) {
		return $myrow['id']."|".$myrow['title']."|".$myrow['code'];
	}
}

function minor_info_by_code($db, $mid) {
	$result = mysqli_query($db, "select id, title, code from subtopics where id = '$mid'", $db);
	while ($myrow = mysqli_fetch_assoc($result)) {
		return $myrow['id'] . "|" . $myrow['title'] . "|" . $myrow['code'];
	}
}

function collection_title($db,$cid) {
	$result = mysqli_query($db, "select id, name from collections where code = '$cid'", $db);
	while($myrow = mysqli_fetch_assoc($result)) {
		return $myrow['id']."|".$myrow['name'];
	}
}

function collection_info($db, $cid) {
	$result = mysqli_query($db, "select * from collections where code = '$cid'", $db);
	while ($myrow = mysqli_fetch_assoc($result)) {
		return $myrow['id']."|".$myrow['name']."|".$myrow['code'];
	}
}

// Get the major and minor for an image ID
function major_minor_by_id($db,$pid) {
	$result = mysqli_query($db, "select topic, subtopic from topic_assignment where pid = '$pid' limit 1", $db);
	while ($myrow = mysqli_fetch_row($result)) {
		list($mid, $major_title) = split("\|", major_info_by_code($db, $myrow[0]));
		list($mid, $minor_title) = split("\|", minor_info_by_code($db, $myrow[1]));
		return $major_title . "|" . $minor_title;
	}
}

// Print a thumbnails
function thumbnail($url, $img, $text, $cit, $major_code = "", $minor_code = "") {
	global $uid, $sid, $redirect, $old_num, $editor;

	$redirect = str_replace("\'", "&#39;", $redirect);

	if(is_admin($uid)) {
		print "<tr><td><a name=\"$sid\"></a></td></tr>";
	}
	print "<tr valign=\"top\" bgcolor=\"#eeeeee\"><td><center>";
	print "<div class=\"wrap1\"><div class=\"wrap2\"><div class=\"wrap3\">";
	print "<a href=\"$url\"><img $s src=\"$img\"></a>";
	print "</div></div></div>";
	print "</center></td>";

	if(!$old_num) $old_num = substr(substr($img, -9), 0, 5);

	if($editor) {
		print "<td><a href=\"/secure/admin/edit_slides.php?id=$sid&redirect=$redirect\">edit<br />$major_code-$minor_code-$old_num</a></td>";
	}
	print "<td bgcolor=\"#ddd\">$text<p><i>$cit</i></p></td></tr>";
}

function search($db, $search_words, $standard) {
	$standard = rtrim($standard, "/");
	print "Searching for $search_words in <b>$standard</b> standard<P>";

	// If a standard is needed, find the ID of it, put in $sid
	if($standard != 'any') {
		list($s_id,$sub_s)=split("_",$standard);
		$result = mysqli_query($db, "SELECT ID FROM Standards_Cal WHERE StandardID = '$s_id' AND SubStandardNum = '$sub_s' LIMIT 1",$db);
		while ($myrow = mysqli_fetch_row($result)) { $sid=$myrow[0]; }
		}

	print "<table width=\"100%\">";

	## Find matching words from image text
	$result = mysqli_query($db, "SELECT * FROM images WHERE CardText LIKE '%$search_words%' AND Current = '1'",$db);
	while ($myrow = mysqli_fetch_row($result)) {

		## Get the standard for this one, if not found, don't show the image
		$s="";
		if($standard != 'any') { $result_s = mysqli_query($db, "SELECT * FROM Standards_Data WHERE ImgID ='$myrow[0]' && SID = '$sid'",$db); }
		else { $result_s = mysqli_query($db, "SELECT * FROM Standards_Data WHERE ImgID ='$myrow[0]'",$db); }
		while ($myrow_s = mysqli_fetch_row($result_s)) { $s="$myrow_s[3]"; }

		if($s) {
			list($major_title,$minor_title)=split("\|",major_minor_by_id($db,$myrow[0]));
			$minor_title_url=str_replace(" ","_", $minor_title);
			$major_title_url=str_replace(" ","_", $major_title);

			## Get the collection
			$result_collection = mysqli_query($db, "SELECT Code FROM Collections WHERE ID = '$myrow[5]'",$db);
			while ($myrow_collection = mysqli_fetch_row($result_collection)) { $c=$myrow_collection[0]; }

			$short_desc=$myrow[3];
			if($myrow[2] != 'IMG0000.jpg') {
				$url="/ic/collection/$c/$major_title_url/$minor_title_url/$myrow[0].html";
				$img="/marchandslides.bak/$myrow[1]/thumbnails/$myrow[2]";
				thumbnail($url,$img,$myrow[3],$myrow[4]);
				}
			}
		}
	print "</table>";
}

function standard_count($db,$what) {
	global $edit;

	$result = mysqli_query($db, "select * from standards_cal group by grade_id", $db);

	while($myrow = mysqli_fetch_row($result)) {
		$subs = "";

		// Then get all the sub standards
		$result_sub = mysqli_query($db, "SELECT * FROM Standards_Cal WHERE GradeID='$myrow[1]'",$db);
		while ($myrow_sub = mysqli_fetch_row($result_sub)) {
			$subs=$subs . " OR SID = '$myrow_sub[0]'";

			// Get the count for this sub_standard
			$result_sub_s = mysqli_query($db, "SELECT COUNT(*) FROM Standards_Data, Images WHERE Images.ImageId = Standards_Data.ImgID AND (Current = '1' $edit) AND SID = '$myrow_sub[0]'", $db);
			while($myrow_sub_s = mysqli_fetch_row($result_sub_s)) {
				$sub_standard_count[$myrow_sub[0]] = $myrow_sub_s[0];
				$total = $total + $myrow_sub_s[0];
			}
		}

		$standard_count[$myrow[1]] = $total;
		$total = 0;
	}

	if($what == 'standard')
		return $standard_count;
	else
		return $sub_standard_count;
}

function fetch_regions($db) {
	mysqli_select_db($db, "image_archive");

	$result = mysqli_query($db, "select id, title from regions order by title asc");

	$regions = array();
	while($region = mysqli_fetch_assoc($result)) {
		$regions[] = $region;
	}

	return $regions;
}

function fetch_topics($db) {
	mysqli_select_db($db, "image_archive");

	$result = mysqli_query($db, "select id, title from topics order by title asc");

	$topics = array();
	while($topic = mysqli_fetch_assoc($result)) {
		$topics[] = $topic;
	}

	return $topics;
}

function fetch_standards($db) {
	return null;
}

function fetch_collections($db) {
	mysqli_select_db($db, "image_archive");

	$result = mysqli_query($db, "select id, name from collections order by name asc");

	$collections = array();
	while($collection = mysqli_fetch_assoc($result)) {
		$collections[] = $collection;
	}

	return $collections;
}

if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}
