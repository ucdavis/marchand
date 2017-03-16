<?php
	session_start();
	if(!( isset( $_SESSION['loggedin'] ) && ($_SESSION['loggedin'] == true ) ) ){
		header('location: /index.php');
	}

	require_once 'app/slides.inc.php';
	require_once 'app/keywords.inc.php';
	require_once '/app/dsp.inc.php';
	require_once '/html/connect.inc';

	if(isset($_POST['redirect'])) $redirect = $_POST['redirect'];

	if(isset($_GET['id'])) $id = $_GET['id'];
	else $id = -1;

	$lesson = array();

	$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
	mysqli_select_db($db,"lessons");
	mysqli_query("SET NAMES 'utf8'");

	if(isset($redirect)) $redirect = str_replace("\\", "", $redirect);

	// Done editing a lesson
	if(isset($_POST['title'])) {
		// Save data
		$post_id = $_POST['id'];
		$title = stripslashes($_POST['title']);
		$creator = stripslashes($_POST['creator']);
		//$topic_id = $_POST['topic'];
		//$standard_id = $_POST['standard'];
		if(isset($_POST['is_university'])) $is_university = 1; else $is_university = 0;
		if(isset($_POST['is_high_school'])) $is_high_school = 1; else $is_high_school = 0;
		if(isset($_POST['is_middle_school'])) $is_middle_school = 1; else $is_middle_school = 0;
		if(isset($_POST['is_elementary'])) $is_elementary = 1; else $is_elementary = 0;

		$ext1 = "";
		$ext2 = "";
		if($post_id != -1) {
			$ext1 = "id, ";
			$ext2 = "'$post_id', ";
		}

		// Save the data
		$result = mysqli_query($db,"replace into lessons ($ext1 title, creator, is_university, is_middle_school, is_high_school, is_elementary) values($ext2 '".mysqli_real_escape_string($db,$title)."', '".mysqli_real_escape_string($db,$creator)."', '".mysqli_real_escape_string($db,$is_university)."', '".mysqli_real_escape_string($db,$is_middle_school)."', '".mysqli_real_escape_string($db,$is_high_school)."', '".mysqli_real_escape_string($db,$is_elementary)."')");

		$id = mysqli_insert_id($db);

		// Determine any text boxes submitted
		for($i = 1; ; $i++) {
			if(isset($_POST['text_block_'.$i.'_title'])) {
				$title = stripslashes($_POST['text_block_'.$i.'_title']);
				$body = stripslashes($_POST['text_block_'.$i]);
				$lesson_id = $id;
				$text_block_num = $i;

				if($title == 'Title') break;
				$result = mysqli_query($db,"replace into text_blocks (title, body, lesson_id, text_block_num) values('".mysqli_real_escape_string($db, $title)."', '".mysqli_real_escape_string($db, $body)."', '".mysqli_real_escape_string($db,$lesson_id)."', '".mysqli_real_escape_string($db,$text_block_num)."')");
			} else {
				break;
			}
		}

		// Determine any documents submitted
		for($i = 1; ; $i++) {
			if(isset($_POST['document_'.$i])) {
				$sid = $_POST['document_'.$i.'_sid'];
				$body = stripslashes($_POST['document_'.$i]);
				$caption_override = $_POST['document_'.$i.'_sid_caption'];
				$lesson_id = $id;
				$document_num = $i;

				if($body == 'Text here') {
					// They entered no text
					$body = "";
				}
				$result = mysqli_query($db,"replace into documents (body, sid, caption_override, document_num, lesson_id) values('".mysqli_real_escape_string($db,$body)."', '".mysqli_real_escape_string($db,$sid)."', '".mysqli_real_escape_string($db,$caption_override)."', '".mysqli_real_escape_string($db,$document_num)."', '".mysqli_real_escape_string($db,$lesson_id)."')");
			} else {
				break;
			}
		}

		// Redirect to where they came from
		header("Location: http://historyproject.ucdavis.edu/secure/admin/list_lessons.php");
		die;
	}

	// Look up lesson if available
	if($id != -1) {
		$result = mysqli_query($db,"select * from lessons where id='".mysqli_real_escape_string($db,$id)."'");

		$lesson = mysqli_fetch_assoc($db,$result);
	}

	include '/snippets/header.htm';

	if(isset($redirect)) $redirect = str_replace("\'", "'", $redirect);
?>
	<div id="MainLogo">
		<img src="/images/logo.gif" alt="History Project Home" />
	</div>

	<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="jquery.selectboxes.pack.js"></script>
	<script type="text/javascript" src="edit_lessons.js"></script>
	<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
		tinyMCE.init({
			mode : "textareas",
			theme : "advanced",
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,anchor,cleanup,code,|,bullist,numlist",
			theme_advanced_buttons2 : "",
			theme_advanced_buttons3 : ""
		});
		});

	</script>

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

				<h1><a href="http://historyproject.ucdavis.edu/lessons/">Lesson Plans</a> > <a href="javascript:history.go(-1);">Search Results</a> > Edit Lesson</h1>

				<form method="POST" action="edit_lessons.php?id=<?php echo $id; ?>">

					<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
					<input type="hidden" name="id" value="<?php echo $id; ?>" />

					<h2 style="display: inline;">Lesson Name</h2> <input name="title" type="text" size="60" value="<?php echo $lesson['title']; ?>" /><br />

					<br />

					<h2 style="display: inline;">Creator</h2> <input name="creator" type="text" size="60" value="<?php echo $lesson['creator']; ?>" /><br />

					<br />

					<?php
						$keywords = fetch_keywords_by_lid($lesson['id']);

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
						<legend>Regions</legend>
						<table style="margin-left: 1.5em;">
						<?php
							$count = 0;

							if($id > 0) {
								// Get the assigned regions
								$regions = mysqli_query($db,"select image_archive.regions.title as title, image_archive.regions.id as rid from region_assignments, image_archive.regions where region_assignments.rid = regions.id and region_assignments.lid = '".mysqli_real_escape_string($db,$id)."'");

								while($region = mysqli_fetch_assoc($regions)) {
									echo "<tr><td>".$region['title']."</td><td><input type=\"button\" value=\"Remove\" onClick=\"javascript:lesson_remove_region('".$id."', '".$region['rid']."');\" /></td></tr>";
									$count++;
								}
							}

							if($count == 0) echo "<tr><td>No regions currently assigned</td></tr>";
						?>
						</table>

						<br />

						<select name="additional_region">
						<?php
							// Get the regions
							$regions = mysqli_query($db,"select * from image_archive.regions order by title asc");
							while($region = mysqli_fetch_assoc($regions)) {
								if($region['id'] == -1) continue;
								echo "<option value=\"".$region['id']."\">".$region['title']."</option>";
							}
						?>
						</select> <input type="button" value="Assign" onClick="javascript:lesson_assign_region(<?php echo $id; ?>, $('select[name=additional_region]').val());" />

						<br /><br />

						<h2 style="display: inline;">Create New Region:</h2>
						<input type="text" value="New Region Name" id="add_region_input" /> <input type="button" value="Create" onClick="add_region();" disabled />
					</fieldset>

					<br /><br />

					<fieldset>
						<legend>Topics</legend>
						<table style="margin-left: 1.5em;">
						<?php
							// Determine the image's topic/theme
							$count = 0;

							if($id > 0) {
								$result = mysqli_query($db, "select topic_assignments.tid as tid, image_archive.topics.title as title from image_archive.topics, topic_assignments where topic_assignments.lid='".mysqli_real_escape_string($db,$id)."' and topic_assignments.tid = topics.id");

								while($row = mysqli_fetch_assoc($result)) {
									echo "<tr><td>".$row['title']."</td><td><input type=\"button\" value=\"Remove\" onClick=\"javascript:lesson_remove_topic('".$id."', '".$row['tid']."');\" /></td></tr>";
									$count++;
								}
							}

							if($count == 0) echo "<tr><td>No topics currently assigned</td></tr>";
						?>
						</table>

						<br />

						<select name="additional_topic">
							<option value="-1">Assign Additional Topic</option>

							<?php
								// Get the topics
								$topics = mysqli_query($db,"select * from image_archive.topics group by title order by title");
								while($topic = mysqli_fetch_assoc($topics)) {
									print "<option value=\"".$topic['id']."\">".$topic['title']."</option>".chr(10);
								}
							?>

						</select> <input type="button" value="Assign" onClick="javascript:lesson_assign_topic(<?php echo $id; ?>, $('select[name=additional_topic]').val());" />

						<br /><br />

						<h2 style="display: inline;">Create New Topic:</h2>
						<input type="text" value="New Topic Name" id="add_topic_input" /> <input type="button" value="Create" onClick="add_topic();" disabled />
					</fieldset>

					<br /><br />

					<fieldset>
						<legend>Standards</legend>
						<h2>California</h2>

						<table>
						<?php
						$count = 0;

						if($id > 0) {
							// List the standards currently applied
							$standards = mysqli_query($db,"select standards_data.id as id, image_archive.standards_cal.grade_id as grade_id, image_archive.standards_cal.standard_id as standard_id, image_archive.standards_cal.description as description from image_archive.standards_cal, standards_data where lesson_id = '".mysqli_real_escape_string($db, $id)."' and standards_data.sid = image_archive.standards_cal.id and standards_data.stype = 0");

							while($standard = mysqli_fetch_assoc($standards)) {
								$count++;
								if($standard['grade_id'] == "0") $standard['grade_id'] = "K";
								print "<tr><td><p>".$standard['grade_id'].".".$standard['standard_id']." - ".$standard['description']."</p></td><td><input type=\"button\" value=\"Remove\" onClick=\"javascript:lesson_remove_standard('".$id."', '".$standard['id']."', 0);\" /></td></tr>";
							}
						}

						if($count == 0) print "<tr><td><p>No standards currently applied</p></td></tr>";
						?>
						</table>

						<select name="additional_cal_standard" style="width: 500px;">
							<option>Assign Additional California Standard</option>
							<?php
								$result_standards = mysqli_query($db,"select id, grade_id, standard_id, description from image_archive.standards_cal order by grade_id, standard_id");

								while($myrow_standards = mysqli_fetch_assoc($result_standards)) {
									$stand = substr($myrow_standards['description'], 0, 80);
									if($myrow_standards['grade_id'] == "0") $myrow_standards['grade_id'] = "K";
									print "<option value=\"".$myrow_standards['id']."\" $s>".$myrow_standards['grade_id'].".".$myrow_standards['standard_id']." - $stand...</option>\n";
								}
							?>
						</select> <input type="button" value="Assign" onClick="javascript:lesson_assign_standard(<?php echo $id; ?>, $('select[name=additional_cal_standard]').val(), 0);" /><br />

						<br /><hr style="border-top: 1px solid #999;" /><br />

						<h2>National/World</h2>

						<table>
						<?php
						$standards = array();

						if($id > 0) {
							// List the standards currently applied
							$standards = dsp_fetch_standards_nat($db, $id);

							foreach($standards as $standard) {
								print "<tr><td><p>".$standard['label']."</p></td><td><input type=\"button\" value=\"Remove\" onClick=\"javascript:lesson_remove_standard('".$id."', '".$standard['id']."', 1);\" /></td></tr>";
							}
						}

						if(count($standards) == 0) print "<tr><td><p>No standards currently applied</p></td></tr>";
						?>
						</table>

						<select name="additional_nat_standard" style="width: 500px;">
							<option>Assign Additional National/World Standard</option>
							<?php
								$standards = dsp_fetch_standards_nat($db);

								foreach($standards as $standard) {
									print "<option value=\"".$standard['id']."\">".$standard['label'];
								}
							?>
						</select> <input type="button" value="Assign" onClick="javascript:lesson_assign_standard(<?php echo $id; ?>, $('select[name=additional_nat_standard]').val(), 1);" /><br />
					</fieldset>

					<br />

					<h2>Grade Level</h2>
					<div style="float: left; width: 130px;">
						<input type="checkbox" name="is_university" <?php if($lesson['is_university']) echo "checked"; ?> /> University<br />
						<input type="checkbox" name="is_middle_school" <?php if($lesson['is_middle_school']) echo "checked"; ?> /> Middle School
					</div>
					<div style="margin-left: 130px;">
						<input type="checkbox" name="is_high_school" <?php if($lesson['is_high_school']) echo "checked"; ?> /> High School<br />
						<input type="checkbox" name="is_elementary" <?php if($lesson['is_elementary']) echo "checked"; ?> /> Elementary
					</div>

					<br />

					<table id="text_blocks"><tbody>

					<?php
						$new_num = 1;

						// Look for exisiting text blocks
						mysqli_select_db($db,"lessons");
						mysqli_query($db,"SET NAMES 'utf8'");
						$result = mysqli_query($db,"select * from text_blocks where lesson_id = '".mysqli_real_escape_string($db, $id)."' order by text_block_num asc");

						if(mysqli_num_rows($result) > 0) {

							while($row = mysqli_fetch_assoc($result)) {

								echo "<tr>
									<td>
										<div style=\"float: left; width: 120px;\">
											<h2>Text Block #".$row['text_block_num']."</h2>
											<br />
											<input type=\"button\" value=\"Delete Block\" onClick=\"javascript:delete_block(".$id.", ".$row['text_block_num'].");\" />
										</div>
										<div style=\"margin-left: 120px;\">
											<input type=\"text\" name=\"text_block_".$row['text_block_num']."_title\" value=\"".$row['title']."\" size=\"65\" /><br />
											<textarea name=\"text_block_".$row['text_block_num']."\" rows=\"20\" cols=\"65\">".$row['body']."</textarea><br />
										</div>
									</td>
								</tr>
								";

								$new_num++;

							}

						}
					?>
					</tbody></table>

					<br />

					<center><input type="button" value="Add Another Text Block" onClick="javascript:add_text_block();" /></center>

					<br />

					<hr />

					<br />

					<table id="documents"><tbody>

					<?php
						$new_num = 1;

						// Look for exisiting documents

						mysqli_select_db($db,"lessons");
						$result = mysqli_query($db,"select * from documents where lesson_id = '".mysqli_real_escape_string($db,$id)."' order by document_num asc");

						if(mysqli_num_rows($result) > 0) {

							while($row = mysqli_fetch_assoc($result)) {

								echo "<tr>
										<td>
											<div style=\"float: left; width: 120px;\">
												<h2>Document #".$row['document_num'].":</h2>

												<br />

												<img src=\"http://historyproject.ucdavis.edu/ic/get_image.php?id=".$row['sid']."&thumb\" alt=\"\" width=\"100px\" /><br />

												<br />

												<!--<h2>Image Caption/Text</h2> <input type=\"text\" name=\"document_".$row['document_num']."_sid_caption\" value=\"".$row['caption_override']."\" size=\"60\" />-->

												<br />

												<h2 style=\"display: inline;\">Image ID: </h2> <input type=\"text\" value=\"".$row['sid']."\" name=\"document_".$row['document_num']."_sid\" size=\"8\" /><br />

												<br />

												<input type=\"button\" value=\"Delete Document\" onClick=\"javascript:delete_document(".$id.", ".$row['document_num'].");\" /><br />
												<br />
											</div>

											<div style=\"margin-left: 120px;\">
												<textarea name=\"document_".$row['document_num']."\" rows=\"3\" cols=\"65\">".$row['body']."</textarea><br />
											</div>

											<br /><br />
										</td>
									</tr>
								";

								$new_num++;

							}

						}
					?>
					</tbody></table>

					<center><input type="button" value="Add Another Document" onClick="javascript:add_document();" /></center>

					<br /><br />

					<input type="submit" value="Submit Lesson" />
				</form>

				<br /><br />

<?php


echo <<<EOT
			</div> <!-- content -->

			<div id="sideCol">
				<p class="bodyText">
			    	<h2 style="margin-bottom: 10px;">Add New Lesson</h2>
			    	<input type="button" value="Add New" onClick="javascript:window.location = 'http://historyproject.ucdavis.edu/secure/admin/edit_lessons.php';" /><br /><br />

			        <h2 style="margin-bottom: 10px;">Lesson Search</h2>

			        <p>Use one or more fields below to conduct your search.</p>

					<input type="text" value="Enter keyword(s)" /><br />

					<br />

					<select style="width: 150px;">
						<option>Topic/Theme</option>
					</select>
					<br /><br />
					<select style="width: 150px;">
						<option>Standard</option>
					</select>
					<br /><br />
					<select style="width: 150px;">
						<option>Grade Level</option>
					</select>
				</p>
			</div> <!--end sideCol-->

			<div style="clear:both;"></div>
		</div> <!--end innerWrapper2-->
	</div> <!--end innerWrapper-->
EOT;

	include '/snippets/footer.htm';
