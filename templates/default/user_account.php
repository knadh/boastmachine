<div>
<?php 
	echo str_replace("%name%", "<strong>".$user."</strong>", $lang['user_welcome']). "<br />\n";
	echo str_replace("%login%", "<strong>".$last_login."</strong>", $lang['user_last_login']);
?>
<br /><br />

<?php
	echo str_replace("%level%", $user_info['level'], $lang['user_level_info']). "<br />\n";
	echo "<strong>".$lang['user_level_'.$user_info['level']]."</strong>";
?>
<br /><br />
<?php

	echo $lang['user_blogs']."<br />\n";

	// Get the blogs associated with the user
	$blog_list=unserialize($user_info['blogs']);
	$blog_list_box="";


	if(!defined('IS_ADMIN')) {
		for($n=0;$n<count($blog_list);$n++) {
			$blog_name=$db->query("SELECT blog_name,blog_file FROM ".MY_PRF."blogs WHERE id='{$blog_list[$n]}' AND frozen='0'", false);
			echo "<a href=\"{$bmc_vars['site_url']}/{$blog_name['blog_file']}\"><strong>".$blog_name['blog_name']."</strong></a> , \n";
			$blog_list_box.="<option value=\"{$blog_list[$n]}\">{$blog_name['blog_name']}</option>";
		}
	} else {
		$blog_name=$db->query("SELECT id,blog_name,blog_file FROM ".MY_PRF."blogs");
		$sel="";
		foreach($blog_name as $this_blog) {

			if((defined('BLOG') && BLOG) && BLOG == $this_blog['id']) {
				$sel=" selected";
			} else {
				$sel="";
			}

			echo "<a href=\"{$bmc_vars['site_url']}/{$this_blog['blog_file']}\"><strong>".$this_blog['blog_name']."</strong></a> , \n";
			$blog_list_box.="<option value=\"{$this_blog['id']}\"{$sel}>{$this_blog['blog_name']}</option>";
		}

	}

?>

<br /><br /><div class="hr_line"></div><br />

<form name="blog_list" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="action" value="new_post" />
<select name="blog">
<?php echo $blog_list_box; ?>
</select>
</form>

<table border="0" align="center" cellpadding="20" cellspacing="0" summary="Links">
	<thead>
		<tr>

<?php
	// Now show the right links for the user, matching his privilages
	if($user_info['level'] >= 2) {
?>
			<th id="th037E3CA80000" valign="top" align="center">
			<a href="javascript:document.blog_list.submit();"><img src="<?php echo $bmc_vars['site_url']."/templates/".CFG_THEME; ?>/images/ico_new.gif" alt="<?php echo $lang['user_post_new']; ?>" /></a><br /><a href="javascript:document.blog_list.submit();"><?php echo $lang['user_post_new']; ?></a>
			</th>
<?php	}
	// Link for users with level >3
	if($user_info['level'] >= 3) {
?>
			<th id="th037E3CA80001" valign="top" align="center">
			<a href="?action=list_posts"><img src="<?php echo $bmc_vars['site_url']."/templates/".CFG_THEME; ?>/images/ico_edit.gif" alt="<?php echo $lang['user_post_edit']; ?>" /></a><br /><a href="?action=list_posts"><?php echo $lang['user_post_edit']; ?></a>
			</th>
<?php	} ?>

			<th id="th037E3CA80003" valign="top" align="center">
			<a href="?action=my_account"><img src="<?php echo $bmc_vars['site_url']."/templates/".CFG_THEME; ?>/images/ico_prof.gif" alt="<?php echo $lang['user_acc']; ?>" /></a><br /><a href="?action=my_account"><?php echo $lang['user_acc']; ?></a>
			</th>

			<th id="th037E3CA80004" valign="top" align="center">
			<a href="?action=logout"><img src="<?php echo $bmc_vars['site_url']."/templates/".CFG_THEME; ?>/images/ico_logout.gif" alt="<?php echo $lang['user_logout']; ?>" /></a><br /><a href="?action=logout"><?php echo $lang['user_logout']; ?></a>
			</th>

<?php
	// Link for ADMIN
	if($user_info['level'] == 4) {
?>
			<th id="th037E3CA80005" valign="top" align="center">
			<a href="<?php echo $bmc_vars['site_url']."/".BMC_DIR."/admin.php"; ?>"><img src="<?php echo $bmc_vars['site_url']."/templates/".CFG_THEME; ?>/images/ico_admin.gif" alt="<?php echo $lang['user_admin']; ?>" /></a><br /><a href="<?php echo $bmc_vars['site_url']."/".BMC_DIR."/admin.php"; ?>"><?php echo $lang['user_admin']; ?></a>
			</th>
<?php	} ?>

		</tr>
	</thead>
</table>

</div>
