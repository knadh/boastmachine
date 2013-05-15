<?php

	// Simple statistics

if(defined('IS_ADMIN') && IS_ADMIN) {

	if(defined('BLOG')) {
		$blog=BLOG;
	}

	if(isset($blog)) { echo "<strong>".BLOG_NAME."</strong><br />"; } else { $num=null; }

	if(isset($blog))
		$num=bmc_getCount($blog); // Get the blog,post,users,comments count
	else {
		$num=bmc_getCount();
	}

	echo "{$lang['admin_stats_blogs']} : {$num['blogs']}<br />";
	echo "{$lang['admin_stats_users']} : {$num['users']}<br />";
	echo "{$lang['admin_stats_posts']} : {$num['posts']}<br />";
	echo "{$lang['admin_stats_cmts']} : {$num['comments']}<br />";

}

?>