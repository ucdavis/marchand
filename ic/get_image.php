<?php
	if(!isset($_GET['id'])) die;

	include '../html/connect.inc';

	$id = (int)$_GET['id'];

	// do they want a thumbnail or the real deal?
	if(isset($_GET['thumb']))
		$thumb = true;
	else
		$thumb = false;

	if($id == 0) {
		// placeholder for no image
		$src = "/var/www/html/historyproject.ucdavis.edu/marchandslides.bak/thumbnails/IMG0000.jpg";
		$public = true;
	} else {
		// Get the image information from the database
		$db = mysql_connect("localhost", $connect["username"], $connect["password"]);
		mysql_select_db("image_archive", $db);

		$result = mysql_query("select file, thumbnail, public from images where id = '".mysql_real_escape_string($id)."'", $db);

		$image = mysql_fetch_assoc($result);

		if($thumb) {
			$src = "/var/www/html/historyproject.ucdavis.edu/marchandslides.bak/".$image['thumbnail'];
			if(!file_exists($src)) {
				$src = "/var/www/html/historyproject.ucdavis.edu/".$image['thumbnail'];
			}
		} else {
			$src = "/var/www/html/historyproject.ucdavis.edu/marchandslides.bak/".$image['file'];
			if(!file_exists($src)) {
				$src = "/var/www/html/historyproject.ucdavis.edu/".$image['file'];
			}
		}

		$public = $image['public'];
	}

	// The follow bits of overlay code are based on an article retrived from http://www.codingforums.com/showthread.php?t=72317 on June 3, 2010
	$overlay = '/var/www/html/historyproject.ucdavis.edu/images/copyright.gif';
	$dir = '';

	$image = $src;

	// Set offset from bottom-right corner
	$w_offset = 0;
	$h_offset = 0;

	$extension = strtolower(substr($image, strrpos($image, ".") + 1));

	// Load image from file
	switch ($extension)
	{
	    case 'jpg':
	        $background = imagecreatefromjpeg($dir . $image);
	        break;
	    case 'jpeg':
	        $background = imagecreatefromjpeg($dir . $image);
	        break;
	    case 'png':
	        $background = imagecreatefrompng($dir . $image);
	        break;
	    case 'gif':
	        $background = imagecreatefromgif($dir . $image);
	        break;
	    default:
	        die("Image is of unsupported type.");
	}

	// Find base image size
	$swidth = imagesx($background);
	$sheight = imagesy($background);

	// Turn on alpha blending
	imagealphablending($background, true);

	if($public == false) {
		// Create overlay image
		$overlay = imagecreatefromgif($dir . $overlay);

	 	$w_offset = ($swidth / 2) - (imagesx($overlay) / 2);
	 	$h_offset = ($sheight / 2) - (imagesy($overlay) / 2);

		// Get the size of overlay
		$owidth = imagesx($overlay);
		$oheight = imagesy($overlay);

		// Overlay watermark
		imagecopy($background, $overlay, $swidth - $owidth - $w_offset, $sheight - $oheight - $h_offset, 0, 0, $owidth, $oheight);
	}

	// Output header and final image
	header("Content-type: image/jpeg");
	//header("Content-Disposition: filename=" . $image);
	imagejpeg($background);

	// Destroy the images
	imagedestroy($background);
	imagedestroy($overlay);
