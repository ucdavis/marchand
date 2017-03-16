<?php
	require_once '../app/ic.inc.php';
	require_once '../html/connect.inc';

	if(!isset($_GET['id'])) die;

	$db = mysql_connect("localhost", $connect["username"], $connect["password"]);
	mysql_select_db("image_archive", $db);

	$image = fetch_image($_GET['id']);

	$filename = explode("/", $image['file']);

	$filename = $filename[count($filename) - 1];

	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-Disposition: attachment; filename=$filename");
	header("Content-Type: application/zip");
	header("Content-Transfer-Encoding: binary");

	readfile("/var/www/html/historyproject.ucdavis.edu/marchandslides.bak/".$image['file']);
