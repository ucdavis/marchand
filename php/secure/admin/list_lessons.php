<?php
	session_start();
	if(!( isset( $_SESSION['loggedin'] ) && ($_SESSION['loggedin'] == true ) ) ){
		header('location: /index.php');
	}

	require_once 'app/slides.inc.php';
	require_once '/var/www/html/connect.inc';

	$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
	mysqli_select_db($db, "lessons");

	include '/var/www/html/historyproject.ucdavis.edu/snippets/header.htm';
?>
	<div id="MainLogo">
		<img src="/images/logo.gif" alt="History Project Home" />
	</div>

	<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="jquery.selectboxes.pack.js"></script>
	<script type="text/javascript" src="edit_lessons.js"></script>

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

				<h1><a>Lesson Plans</a> > View All</h1>

				<br />

				<?php
					$result = mysqli_query($db, "select id, title, creator from lessons order by id asc");

					while($row = mysqli_fetch_assoc($result)) {
						echo "<a href=\"/lessons/view_lesson.php?id=".$row['id']."\"><h4>".$row['title']."</h4></a><p>By ".$row['creator']." - <a href=\"http://historyproject.ucdavis.edu/secure/admin/edit_lessons.php?id=".$row['id']."\">Edit</a></p>";
					}
				?>

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

<?php
	include '/var/www/html/historyproject.ucdavis.edu/snippets/footer.htm';
