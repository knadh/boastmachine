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


	// Validate the user
	$user=bmc_isLogged(); // the user

	if(empty($user)) {
		bmc_Go($bmc_vars['site_url']."/login.php");
		exit;
	}

	// ====================
	// Logout (We should allow the user to Logout, even if he's an admin or not :)

	if(isset($_GET['action']) && $_GET['action']=="logout") {
		// Delete the cookies
		setcookie("BMC_user", "" ,time()-604800,BMC_COOKIE,BMC_COOKIE_DOMAIN);
		setcookie("BMC_user_password", "" ,time()-604800,BMC_COOKIE,BMC_COOKIE_DOMAIN);

		bmc_Go($bmc_vars['site_url']."/login.php");
		exit();
	}


	// Get the userdata
	$user_info=$db->query("SELECT id,user_login,last_login,user_pass,level,blogs FROM ".MY_PRF."users WHERE user_login='{$user}'", false);
	// Extra security check
	if(empty($user_info['user_login'])) {
		bmc_Go($bmc_vars['site_url']."/login.php");
		exit;
	}

	if(isset($_REQUEST['blog']) && !defined('BLOG')) {
		define("BLOG", $_REQUEST['blog']);
	}

	$logged_user_id=$user_info['id'];

	if(isset($_REQUEST['blog']) && !defined('BLOG') && is_numeric($_REQUEST['blog'])) {
		if(!$db->row_count("SELECT id FROM ".MY_PRF."blogs WHERE id='{$_REQUEST['blog']}' AND frozen='0'")) {
			bmc_template('error_page', $lang['post_no_blog']);
		} else {
			define('BLOG', $_REQUEST['blog']);
		}
	}

	// Perform various actions
	if(isset($_REQUEST['action'])) {


		switch($_REQUEST['action']) {

			// New post
			case 'new_post':
				if($user_info['level'] >= 2) {
					bmc_Template('page_header', $lang['user_new_title']);
					include CFG_ROOT."/inc/users/post_form.php";
					bmc_Template('page_footer');
					exit();
				} else {
					bmc_Go("?null");
				}

			// My account
			case 'my_account':
			case 'edit_user':
			include CFG_ROOT."/inc/users/user.inc.php";
			exit;


			// List the posts
			case 'list_posts':

			case 'delete_posts': // Delete multiple posts
				if($user_info['level'] >= 2) {
					include CFG_ROOT."/inc/users/posts.inc.php";
					exit;
				} else {
					bmc_Go("?null");
				}

			case 'delete_post': // Delete a single post (3.1)
				if($user_info['level'] >= 2) {
					if(isset($_REQUEST['blog']) && isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) && is_numeric($_REQUEST['blog']))	{

						if(!defined('IS_ADMIN') || !IS_ADMIN) {
							$post_del_data=$db->query("SELECT author FROM ".MY_PRF."posts WHERE id='{$_REQUEST['id']}'", false);	// Get the post author
							// Check whether the real author is the one deleting the post
							if($user_info['id'] == $post_del_data['author']) {
								$db->query("DELETE FROM ".MY_PRF."posts WHERE blog='{$_REQUEST['blog']}' AND id='{$_REQUEST['id']}'");
								$db->query("DELETE FROM ".MY_PRF."comments WHERE post='{$_REQUEST['id']}'");
								// Delete the post
								bmc_updateCache('archive'); // Update the cache
								include CFG_ROOT."/inc/core/rss.build.php";	// Rebuild xml feeds
							}
						} else {
							$db->query("DELETE FROM ".MY_PRF."posts WHERE blog='{$_REQUEST['blog']}' AND id='{$_REQUEST['id']}'");
							$db->query("DELETE FROM ".MY_PRF."comments WHERE post='{$_REQUEST['id']}'");
							bmc_updateCache('archive'); // Update the cache
							include CFG_ROOT."/inc/core/rss.build.php";	// Rebuild xml feeds

							// Delete the post
						}
					}
						bmc_Go($bmc_vars['site_url']."/".BLOG_FILE);
				} else {
					bmc_Go("?null");
				}


			// Save a new post
			case 'save_post':
				if($user_info['level'] >= 2) {
					include CFG_ROOT."/inc/users/post.inc.php";
					exit;
				} else {
					bmc_Go("?null");
				}

			// Preview a post
			case 'preview_post':
				if($user_info['level'] >= 2) {
					include CFG_ROOT."/inc/users/preview_post.php";
					exit;
				} else {
					bmc_Go("?null");
				}

			// Modify a post
			case 'mod_post':

				if($user_info['level'] > 2) {
				include CFG_ROOT."/inc/users/edit.inc.php";
				exit;
				} else {
					bmc_Go("?null");
				}

			// Edit page
			case 'edit_post':
				if($user_info['level'] > 2 && isset($_GET['blog']) && is_numeric($_GET['blog'])) {
				bmc_Template('page_header', $lang['post_edit_title']);
				include CFG_ROOT."/inc/users/edit_form.php";
				bmc_Template('page_footer');
				exit;
				} else {
					bmc_Go("?null");
				}

			// Edit comments
			case 'edit_comments':
			case 'mod_comment':
				if($user_info['level'] > 2) {
				include CFG_ROOT."/inc/core/admin/comments.inc.php";
				exit;
				} else {
					bmc_Go("?null");
				}

			// Delete comments
			case 'delete_comment':
				if($user_info['level'] > 2) {
				include CFG_ROOT."/inc/core/admin/comments.inc.php";
				exit;
				} else {
					bmc_Go("?null");
				}

			default:
			bmc_Go("?null");
	
		}
	}



	// The last login info

	$last_login=0;
	$current_login=0;

	if($user_info['last_login']) {
		list($last_login, $current_login)=explode("|", $user_info['last_login']);
	}

	bmc_Template('page_header', $lang['user_mbr_title']);

	include CFG_PARENT."/templates/".CFG_THEME."/user_account.php";

	bmc_Template('page_footer');

?>