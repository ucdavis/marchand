<?php
	if(!isset($_POST['query'])) die; // need valid input
	
	$db = mysql_connect("localhost", "hc", "admin");
	mysql_select_db("image_archive", $db);
	
	$result = mysql_query("select id, thumbnail from images where match(title, card, citation, notes) against ('".mysql_real_escape_string($_POST['query'])."')", $db);
	