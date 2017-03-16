<?
if(!$redirected && $text) {
	$url = "/ic/search/$text/$standard/";
	#print $url;
	header("Location: $url"); 

	}

?>

<?php
require_once "../n/classes/Connection.php";
$Connection = new Connection();

$quote = $Connection->GetRandomQuote();


include "../n/snippets/header.htm"; 
include "../n/snippets/navigation.php";
 ?>
<div id="innerWrapper">
<div id="innerWrapper2">

<div id="content">


<!--<TITLE>History Project Image Collection</TITLE>

<STYLE TYPE="text/css">@import "/ic/ic.css";</STYLE>
-->
<?php

## Mess with the user, this is totally stupid
list($uid)=split("-",$_COOKIE["AuthUser"]);
$redirect=$_SERVER["REDIRECT_URL"];

if($uid == 'safutrel' || $uid == 'kev') { $editor=1; $edit="OR Current = '0'"; }
else { $editor=0; }

$db = mysql_connect("localhost", "hc", "admin");
mysql_select_db("ImageCollections",$db);

## Get titles and codes of stuff
$sub_s=rtrim($sub_s,"/");
list($s_id,$sub_s)=split("_",$sub_s);
list($sub_s,$sid)=split("/",$sub_s);
list($sid)=split("\.",$sid);

## Get the major,minor titles with underscores replaces spaces
if(!$sid) { list($major_title_url,$minor_title_url,$sid)=split("/",$major_minor); }
$major_title=str_replace("_"," ", $major_title_url);
$minor_title=str_replace("_"," ", $minor_title_url);

## If a collection is listed, get the ID, code and name
if($c) { list($collection_id,$collection_title,$collection_code)=split("\|",collection_info($db,$c));}

if($minor_title_url) { list($minor_id,$minor_title,$minor_code)=split("\|",minor_title($db,$minor_title)); }
if($major_title_url) { list($major_id,$major_title,$major_code)=split("\|",major_title($db,$major_title)); }

## Search for text
if($text) { search($db,$text,$standard); }

elseif($s) {
	
	$bg="#EEEEEE";
	## Print some fancy title
	print "<DIV ID=ic_title>";
	$ss=$s_id . "_" . $sub_s;
	print "<B><A HREF=/ic/>Image Collection</A></B> / <A HREF=/ic/standard/$s/>$s</A> ";
	if($sub_s) { print "/ <A HREF=/ic/standard/$s/$ss/>$s_id / $sub_s</A> "; }
	else { print "/ All Standards "; }
	if($sid) { print " / $sid"; }
	print "</DIV>";

	if(!$sub_s && !$sid) {
		$sub_standard_count=standard_count($db,"sub");
		#print "<P>Browse by:<P>";
		## Show Collections
		#print "Standard
		print "<TABLE WIDTH=100%><TR BGCOLOR=557799><TD>Standard</TD><TD>Images</TD><TD>Description</TD></TR>";
		$result = mysql_query("SELECT * FROM Standards_Cal  WHERE GradeID = '$s' ORDER BY GradeID, StandardID, SubStandardNum",$db);
		while ($myrow = mysql_fetch_row($result)) {
			$count=$sub_standard_count[$myrow[0]];
			#if(!$count) { print "<TR BGCOLOR=$bg VALIGN=TOP><TD NOWRAP>$myrow[2] - $myrow[3]</TD><TD>0</TD><TD>$myrow[4]</TD></TR>"; }
			if($count) { print "<TR BGCOLOR=$bg VALIGN=TOP><TD NOWRAP><A HREF=/ic/standard/$s/$myrow[2]_$myrow[3]/>$myrow[2] - $myrow[3]</A></TD><TD>$count</TD><TD>$myrow[4]</TD></TR>"; }
			if($bg=="#EEEEEE") { $bg="#FFFFFF"; }
			else { $bg="#EEEEEE"; }
			}
		print "</TABLE>";
		}
	elseif($sub_s && !$sid) {
	print "<TABLE BORDER=0>";
	## Get standard ID from standard
		$q="SELECT * FROM Standards_Cal WHERE StandardID = '$s_id' AND SubStandardNum = '$sub_s'";

		$result = mysql_query("$q",$db);
		while ($myrow = mysql_fetch_row($result)) { $sid=$myrow[0]; }

		$result = mysql_query("SELECT * FROM Standards_Data WHERE SID ='$sid'",$db);
		while ($myrow = mysql_fetch_row($result)) {

			$result_details = mysql_query("SELECT * FROM Images WHERE ImageID = '$myrow[2]' AND (Current = '1' $edit) LIMIT 1",$db);
			while ($myrow_details = mysql_fetch_row($result_details)) {
				$short_desc=substr($myrow_details[3],0,100) . "...";
				$img=$s_id . "_" . $sub_s . "/$myrow_details[0].html";
				if($myrow_details[2] != 'IMG0000.jpg') { 
					$old_num=$myrow_details[8];
					$url="/ic/standard/$s/$img";
					$img="/marchandslides.bak/$myrow_details[1]/thumbnails/$myrow_details[2]";
					thumbnail($url,$img,$myrow_details[3],$myrow_details[4]); 
					}
				$count++;
				}
			}
		print "</TABLE>";
		}

	elseif($sid) { show_indi_slide($db,$sid); }

	}

elseif($c) {
	## Print some fancy title
	print "<DIV ID=ic_title><B><A HREF=/ic/>Image Collection</A></B> / <A HREF=/ic/collection/$collection_code/>$collection_title</A> ";
	if($major_title) { print "/ <A HREF=/ic/collection/$collection_code/$major_title_url/>$major_title</A> "; }
	else { print "/ All Majors "; }
	if($minor_title) { print "/ <A HREF=/ic/collection/$collection_code/$major_title_url/$minor_title_url/>$minor_title</A> "; }
	else { print "/ All Minors "; }
	if($sid) { print " / $sid"; }

	print "</DIV>";


	## List major categories for a collection
	if(!$major_title_url && !$minor_title_url) {
		print "<DIV ID=navcontainer><UL ID=navlist>";

		$found=array();

		$q="SELECT ImageID FROM Images WHERE Collection = '$collection_id'";
		#print "$q<BR>";
		$result = mysql_query("$q",$db);
		while ($myrow = mysql_fetch_row($result)) {
			$new=$new . "OR PID = '$myrow[0]' ";
			#list($major_id,$major_title,$major_code)=split("\|",major_info_by_code($db,$myrow_maj[2]));
			## Convert title to url friendly text
			#$major_title_url=str_replace(" ","_", $major_title);
			#print "<LI><A HREF='/ic/collection/$c/$major_title_url/'>$major_title";
			}
		$new = "SELECT Major FROM Majors_Minors WHERE ID = '0' " . $new . "GROUP BY Major";
		#print $new;
		#$q="SELECT ImageID FROM Images WHERE Collection = '$collection_id'";
		#print "$q<BR>";
		$result = mysql_query("$new",$db);
		while ($myrow = mysql_fetch_row($result)) {
			list($major_id,$major_title,$major_code)=split("\|",major_info_by_code($db,$myrow[0]));
			$major_title_url=str_replace(" ","_", $major_title);
			print "<LI><A HREF='/ic/collection/$c/$major_title_url/'>$major_title";
			if($editor) { print " - $major_code"; }
			print "</A>";
			}
		## List the Majors
		#$result_maj = mysql_query("SELECT * FROM Majors_Minors",$db);
		#  GROUP BY Major
		#while ($myrow_maj = mysql_fetch_row($result_maj)) {

			## Get a sample image from this and see which collection it is in
			#$q="SELECT * FROM Images WHERE ImageID = '$myrow_maj[1]' AND Collection = '$collection_id'";
			#print "$q<BR>";
			#$result = mysql_query("$q",$db);
			#while ($myrow = mysql_fetch_row($result)) {
				## Get Major Title
				#print "Found one";
				## Stupid, fix this
				#foreach($found as $done) {
				#	if($done = '$major_title') { $done=1; }
				#	}
				#if(!$done) { array_push($found,$done); }

				#list($major_id,$major_title,$major_code)=split("\|",major_info_by_code($db,$myrow_maj[2]));
				## Convert title to url friendly text
				#$major_title_url=str_replace(" ","_", $major_title);

				#if(!$done) { 
				#	print "<LI><A HREF='/ic/collection/$c/$major_title_url/'>$major_title";
				#	if($editor) { print " - $major_code"; }
				#	print "</A>";
				#	}
				#$done=0;
				#}
			#}
		}

	## Get list of minors for a specific category
	elseif($major_title_url && !$minor_title_url) {
		print "<DIV ID=navcontainer><UL ID=navlist>";

		## Get Minor IDs
		$result_minor_id = mysql_query("SELECT Minor FROM Majors_Minors WHERE Major = '$major_id' GROUP BY Minor",$db);
		while ($myrow_minor_id = mysql_fetch_row($result_minor_id)) {
			## Get Minors Title
			list($minor_id,$minor_title,$minor_code)=split("\|",minor_info_by_code($db,$myrow_minor_id[0]));
			$minor_title_url=str_replace(" ","_", $minor_title);
			$minor_title_url=str_replace("&","&amp;", $minor_title_url);
			$major_title_url=str_replace(" ","_", $major_title);

			print "<LI><A HREF=\"/ic/collection/$c/$major_title_url/$minor_title_url/\">$minor_title";
			if($editor) { print " - $minor_code"; }
			print "</A>";
			}
		}

	## List images for a major and minor
	elseif($major_title_url && $minor_title_url && !$sid) {
		print "<TABLE BORDER=0>";
		## Get Minors Title
		#print "<TR VALIGN=TOP>";
		
		$result_major_title = mysql_query("SELECT PID FROM Majors_Minors WHERE Major = '$major_id' && Minor = '$minor_id'",$db);
		while ($myrow_major_title = mysql_fetch_row($result_major_title)) {

			$result = mysql_query("SELECT * FROM Images WHERE ImageID = '$myrow_major_title[0]'  AND Collection = '$collection_id' AND (Current = '1' $edit) LIMIT 1",$db);
			while ($myrow = mysql_fetch_row($result)) {
				#$short_desc=substr($myrow[3],0,100) . "...";
				$short_desc=$myrow[3];
				$img=$myrow[0].".html";
				$old_num=$myrow[8];
				$sid=$myrow[0];
				$url="/ic/collection/$c/$major_title_url/$minor_title_url/$img";
				$img="/marchandslides.bak/$myrow[1]/thumbnails/$myrow[2]";
				thumbnail($url,$img,$myrow[3],$myrow[4]); 

				if($myrow[6] == '1') { $shown="yes"; }
				else { $shown="no"; }
				if($count>4) { print "</TR><TR>";$count=0; }
				
				$count++;
				}
			}
		print "</TABLE>";
		}

	elseif($sid) { show_indi_slide($db,$sid); }

	}

else { mainmenu($db,$standard_count,$sub_standard_count); }

###################################
########## The Functions ##########
###################################
function show_indi_slide($db,$sid) {

	## Update download count
	$result = mysql_query("UPDATE Images SET Views=Views+1 WHERE ImageID = '$sid' AND Current = '1' LIMIT 1",$db);

	## Pull out image info
	$q="SELECT * FROM Images WHERE ImageID = '$sid' LIMIT 1";
	#print $q;
	$result = mysql_query("$q",$db);
	while ($myrow = mysql_fetch_row($result)) {

		## Get the standards
		$result_stand = mysql_query("SELECT * FROM Standards_Data WHERE ImgID = '$sid'",$db);
		while ($myrow_stand = mysql_fetch_row($result_stand)) {
			## Get name of standard
			$result_stand_name = mysql_query("SELECT * FROM Standards_Cal WHERE ID = '$myrow_stand[3]'",$db);
			while ($myrow_stand_name = mysql_fetch_row($result_stand_name)) {
				$stand=$myrow_stand_name[2]."-".$myrow_stand_name[3]." ".$myrow_stand_name[4];
				}
			}

		if($myrow[2] != 'IMG0000.jpg') {
			print "<TABLE BORDER=0>
				<TR><TD COLSPAN=2><A HREF=/marchandslides.bak/$myrow[1]/images/$myrow[2]><IMG BORDER=0 WIDTH=600 SRC='/marchandslides.bak/$myrow[1]/images/$myrow[2]'></A></TD></TR>
				<TR><TD BGCOLOR=CCCCCC>Notes about this image:</TD><TD>$myrow[3]</TD></TR>
				<TR><TD BGCOLOR=CCCCCC>Citation:</TD><TD><I>$myrow[4]</I></TD></TR>";
				if($stand) { print "<TR><TD BGCOLOR=CCCCCC>Standard:</TD><TD><I>$stand</I></TD></TR>"; }
			print "</TABLE></CENTER>";
			}
		}
	}

function mainmenu($db) {
	global $edit;

	## Count the standards
	$standard_count=standard_count($db,"standard");
	#$sub_standard_count=standard_count($db,"sub");
	
	## Search box
		print "<FORM ACTION=><INPUT TYPE=TEXT NAME=text SIZE=50>";
		print "<SELECT NAME=standard><OPTION VALUE=any>Any Standard";

		$result = mysql_query("SELECT * FROM Standards_Cal  ORDER BY GradeID, StandardID, SubStandardNum",$db);
		while ($myrow = mysql_fetch_row($result)) {
			$link=$myrow[2]."_".$myrow[3];
			print "<OPTION VALUE=$link>$myrow[2] - $myrow[3]</A>";
			}

		print "</SELECT><INPUT TYPE=SUBMIT VALUE='Search Slides'></FORM><P>";

		mysql_select_db("ImageCollections",$db);

		print "<H1>Browse by:</H1>";
		## Show Collections
		print "<HR>Collection";
		print "<DIV ID=navcontainer>";
		print "<UL ID=navlist>";
		$result = mysql_query("SELECT * FROM Collections ORDER BY Name",$db);
		while ($myrow = mysql_fetch_row($result)) {
			$count=0; 
			## Get image count

			$result_count = mysql_query("SELECT COUNT(*) FROM Images WHERE Collection = '$myrow[0]' AND (Current = '1' $edit)",$db);
			while ($myrow_count = mysql_fetch_row($result_count)) { $count=$myrow_count[0]; }

#			if(!$count) { print "<LI>$myrow[1] - $count images"; }
			if($count) { print "<LI><A HREF=/ic/collection/$myrow[2]/>$myrow[1] - $count images</A>"; }
			}
		print "</DIV>";

		## State Standards
		print "<HR>State Standard";
		print "<DIV ID=navcontainer>";
		print "<ul id=navlist>";
		$result = mysql_query("SELECT * FROM Standards_Cal  GROUP BY 'GradeID' ORDER BY GradeID, StandardID, SubStandardNum",$db);
		while ($myrow = mysql_fetch_row($result)) {
			$count=0;
			$count=$standard_count[$myrow[1]];
			if($count) { print "<LI><A HREF=/ic/standard/$myrow[1]/>$myrow[1] - $count images";  }
			}
		print "<UL></DIV>";
	}

function minor_title($db,$mid) {
	$result = mysql_query("SELECT ID,MinorText,MinorCode FROM Minors WHERE MinorText = '$mid'",$db);
	while ($myrow = mysql_fetch_row($result)) { 
		return $myrow[0] . "|" . $myrow[1] . "|" . $myrow[2];
		}
	}

function major_title($db,$mid) {
	$result = mysql_query("SELECT ID,MajorTitle,Major FROM Majors WHERE MajorTitle = '$mid'",$db);
	while ($myrow = mysql_fetch_row($result)) { 
		return $myrow[0] . "|" . $myrow[1] . "|" . $myrow[2];
		}
	}

function major_info_by_code($db,$mid) {
	$result = mysql_query("SELECT ID,MajorTitle,Major FROM Majors WHERE ID = '$mid'",$db);
	while ($myrow = mysql_fetch_row($result)) { 
		return $myrow[0] . "|" . $myrow[1] . "|" . $myrow[2];
		}
	}

function minor_info_by_code($db,$mid) {
	$result = mysql_query("SELECT ID,MinorText,MinorCode FROM Minors WHERE ID = '$mid'",$db);
	while ($myrow = mysql_fetch_row($result)) { 
		return $myrow[0] . "|" . $myrow[1] . "|" . $myrow[2];
		}
	}

function collection_title($db,$cid) {
	$result = mysql_query("SELECT ID,Name FROM Collections WHERE Code = '$cid'",$db);
	while ($myrow = mysql_fetch_row($result)) { 
		return $myrow[0] . "|" . $myrow[1];
		}
	}

function collection_info($db,$cid) {
	$result = mysql_query("SELECT * FROM Collections WHERE Code = '$cid'",$db);
	while ($myrow = mysql_fetch_row($result)) { 
		return $myrow[0]."|".$myrow[1]."|".$myrow[2];
		}
	}

## Get the major and minor for an image ID
function major_minor_by_id($db,$pid) {
	$result = mysql_query("SELECT Major,Minor FROM Majors_Minors WHERE PID = '$pid' LIMIT 1",$db);
	while ($myrow = mysql_fetch_row($result)) {
		list($mid,$major_title)=split("\|",major_info_by_code($db,$myrow[0]));
		list($mid,$minor_title)=split("\|",minor_info_by_code($db,$myrow[1]));
		return $major_title . "|" . $minor_title;
		}
	}

## Print a thumbnails
function thumbnail($url,$img,$text,$cit) {
	global $uid,$sid,$redirect,$minor_code,$major_code,$old_num,$editor;

	$redirect=str_replace("\'","&#39;",$redirect);

	#print "<DIV ID=ic_thumb>";
	if($uid == 'kev' || $uid == 'safutrel') { 
		#$img = str_replace("thumbnails","images", $img); 
		#$s="WIDTH=200"; 
		print "<TR><TD><A NAME=$sid></A></TD></TR>"; 
		}
	print "<TR VALIGN=TOP BGCOLOR=EEEEEE><TD><CENTER>";
	print "<DIV CLASS=wrap1><DIV CLASS=wrap2><DIV CLASS=wrap3>";
	print "<A HREF=$url><IMG BORDER=0 $s SRC='$img'></A>";
	print "</DIV></DIV></DIV>";
	print "</TD>";

	if(!$old_num) { $old_num=substr(substr($img,-9),0,5); }
	if($editor) { print "<TD ><A HREF=/secure/admin/edit_slides.php?sid=$sid&W=edit&redirect=$redirect>edit<BR>$major_code-$minor_code-$old_num</A></TD>"; }
	print "<TD BGCOLOR=AAAAAA>$text<P><I>$cit</I></TD></TR>";
	}

function search($db,$search_words,$standard) {

	$standard=rtrim($standard,"/");
	print "Searching for $search_words in <B>$standard</B> standard<P>";

	## If a standard is needed, find the ID of it, put in $sid
	if($standard != 'any') {
		list($s_id,$sub_s)=split("_",$standard);
		$result = mysql_query("SELECT ID FROM Standards_Cal WHERE StandardID = '$s_id' AND SubStandardNum = '$sub_s' LIMIT 1",$db);
		while ($myrow = mysql_fetch_row($result)) { $sid=$myrow[0]; }
		}

	print "<TABLE WIDTH=100%>";

	## Find matching words from image text
	$result = mysql_query("SELECT * FROM Images WHERE CardText LIKE '%$search_words%' AND Current = '1'",$db);
	while ($myrow = mysql_fetch_row($result)) {

		## Get the standard for this one, if not found, don't show the image
		$s="";
		if($standard != 'any') { $result_s = mysql_query("SELECT * FROM Standards_Data WHERE ImgID ='$myrow[0]' && SID = '$sid'",$db); }
		else { $result_s = mysql_query("SELECT * FROM Standards_Data WHERE ImgID ='$myrow[0]'",$db); }
		while ($myrow_s = mysql_fetch_row($result_s)) { $s="$myrow_s[3]"; }

		if($s) {
			list($major_title,$minor_title)=split("\|",major_minor_by_id($db,$myrow[0]));
			$minor_title_url=str_replace(" ","_", $minor_title);
			$major_title_url=str_replace(" ","_", $major_title);

			## Get the collection
			$result_collection = mysql_query("SELECT Code FROM Collections WHERE ID = '$myrow[5]'",$db);
			while ($myrow_collection = mysql_fetch_row($result_collection)) { $c=$myrow_collection[0]; }

			$short_desc=$myrow[3];
			if($myrow[2] != 'IMG0000.jpg') { 
				$url="/ic/collection/$c/$major_title_url/$minor_title_url/$myrow[0].html";
				$img="/marchandslides.bak/$myrow[1]/thumbnails/$myrow[2]";
				thumbnail($url,$img,$myrow[3],$myrow[4]); 
				}
			}
		}
	}

function standard_count($db,$what) {
	global $edit;

	$result = mysql_query("SELECT * FROM Standards_Cal GROUP BY GradeID",$db);
	while ($myrow = mysql_fetch_row($result)) {
		$subs="";

		## Then get all the sub standards
		$result_sub = mysql_query("SELECT * FROM Standards_Cal WHERE GradeID='$myrow[1]'",$db);
		while ($myrow_sub = mysql_fetch_row($result_sub)) {
			$subs=$subs . " OR SID = '$myrow_sub[0]'";

			## Get the count for this sub_standard
			$q="SELECT COUNT(*) FROM Standards_Data, Images WHERE Images.ImageId = Standards_Data.ImgID AND (Current = '1' $edit) AND SID = '$myrow_sub[0]'";
			$result_sub_s = mysql_query("$q",$db);
			while ($myrow_sub_s = mysql_fetch_row($result_sub_s)) { $sub_standard_count[$myrow_sub[0]] = $myrow_sub_s[0]; $total=$total+$myrow_sub_s[0]; }
			}
		## Take out the first OR
		#$subs=substr($subs,3);

		## Get the count for this standard
		#$result_sub = mysql_query("SELECT COUNT(*) FROM Standards_Data WHERE ($subs)",$db);
		#while ($myrow_sub = mysql_fetch_row($result_sub)) { $count=$myrow_sub[0]; }

		$standard_count[$myrow[1]] = $total;
		$total=0;
#count;
		}

		if($what == 'standard') { return $standard_count; }
		else { return $sub_standard_count; }
	}
?>

</div><!--end content-->

<div id="sideCol">
	<p class="bodyText">
		<?php include "../n/snippets/sidequote.php"; ?>
	</p>
</div><!--end sideCol-->

<div style="clear:both;"></div>
</div><!--end innerWrapper2-->
</div><!--end innerWrapper-->

<?php include "../n/snippets/footer.htm"; ?>



