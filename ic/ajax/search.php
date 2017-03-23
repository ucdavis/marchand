<?php
	if(!isset($_POST['query'])) die; // need valid input

	$db = mysqli_connect("localhost", "hc", "admin");
	mysqli_select_db($db, "image_archive");

	$result = mysqli_query($db, "select id, thumbnail from images where match(title, card, citation, notes) against ('".mysqli_real_escape_string($db, $_POST['query'])."')");
