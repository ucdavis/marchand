<?php
	require_once 'app/slides.inc.php';
	require_once 'app/keywords.inc.php';
	require_once '/app/ic.inc.php';

	if(isset($_POST['redirect'])) $redirect = $_POST['redirect'];
	$what = $_GET['what'];
	$id = $_GET['id'];

	$db = mysqli_connect("localhost", "hc", "admin");
	mysqli_select_db($db, "image_archive");

	$redirect = str_replace("\\", "", $redirect);

	// Done editing a slide
	if($id && $what == 'submit' && isset($_POST['title'])) {
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

		$result = mysqli_query($db, "update images set title='".mysqli_real_escape_string($db, $_POST['title'])."', card='".mysqli_real_escape_string($db, $_POST['card_text'])."', citation='".mysqli_real_escape_string($db, $_POST['citation'])."', region_id='".mysqli_real_escape_string($db, $_POST['region'])."', collection='".mysqli_real_escape_string($db, $_POST['collection'])."', public='".mysqli_real_escape_string($db, $_POST['public'])."', notes='".mysqli_real_escape_string($db, $_POST['notes'])."', featured='".mysqli_real_escape_string($db, $_POST['featured'])."' where id='".mysqli_real_escape_string($db, $id)."'");

		if($result == false) {
			echo "<b>There was an error saving the data. Please copy this text and give it to the system administrator.</b><br />Error: ".mysqli_error($db);
		}

		// And another separate bit of logic and query to save the keywords
		$keywords = split_keyword_string($_POST['keywords']);
		foreach($keywords as $keyword) {
			// No room for errors here ... (FIXME)
			$result = mysqli_query($db, "insert into keywords (title) values('".mysqli_real_escape_string($db, $keyword)."') on duplicate key update title = '".mysqli_real_escape_string($db, $keyword)."'");
			$keyword_id = mysqli_insert_id();
			$result = mysqli_query($db, "insert into keyword_assignments (sid, kid) values('".mysqli_real_escape_string($db,$id)."', '".mysqli_real_escape_string($db,$keyword_id)."') on duplicate key update sid = '".mysqli_real_escape_string($db,$id)."'");
		}

		// Redirect to where they came from
		//header("Location: http://historyproject.ucdavis.edu$redirect#$id");
		//echo "<script type=\"text/javascript\">
		//	history.go(-2);
		//</script>";
		header("Location: ".$_COOKIE['last_location']);
		die;
	}

	include '/snippets/header.htm';

	$redirect = str_replace("\'", "'", $redirect);

	$result = mysqli_query($db,"select * from images where id = '$id'");

	$image = mysqli_fetch_assoc($result);

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

				<form method="POST" action="edit_slides.php?id=<?php echo $id; ?>&what=submit">

					<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />

					<h2 style="display: inline;">Image Title</h2> <input name="title" type="text" size="35" value="<?php echo $image['title']; ?>" /> <h2 style="display: inline;">Image ID</h2> <input type="text" disabled name="id" value="<?php echo $image['id']; ?>" size="8" /><br />

					<br />

					<h2 style="display: inline;">Image Preview</h2><br /><small><i>(click image to view full resolution)</i><br /></small><br />
					<div style="float: left; width: 225px;">
						<a href="/marchandslides.bak/<?php echo $image['file']; ?>"><img src="/marchandslides.bak/<?php echo $image['thumbnail']; ?>" width="225" /></a>
					</div>
					<div style="margin-left: 235px;">
						<a href="javascript:rotate('/marchandslides.bak/<?php echo $image['file']; ?>', -1);"><img src="left90.gif" width="25" height="25" /> Rotate Left</a><br />
						<br /><br />
						<a href="javascript:rotate('/marchandslides.bak/<?php echo $image['file']; ?>', 1);"><img src="right90.gif" width="25" height="25" /> Rotate Right</a><br />
						<br /><br />
						<a href="javascript:flip('/marchandslides.bak/<?php echo $image['file']; ?>');"><img src="flip180.gif" width="25" height="25" /> Rotate 180 degrees</a><br />
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
							$regions = mysqli_query($db, "select regions.title as title, regions.id as rid from region_assignments, regions where region_assignments.rid = regions.id and region_assignments.sid = '".mysqli_real_escape_string($db,$id)."'");
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
								$topics = mysqli_query($db, "select * from topics group by title order by title");
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
						$result_haveit = mysqli_query($db, "select * from standards_data where image_id = '$id'");

						while($myrow_haveit = mysqli_fetch_assoc($result_haveit)) {
							list($gid, $sid, $ssid, $stext) = split("\|", standardinfo($db, $myrow_haveit['sid']));
							print "<tr><td><p>$sid - $ssid - $stext</p></td><td><input type=\"button\" value=\"Remove\" onClick=\"javascript:remove_standard('".$id."', '".$myrow_haveit['id']."');\" /></td></tr>";
						}
						?>
						</table>

						<select name="additional_standard" style="width: 500px;">
							<option>Assign Additional California Standard</option>
							<?php
								$result_standards = mysqli_query($db, "select id, grade_id, standard_id, sub_standard_num, sub_standard_text from standards_cal order by grade_id, standard_id, sub_standard_num");

								while($myrow_standards = mysqli_fetch_assoc($result_standards)) {
									$stand = substr($myrow_standards['sub_standard_text'], 0, 80);
									print "<option value=\"".$myrow_standards['id']."\" $s>".$myrow_standards['standard_id']." ".$myrow_standards['sub_standard_num']." - $stand...</option>\n";
								}
							?>
						</select> <input type="button" value="Add" onClick="javascript:assign_standard(<?php echo $id; ?>, <?php echo $image['collection']; ?>, $('select[name=additional_standard]').val());" /><br />
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
					<textarea name="notes" rows="3" cols="80"><?php echo $image['notes']; ?></textarea>

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
