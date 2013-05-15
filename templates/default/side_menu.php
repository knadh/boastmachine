<div id="menu">

<?php include_once CFG_ROOT."/calendar.php"; ?>

<br /><br />
<a href="http://boastology.com"><img src="<?php echo $bmc_vars['site_url']."/templates/".CFG_THEME."/images/bm_powered.gif"; ?>" alt="Powered by boastMachine" /></a>

<div class="menu_item">
<strong><?php echo $lang['recent_posts']; ?></strong><br />
<?php bmc_show_list('posts','','<br />',BLOG,10); ?>
</div> <!-- end menu_item //-->

<div class="menu_item">
<strong><?php echo $lang['archive']; ?></strong><br />
<?php bmc_show_list('archive','','<br />',BLOG); ?>
</div> <!-- end menu_item //-->

<div class="menu_item">
<strong><?php echo $lang['cats']; ?></strong><br />
<?php bmc_show_list('cats','','<br />',BLOG); ?>
</div> <!-- end menu_item //-->

<div class="menu_item">
<strong><?php echo $lang['blog_roll']; ?></strong><br />
<?php bmc_show_list('blogs','','<br />'); ?>
</div> <!-- end menu_item //-->

<?php if($bmc_vars['user_search']) { ?>
<div class="menu_item">
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div>
<strong><?php echo $lang['search']; ?></strong>
</div>
<div>
<input type="hidden" name="action" value="search" />
<input type="hidden" name="item" value="title" />
<input type="hidden" name="blog" value="<?php echo BLOG; ?>" />
<input type="text" name="key" size="15" /><br />

<input type="submit" value="<?php echo $lang['search']; ?>" />
</div>
</form>
</div> <!-- end menu_item //-->
<?php } ?>

<?php
	if($bmc_vars['rss_feed'] && !empty($i_blog['rss_feed'])) {

	// The rss filenames are generated in the form, weblog_name.rss
	// So..

	$rss_file=str_replace(".php","",BLOG_FILE);
?>
<div class="menu_item">
<strong><?php echo $lang['syndicate']; ?></strong><br />
<a href="<?php echo $bmc_vars['site_url']; ?>/rss/<?php echo $rss_file; ?>_rss1.xml">RSS .92</a><br />
<a href="<?php echo $bmc_vars['site_url']; ?>/rss/<?php echo $rss_file; ?>_rss2.xml">RSS 2.0</a><br />
<a href="<?php echo $bmc_vars['site_url']; ?>/rss/<?php echo $rss_file; ?>_atom.xml">Atom .03</a><br />
</div> <!-- end menu_item //-->
<?php
	}
?>

<div class="menu_item">
<?php
	// If the user is not logged in, show the login box
	$user=bmc_isLogged();
	if(!$user) {
?>
<strong><?php echo $lang['user_box_txt']; ?></strong>
<form method="post" action="<?php echo $bmc_vars['site_url']; ?>/login.php">
<div>
<?php echo $lang['user_login']; ?> :<br /> <input type="text" name="user_login" size="15" /><br />
<?php echo $lang['user_pass']; ?> :<br /> <input type="password" name="password" size="15" /><br />
<?php echo $lang['user_login_remember']; ?> <br /> <input type="checkbox" name="remember" value="1" />
<br />
<input type="submit" value="<?php echo $lang['user_login_but']; ?>" />
</div>
</form>
<a href="<?php echo $bmc_vars['site_url']; ?>/register.php"><?php echo $lang['user_signup']; ?></a><br />
<?php

	}
	else {
	// else, show the account link
	?>

	<strong>&quot; <?php echo $user; ?> &quot;</strong>
	&nbsp;&nbsp;&nbsp;<a href="javascript: var jump;" onFocus="javascript:toggle_floating_layer(0); this.blur();" accesskey="u">x</a>
	<br />
	<a href="<?php echo $bmc_vars['site_url']; ?>/user.php?blog=<?php echo BLOG; ?>"><?php echo $lang['user_box_acc']; ?></a><br />
	<a href="<?php echo $bmc_vars['site_url']; ?>/user.php?action=logout"><?php echo $lang['user_logout']; ?></a>


	<div id="floating_layer">
	<?php if($bmc_vars['logged_in_user']['level'] >= 2) { ?>
		<a href="<?php echo $bmc_vars['site_url']."/user.php"; ?>?action=new_post&amp;blog=<?php echo BLOG; ?>"><?php echo $lang['user_post_new']; ?></a><br />
	<?php } ?>

	<?php if($bmc_vars['logged_in_user']['level'] > 3) { ?>
		<a href="<?php echo $bmc_vars['site_url']."/user.php"; ?>?action=list_posts&amp;blog=<?php echo BLOG; ?>"><?php echo $lang['user_post_edit']; ?></a><br />
	<?php } ?>

		<a href="<?php echo $bmc_vars['site_url']."/user.php"; ?>?action=my_account"><?php echo $lang['user_acc']; ?></a><br />

	<?php if(defined('IS_ADMIN')) { ?>
		<a href="<?php echo $bmc_vars['site_url']."/".BMC_DIR."/admin.php"; ?>"><strong><?php echo $lang['user_admin']; ?></strong></a><br />
	<?php } ?>

		<a href="<?php echo $bmc_vars['site_url']."/user.php"; ?>?action=logout"><?php echo $lang['user_logout']; ?></a><br />
	</div>
	<script type="text/javascript">
	<!--
		load_floating_layer();	// Initiate the popup layer
		toggle_floating_layer(1);	// Make it hidden by default
	//-->
	</script>
<?php
	}
?>
</div> <!-- end menu_item //-->

<div class="menu_item">
<strong><?php echo $lang['links']; ?></strong><br />
<?php
	include CFG_PARENT."/templates/".CFG_THEME."/links.php";
?>
</div> <!-- end menu_item //-->

<div class="menu_item">
<a href="http://validator.w3.org/check/?uri=referer" title="Valid XHTML">Valid XHTML</a><br />
</div> <!-- end menu_item //-->

<br />


</div> <!-- end menu //-->
