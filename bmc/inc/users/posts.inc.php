<?php

/*
  ===========================

  boastMachine v3.1
  Released : Sunday, June 5th 2005 ( 06/05/2005 )
  http://boastology.com

  Developed by Kailash Nadh
  Email   : mail@kailashnadh.name
  Website : http://kailashnadh.name, http://bnsoft.net
  Blog    : http://boastology.com/blog

  boastMachine is a free software licensed under GPL (General public license)

  ===========================
*/

	if(!isset($user_info['level']) || $user_info['level'] <= 2) {
		// The user doesnt have right to access this script
		bmc_Go("?null");
	}

	// Delete the posts if requested
	if(isset($_POST['action']) && $_POST['action'] == "delete_posts" && !empty($_POST['chk_delete']) ) {
		$post_list=$_POST['chk_delete']; // Get the 'to be deleted' post list as array

		foreach($post_list as $p) {

			// Get the blog and author
			$del_post_info=$db->query("SELECT blog,author FROM ".MY_PRF."posts WHERE id='{$p}'", false);

			if($del_post_info['author'] == $user_info['id']) {
				$db->query("DELETE FROM ".MY_PRF."posts WHERE id='{$p}' AND author='{$user_info['id']}'"); // Delete the post
				$db->query("DELETE FROM ".MY_PRF."comments WHERE post='{$p}'"); // Delete the comments

				$blogs_rss[]=$del_post_info['blog'];

			}
		}


	bmc_updateCache('archive'); // Update the cache

	// Generate the RSS feeds for all the blogs modded
	if($bmc_vars['rss_feed']) {

		for($n=0;$n<count($blogs_rss);$n++) {
			$i_blog=$db->query("SELECT * FROM ".MY_PRF."blogs WHERE id='{$blogs_rss[$n]}' AND frozen='0'",false);
			include CFG_ROOT."/inc/core/rss.build.php";
		}
	}

		bmc_Go("?action=list_posts");	// redirect

	}

	// ===================================


	// Page header
	bmc_Template('page_header', $lang['admin_blog_list_posts']);

	if(isset($_GET['selIndex'])) $selIndex=$_GET['selIndex']; else $selIndex=0;


// Print the select box
echo <<<EOF
<br />
<div>
</div>
<div id="form_fields">
<strong>{$lang['admin_post_show']}</strong>
<form name="posts_range">
<div><select name="show_posts" size="1" onChange="javascript:showPosts();">
<option selected value="this_week">{$lang['admin_post_week']}</option>
<option value="this_month">{$lang['admin_post_month']}</option>
<option value="last_month">{$lang['admin_post_last_month']}</option>
<option value="last_six">{$lang['admin_post_last_6month']}</option>
<option value="last_year">{$lang['admin_post_last_year']}</option>
</select></div>
</form>
</div>
<div>
<script type="text/javascript">
<!--
	posts_range.show_posts.selectedIndex=$selIndex;
//-->
</script>
</div>
EOF;


	$query_str="";	// The query strings are to be stored for page number to work correctly in accordance to all the criteria

	// Posts sorting
	$sort="date DESC";

	if(isset($_GET['sort'])) {

		switch($_GET['sort']) {
		 case 'title':
		 $sort="title";
		 $query_str="&amp;sort=title";
		 break;

		 case 'date':
		 $sort="date";
		 $query_str="&amp;sort=date";
		 break;

		 case 'blog':
		 $sort="blog";
		 $query_str="&amp;sort=blog";
		 break;

		 default:
		 $sort="date DESC";
		 break;
		}
	}


// Check whether a range has been specified
if(isset($_GET['range'])) {
	switch ($_GET['range']) {

		case 'this_week':
		$range_sql="and date >= '".(time()-604800)."'";
		$query_str.="&amp;range=this_week";
		break;


		case 'this_month':
		$start_time=mktime('0','0','0',bmc_Date(0,"m"),1,bmc_Date(0,"Y")); // Time stamp of the beginning day of this month
		$end_time=mktime('12','59','59',bmc_Date(0,"m"),bmc_Date(0,"t"),bmc_Date(0,"Y")); // Time stamp of the last day of this month
		$range_sql="and date >= '$start_time' and date <= '$end_time'";
		$query_str.="&amp;range=this_month";
		break;


		case 'last_month':
			if(bmc_Date(0,"m") == 1) {
				$last_month=12;
			} else {
				$last_month=bmc_Date(0,"m")-1;
			}

		$start_time=mktime('0','0','0',$last_month,1,bmc_Date(0,"Y")); // Time stamp of the beginning day of this month
		$end_time=mktime('12','59','59',$last_month,bmc_Date(0,"t"),bmc_Date(0,"Y")); // Time stamp of the last day of this month
		$range_sql="and date >= '$start_time' and date <= '$end_time'";
		$query_str.="&amp;range=last_month";
		break;


		case 'last_six':
		$range_sql="and date >= '".(time()-15552000)."'";
		$query_str.="&amp;range=last_six";
		break;


		case 'last_year':
		$range_sql="and date >= '".(time()-31104000)."'";
		$query_str.="&amp;range=lat_year";
		break;
	}

} else {
	$range_sql="";
}

	// Page numbering..
	$per_page=$bmc_vars['p_page'];
	if(empty($_GET['p']) || $_GET['p'] < 0 || !is_numeric($_GET['p'])) { $pg=1; } else { $pg=trim($_GET['p']); }

	$start=($pg*$per_page)-$per_page;


	// Do the query
	$data=$db->query("SELECT id,author,date,title,user_comment,status,blog FROM ".MY_PRF."posts WHERE author='{$user_info['id']}' $range_sql ORDER by $sort LIMIT $start,$per_page");

	// No posts!
	if(empty($data)) {
		echo $lang['admin_search_no'];
	} else {

		$post_count=$db->row_count("SELECT id FROM ".MY_PRF."posts WHERE author='{$user_info['id']}'");

		// Keep the post count under the limit
		if(($start+$per_page) > $post_count) {
			$per_page=$post_count-$per_page;
		}

		$nm=$post_count; // Some preparations for the page numbering
		$x=$nm/$bmc_vars['p_page'];

		// Generate the page numbers
		if($x<1) { $x=0; }
		if($nm%$per_page == 0) { $x=$x-1; }
?>

		<div class="page_num">
		<?php echo $lang['page']; ?> :&nbsp;
		<?php
		// Generate the page numbers
		for($n=1;$n<=$x+1;$n++) {
			echo "<a href=\"?action=list_posts&amp;p={$n}{$query_str}\" title=\"{$lang['page']} {$n}\">{$n}</a>&nbsp;";
		}

?>
		</div> <!-- end page_num //-->
<?php
		include CFG_ROOT."/inc/users/posts_list_table.php"; // Load the post list table template
	}
?>

<script type="text/javascript">
<!--
function showPosts() {
	document.location="?action=list_posts&amp;p=<?php echo $pg; ?>&amp;selIndex="+posts_range.show_posts.selectedIndex+"&amp;range="+posts_range.show_posts.value;
}
//-->
</script>
<?php
	bmc_Template('page_footer');
	// Footer
?>