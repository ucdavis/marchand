<?php
	if(isset($redirected) && isset($text)) {
		if(!$redirected && $text) {
			$url = "/ic/search/$text/$standard/";
			header("Location: $url");
		}
	}

	require '../vendor/autoload.php';

	phpCAS::client(CAS_VERSION_2_0, "cas.ucdavis.edu", 443, "cas");
	// phpCAS::setCasServerCACert("/etc/pki/tls/cert.pem");
	$cas_in = phpCAS::checkAuthentication(); // gateway / passive

	require_once "../classes/Connection.php";
	require_once '../app/ic.inc.php';
	require_once '../html/secure.inc.php';
	require_once '../html/connect.inc';

	include "../snippets/header.htm";
	include "../snippets/navigation.php";

	$Connection = new Connection();
	$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
	mysqli_select_db($db, "image_archive");

	$quote = $Connection->GetRandomQuote();

	// Anybody logged in?
	if($cas_in) {
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
<script type="text/javascript" src="js/jquery.timers.js"></script>
<script type="text/javascript" src="js/gallery.js"></script>
<script type="text/javascript">
	editor = <?php echo $editor; ?>;
</script>

<style type="text/css">
	.gallery-thumbnail {
		max-width: 120px;
		max-height: 120px;
		width: expression(this.width > 120 ? "120px" : true);
		height: expression(this.height > 120 ? "120px" : true);
	}
</style>

<div id="innerWrapper">
	<div id="innerWrapper2">
		<div id="content">

			<h1>Image Collection</h1>

			<?php if(!isset($_GET['bestof'])) { ?>
			<span id="intro">
				<p>
The Marchand Image Collection comprises more than 8600 images, many of which have been digitized from slides and therefore are not available elsewhere. Faculty members of the UC Davis History Department contribute images used in their teaching. A growing collection with maps, Aztec codices, early Americana, advertising posters and more. </p>

				<h4>Search and browse our image archive at the right or view a sampling of our Best images below:</h4>
				<br />
			</span>
			<?php } ?>

			<div id="gallery">
 				<?php
					if(!isset($_GET['region']) && !isset($_GET['topic']) && !isset($_GET['standard_cal']) && !isset($_GET['collection'])) {
						if(!isset($_GET['bestof'])) {
							// display the featured galleries as the default

							// fetch the featured topics
							$featured_topics = array();
							$result = mysqli_query($db, "select id, title from topics where featured=1");
							while($row = mysqli_fetch_assoc($result)) $featured_topics[] = $row;

							echo "<table><tr style=\"vertical-align: top;\">";
							$row_count = 0;

							foreach($featured_topics as $topic) {

								$result = mysqli_query($db, "select images.id as id, images.thumbnail as thumbnail, images.title as title, images.card as card, images.citation as citation from images, topic_assignments where images.id = topic_assignments.sid and topic_assignments.tid = ".mysqli_real_escape_string($db, $topic['id'])." and images.featured=1 and images.public=1 order by rand() limit 1");

								if(mysqli_num_rows($result) > 0) {
									while($row = mysqli_fetch_assoc($result)) {
										echo "<td style=\"text-align: center;\"><div id=\"".$row['id']."\" style=\"background-color: #fff; width: 125px; height: 125px; text-align: center;\"><a name=\"".$row['id']."\"></a><a href=\"/ic/index.php?bestof=".$topic['id']."\"><img class=\"gallery-thumbnail\" src=\"http://historyproject.ucdavis.edu/ic/get_image.php?id=".$row['id']."&thumb\" style=\"float: none; margin: 0;\" /></a></div><div style=\"width: 125px; clear: both; overflow: hidden;\"><h4>".$topic['title']."</h4></div>";

										echo "</td><td width=\"25\">&nbsp;&nbsp;</td>";

										$row_count++;

										if($row_count == 4) {
											echo "</tr><tr><td colspan=\"8\">&nbsp;</td></tr><tr style=\"vertical-align: top;\">";
											$row_count = 0;
										}
									}
								}
							}

							echo "</tr></table>";
						} else {
							// display a specific featured gallery

							// get the name of this topic
							$result = mysqli_query($db, "select title from topics where id='".mysqli_real_escape_string($db, $_GET['bestof'])."' limit 1");
							$row = mysqli_fetch_assoc($result);
							echo "<h1>Best of ".$row['title']."</h1><br />";

							echo "<table><tr style=\"vertical-align: top;\">";
							$row_count = 0;

							$result = mysqli_query($db, "select images.id as id, images.thumbnail as thumbnail, images.title as title, images.card as card, images.citation as citation from images, topic_assignments where images.id = topic_assignments.sid and topic_assignments.tid = ".mysqli_real_escape_string($db, $_GET['bestof'])." and images.featured=1 and images.public=1 order by rand() limit 24");

							if(mysqli_num_rows($result) > 0) {
								while($row = mysqli_fetch_assoc($result)) {
									echo "<td style=\"text-align: center;\"><div id=\"".$row['id']."\" style=\"background-color: #fff; width: 125px; height: 125px; text-align: center;\"><a name=\"".$row['id']."\"></a><a href=\"/ic/image_details.php?id=".$row['id']."\"><img class=\"gallery-thumbnail\" src=\"http://historyproject.ucdavis.edu/ic/get_image.php?id=".$row['id']."&thumb\" style=\"float: none; margin: 0;\" /></a></div><div style=\"width: 125px; clear: both; overflow: hidden;\">".$row['title']."</div>";

									echo "</td><td width=\"25\">&nbsp;&nbsp;</td>";

									$row_count++;

									if($row_count == 4) {
										echo "</tr><tr><td colspan=\"8\">&nbsp;</td></tr><tr style=\"vertical-align: top;\">";
										$row_count = 0;
									}
								}
							}

							echo "</tr></table>";
						}
					}
				?>
 			</div> <!-- gallery -->
 		</div><!--end content-->

 		<div id="sideCol">
            <div style="width:71px; overflow:hidden;"><div class="fb-like" data-href="http://www.facebook.com/pages/The-History-Project/152551101478324" data-send="false" data-width="150" data-show-faces="false"></div></div>
             <hr />
 	        <?php include 'search_sidebar.inc.php'; ?>
 		</div> <!--end sideCol-->

		<div style="clear: both;"></div>
	</div> <!--end innerWrapper2-->
</div> <!--end innerWrapper-->

<?php
	// conduct a search if redirected here for a search via javascript/GET (e.g., from image_details.php's search button)
	if(isset($_GET['region']) && isset($_GET['topic']) && isset($_GET['standard_cal']) && isset($_GET['collection'])) {
		echo "
			<script type=\"text/javascript\">
				$(\"select[name=search_term_collection]\").val(".$_GET['collection'].");
				$(\"select[name=search_term_standard_cal]\").val(".$_GET['standard_cal'].");
				$(\"select[name=search_term_region]\").val(".$_GET['region'].");
				$(\"select[name=search_term_topic]\").val(".$_GET['topic'].");
				";

		if(isset($_GET['query'])) {
			echo "$(\"input[name=search_term_query]\").val('".$_GET['query']."');";
		}

		if(isset($_GET['index']) && isset($_GET['per_page'])) {
			echo "index = ".$_GET['index'].";";
			echo "per_page = ".$_GET['per_page'].";";
			echo "search_gallery(false);";
		} else {
			echo "search_gallery(true);";
		}

		echo "</script>";
	}

	include "../snippets/footer.htm";
?>
