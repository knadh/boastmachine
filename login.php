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

	// Forgot password
	if(isset($_REQUEST['action']) && $_REQUEST['action']=="forgot_pass") { bmc_forgot_pass(); exit; }


	// Check for cookies (is the user already logged in?)


	if(isset($bmc_vars['logged_in_user'])) {
		// Redirect to the user page
		bmc_Go("user.php");
		exit;
	}

	$user_message="";	// Message/notice displayed on the member page

	// Show the user login form
	if(!isset($_POST['user_login']) || !isset($_POST['password'])) {
		bmc_Template('page_header', $lang['user_login_title']);
		include CFG_PARENT."/templates/".CFG_THEME."/login_form.php";
		bmc_Template('page_footer');
		exit;
	}

	// Get the userdata
	$user=$db->query("SELECT user_login,last_login,user_pass,level FROM ".MY_PRF."users WHERE user_login='{$_POST['user_login']}'", false);


		// Invalid info!
		if(empty($user['user_pass']) || $user['user_pass'] != md5($_POST['password'])) {

			bmc_Template('page_header', $lang['user_login_title']);
			$user_message=$lang['user_login_false']; // Show the error
			include CFG_PARENT."/templates/".CFG_THEME."/login_form.php";
			bmc_Template('page_footer');

			exit;
		}

		// The user is suspended/frozen
		if($user['level'] == "0") {
			bmc_template('error_page', $lang['user_frozen']);
		}



	// Set the cookies


	// Set the cookie's expiration to +7 days
	if(isset($_POST['remember'])) {
		setcookie("BMC_user", $_POST['user_login'] ,time()+604800,BMC_COOKIE,BMC_COOKIE_DOMAIN);
		setcookie("BMC_user_password", $user['user_pass'] ,time()+604800,BMC_COOKIE,BMC_COOKIE_DOMAIN);
	} else {
		setcookie("BMC_user", $_POST['user_login'],0,BMC_COOKIE,BMC_COOKIE_DOMAIN);
		setcookie("BMC_user_password", $user['user_pass'],0,BMC_COOKIE,BMC_COOKIE_DOMAIN);
	}


	// Save the user's 'last login' info

	if(!empty($user['last_login'])) {
		list($old_login,$last_login)=explode("|",$user['last_login']); // Get the last login info
	} else {
		$last_login=0;
	}

	$login_info=$last_login."|".bmc_Date(0,"r")."  ( ".$_SERVER['REMOTE_ADDR']." )";

	$db->query("UPDATE ".MY_PRF."users SET last_login='{$login_info}' WHERE user_login='{$_POST['user_login']}'", false);


	// Redirect to the user page
	bmc_Go("user.php");
	exit;


	// Forgot Password
	function bmc_forgot_pass() {
		global $lang, $db, $bmc_vars;

		if(!isset($_POST['action'])) {
			bmc_Template('page_header', $lang['user_forgot_pass']);
			include CFG_PARENT."/templates/".CFG_THEME."/forgot_info.php";
			bmc_Template('page_footer'); exit;
		}


		$user=$db->query("SELECT id,user_login,user_name,user_email FROM ".MY_PRF."users WHERE user_email='{$_POST['email']}'", false);
	
		// There's no such email in the database!
		if(!$user) {
			bmc_Template('page_header', $lang['user_forgot_pass']);
			$user_message=$lang['user_forgot_false']; // Show the error
			include CFG_PARENT."/templates/".CFG_THEME."/forgot_info.php";
			bmc_Template('page_footer');
			exit;
		}

		// Reset the user password to some random string
		$password=strtoupper(substr(md5(rand(3333,9999)),0,5));
		$db->query("UPDATE ".MY_PRF."users SET user_pass='".md5($password)."' WHERE id='{$user['id']}'");

		// Load the message
		$message=@fread(fopen(CFG_PARENT."/templates/user_forgot_pass.txt","r"), filesize(CFG_PARENT."/templates/user_forgot_pass.txt"));

		// Replace the custom tags with real values (See docs for information)
		$message=str_replace("[NAME]", $user['user_name'],$message);
		$message=str_replace("[MY_SITE]", $bmc_vars['site_url'],$message);
		$message=str_replace("[USERNAME]", $user['user_login'],$message);
		$message=str_replace("[PASSWORD]", $password,$message);
		$message=str_replace("[DATE]", bmc_Date($time),$message);

		// Send the mail
		bmc_Mail($user['user_email'], $lang['user_forgot_subject']." : ".$bmc_vars['site_url'], $message);

		bmc_Template('page_header',$lang['user_forgot_send_msg']);
		echo $lang['user_forgot_send_msg'];
		bmc_Template('page_footer');
	}
?>