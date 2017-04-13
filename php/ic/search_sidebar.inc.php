<h2 style="margin-bottom: 10px;">Image Search</h2>
			<p class="bodyText">

		        Use one or more fields below to conduct your search.<br /><br />

				<!--<input type="text" name="keywords" value="Enter keyword(s)" /><br />

				<br />-->

				Keywords:<br /><input type="text" name="search_term_query" value="" size="16" /><br />

				<br />

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
				<br /><br />
				<!--<select style="width: 150px;" name="standard_nat">
					<option value="-1">National Standard</option>
				</select>
				<br /><br />-->
				<select style="width: 150px;" name="search_term_standard_cal">
					<option value="-1">California Standard</option>

					<?php
						$standards = fetch_standards_ca($db);

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
				<small><a href="#" onClick="javascript:$('div#advanced_search').show();">Advanced Options</a></small><br />
				<div id="advanced_search" style="display: none;">
					<p>
						<small>&nbsp;&nbsp;&nbsp;&nbsp;ID: <input type="text" name="search_term_id" value="" size="4" /></small><br />
					</p>
				</div>

				<br />

				<input type="button" value="Search" onClick="javascript:redirect_to_search(true, 0);" /><br />

				<br />

				<p><small>
					View Options:<br />

					<span id="view_options">
						<?php
							if(!isset($_COOKIE['ic_view']) || ($_COOKIE['ic_view'] == 0)) {
								echo "Thumbnail or <a href=\"javascript:switch_view(1);\">List</a>";
							} else {
								echo "<a href=\"javascript:switch_view(0);\">Thumbnail</a> or List";
							}
						?>
					</span><br />
					<br />
					Items per page:<br />
					<select id="slides_per_page">
						<option value="24">24</option>
						<option value="48">48</option>
						<option value="72">72</option>
						<option value="96">96</option>
						<option value="250">250</option>
						<option value="500">500</option>
						<option value="1000">1000</option>
						<option value="2000">2000</option>
					</select>
				</small></p>
			</p>

			<p style="line-height: 100%;">
			<br />
			<small>
			Please note: These images are for educational use only; they are not to be used or reproduced in any way for commercial use.<br />
			 <br />
			If you are a copyright owner of any image or document on this site and believe our website has not properly attributed your work to you or has used it without permission, we want to hear from you. Please contact <a href="mailto:historyproject@ucdavis.edu">historyproject@ucdavis.edu</a> with your contact information and a link to the relevant content.</small>
			</p>
