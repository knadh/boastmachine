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

	if(!defined('IN_BMC')) {
		die("Access Denied!");
	}

// Edit user form
if(isset($_GET['action']) && $_GET['action']=="my_account") {

	// Check the user
	$user=$db->query("SELECT * FROM ".MY_PRF."users WHERE id='{$logged_user_id}'", false);

	// User with that ID doesn't exist;
	if(!$user) {
		bmc_template('error_page', $lang['admin_user_no']);
	}

	bmc_Template('page_header', $lang['user_acc']);

	// Include the user info Form
	include CFG_ROOT."/inc/users/user_form.php";

	bmc_Template('page_footer');
	exit;
}

// Edit the user data
if($_POST['action'] == "edit_user" && isset($_POST['do']) && $_POST['do'] == "edit") {

	// Do some checking and validation
	if(empty($_POST['user_name']) || empty($_POST['user_email']) || !strpos($_POST['user_email'],"@")) {
		bmc_template('error_page', $lang['empty_fields']);
	}


	if(!empty($_POST['user_pass']) && !empty($_POST['user_pass2'])) {

		// Passwords too short
		if(strlen($_POST['user_pass']) < 5) {
			bmc_template('error_page', $lang['user_short_pass']);
		}

		// Passwords dont match
		if($_POST['user_pass'] != $_POST['user_pass2']) {
			bmc_template('error_page', $lang['user_pass_nomatch']);
		} else { $pass_changed=true; }
	}

	// Check whether the user is trying to take a used email id
	$result=$db->query("SELECT id,user_email FROM ".MY_PRF."users WHERE id='{$logged_user_id}'", false);

		if(isset($result) && $result['id'] != $logged_user_id) {
			bmc_template('error_page', $lang['user_exists_msg']);
		}

	// Yes, the password has been changed
	if(isset($pass_changed) && $pass_changed == true) {
		$password_sql=",user_pass='".md5($_POST['user_pass'])."' ";
	} else {
		$password_sql="";
	}


	// The birth date
	$birth=$_POST['user_birth_day']."/".$_POST['user_birth_month']."/".$_POST['user_birth_year'];


	// The display id
	if(isset($_POST['user_showid'])) {
		switch ($_POST['user_showid']) {
			case 'user_name':
			$show_id="user_name";
			break;

			case 'user_nick':
			$show_id="user_nick";
			break;

			case 'user_login':
			$show_id="user_login";
			break;

			default:
			$show_id="user_name";
			break;
		}
	}

	// Display email?
	if(isset($_POST['user_show_email']) && $_POST['user_show_email']=="true") {
		$show_email=1;
	} else {
		$show_email=0;
	}

	// Display pic?
	if(isset($_POST['user_show_pic']) && $_POST['user_show_pic']=="true") {
		$show_pic=1;
	} else {
		$show_pic=0;
	}


	// Display profile?
	if(isset($_POST['user_show_profile']) && $_POST['user_show_profile']=="true") {
		$show_profile=1;
	} else {
		$show_profile=0;
	}

	// The user level
	if(isset($_POST['user_level'])) {
		switch ($_POST['user_level']) {
			case '0':
			case '1':
			case '2':
			case '3':
			case '4':
			$level=$_POST['user_level'];
			break;			

			default:
			$level='1';
		}
	}


	// =========== Upload the user picture

	if($_FILES['user_pic']['name']) {
		// Check or valid filesize
		if(!isset($_FILES['user_pic']['size']) || $_FILES['user_pic']['size'] > ($bmc_vars['user_pic_size']*1024)) {
			bmc_Template('error_page',str_replace("%size%",$bmc_vars['user_pic_size'],$lang['user_pic_size_fail']));
		}

		$ext=explode(".",$_FILES['user_pic']['name']);
		$ext=trim($ext[count($ext)-1]);

		$valid_exts=array("jpg","jpeg","png","tiff","gif","bmp");
		$valid_exts=array_flip($valid_exts);

		// checks file extension. Major security flaw. Fixed in 3.1
		if(!isset($valid_exts[$ext])) {
			bmc_Template('error_page',$lang['file_fail_ext']);
		}

		$user_pic=$user."_pic.".$ext;

		@move_uploaded_file($_FILES['user_pic']['tmp_name'], CFG_PARENT."/files/".$user_pic);

			// Verify image
			$img=@getimagesize(CFG_PARENT."/files/".$user_pic);

			// BIG SIZE!
			if((!isset($img[0]) || !isset($img[1])) || ($img[0] > $bmc_vars['user_pic_width'] || $img[1] > $bmc_vars['user_pic_height'])) {
				@unlink(CFG_PARENT."/files/".$user_pic);
				bmc_Template('error_page',str_replace("%width%",$bmc_vars['user_pic_width'],str_replace("%height%",$bmc_vars['user_pic_height'],$lang['user_pic_dimension_fail'])));
			}
		$user_pic_sql=",user_pic='{$user_pic}'";
	} else {
		$user_pic_sql="";
	}


	$db->query("UPDATE ".MY_PRF."users SET user_name='{$_POST['user_name']}',user_email='{$_POST['user_email']}',user_nick='{$_POST['user_nick']}',user_url='{$_POST['user_url']}', user_location='{$_POST['user_location']}',user_birth='{$birth}',user_yim='{$_POST['user_yim']}',user_msn='{$_POST['user_msn']}',user_icq='{$_POST['user_icq']}',user_profile='{$_POST['user_profile']}',user_showid='{$show_id}',user_show_email='{$show_email}',user_show_pic='{$show_pic}',public_profile='{$show_profile}' $password_sql $user_pic_sql WHERE id='{$logged_user_id}'");

	bmc_Go("Location: ?action=list_users");

}

?>