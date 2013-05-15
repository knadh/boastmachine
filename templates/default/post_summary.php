<!-- Begin post //-->
<div id="post-<?php echo $post_data['id']; ?>">

<h1 class="post_title"><a href="<?php echo "{$bmc_vars['site_url']}/".bmc_SE_friendly_url('post',BLOG_FILE,$post_data['id'],$post_data['title']); ?>" title="Permalink : <?php echo $title; ?>"><?php echo $title; ?></a></h1>
<small class="post_date"><?php echo $date; ?></small>
	<div class="post_text">
	<?php echo $summary; ?>

	<?php
		// The more.. link for expanded post
		if(!empty($post_data['data'])) {
	?>
	<br /><br />
	[ <a href="<?php echo "{$bmc_vars['site_url']}/".bmc_SE_friendly_url('post',BLOG_FILE,$post_data['id'],$post_data['title']); ?>" title="Permalink : <?php echo $title; ?>"><?php echo $lang['str_more']; ?>..</a> ]
	<?php
		}
	?>
	<br /><br />
	</div> <!-- end post_text //-->
</div> <!-- end post //-->

<?php
	// If there are attached files, print them in a box

if($file_list) {
?>
	<div class="file_list">
	<strong><?php echo $lang['att_file']; ?></strong><br />
	<?php echo $file_list; ?>
	</div>
<?php
}
?>

<br />
<div class="post_info">
<?php echo $lang['str_by']; ?> <a href="<?php echo $bmc_vars['site_url']; ?>/profile.php?id=<?php echo $post_data['author']; ?>"><?php echo $user_name; ?></a>
<?php echo $lang['str_in']; ?> <a href="<?php echo $bmc_vars['site_url']."/".bmc_SE_friendly_url('cat',BLOG_FILE,$post_data['cat'],"/"); ?>"><?php echo $cat; ?></a>

<?php 
	// Show the comment links if commenting is enabled
	if($bmc_vars['user_comment'] && $post_data['user_comment']) { ?>
		<br /><a href="<?php echo "{$bmc_vars['site_url']}/".bmc_SE_friendly_url('post',BLOG_FILE,$post_data['id'],$post_data['title']); ?>#cmt"><?php echo $cmn; ?> <?php echo $lang['comments']; ?></a> 
<?php
	}
?>

<?php 
	// Show the trackbacks links if trackback is enabled
	if($bmc_vars['trackbacks'] && $post_data['accept_trackback']) { ?>
		, <a href="<?php echo "{$bmc_vars['site_url']}/".bmc_SE_friendly_url('post',BLOG_FILE,$post_data['id'],$post_data['title']); ?>#track"><?php echo $tracks; ?> <?php echo $lang['trackbacks']; ?></a>
<?php
	}
?>

<?php
	// If the admin is logged in/or the author of the post, then show the ip of the message author
	if(defined('IS_ADMIN') && IS_ADMIN || ( isset($bmc_vars['logged_in_user']['id']) && $bmc_vars['logged_in_user']['id'] == $post_data['author'] && $bmc_vars['logged_in_user']['level'] > 2)) {
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
( <a href="http://network-tools.com/default.asp?host=<?php echo $post_data['user_ip']; ?>" title="IP"><?php echo $post_data['user_ip']; ?></a> )&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
( <a href="<?php echo $bmc_vars['site_url']."/user.php?action=edit_post&amp;blog=".BLOG."&amp;id=".$post_data['id']; ?>" title="<?php echo $lang['admin_post_edit']; ?>"><?php echo $lang['admin_post_edit']; ?></a> )&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
( <a href="<?php echo $bmc_vars['site_url']."/user.php?action=delete_post&amp;blog=".BLOG."&amp;id=".$post_data['id']; ?>" title="<?php echo $lang['admin_but_del']; ?>"><?php echo $lang['admin_but_del']; ?></a> )&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
	}
?>


</div><!-- end post_info //-->
<!-- END POST //-->

