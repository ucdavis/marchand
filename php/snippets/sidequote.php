<?php
	echo '<em>&#8220;' . $quote['quote'] . '&#8221;<br /><br />';
	echo $quote['name'] . '<br />';
	if(isset( $quote['position']) && ($quote['position'] != "")){ echo $quote['position'] . '<br />'; } 
	echo $quote['organization'] . '</em>';
?>
