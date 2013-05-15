<?php

	// This template holds 2 things, the search box and the search results format
	// IF THE VARIABLE $box is TRUE, then print the box, else return the results


	if($box) {
?>

<div id="form_fields">
<form name="search" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="blog" value="<?php echo $_REQUEST['blog']; ?>" />
<input type="hidden" name="action" value="search" />

<?php echo $lang['search_in']; ?>
<select name="item">
<div>
<option value="title"><?php echo $lang['search_title']; ?></option>
<option value="content"><?php echo $lang['search_content']; ?></option>
</div>
</select>

<input type="text" name="key" value="<?php echo $search_key; ?>" />
<input type="submit" value="<?php echo $lang['search']; ?>" />
</form>
</div>

<?php
	} else {

	// The search result format here
?>
<br /><br />

<span class="post_title"><a href="<?php echo "{$bmc_vars['site_url']}/".bmc_SE_friendly_url('post',BLOG_FILE,$i_post['id'],$title); ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></span><br />
<?php echo $date; ?><br />
<?php echo $lang['posted_by']; ?> <a href="profile.php?id=<?php echo $i_post['author']; ?>"><?php echo $user_name; ?></a> <?php echo $lang['str_in']; ?> 
<a href="<?php echo $bmc_vars['site_url']."/cat/".str_replace(".php","",BLOG_FILE)."/".$i_post['cat']; ?>" title="<?php echo $cat_name; ?>"><?php echo $cat_name; ?></a>

<?php
	}
?>