<?php
	define('__ROOT__', dirname(dirname(dirname(__FILE__))));
	include (__ROOT__.'/app/ic.inc.php');
	require_once(__ROOT__.'/html/secure.inc.php');
	require_once(__ROOT__.'/html/connect.inc');

	//include_once('CAS/CAS.php');

	//phpCAS::client(CAS_VERSION_2_0, "cas.ucdavis.edu", 443, "cas");
	//phpCAS::setCasServerCACert("/etc/pki/tls/cert.pem");
	//$cas_in = phpCAS::checkAuthentication(); // gateway / passive

	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Tue, 27 Apr 2100 08:00:00 GMT');
	header('Content-type: application/json');

	session_start();

	$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
	mysqli_select_db($db, "image_archive");

	$and = false; // flag used to indicate if we need to specify an AND in the query string

	// Anybody logged in?
	if(isset($_SESSION['uid']))
		$uid = $_SESSION['uid'];
	else
		$uid = false;

	if(is_admin($uid)) {
		$editor = 1;
	} else {
		$editor = 0;
	}

	// Build the query string
	if($_POST['collection'] != -1) {
		$collection_str = "images.collection='".mysqli_real_escape_string($db, (int)$_POST['collection'])."'";
		$and = true;
	} else {
		$collection_str = "";
	}
	$region_str = "";
	if($_POST['region'] != -1) {
		if($and) {
			$region_str = "and ";
		}
		$region_str .= "region_assignments.rid='".mysqli_real_escape_string($db, (int)$_POST['region'])."' and region_assignments.sid = images.id";
		$region_table_str = ", region_assignments";
		$and = true;
	} else {
		$region_str = "";
		$region_table_str = "";
	}
	$topic_str = "";
	if($_POST['topic'] != -1) {
		if($and) {
			$topic_str = "and ";
		}
		$topic_str .= "topic_assignments.sid = images.id and topic_assignments.tid='".mysqli_real_escape_string($db, (int)$_POST['topic'])."'";
		$topic_table_str = ", topic_assignments";
		$and = true;
	} else {
		$topic_str = "";
		$topic_table_str = "";
	}
	$standard_cal_str = "";
	if($_POST['standard_cal'] != -1) {
		if($and) {
			$standard_cal_str = "and ";
		}
		$standard_cal_str .= "standards_data.image_id = images.id and standards_data.sid = '".mysqli_real_escape_string($db,$_POST['standard_cal'])."'";
		$and = true;
	} else {
		$standard_cal_str = "";
	}

	if($_POST['standard_cal'] != -1) {
		$standard_table_str = ", standards_data";
	} else {
		$standard_table_str = "";
	}

	$query_term_str = "";
	if(strlen($_POST['query']) > 0) {
		if($and) {
			$query_term_str = "and ";
		}
		$query_term_str .= "match(title, card, citation, notes) against ('".mysqli_real_escape_string($db, $_POST['query'])."')";
		$and = true;
	} else {
		$query_term_str = "";
	}

	$data = array();
	$standard_str = "";

	if((strlen($collection_str) == 0) && (strlen($region_str) == 0) && (strlen($standard_str) == 0) && (strlen($topic_str) == 0) && (strlen($standard_cal_str) == 0) && (strlen($query_term_str) == 0))
		$where_str = "where id = -1"; // abort, there should be nothing
	else {
		if($editor) {
			$where_str = "where ";
		} else {
			$where_str = "where public = 1 and ";
		}
	}

	$start = $_POST['index'];
	$limit = $_POST['per_page'];

	// Query for the images
	if(!isset($standard_str)) $standard_str = "";
	$query = "select images.id, images.thumbnail, images.title, images.card, images.citation from images $standard_table_str $topic_table_str $region_table_str $where_str $collection_str $region_str $standard_str $topic_str $standard_cal_str $query_term_str limit $start, $limit";

	$data['query'] = $query;
	$result = mysqli_query($db, $query);
	if($result == false) {
		echo "Query error:".mysqli_error($db).chr(10).chr(10);
		echo "Query was:".$query.chr(10).chr(10);
		die;
	}

	$images = array();
	while($row = mysqli_fetch_assoc($result)) {
		// do a little sanitizing
		$row['title'] = utf8_encode($row['title']);
		$row['card'] = utf8_encode(stripslashes($row['card']));
		$row['citation'] = utf8_encode(stripslashes($row['citation']));

		$row['card'] = str_replace("\\\"", "\"", $row['card']);
		$row['citation'] = str_replace("\\\"", "\"", $row['citation']);

		$images[] = $row;
	}

	// Query for the total
	$query = "select count(images.id) from images $standard_table_str $topic_table_str $region_table_str $where_str $collection_str $region_str $standard_str $topic_str $standard_cal_str $query_term_str";
	//$data['query2'] = $query;
	$result = mysqli_query($db, $query);
	if($result == false) {
		echo "Query2 error:".mysqli_error($db)."<br />";
		echo "Query2 was:".$query."<br />";
		die;
	}

	$total = mysqli_fetch_assoc($result);
	$total = $total['count(images.id)'];

	$data['total'] = $total;
	$data['images'] = $images;

	echo json_encode($data);
