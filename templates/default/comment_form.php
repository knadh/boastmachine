<br /><br />
<?php
	// The admin's deleting a comment


	if(isset($_GET['action']) && isset($_GET['cmt']) && $_GET['action'] == 'delete_comment' && is_numeric($_GET['cmt'])) {
		if(defined('IS_ADMIN') && IS_ADMIN || ( isset($bmc_vars['logged_in_user']['id']) && $bmc_vars['logged_in_user']['id'] == $post_author['author'] && $bmc_vars['logged_in_user']['level'] > 2)) {
			$db->query("DELETE FROM ".MY_PRF."comments WHERE id='{$_GET['cmt']}'");
			$db->query("DELETE FROM ".MY_PRF."comments WHERE parent_id='{$_GET['cmt']}'");	// Delete all child comments
			bmc_Go("?id=".$post_data['id']."#cmt"); // Redirect
		}
	}

?>
<div class="hr_line"></div>
<div id="comment_form">

<form id="comment_fields_form" method="post" action="<?php echo $bmc_vars['site_url']."/comments.php"; ?>">
<div class="comment_form">

	<div id="cmt_reply_to">&nbsp;</div>

<input type="hidden" name="action" value="post_comment" />
<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
<input type="hidden" name="parent_id" />
<input type="hidden" name="blog" value="<?php echo BLOG; ?>" />

<?php

	// Show the name,email,url boxes only if its a guest user
	// if a user is already logged in, no need

	if(!isset($bmc_vars['logged_in_user'])) {


	// The guest's user info has been saved, load it back
	if(isset($_COOKIE['BMC_cmt_guest'])) {
		$guest_info=unserialize(stripslashes($_COOKIE['BMC_cmt_guest']));
	}

?>

<?php echo $lang['cmt_name']; ?><br />
<input type="text" name="name" value="<?php if(isset($guest_info)) echo $guest_info['name']; ?>" /><br />

<?php echo $lang['cmt_email']; ?><br />
<input type="text" name="email" value="<?php if(isset($guest_info)) echo $guest_info['email']; ?>" /><br />

<?php echo $lang['cmt_url']; ?><br />
<input type="text" name="url" value="<?php if(isset($guest_info)) echo $guest_info['url']; ?>" /><br /><br />

<input type="checkbox" name="remember" value="true"<?php if(isset($guest_info)) echo " checked"; ?> />
<?php echo $lang['user_login_remember']; ?>

<br /><br />

<?php
	}
	else {
		// If the user is logged, print his username
		echo $lang['str_by']." <strong>".$bmc_vars['logged_in_user']['name']."</strong><br />\n";
	}
?>
<?php echo $lang['cmt_comment']; ?><br />
<textarea name="comments" rows="10" cols="50"></textarea><br /><br />

<?php
	// Image verification mod
	if($bmc_vars['image_verify']) {
?>
<img src="<?php echo $bmc_vars['site_url']."/".$bmc_vars['bmc_dir']."/image_verify.php"; ?>" alt="<?php echo $lang['cmt_verify']; ?>" /><br />
<?php echo $lang['cmt_verify']; ?><br />
<input type="text" name="verify" />
<?php
	}
?>

<br /><br />
<input type="submit" value="<?php echo $lang['cmt_submit_but']; ?>" />
</div>
</form>

</div>

<?php
	// If threading is enabled, load the necessary javascript
	if($bmc_vars['user_comment_threading']) {
?>
<script type="text/javascript">
<!--

function threadComment(lyr,cmt_id)
{

	document.getElementById('comment_fields_form').parent_id.value=cmt_id;
	document.getElementById(lyr).style.visibility="visible";
	document.getElementById(lyr).innerHTML="<strong><?php echo $lang['cmt_thread_reply_id']; ?> #"+cmt_id+"</strong> ( <a href=\"javascript:threadCommentReset('cmt_reply_to');\">x</a> )";
	document.location="#"+lyr;

}

function threadCommentReset(lyr)
{
	document.getElementById(lyr).style.visibility="hidden";
	document.getElementById(lyr).innerHTML="";
	document.getElementById('comment_fields_form').parent_id.value="";
}
//-->
</script>
<?php } ?>