<?php
	$file = $_POST['filename'];

	$large = '/var/www/html/historyproject.ucdavis.edu'.$file;
	$thumb = '/var/www/html/historyproject.ucdavis.edu'.str_replace('images', 'thumbnails', $file);

	// flip the large one
	$cmd = "/usr/bin/convert -flip \"$large\" \"$large\"";
	system($cmd);

	// flip the thumbnail
	$cmd = "/usr/bin/convert -flip \"$thumb\" \"$thumb\"";
	system($cmd);

