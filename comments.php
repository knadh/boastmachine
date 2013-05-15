<?php

/*
  ===========================

  boastMachine v3.1 (BETA 2)
  Released : Tuesday, May 5th 2005 ( 05/05/2005 )
  http://boastology.com

  Developed by Kailash Nadh
  Email   : mail@kailashnadh.name
  Website : kailashnadh.name, bnsoft.net

  boastMachine is a free software and is licensed under GPL (General public license)

  ===========================
*/


	include_once dirname(__FILE__)."/config.php";
	include_once dirname(__FILE__)."/$bmc_dir/main.php";


	if(!isset($_REQUEST['id']) || !is_numeric(trim($_REQUEST['id']))) {
		bmc_Go($bmc_vars['site_url']);
	}


	// Check whether commenting is enabled
	if(!$bmc_vars['user_comment']) {
		bmc_template('error_page', $lang['cmt_no_comment']);
	}

	if(isset($_REQUEST['blog']) && is_numeric($_REQUEST['blog'])) {
		$i_post=$db->query("SELECT id,user_comment,user_comment_notify,title,author FROM ".MY_PRF."posts WHERE blog='{$_REQUEST['blog']}' AND id='{$_REQUEST['id']}' AND status='1'", false);

		if(!$i_blog['blog_name']) {
			bmc_template('error_page', $lang['no_blog']);
		}

		if(!$i_post['id']) {
			bmc_template('error_page', $lang['no_id']);
		}

		// Commenting is not enabled for the current post
		if(!$i_post['user_comment']) {
			bmc_template('error_page', $lang['cmt_no_comment_post']);
		}

	
	} else {
			bmc_template('error_page', $lang['no_blog']);
	}


	$user=bmc_isLogged(); // The currently logged in user

	// Check whether guests can comment
	if(!$bmc_vars['user_comment_guests'] && !$user) {
		bmc_template('error_page', $lang['cmt_guest_no']);
	}

	// Show the comment form
	if(!isset($_POST['action']) || $_POST['action'] != "post_comment") {
		bmc_Template('page_header', $lang['cmt_post_ttl']);
		include CFG_PARENT."/templates/".CFG_THEME."/comment_form.php";
		bmc_Template('page_footer');
		exit;
	}


// =============== Save the comment

if(isset($_POST['action']) && $_POST['action'] == "post_comment" && isset($_POST['id']) && is_numeric($_POST['id'])) {

	// Check whether the user is posting more than 1 comment/session
	if($bmc_vars['user_comment_session']) {
		if(isset($_COOKIE['bmc_cmt_sess'])) {
			$commented=unserialize($_COOKIE['bmc_cmt_sess']); // Get the list of posts on which the user has commented

			if(isset($commented[$_REQUEST['id']])) {
				bmc_template('error_page', $lang['error'],$lang['del_cmt_sess']);
			}

		}
	}

	// Check for empty fields
	if(empty($user)) {

		if(empty($_POST['name'])) {
			bmc_template('error_page', $lang['empty_fields']);
		}

		if(empty($_POST['email'])) {
			$email="";
		} else {
			$email=$_POST['email'];
		}

		if(empty($_POST['url'])) {
			$url="";
		} else {
			$url=$_POST['url'];
		}

	} else {
		// Get the user's ID
		$user_info=$db->query("SELECT id FROM ".MY_PRF."users WHERE user_login='{$user}'", false);
		$user_id=$user_info['id'];
	}


	if(!isset($_POST['comments']) || empty($_POST['comments'])) {
		bmc_template('error_page', $lang['empty_fields']);
	}


	// If Image verification is enabled, DO IT (Added in 3.1)
		if($bmc_vars['image_verify']) {
			session_start();	// Start the session

			// Check whether the code entered by the user matches the code the script generated
			if(!isset($_SESSION['img_verification']) || trim($_POST['verify']) != $_SESSION['img_verification']) {
				bmc_template('error_page', $lang['cmt_verify_wrong']);
			} else {
				unset($_SESSION['img_verification']);
			}
		}


	// Get the parent comment id if it was a thread reply
	$parent_id="";
	if($bmc_vars['user_comment_threading']) {
		if(!empty($_POST['parent_id'])) {
			$parent_id=$_POST['parent_id'];
		}
	}


	// Remember info of the guest (3.1)
	if(isset($_COOKIE['BMC_cmt_guest']) && !isset($_POST['remember'])) {
		setcookie("BMC_cmt_guest", '' ,time()-604800,BMC_COOKIE,BMC_COOKIE_DOMAIN);
	}

	if(!$user && isset($_POST['remember'])) {

		$guest_info['name']=$_POST['name'];
		$guest_info['email']=$_POST['email'];
		$guest_info['url']=$_POST['url'];

		$guest_info_serialized=serialize($guest_info);	// Serialize the array

		setcookie("BMC_cmt_guest", $guest_info_serialized ,time()+604800,BMC_COOKIE,BMC_COOKIE_DOMAIN);
	}



	// CHECK FOR SPAM
	bmc_filterSpam($_POST['comments']);


	$time_now=time();

	// Save the name,email,url for unregistered users
	if(empty($user)) {
		$db->query("INSERT INTO ".MY_PRF."comments (auth_name,auth_email,auth_url,auth_ip,data,post,parent_id,date,blog) VALUES('{$_POST['name']}','$email','$url','".$_SERVER['REMOTE_ADDR']."','{$_POST['comments']}','{$_POST['id']}','{$parent_id}','{$time_now}','{$_REQUEST['blog']}')");
	} else {
		$db->query("INSERT INTO ".MY_PRF."comments (author,auth_ip,data,post,parent_id,date,blog) VALUES('{$user_id}','".$_SERVER['REMOTE_ADDR']."','{$_POST['comments']}','{$_POST['id']}','{$parent_id}','".time()."','{$_REQUEST['blog']}')");
	}


	// Notify the author about the comment (3.1)
	if($bmc_vars['user_comment_notify'] && $i_post['user_comment_notify']) {

		// Get the email of the author of the post
		$author_info=$db->query("SELECT user_email,user_login FROM ".MY_PRF."users WHERE id='{$i_post['author']}'", false);

		$message=@fread(fopen(CFG_PARENT."/templates/new_comment_notify.txt","r"), filesize(CFG_PARENT."/templates/new_comment_notify.txt"));

			if(isset($guest_info['name'])) {
				$poster_name=$guest_info['name'];	// Guest
			} else {
				$poster_name=$user;	// Registered user
			}

		// If the author himself is not commenting, send the info..
		if($user != $author_info['user_login']) {

			// Replace the custom tags with real values (See docs for information)
			$message=str_replace("[NAME]", $poster_name,$message);
			$message=str_replace("[IP]", $_SERVER['REMOTE_ADDR'],$message);
			$message=str_replace("[POST_TITLE]", $i_post['title'],$message);
			$message=str_replace("[POST_URL]", $bmc_vars['site_url']."/".bmc_SE_friendly_url('post',BLOG_FILE,$i_post['id'],$i_post['title']), $message);
			$message=str_replace("[TIME]", bmc_Date($time_now),$message);

			// There goest the mail !
			bmc_Mail($author_info['user_email'],$lang['cmt_notfiy_subject'], $message);
		}

	}

	// Set the cookie for 'once per session' comment
	if($bmc_vars['user_comment_session']) {
		$commented[$_REQUEST['id']]=1;
		setcookie('bmc_cmt_sess',serialize($commented),0,BMC_COOKIE,BMC_COOKIE_DOMAIN);
	}




	bmc_Go($bmc_vars['site_url']."/".bmc_SE_friendly_url('post',$i_blog['blog_file'],$i_post['id'],$i_post['title'])."#cmt");	// redirect to the posts page

}


?>