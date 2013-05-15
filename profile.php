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

	include_once dirname(__FILE__)."/config.php";
	include_once dirname(__FILE__)."/$bmc_dir/main.php";



	// Invalid user ID
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		bmc_Template('error_page', $lang['profiles_invalid']);
	}


	// Get the user info from the DB
	$user_info=$db->query("SELECT * FROM ".MY_PRF."users WHERE id='{$_GET['id']}'", false);

	// The user is invalid
	if(!isset($user_info['id'])) {
		bmc_Template('error_page', $lang['profiles_invalid']);
	}


	// The user has chosen not to show his profile to the public
	if(!$user_info['public_profile']) {
		bmc_Template('error_page',$lang['profiles_no_pub']);
	}

	$user_name=bmc_dispUser($user_info['id']); // User's display name

	// Page header
	bmc_Template('page_header',$lang['user_profile']." :: ".$user_name);

	// Include the user profile template file
	include CFG_PARENT."/templates/".CFG_THEME."/profile.php";

	bmc_Template('page_footer'); // Page footer

?>