<h1 class="post_title"><?php echo $title; ?></h1>
<small class="post_date"><?php echo $date; ?></small>

<div class="post_info">
<?php echo $lang['posted_by'];?> <a href="<?php echo $bmc_vars['site_url']; ?>/profile.php?id=<?php echo $post_data['author']; ?>"><?php echo $user_name; ?></a> 
<?php echo $lang['str_in']; ?> <a href="<?php echo $bmc_vars['site_url']."/cat/".str_replace(".php","",BLOG_FILE); ?>/<?php echo $post_data['cat']; ?>"><?php echo $cat; ?></a>

<?php
	// If the admin is logged in, then show the ip of the message author
	if(defined('IS_ADMIN') && IS_ADMIN) {
?>
&nbsp;&nbsp; ( <a href="http://network-tools.com/default.asp?host=<?php echo $post_data['user_ip']; ?>" title="Trace IP"><?php echo $post_data['user_ip']; ?></a> )
<?php
	}
?>
<br />
<?php
	// Show the voting/rating stats
	if($bmc_vars['user_vote'] && $post_data['user_vote']) {
		echo $lang['rating'].": ".$total_rating."/5 &nbsp;&nbsp;";
		echo "<a href=\"javascript:popWin('{$bmc_vars['site_url']}/vote.php?id=".$post_data['id']."')\">".$lang['votes']."</a> : ".$total_votes;
	}
?>

</div><!-- end post_info //-->

<div class="post">
<div id="post_text"><?php echo $msg; ?></div>
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

<div class="main_page_info">
<?php
	// Mail the article page
	if($bmc_vars['user_send_post']) {
echo <<<EOF
<a href="{$bmc_vars['site_url']}/mail.php?id={$post_data['id']}&amp;blog=$blog" title="{$lang['send_post_title']}">{$lang['send_post']}</a>&nbsp;&nbsp;&nbsp;
EOF;
	}
?>
<a href="<?php echo $bmc_vars['site_url']."/".BLOG_FILE; ?>?print=<?php echo $post_data['id']; ?>"><?php echo $lang['print']; ?></a>&nbsp;&nbsp;&nbsp;
</div><!-- end entry_info //-->

<?php
	// If trackback is enabled for this post, display the trackback info
	if($bmc_vars['trackbacks'] && $post_data['accept_trackback']) { ?>

<div class="hr_line"></div>
<h3 id="track"><?php echo $lang['trackbacks']; ?></h3>
<?php echo $lang['trackback_msg']; ?><br />
<strong><?php echo $bmc_vars['site_url']."/trackback.php/".BLOG."/".$post_data['id']; ?></strong>

<?php include CFG_PARENT."/templates/".CFG_THEME."/track_backs.php"; ?>

<br /><br />
<?php } ?>

<!-- comments //-->
<div class="hr_line"></div>
<?php if($bmc_vars['user_comment']) { ?>
<h3 id="cmt"><?php echo $lang['comments']; ?></h3>
<?php } ?>