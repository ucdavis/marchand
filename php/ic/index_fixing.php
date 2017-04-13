<?php
	if(!$redirected && $text) {
		$url = "/ic/search/$text/$standard/";
		header("Location: $url");
	}

	require_once "../classes/Connection.php";
	require_once '../app/ic.inc.php';
	require_once '../html/secure.inc.php';
	$Connection = new Connection();

	$quote = $Connection->GetRandomQuote();

	include "../snippets/header.htm";
	include "../snippets/navigation.php";
?>

<div id="innerWrapper">
	<div id="innerWrapper2">
		<div id="content">
<?php
	## Mess with the user, this is totally stupid
	list($uid) = split("-", $_COOKIE["AuthUser"]);
	$redirect = $_SERVER["REDIRECT_URL"];

	if(is_admin($uid)) {
		$editor = 1;
		$edit = "or public = 0";
	} else {
		$editor = 0;
	}

	$db = mysqli_connect("localhost", "hc", "admin");
	mysqli_select_db($db, "image_archive");

	## Get titles and codes of stuff
	$sub_s = rtrim($sub_s,"/");
	list($s_id,$sub_s) = split("_",$sub_s);
	list($sub_s,$sid) = split("/",$sub_s);
	list($sid) = split("\.",$sid);

	## Get the major,minor titles with underscores replaces spaces
	if(!$sid) list($major_title_url, $minor_title_url, $sid) = split("/", $major_minor);

	$major_title = str_replace("_", " ", $major_title_url);
	$minor_title = str_replace("_", " ", $minor_title_url);

	## If a collection is listed, get the ID, code and name
	if($c) list($collection_id, $collection_title, $collection_code) = split("\|", collection_info($db, $c));

	if($minor_title_url) list($minor_id, $minor_title, $minor_code) = split("\|", minor_title($db, $minor_title));
	if($major_title_url) {
		list($major_id, $major_title, $major_code) = split("\|", major_title($db, $major_title));
	}

	## Search for text
	if($text) {
		search($db,$text,$standard);
	} elseif( $s ) {
		$bg = "#eeeeee";
		## Print some fancy title
		print "<div id=\"ic_title\">";
		$ss = $s_id . "_" . $sub_s;
		print "<B><A HREF=/ic/>Image Collection</A></B> / <A HREF=/ic/standard/$s/>$s</A> ";
		if($sub_s) { print "/ <A HREF=/ic/standard/$s/$ss/>$s_id / $sub_s</A> "; }
		else { print "/ All Standards "; }
		if($sid) { print " / $sid"; }
		print "</div>";

		if(!$sub_s && !$sid) {
			#$sub_standard_count=standard_count($db,"sub");
			#print "<P>Browse by:<P>";
			## Show Collections
			#print "Standard
			print "<TABLE WIDTH=100%><TR BGCOLOR=557799><TD>Standard</TD><TD>Images</TD><TD>Description</TD></TR>";
			$result = mysqli_query($db, "select * from standards_cal  where grade_id = '$s' order by grade_id, standard_id, sub_standard_num");
			while ($myrow = mysqli_fetch_row($result)) {
				#$count=$sub_standard_count[$myrow[0]];
				#print "UPDATE Standards_Cal SET CurrentCount = '$count' WHERE ID = '$myrow[0]' LIMIT 1;<BR>";
				$count=$myrow[5];
				if(!$count) { print "<TR BGCOLOR=$bg VALIGN=TOP><TD NOWRAP>$myrow[2] - $myrow[3]</TD><TD>0</TD><TD>$myrow[4]</TD></TR>"; }
				if($count) { print "<TR BGCOLOR=$bg VALIGN=TOP><TD NOWRAP><A HREF=/ic/standard/$s/$myrow[2]_$myrow[3]/>$myrow[2] - $myrow[3]</A></TD><TD>$count</TD><TD>$myrow[4]</TD></TR>"; }
				if($bg=="#EEEEEE") { $bg="#FFFFFF"; }
				else { $bg="#EEEEEE"; }
				}
			print "</TABLE>";
			}
		elseif($sub_s && !$sid) {

			print "<table border=\"0\">";
			// Get standard ID from standard
			$result = mysqli_query($db, "select * from standards_cal where standard_id = '$s_id' and sub_standard_num = '$sub_s'");

			while($myrow = mysqli_fetch_assoc($result)) {
				$sid = $myrow['id'];
			}

			$result = mysqli_query($db, "select * from standards_data where sid ='$sid'");
			while ($myrow = mysqli_fetch_assoc($result)) {

				$result_details = mysqli_query($db, "select * from images where id = '".$myrow['image_id']."' and (public = '1' $edit) limit 1");
				while ($myrow_details = mysqli_fetch_assoc($result_details)) {

					$short_desc = substr($myrow_details['title'], 0, 100)."...";
					$img = $s_id."_".$sub_s."/$myrow_details[0].html";

					if($myrow_details['thumbnail'] != 'IMG0000.jpg') {
						$old_num = $myrow_details[8];
						$img = "/marchandslides.bak/".$myrow_details['thumbnail'];
						$url = "/marchandslides.bak/".$myrow_details['file'];

						thumbnail($url, $img, $myrow_details['title'], $myrow_details['citation']."<br /><hr style=\"border-color: #999;\" /><br />".$myrow['card'], $major_code, $minor_code);
					}
					$count++;
				}
			}
			print "</table>";
		} else if($sid) {
			show_indi_slide($db,$sid);
		}

	} else if($c) {
		// Print some fancy title
		print "<DIV ID=ic_title><B><A HREF=/ic/>Image Collection</A></B> / <A HREF=/ic/collection/$collection_code/>$collection_title</A> ";
		if($major_title) { print "/ <A HREF=/ic/collection/$collection_code/$major_title_url/>$major_title</A> "; }
		else { print "/ All Topics "; }
		if($minor_title) { print "/ <A HREF=/ic/collection/$collection_code/$major_title_url/$minor_title_url/>$minor_title</A> "; }
		else { print "/ All Subtopics "; }
		if($sid) { print " / $sid"; }

		print "</DIV>";

		## Lists majors from majors_minors list where the collection is correct
		## Updated 04/16/2008 - DONE
		if(!$major_title_url && !$minor_title_url) {
			print "<DIV ID=navcontainer><UL ID=navlist>";
			$result = mysqli_query($db, "select images.id, topic_assignment.topic from images, topic_assignment where images.id = topic_assignment.pid and images.collection = '$collection_id' group by topic");
			while ($myrow = mysqli_fetch_row($result)) {
				list($major_id, $major_title, $major_code) = split("\|", major_info_by_code($db, $myrow[1]));
				$major_title_url = str_replace(" ", "_", $major_title);
				print "<li><a href='/ic/collection/$c/$major_title_url/'>$major_title";
				if($editor) { print " - $major_code"; }
				print "</li></a>";
				}
			print "</DIV>";
			}

		## Get list of minors for a specific category
		elseif($major_title_url && !$minor_title_url) {
			print "<DIV ID=navcontainer><UL ID=navlist>";

			// Get subtopic IDs
			$result_minor_id = mysqli_query($db, "select subtopic from topic_assignment where topic = '$major_id' group by subtopic");
			while ($myrow_minor_id = mysqli_fetch_row($result_minor_id)) {
				## Get Minors Title
				list($minor_id,$minor_title,$minor_code)=split("\|",minor_info_by_code($db,$myrow_minor_id[0]));
				$minor_title_url=str_replace(" ","_", $minor_title);
				$minor_title_url=str_replace("&","&amp;", $minor_title_url);
				$major_title_url=str_replace(" ","_", $major_title);

				print "<li><a href=\"/ic/collection/$c/$major_title_url/$minor_title_url/\">$minor_title";
				if($editor) { print " - $minor_code"; }
				print "</a></li>";
				}
			print "</DIV>";
			}

		// List images for a major and minor
		elseif($major_title_url && $minor_title_url && !$sid) {
			print "<table>";

			// Get Minors Title
			$query = "select pid from topic_assignment where topic = '$major_id' and subtopic = '$minor_id'";
			$result_major_title = mysqli_query($db, $query);
			while ($myrow_major_title = mysqli_fetch_row($result_major_title)) {

				$result = mysqli_query($db, "select * from images where id = '$myrow_major_title[0]' and collection = '$collection_id' and (public = '1' $edit) limit 1");

				while($myrow = mysqli_fetch_assoc($result)) {
					$short_desc = $myrow['title'];
					$img = $myrow['id'].".html";
					//$old_num = $myrow[8];
					$sid = $myrow['id'];
					$url = "/ic/collection/$c/$major_title_url/$minor_title_url/$img";
					$img = "/marchandslides.bak/".$myrow['thumbnail'];
					$url = "/marchandslides.bak/".$myrow['file'];

					thumbnail($url, $img, $myrow['title'], $myrow['citation']."<br /><hr style=\"border-color: #999;\" /><br />".$myrow['card'], $major_code, $minor_code);

					if($myrow['public'] == '1')
						$shown="yes";
					else
						$shown="no";

					if($count > 4) {
						print "</tr><tr>";
						$count = 0;
					}

					$count++;
					}
				}
			print "</table>";
			}

		else if($sid) {
			show_indi_slide($db, $sid);
		}

	} else {
		print "<H1>Browse by:</H1>";

		// Show Collections
		print "<hr />Collection";
		print "<div id=\"navcontainer\">";
		print "<ul id=\"navlist\">";
		$result = mysqli_query($db, "select * from collections order by name");
		while ($myrow = mysqli_fetch_assoc($result)) {
			$count = 0;

			// Get image count
			$result_count = mysqli_query($db, "select count(*) from images where collection = '".$myrow['id']."' and (public = '1' $edit)");
			while($myrow_count = mysqli_fetch_row($result_count)) {
				$count = $myrow_count[0];
			}

			if($count) {
				print "<li><a href=\"/ic/collection/".$myrow['code']."/\">".$myrow['name']." - $count images</a></li>";
			}
		}
		print "</ul></div>";

		## State Standards
		print "<hr />State Standard";
		print "<div id=\"navcontainer\">";
		print "<ul id=navlist>";
		$result = mysqli_query($db, "select * from standards_cal group by grade_id order by grade_id, standard_id, sub_standard_num");
		while ($myrow = mysqli_fetch_row($result)) {
			$c = 0;
			## Get current count
			$result_count = mysqli_query($db, "SELECT current_count from standards_cal where grade_id = '$myrow[1]'");
			while($myrow_count = mysqli_fetch_row($result_count)) {
				$c = $myrow_count[0] + $c;
			}

			$count = 0;
			$count = $standard_count[$myrow[1]];
			if($c) {
				print "<li><a href=\"/ic/standard/$myrow[1]/\">$myrow[1] - $c images</li>";
			}
		}
		print "</ul></div>";
	}

	$end = date("s");
	$s = $end - $start;
?>
		</div><!--end content-->

		<div id="sideCol">
			<p class="bodyText">
				<?php
					include "../snippets/sidequote.php";
				?>
			</p>
		</div> <!--end sideCol-->

		<div style="clear: both;"></div>
	</div> <!--end innerWrapper2-->
</div> <!--end innerWrapper-->

<?php
	include "../snippets/footer.htm";
