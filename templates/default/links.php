<?php

	// Get the links
	$links=$db->query("SELECT * FROM ".MY_PRF."links WHERE blog='".BLOG."' or blog='0'");

	if(!empty($links)) {
		foreach($links as $link) {
			echo "<a href=\"http://".str_replace("http://","",$link['url'])."\" title=\"".htmlspecialchars(bmc_htmlEntities($link['description']))."\">".bmc_htmlEntities($link['title'])."</a><br />\n";
		}
	}

?>