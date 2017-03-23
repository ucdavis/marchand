<?php
	if(isset($redirected) && isset($text)) {
		if(!$redirected && $text) {
			$url = "/ic/search/$text/$standard/";
			header("Location: $url");
		}
	}

	include_once('../vendor/CAS-1.3.4/CAS.php');

	phpCAS::client(CAS_VERSION_2_0, "cas.ucdavis.edu", 443, "cas");
	// phpCAS::setCasServerCACert("/etc/pki/tls/cert.pem");
	//$cas_in = phpCAS::checkAuthentication(); // gateway / passive

	require_once "../classes/Connection.php";
	require_once '../app/ic.inc.php';
	require_once '../html/connect.inc';

	include "../snippets/header.htm";
	include "../snippets/navigation.php";

	$Connection = new Connection();
	$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
	mysqli_select_db($db, "image_archive");

	$quote = $Connection->GetRandomQuote();

	// Anybody logged in?
	if(phpCAS::isAuthenticated()) {
		$uid = phpCAS::getUser();
	} else {
		$uid = false;
	}

	if(is_admin($uid)) {
		$editor = 1;
	} else {
		$editor = 0;
	}
?>

<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/gallery.js"></script>

<div id="innerWrapper">
	<div id="innerWrapper2">
		<div id="content">

			<h1><a href="http://historyproject.ucdavis.edu/ic/">Image Archive</a> > <a href="javascript:history.go(-1);">Search Results</a> > Image Detail</h1>

			<?php
				$image = fetch_image($db, $_GET['id']);
			?>

			<div style="float: left; width: 135px;">
				<a href="http://historyproject.ucdavis.edu/marchandslides.bak/<?php echo $image['file']; ?>"><img src="http://historyproject.ucdavis.edu/ic/get_image.php?id=<?php echo $image['id']; ?>&thumb" alt="" width="125" /></a><br />
				<p><i>(click to view larger)</i></p>
				<input type="button" value="Download Now" onClick="window.location.href='http://historyproject.ucdavis.edu/ic/download_image.php?id=<?php echo $image['id']; ?>'" />
				<?php
					if($editor) {
						echo "<br /><input type=\"button\" value=\"Edit\" onClick=\"javascript:window.location.href = 'http://historyproject.ucdavis.edu/secure/admin/edit_slides.php?id=".$image['id']."'\" />";
					}
				?>
			</div>
			<div style="margin-left: 135px;">
				<h4><?php echo $image['title']; ?></h4>
				<p><em>From the Collection of <?php echo $image['collection']; ?></em></p>
				<p><?php echo $image['card']; ?></p>
				<p><em>Topic(s):</em><br /></p>
				<ul style="margin-left: 1em;">
				<?php
					$topics = fetch_image_topics($db, $image['id']);

					foreach($topics as $topic) {
						echo "<li>".$topic['title']."</li>";
					}
					if(count($topics) == 0) echo "<li>No topics currently assigned.</li>";
				?>
				</ul>

				<p><em>Regions(s):</em><br /></p>
				<ul style="margin-left: 1em;">
				<?php
					$regions = fetch_image_regions($db, $image['id']);

					foreach($regions as $region) {
						echo "<li>".$region['title']."</li>";
					}
					if(count($regions) == 0) echo "<li>No regions currently assigned.</li>";
				?>
				</ul>

				<p><em>California Standard(s):</em><br /></p>
				<ul style="margin-left: 1em;">
				<?php
					$standards = fetch_standards_ca($db, $image['id']);

					foreach($standards as $standard) {
						echo "<li>".$standard['label']."</li>";
					}
					if(count($standards) == 0) echo "<li>No California Standards currently assigned.</li>";
				?>
				</ul>

				<p><em>National Standard(s):</em><br /></p>
				<ul style="margin-left: 1em;">
				<?php
					$standards = fetch_standards_nat($db, $image['id']);

					foreach($standards as $standard) {
						echo "<li>".$standard['label']."</li>";
					}
					if(count($standards) == 0) echo "<li>No National Standards currently assigned.</li>";
				?>
				</ul>

				<p><em>Citation(s):</em><br /></p>
				<p><?php echo $image['citation']; ?></p>
			</div>

		</div><!--end content-->

		<div id="sideCol">
	        <?php include 'search_sidebar.inc.php'; ?>
		</div> <!--end sideCol-->

		<div style="clear: both;"></div>
	</div> <!--end innerWrapper2-->
</div> <!--end innerWrapper-->

<?php
	include "../snippets/footer.htm";
