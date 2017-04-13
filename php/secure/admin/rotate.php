<?php
	$file = $_POST['filename'];
	$dir = $_POST['dir'];

	$large = '/var/www/html/historyproject.ucdavis.edu'.$file;
	$thumb = '/var/www/html/historyproject.ucdavis.edu'.str_replace('images', 'thumbnails', $file);

	if($dir == "-1") {
		$degrees = "-90";
	} else {
		$degrees = "90";
	}

	// rotate the large one
	$cmd = "/usr/bin/convert -rotate $degrees \"$large\" \"$large\"";
	system($cmd);

	// rotate the thumbnail
	$cmd = "/usr/bin/convert -rotate $degrees \"$thumb\" \"$thumb\"";
	system($cmd);
