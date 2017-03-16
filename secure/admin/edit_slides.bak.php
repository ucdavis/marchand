<?php
## Here are the possible fields in the KHMaster database
## ImageID, Major, Minor, SlideNum, CardText, Citation, 
## Collection, DownloadTracker, Collection2, Current

#####################################
########## VARIABLES BEGIN ##########
#####################################
## The database, with username and password
	$db = mysql_connect("localhost", "hc", "admin");

## Other variables
	$font=5;
	$tablewidth="850";
	$me="edit_slides.php";
	$title="<TITLE>Slide Editing</TITLE>";

## The connection variables
	$count=0;

## The database
	mysql_select_db("ImageCollections",$db);

$redirect=str_replace("\\","",$redirect);

## Add a major
	if($W == 'addmaj') {
		$q = "INSERT INTO Majors VALUES(NULL,'','$New','US')";
		#print $q;
		print "Added Major";
		mysql_query("$q",$db);
		}

## Add a minor
	if($W == 'addmin') {
		$q = "INSERT INTO Minors VALUES(NULL,'ZZ','Z','$New')";
		#print $q;
		print "Added Minor";
		mysql_query("$q",$db);
		}

## Done editing a slide
	if($ID && $W == 'Editdone') {
		#print ("<CENTER><FONT SIZE=$font>Entry Updated</FONT></CENTER><P>");
		mysql_select_db("ImageCollections",$db);
		#$CardText =  ereg_replace ("'", "\'", $CardText);
		#$Citation =  ereg_replace ("'", "\'", $Citation);
		$query = "UPDATE Images SET CardText='$CardText',Citation='$Citation',Current='$Current' WHERE ImageID='$ID' LIMIT 1";
		mysql_query("$query",$db);
		#print "$query";

		## Update the major and minor
		$q = "Update Majors_Minors SET Minor='$Min',Major='$Maj' WHERE PID = '$ID' LIMIT 1";
		#print $q;
		mysql_query("$q",$db);

		## Update the standard text, if needed
		if($StateStand != "None") {
			$q = "INSERT INTO Standards_Data VALUES(NULL,'0','$ID','$StateStand')";
			#print $q;
			mysql_query("$q",$db);
			}

		## Redirect to where they came from
		header("Location: http://historyproject.ucdavis.edu$redirect#$ID");
		}

## Edit a slide
	else { print $title; editslide($db,$sid,$redirect); }

?>

<html>
<head>
	<title>Slide Editing</title>
	<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="edit_slides.js"></script>
</head>
<body>

<?php
#($sid && ($W == 'edit' || $W == 'addmin' || $W == 'addmaj') ) { print $title; editslide($db,$sid,$redirect); }

## Just list the slides
	#else{listslides();}

###############################
########## FUNCTIONS ##########
###############################
function editslide($db,$ID,$redirect) {
	global $me,$tablewidth;
	#mysql_select_db("HistoryProject",$db);
#print "$sid,$ID,$redirect";

	$redirect=str_replace("\'","'",$redirect);

	print("<FORM STYLE='font-family:Arial' METHOD=POST ACTION=$me?ID=$ID&W=Editdone><CENTER>");
	print "<INPUT TYPE=HIDDEN NAME=redirect VALUE=\"$redirect\">";
	print("<TABLE BORDER=1 WIDTH='$tablewidth'>");

	$result = mysql_query("SELECT * FROM Images WHERE ImageID = '$ID'",$db);

	while ($myrow = mysql_fetch_row($result)) {

		list($major_id,$minor_id)=split("\t",major_minor($db,$ID));
 		list($major_title,$major_code)=split("\|",majortitle($db,$major_id));
		list($minor_title,$minor_code)=split("\|",minortitle($db,$minor_id));

		print "<TR BGCOLOR=EEEEEE VALIGN=TOP><TD ALIGN=CENTER><FONT SIZE=5>Slide Location / Name: $myrow[1] / $myrow[2] ($major_code-$minor_code)</FONT></TD></TR>\n";
		print "<TR VALIGN=TOP><TD>CardText:<BR><TEXTAREA STYLE='font-family:Arial' NAME=CardText ROWS=6 COLS=100>$myrow[3]</TEXTAREA></TD></TR>\n";
		print "<TR VALIGN=TOP><TD>Citation:<BR><TEXTAREA STYLE='font-family:Arial' NAME=Citation ROWS=4 COLS=100>$myrow[4]</TEXTAREA></TD></TR>\n";
		print "<TR VALIGN=TOP><TD ALIGN=LEFT>\n";

		print "Major: ";
		print "<SELECT NAME=Maj>";
		print "<OPTION SELECTED VALUE=$major_id>$major_title";

		## Get the Majors
		$result_major = mysql_query("SELECT * FROM Majors GROUP BY MajorTitle ORDER BY MajorTitle",$db);
		while ($myrow_major = mysql_fetch_row($result_major)) {
			print "<OPTION VALUE=$myrow_major[0]>$myrow_major[2]";
			}

		print "</SELECT>Minor: ";
		print "<SELECT NAME=Min>";
		print "<OPTION SELECTED VALUE=$minor_id>$minor_title";

		## Get the Minors
		$result_minor = mysql_query("SELECT * FROM Minors GROUP BY MinorText ORDER BY MinorText",$db);
		while ($myrow_minor = mysql_fetch_row($result_minor)) {
			print "<OPTION VALUE=$myrow_minor[0]>$myrow_minor[3]";
			}

		print "</SELECT>";

		print "</TD></TR>";

		print "<TR VALIGN=TOP><TD ALIGN=LEFT>";
		print "Shown on Website? (1=yes,0=no)<SELECT NAME=Current>";
		print "<OPTION SELECTED VALUE=$myrow[6]>$myrow[6]";
		print "<OPTION>0<OPTION>1";
		print "</SELECT>";

		print "</TD></TR>";

		## List state standards
		print "<TR><TD><B>Current State Standards:</B><BR>";
		## Find if we already have a standard for this image
		$q="SELECT * FROM Standards_Data WHERE ImgID = '$ID'";
		#print $q;
		$result_haveit = mysql_query("$q",$db);
		while ($myrow_haveit = mysql_fetch_row($result_haveit)) { 
			list($gid,$sid,$ssid,$stext)=split("\|",standardinfo($db,$myrow_haveit[3]));
			print "$sid - $ssid - $stext<BR>";
			}
		#print $havestandard;
#mysql_select_db("ImageCollections",$db);
		print "<SELECT NAME=StateStand><OPTION VALUE='None'>Select to Add a New Standard";
		#mysql_select_db("Standards",$db);
		$q="SELECT * FROM Standards_Cal ORDER BY GradeID,StandardID,SubStandardNum";
		$result_standards = mysql_query("$q",$db);
		while ($myrow_standards = mysql_fetch_row($result_standards)) {
			#if($havestandard == $myrow_standards[0]) { $s="SELECTED"; }
			#else { $s=""; }
			$stand=substr($myrow_standards[4],0,80);
			print "<OPTION VALUE='$myrow_standards[0]' $s>$myrow_standards[2] $myrow_standards[3] - $stand...\n";
			}
		#mysql_select_db("ImageCollections",$db);
		print "</SELECT></FORM></TD></TR>";
		print "<tr><td><input type=\"button\" value=\"Rotate Left\" onClick=\"javascript:rotate('/marchandslides.bak/$myrow[1]/images/$myrow[2]', -1);\" /> <input type=\"button\" value=\"Rotate Right\" onClick=\"javascript:rotate('/marchandslides.bak/$myrow[1]/images/$myrow[2]', 1);\" /><input type=\"button\" value=\"Flip Vertical\" onClick=\"javascript:flip('/marchandslides.bak/$myrow[1]/images/$myrow[2]');\" /></td></tr>";
		print "<TR><TD><INPUT TYPE=SUBMIT VALUE='Submit-->>'></TD></TR>";
		print "</TABLE>";

		print ("<CENTER><IMG BORDER=5 SRC='/marchandslides.bak/$myrow[1]/images/$myrow[2]'>");

		print "<P><TABLE><TR><TD BGCOLOR=DDDDDD>";
		print "<FORM METHOD=POST ACTION=?sid=$ID&redirect=$redirect>Major: <INPUT TYPE=RADIO NAME=W VALUE=addmaj> Minor: <INPUT TYPE=RADIO NAME=W VALUE=addmin>";
		print "<INPUT TYPE=TEXT NAME=New SIZE=30>";
		print "<INPUT TYPE=SUBMIT VALUE='Add It'>";
		print "</TD></TR></TABLE>";
		}
	}

function standardinfo($db,$sid) {
	#mysql_select_db("Standards",$db);
	$q="SELECT * FROM Standards_Cal WHERE ID = '$sid' LIMIT 1";
	#print $q;
	$result_standards = mysql_query("$q",$db);
	while ($myrow_standards = mysql_fetch_row($result_standards)) {
		$new=$myrow_standards[1] . "|" . $myrow_standards[2] . "|" . $myrow_standards[3] . "|" . $myrow_standards[4];
		}
	#mysql_select_db("HistoryProject",$db);
	return $new;
	}

## Get the Major Title based on ID
function majortitle($db,$major_id) {
	$result = mysql_query("SELECT MajorTitle,Major FROM Majors WHERE ID = '$major_id'",$db);
	while ($myrow = mysql_fetch_row($result)) { $new=$myrow[0]."|".$myrow[1]; return $new;}
	}

## Get the Minor Title based on ID
function minortitle($db,$minor_id) {
	$result = mysql_query("SELECT MinorText,MinorCode FROM Minors WHERE ID = '$minor_id'",$db);
	while ($myrow = mysql_fetch_row($result)) { $new=$myrow[0]."|".$myrow[1]; return $new;}
	}

## Get Major and Minor IDs
function major_minor($db,$sid) {
	$result = mysql_query("SELECT Major,Minor FROM Majors_Minors WHERE PID = '$sid'",$db);
	while ($myrow = mysql_fetch_row($result)) { $new=$myrow[0]."\t".$myrow[1]; }
	return $new;
	}

?>


</body>
</html>

