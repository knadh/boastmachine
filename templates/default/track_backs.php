<?php
	// The admin's deleting this trackback
	if(isset($_GET['action']) && $_GET['action'] == 'delete_trackback' && isset($_GET['track']) && is_numeric($_GET['track'])) {


		if(defined('IS_ADMIN') && IS_ADMIN || ( isset($bmc_vars['logged_in_user']['id']) && $bmc_vars['logged_in_user']['id'] == $post_author['author'] && $bmc_vars['logged_in_user']['level'] > 2)) {
			$db->query("DELETE FROM ".MY_PRF."trackbacks WHERE id='{$_GET['track']}'");

			bmc_Go("?id=".$_GET['id']."#track"); // Redirect
		}
	}

?>
<br /><br />
<?php echo $lang['trackback_list_title']; ?><br /><br />
<div class="track">
<?php
	// This template has some serious php codes :)

	// Get the trackbacks for this post
	$trackbacks=$db->query("SELECT * FROM ".MY_PRF."trackbacks WHERE post='{$post_data['id']}'");

// Print all the trackbacks
foreach($trackbacks as $track) {
?>

<a href="<?php echo $track['url']; ?>" title="<?php echo $track['title']; ?>"><?php echo $track['title']; ?></a>

<?php
	// If the user logged in is the admin, show the 'delete' link
	if(defined('IS_ADMIN') && IS_ADMIN || ( isset($bmc_vars['logged_in_user']['id']) && $bmc_vars['logged_in_user']['id'] == $post_author['author'] && $bmc_vars['logged_in_user']['level'] > 2)) {
?>
&nbsp;&nbsp;<a href="<?php echo $bmc_vars['site_url']."/".BLOG_FILE; ?>?action=delete_trackback&track=<?php echo $track['id']; ?>&id=<?php echo $post_data['id']; ?>">(<?php echo $lang['admin_but_del']; ?>)</a>
<?php
	}
?>

<br />
<?php echo nl2br(bmc_wordwrap($track['excerpt'], htmlspecialchars($bmc_vars['summary_wrap']))); ?><br />
<?php echo $lang['blog']; ?> : <?php echo htmlspecialchars($track['title']); ?><br />
<?php echo $lang['tracked_on']; ?> : <?php echo bmc_Date($track['date'], "r"); ?>
<br /><br />
<?php
}
?>
</div><br /><br />