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


	// Check whether user registration is being accepted at present
	if(!$bmc_vars['user_registration']) {
		// Nopes!
		bmc_Template('page_header', $lang['user_no_accept']);
		echo $lang['user_no_accept'];
		bmc_Template('page_footer');
		exit;
	}



	// Show the user registration form
	if(!isset($_POST['action'])) {
		bmc_Template('page_header', $lang['user_reg_title']);
		include CFG_PARENT."/templates/".CFG_THEME."/signup_form.php";
		bmc_Template('page_footer');
		exit;
	}




// Check for empty fields
if(empty($_POST['password']) || empty($_POST['user_login']) || empty($_POST['full_name']) || empty($_POST['email']) || empty($_POST['blogs'])) {
	bmc_template('error_page', $lang['empty_fields'],$lang['empty_fields']);
}

// Userneame too short
if(strlen($_POST['user_login']) < 3) {
	bmc_template('error_page', $lang['user_short_user']);
}

// Userneame too short
if(strlen($_POST['password']) < 5) {
	bmc_template('error_page', $lang['user_short_pass']);
}

	// Check whether the user already exists
	$user=$db->row_count("SELECT id FROM ".MY_PRF."users WHERE user_login='{$_POST['user_login']}' OR user_email='{$_POST['email']}'", false);

	if($user) {
		// Yes! He does!!
		bmc_template('error_page', $lang['user_exists_msg'],$lang['user_exists_msg']);
	}


	// Check whehter the user is trying to register himself on frozen blogs
	for($n=0;$n<count($_POST['blogs']);$n++) {
		if($db->row_count("SELECT id FROM ".MY_PRF."blogs WHERE id='{$_POST['blogs'][$n]}' AND frozen='true'")) {
			bmc_Template('error_page',$lang['user_blogs_no_assoc']);
		}
	}


	$blog_list=serialize($_POST['blogs']);

	$time=time(); // The signup time

	$level=$bmc_vars['user_default_level']; // Default user level

	// Add the user to the DB
	$db->query("INSERT INTO ".MY_PRF."users (user_login,user_name,user_pass,user_email,user_url,date,blogs,level) VALUES('".str_replace(" ","_",$_POST['user_login'])."','{$_POST['full_name']}','".md5($_POST['password'])."','{$_POST['email']}','{$_POST['url']}','$time','$blog_list','$level')");

	// Send a welcome mail if set
	if($bmc_vars['user_new_welcome']) {

		// Load the message
		$message=@fread(fopen(CFG_PARENT."/templates/welcome_user.txt","r"), filesize(CFG_PARENT."/templates/welcome_user.txt"));

		// Replace the custom tags with real values (See docs for information)
		$message=str_replace("[NAME]", $_POST['full_name'],$message);
		$message=str_replace("[MY_SITE]", $bmc_vars['site_url'],$message);
		$message=str_replace("[USERNAME]", $_POST['user_login'],$message);
		$message=str_replace("[PASSWORD]", $_POST['password'],$message);
		$message=str_replace("[DATE]", bmc_Date($time),$message);

		// Send the mail
		bmc_Mail($_POST['email'], $lang['user_welcome_subject']." : ".$bmc_vars['site_url'], $message);
	}


	// Notify the admin if necessary
	if($bmc_vars['user_new_notify']) {

		// Load the message
		$message=@fread(fopen(CFG_PARENT."/templates/new_user_notify.txt","r"), filesize(CFG_PARENT."/templates/new_user_notify.txt"));

		// Replace the custom tags with real values (See docs for information)
		$message=str_replace("[NAME]", $_POST['full_name'],$message);
		$message=str_replace("[MY_SITE]", $bmc_vars['site_url'],$message);
		$message=str_replace("[USERNAME]", $_POST['user_login'],$message);
		$message=str_replace("[PASSWORD]", $_POST['password'],$message);
		$message=str_replace("[DATE]", bmc_Date($time),$message);

		// Send the mail
		bmc_Mail($bmc_vars['site_email'], $lang['user_notify_subject']." : ".$bmc_vars['site_url'], $message);

	}

// Show the success message
bmc_Template('page_header', $lang['user_reg_success_title']);
echo $lang['user_reg_success_msg'];
bmc_Template('page_footer'); exit;


?>