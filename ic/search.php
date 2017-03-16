<?php
	if(isset($redirected) && isset($text)) {
		if(!$redirected && $text) {
			$url = "/ic/search/$text/$standard/";
			header("Location: $url");
		}
	}

	require_once "../classes/Connection.php";
	require_once '../app/ic.inc.php';
	require_once '../html/connect.inc';

	include "../snippets/header.htm";
	include "../snippets/navigation.php";

	$db = mysqli_connect("localhost", $connect["username"], $connect["password"]);
	mysqli_select_db($db, "image_archive");
	$Connection = new Connection($db);

	$quote = $Connection->GetRandomQuote();
?>

<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/gallery.js"></script>
<script type="text/javascript" src="js/search.js"></script>

<div id="innerWrapper">
	<div id="innerWrapper2">
		<div id="content">

			<h1><a href="http://historyproject.ucdavis.edu/ic/">Image Archive</a> > <a href="javascript:history.go(-1);">Search Results</a></h1>

			<p>
				Enter search terms:
			</p>
			<input type="text" name="search_query" size="65" /> <input type="button" value="Search" onClick="javascript:search_archive();" />

		</div><!--end content-->

		<div id="sideCol">
	        <h2 style="margin-bottom: 10px;">Image Search</h2>
			<p class="bodyText">

		        Use one or more fields below to conduct your search.<br /><br />

				<!--<input type="text" name="keywords" value="Enter keyword(s)" /><br />

				<br />-->

				<select style="width: 150px;" name="search_term_region">
					<option value="-1">Region</option>

					<?php
						$regions = fetch_regions($db);

						foreach($regions as $region) {
							echo "<option value=\"".$region['id']."\">".$region['title']."</option>";
						}
					?>
				</select>
				<br /><br />
				<select style="width: 150px;" name="search_term_topic">
					<option value="-1">Topic/Theme</option>

					<?php
						$topics = fetch_topics($db);

						foreach($topics as $topic) {
							echo "<option value=\"".$topic['id']."\">".$topic['title']."</option>";
						}
					?>
				</select>
				<!--<br /><br />
				<select style="width: 150px;" name="standard_nat">
					<option value="-1">National Standard</option>
				</select>-->
				<br /><br />
				<select style="width: 150px;" name="search_term_standard_cal">
					<option value="-1">California Standard</option>

					<?php
						$standards = fetch_standards_ca();

						foreach($standards as $standard) {
							echo "<option value=\"".$standard['id']."\">".$standard['label']."</option>";
						}
					?>
				</select>
				<br /><br />
				<select style="width: 150px;" name="search_term_collection">
					<option value="-1">Collection</option>

					<?php
						$collections = fetch_collections($db);

						foreach($collections as $collection) {
							echo "<option value=\"".$collection['id']."\">".$collection['name']."</option>";
						}
					?>
				</select>

				<br /><br />

				<input type="button" value="Search" onClick="javascript:redirect_to_search(false);" />
			</p>
		</div> <!--end sideCol-->

		<div style="clear: both;"></div>
	</div> <!--end innerWrapper2-->
</div> <!--end innerWrapper-->

<?php
	include "../snippets/footer.htm";
