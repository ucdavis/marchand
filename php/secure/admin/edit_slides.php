<?php
	session_start();
	if(!( isset( $_SESSION['loggedin'] ) && ($_SESSION['loggedin'] == true ) ) ){
		header('location: /index.php');
	}

	require_once 'app/slides.inc.php';
	require_once 'app/keywords.inc.php';
	require_once '/app/ic.inc.php';
	require_once '/html/connect.inc';

	if(isset($_POST['redirect'])) $redirect = $_POST['redirect'];
	if(isset($_GET['what'])) $what = $_GET['what'];
	$id = $_GET['id'];

	$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
	mysqli_select_db($db, "image_archive");
	mysqli_query($db, "SET NAMES 'utf8'");

	if(isset($redirect)) $redirect = str_replace("\\", "", $redirect);

	// Done editing a slide
	if($id && isset($_POST['title'])) {
		// Save data

		// Clean up some errors
		$_POST['title'] = stripcslashes($_POST['title']);
		$_POST['card_text'] = stripcslashes($_POST['card_text']);
		$_POST['citation'] = stripcslashes($_POST['citation']);
		$_POST['notes'] = stripcslashes($_POST['notes']);

		if(!isset($_POST['featured'])) $_POST['featured'] = "off";

		// Translate some of the input
		if($_POST['public'] == "Yes") $_POST['public'] = 1;
		else $_POST['public'] = 0;

		if($_POST['featured'] == "off") $_POST['featured'] = 0;
		else $_POST['featured'] = 1;

		$result = mysqli_query($db, "insert into images (title, card, citation, collection, public, notes, featured, id) values('".mysqli_real_escape_string($db, $_POST['title'])."', '".mysqli_real_escape_string($db, $_POST['card_text'])."', '".mysqli_real_escape_string($db, $_POST['citation'])."', '".mysqli_real_escape_string($db, $_POST['collection'])."', '".mysqli_real_escape_string($db, $_POST['public'])."', '".mysqli_real_escape_string($db, $_POST['notes'])."', '".mysqli_real_escape_string($db, $_POST['featured'])."' , '".mysqli_real_escape_string($db,$id)."') on duplicate key update title='".mysqli_real_escape_string($db, $_POST['title'])."', card='".mysqli_real_escape_string($db, $_POST['card_text'])."', citation='".mysqli_real_escape_string($db, $_POST['citation'])."', collection='".mysqli_real_escape_string($db, $_POST['collection'])."', public='".mysqli_real_escape_string($db, $_POST['public'])."', notes='".mysqli_real_escape_string($db, $_POST['notes'])."', featured='".mysqli_real_escape_string($db, $_POST['featured'])."'");

		if($result == false) {
			echo "<b>There was an error saving the data. Please copy this text and give it to the system administrator.</b><br />Error: ".mysqli_error($db);
			die;
		}

		// And another separate bit of logic and query to save the keywords
		$keywords = split_keyword_string($_POST['keywords']);
		foreach($keywords as $keyword) {
			// No room for errors here ... (FIXME)
			$result = mysqli_query($db, "insert into keywords (title) values('".mysqli_real_escape_string($db, $keyword)."') on duplicate key update title = '".mysqli_real_escape_string($db, $keyword)."'");
			$keyword_id = mysqil_insert_id();
			$result = mysqli_query($db, "insert into keyword_assignments (sid, kid) values('".mysqli_real_escape_string($db, $id)."', '".mysqli_real_escape_string($db,$keyword_id)."') on duplicate key update sid = '".mysqli_real_escape_string($db,$id)."'");
		}

		// did they include any files
		if($_FILES['new_photo']['size'] > 0) {
			$uploads_dir = "/var/www/html/historyproject.ucdavis.edu/marchandslides.bak";
			$tmp_name = $_FILES['new_photo']['tmp_name'];
			$ext = substr($_FILES['new_photo']['name'], strrpos($_FILES['new_photo']['name'], '.') + 1);
			$name = $id.'.'.$ext;

			// delete the old file first
			$result = mysqli_query($db,"select file, thumbnail from images where id='".mysqli_real_escape_string($id)."' limit 1");
			$row = mysqli_fetch_assoc($result);
			assert(mysqli_num_rows($result) > 0);
			unlink("/var/www/html/historyproject.ucdavis.edu/marchandslides.bak/".$row['file']);
			unlink("/var/www/html/historyproject.ucdavis.edu/marchandslides.bak/".$row['thumbnail']);

			// the nature of move_uploaded_file will override the file should it already exist
			move_uploaded_file($tmp_name, "$uploads_dir/$name");

			// generate the thumbnail
			exec("/usr/bin/convert /var/www/html/historyproject.ucdavis.edu/marchandslides.bak/".$name." -thumbnail 225x /var/www/html/historyproject.ucdavis.edu/marchandslides.bak/thumbnails/".$name);

			// update the database with the new filename
			$result = mysqli_query($db, "update images set file='".mysqli_real_escape_string($db,$name)."', thumbnail='".mysqli_real_escape_string($db,"thumbnails/".$name)."' where id='".mysqli_real_escape_string($db,$id)."' limit 1");
		}

		// Redirect to where they came from
		//header("Location: ".$_COOKIE['last_location']);
		//die;
	}

	include '/snippets/header.htm';

	if(isset($redirect)) $redirect = str_replace("\'", "'", $redirect);

	$result = mysqli_query($db, "select * from images where id = '$id'");

	$image = mysqli_fetch_assoc($result);

	if(mysqli_num_rows($result) == 0) {
		// possibly creating a new image
		$image = array();
		$image['id'] = $id;
	}

	// do a little sanitizing
	$image['card'] = stripslashes($image['card']);
	$image['citation'] = stripslashes($image['citation']);
	$image['card'] = str_replace("\\\"", "\"", $image['card']);
	$image['citation'] = str_replace("\\\"", "\"", $image['citation']);
?>
	<div id="MainLogo">
		<img src="/images/logo.gif" alt="History Project Home" />
	</div>

	<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="http://historyproject.ucdavis.edu/ic/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="jquery.selectboxes.pack.js"></script>
	<script type="text/javascript" src="edit_slides.js"></script>
	<script type="text/javascript" src="http://historyproject.ucdavis.edu/ic/js/gallery.js"></script>

	<div id="MainNav">
		<table id="header1">
			<tr>
				<td id="logo" valign="bottom">
					<a href="/" ><img src="/images/logo.gif" alt="History Project Home" /></a>
				</td>
			</tr>
		</table>
	</div>

	<div id="innerWrapper">
		<div id="innerWrapper2">

			<div id="content">

				<h1><a>Image Database</a> > <a>Search Results</a> > <a>Edit Image</a></h1>

				<form method="POST" enctype="multipart/form-data" action="edit_slides.php?id=<?php echo $id; ?>">

					<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />

					<script type="text/javascript">
						$.cookie('last_edit_id', <?php echo $id; ?>, { path: '/' });
					</script>

					<h2 style="display: inline;">Image Title</h2> <input name="title" type="text" size="35" value="<?php echo $image['title']; ?>" /> <h2 style="display: inline;">Image ID</h2> <input type="text" disabled name="id" value="<?php echo $image['id']; ?>" size="8" /><br />

					<br />

					<h2 style="display: inline;">Image Preview</h2><br /><small><i>(click image to view full resolution)</i><br /></small><br />
					<div style="float: left; width: 225px;">
						<a href="/marchandslides.bak/<?php echo $image['file']; ?>"><img src="/ic/get_image.php?id=<?php echo $image['id']; ?>&thumb" width="225" /></a>
					</div>
					<div style="margin-left: 235px;">
						<p>
						<a href="javascript:rotate('/marchandslides.bak/<?php echo $image['file']; ?>', -1);"><img src="left90.gif" width="25" height="25" /> Rotate Left</a><br />
						<br /><br />
						<a href="javascript:rotate('/marchandslides.bak/<?php echo $image['file']; ?>', 1);"><img src="right90.gif" width="25" height="25" /> Rotate Right</a><br />
						<br /><br />
						<a href="javascript:flip('/marchandslides.bak/<?php echo $image['file']; ?>');"><img src="flip180.gif" width="25" height="25" /> Rotate 180 degrees</a><br />
						<br />
						</p>
						<p>Insert/Replace Image:<br /></p><input type="file" name="new_photo" /><br />
						<br />
					</div>

					<div style="clear: both;"></div>

					<h2>Card Text</h2>
					<textarea name="card_text" rows="10" cols="70"><?php echo $image['card']; ?></textarea>

					<br />

					<h2>Citation</h2>
					<textarea name="citation" rows="6" cols="70"><?php echo $image['citation']; ?></textarea>

					<br /><br />

					<fieldset>
						<legend>Regions</legend>
						<table style="margin-left: 1.5em;">
						<?php
							// Get the assigned regions
							$regions = mysqli_query($db,"select regions.title as title, regions.id as rid from region_assignments, regions where region_assignments.rid = regions.id and region_assignments.sid = '".mysqli_real_escape_string($db,$id)."'");
							$count = 0;
							while($region = mysqli_fetch_assoc($regions)) {
								echo "<tr><td>".$region['title']."</td><td><input type=\"button\" value=\"Remove\" onClick=\"javascript:remove_region('".$id."', '".$region['rid']."');\" /></td></tr>";
								$count++;
							}

							if($count == 0) echo "<tr><td>No regions currently assigned</td></tr>";
						?>
						</table>

						<br />

						<select name="additional_region">
						<?php
							// Get the regions
							$regions = mysqli_query($db,"select * from regions order by title asc");
							while($region = mysqli_fetch_assoc($regions)) {
								if($region['id'] == -1) continue;
								echo "<option value=\"".$region['id']."\">".$region['title']."</option>";
							}
						?>
						</select> <input type="button" value="Assign" onClick="javascript:assign_region(<?php echo $id; ?>, $('select[name=additional_region]').val());" />

						<br /><br />

						<h2 style="display: inline;">Create New Region:</h2>
						<input type="text" value="New Region Name" id="add_region_input" /> <input type="button" value="Create" onClick="add_region();" />
					</fieldset>

					<br /><br />

					<fieldset>
						<legend>Topics</legend>
						<table style="margin-left: 1.5em;">
						<?php
							// Determine the image's topic/theme

							$result = mysqli_query($db,"select topic_assignments.tid as tid, topics.title as title from topics, topic_assignments where topic_assignments.sid='".$image['id']."' and topic_assignments.tid = topics.id");

							$count = 0;
							while($row = mysqli_fetch_assoc($result)) {
								echo "<tr><td>".$row['title']."</td><td><input type=\"button\" value=\"Remove\" onClick=\"javascript:remove_topic('".$id."', '".$row['tid']."');\" /></td></tr>";
								$count++;
							}

							if($count == 0) echo "<tr><td>No topics currently assigned</td></tr>";
						?>
						</table>

						<br />

						<select name="additional_topic">
							<option value="-1">Assign Additional Topic</option>

							<?php
								// Get the topics
								$topics = mysqli_query($db,"select * from topics group by title order by title");
								while($topic = mysqli_fetch_assoc($topics)) {
									$selected = "";

									if($topic_id == $topic['id']) $selected = "selected";

									print "<option value=\"".$topic['id']."\" $selected>".$topic['title']."</option>".chr(10);
								}
							?>

						</select> <input type="button" value="Assign" onClick="javascript:assign_topic(<?php echo $id; ?>, $('select[name=additional_topic]').val());" />

						<br /><br />

						<h2 style="display: inline;">Create New Topic:</h2>
						<input type="text" value="New Topic Name" id="add_topic_input" /> <input type="button" value="Create" onClick="add_topic();" />
					</fieldset>

					<br /><br />

					<?php
						if($image['featured']) $checked = "checked";
						else $checked = "";
					?>

					<input type="checkbox" name="featured" <?php echo $checked; ?> /> Designate "Best Of"<br />
					<br />

					<?php
						$keywords = fetch_keywords_by_sid($db, $image['id']);

						$keyword_str = "";

						foreach($keywords as $keyword) {
							$keyword_str .= $keyword.", ";
						}

						$keyword_str = substr($keyword_str, 0, strlen($keyword_str) - 2);
					?>

					<h2>Keywords (comma-separated)</h2>
					<input name="keywords" type="text" size="50" value="<?php echo $keyword_str; ?>" /><br />
					<br />

					<fieldset>
						<legend>Standards</legend>
						<h2>California</h2>

						<table>
						<?php
						// List the standards currently applied
						$standards = mysqli_query($db,"select standards_data.id as id, standards_cal.grade_id as grade_id, standards_cal.standard_id as standard_id, standards_cal.description as description from standards_cal, standards_data where image_id = '$id' and standards_data.sid = standards_cal.id and standards_data.stype = 0");

						$count = 0;
						while($standard = mysqli_fetch_assoc($standards)) {
							$count++;
							if($standard['grade_id'] == "0") $standard['grade_id'] = "K";
							print "<tr><td><p>".$standard['grade_id'].".".$standard['standard_id']." - ".$standard['description']."</p></td><td><input type=\"button\" value=\"Remove\" onClick=\"javascript:remove_standard('".$id."', '".$standard['id']."', 0);\" /></td></tr>";
						}

						if($count == 0) print "<tr><td><p>No standards currently applied</p></td></tr>";
						?>
						</table>

						<select name="additional_cal_standard" style="width: 500px;">
							<option>Assign Additional California Standard</option>
							<?php
								$result_standards = mysqli_query($db, "select id, grade_id, standard_id, description from standards_cal order by grade_id, standard_id");

								while($myrow_standards = mysqli_fetch_assoc($result_standards)) {
									$stand = substr($myrow_standards['description'], 0, 80);
									if($myrow_standards['grade_id'] == "0") $myrow_standards['grade_id'] = "K";
									print "<option value=\"".$myrow_standards['id']."\" $s>".$myrow_standards['grade_id'].".".$myrow_standards['standard_id']." - $stand...</option>\n";
								}
							?>
						</select> <input type="button" value="Assign" onClick="javascript:assign_standard(<?php echo $id; ?>, $('select[name=additional_cal_standard]').val(), 0);" /><br />

						<br /><hr style="border-top: 1px solid #999;" /><br />

						<h2>National/World</h2>

						<table>
						<?php
						// List the standards currently applied
						$standards = fetch_standards_nat($id);

						foreach($standards as $standard) {
							print "<tr><td><p>".$standard['label']."</p></td><td><input type=\"button\" value=\"Remove\" onClick=\"javascript:remove_standard('".$id."', '".$standard['id']."', 1);\" /></td></tr>";
						}

						if(count($standards) == 0) print "<tr><td><p>No standards currently applied</p></td></tr>";
						?>
						</table>

						<select name="additional_nat_standard" style="width: 500px;">
							<option>Assign Additional National/World Standard</option>
							<?php
								$standards = fetch_standards_nat();

								foreach($standards as $standard) {
									print "<option value=\"".$standard['id']."\">".$standard['label'];
								}
							?>
						</select> <input type="button" value="Assign" onClick="javascript:assign_standard(<?php echo $id; ?>, $('select[name=additional_nat_standard]').val(), 1);" /><br />
					</fieldset>

					<br />

					<h2>Collection:</h2>
					<select name="collection">
						<?php
							$selected = "";

							if($image['collection'] == -1) $selected = "selected";
						?>

						<option value="-1" <?php echo $selected; ?>>No Collection Assigned</option>

						<?php
							// Get the collections
							$collections = mysqli_query($db,"select id, name, code from collections order by name asc");
							while($collection = mysqli_fetch_assoc($collections)) {
								$selected = "";

								if($image['collection'] == $collection['id']) $selected = "selected";

								print "<option value=\"".$collection['id']."\" $selected>".$collection['name']."</option>".chr(10);
							}
						?>
					</select><br />

					<br />

					<?php
						if($image['public']) {
							$yes_txt = "checked";
							$no_txt = "";
						} else {
							$yes_txt = "";
							$no_txt = "checked";
						}
					?>

					<h2>Permission to Display:</h2> <input type="radio" name="public" value="Yes" <?php echo $yes_txt; ?> /> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="public" value="No" <?php echo $no_txt; ?> /> No

					<br /><br />

					<h2>Permission Tracking Notes:</h2>
					<textarea name="notes" rows="5" cols="80"><?php echo $image['notes']; ?></textarea>

					<div style="clear: both;"></div>

					<br />

					<!--<h2>Replace Image:</h2>
					<input type="file" />

					<br /><br />-->

					<input type="submit" value="Submit Edits" />

				</form>

				<br /><br />

<?php


echo <<<EOT
			</div> <!-- content -->

			<div id="sideCol">
EOT;
				include '/ic/search_sidebar.inc.php';
?>
			</div> <!--end sideCol-->

			<div style="clear:both;"></div>
		</div> <!--end innerWrapper2-->
	</div> <!--end innerWrapper-->

<?php
	include '/snippets/footer.htm';
