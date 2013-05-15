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

$user=bmc_isLogged(); // Get the username of the logged in user

if(!$user) {
	bmc_Go($bmc_vars['site_url']."/login.php");
}

// ======= The blog to which this post belongs
if(defined('BLOG') && BLOG != "") {
	$blog=BLOG;
} else {
	// There's some serious problem! The constant 'BLOG' is empty!
	bmc_template('error_page', $lang['post_no_blog']); exit;
}


// Check whether its the admin, if not, check whether the blog is open to posts
if(!defined('IS_ADMIN') || !IS_ADMIN) {
	if(!$i_blog['user_registrations']) {
		// Other users are not allowed on this blog
		bmc_Template('error_page',$lang['post_no_other']);
	} else {
		// Check whether the user is associated to this blog
		$user_blogs=unserialize($bmc_vars['logged_in_user']['blogs']);
		$user_blogs=array_flip($user_blogs);

		if(!isset($user_blogs[BLOG])) {
			bmc_Template('error_page',$lang['post_no_associate']);
		}

	}
}


// Invalid call
if(!isset($_POST['action']) || $_POST['action'] != "save_post") {
	bmc_Go($bmc_vars['site_url']); exit;
}


// Empty fields
if(!isset($_POST['title']) || empty($_POST['title']) || empty($_POST['smr']) || !isset($_POST['smr'])) {
	bmc_template('error_page', $lang['empty_fields'],$lang['empty_fields']);
	exit();
}

if(!isset($_POST['cat']) || !isset($_POST['format'])) {
	bmc_template('error_page', $lang['empty_fields'],$lang['empty_fields']);
	exit();
}

if(isset($_POST['password']) && !empty($_POST['password']) && strlen($_POST['password']) < 5) {
	bmc_template('error_page', $lang['user_short_pass'],$lang['user_short_pass']);
	exit;
}


// Setting up the data variables..

// ======= Check whether the user has entered anything in the post body field
if(!isset($_POST['msg']) || empty($_POST['msg'])) {
	$data="";
} else {
	$data=$_POST['msg'];
}


// Password for the current post, if any
if(isset($_POST['password']) && !empty($_POST['password']) && strlen($_POST['password']) > 5) {
	$password=$_POST['password'];
} else {
	$password="";
}

// Keywords if any
if(isset($_POST['keywords']) && !empty($_POST['keywords'])) {
	$keywords=$_POST['keywords'];
} else {
	$keywords="";
}


// ======= Get the post format
if(isset($_POST['format']) && !empty($_POST['format']) && $bmc_vars['post_html']) {
	switch ($_POST['format']) {
		case 'text':
		$format="text";
		break;

		case 'html':
		$format="html";
		break;

		default:
		$format="text";
		break;
	}
} else {
	$format="text";
}


// ======= Decide the post status
if(isset($_POST['status']) && !empty($_POST['status'])) {

	switch($_POST['status']) {
		case 'draft':
		$status="2";
		$draft_date=mktime(1,1,1,$_POST['draft_month'],$_POST['draft_day'],$_POST['draft_year']); // Being a draft, set its appearance date
		break;

		case 'hidden':
		$status="0";
		break;

		default:
		$draft_date="";
		$status="1";
		break;
	}
}


// ======= Is Commenting enabled for thist post?
if(isset($_POST['user_comment'])) {
	$user_comment=1;
} else {
	$user_comment=0;
}


// ======= Enable comment notification? (3.1)
if(isset($_POST['user_comment_notify'])) {
	$user_comment_notify=1;
} else {
	$user_comment_notify=0;
}


// ======= Is Voting enabled for this post?
if(isset($_POST['user_vote'])) {
	$user_vote=1;
} else {
	$user_vote=0;
}

// ======= Is AutoBr enabled for this post?
if(isset($_POST['post_autobr'])) {
	$post_autobr=1;
} else {
	$post_autobr=0;
}

// ======= Accept trackbakcs?
if(isset($_POST['accept_trackback'])) {
	$accept_trackback=1;
} else {
	$accept_trackback=0;
}


// ======= The target category
if(isset($_POST['cat'])) {
	// Something wrong. The selected category couldnt be found in the current blog
	if(!$db->query("SELECT id FROM ".MY_PRF."cats WHERE id='{$_POST['cat']}' AND blog='{$blog}'", false)) {
	bmc_template('error_page', $lang['post_no_cat']); exit;
	}

} else {
	// No category!
	bmc_template('error_page', $lang['post_no_cat']); exit;
}

// ======= File attachments :)
$file_list="";
if(isset($_POST['files']) && !empty($_POST['files'])) {

	$files=explode("|",$_POST['files']); // Create an array of the posted filenames

	for($n=0;$n<count($files);$n++) {
		if(!empty($files[$n])) {

			$file_specs=explode("_",$files[$n]);

			if($file_specs[0] == $user) {
				$file_name=implode("_",$file_specs);
				$file_list.=$file_name."|";
			}
		}
	}
}

// ======= Everything has been thoroughly checked and formatted. Now enter the data into the db
$user_id=$db->query("SELECT id FROM ".MY_PRF."users WHERE user_login='{$user}'", false);
$user_id=$user_id['id'];

$db->query("INSERT INTO ".MY_PRF."posts (title,cat,author,summary,data,keyws,file,format,date,draft_date,password,status,user_comment,user_comment_notify,user_vote,post_autobr,accept_trackback,blog,user_ip) VALUES('{$_POST['title']}','{$_POST['cat']}','{$user_id}','{$_POST['smr']}','{$data}','$keywords','{$file_list}','{$format}','".time()."','{$draft_date}','{$password}','{$status}','{$user_comment}','{$user_comment_notify}','{$user_vote}','{$post_autobr}','{$accept_trackback}','{$blog}','{$_SERVER['REMOTE_ADDR']}')");


	bmc_updateCache('archive'); // Update the archive cache


	// ======= Generate the RSS feeds if set
	if($bmc_vars['rss_feed']) {
		include CFG_ROOT."/inc/core/rss.build.php";
	}


	// ======= Send Pings if necessary
	if($bmc_vars['send_ping']) {
		bmc_ping(); // Call the ping function
	}

	// ======= Send trackbacks if any
	if(!empty($_POST['track_urls'])) {
		// Get this post's id
		$post_id=$db->query("SELECT id FROM ".MY_PRF."posts ORDER BY id DESC LIMIT 1", false);
		$send_track_back=true;
		include CFG_PARENT."/trackback.php";
	}



	// ====== Redirect
	if(defined('IS_ADMIN') && defined('IN_ADMIN')) {
		bmc_Go("Location: {$bmc_vars['site_url']}/".BMC_DIR."/admin.php?blog={$blog}&action=list_posts"); // The admin
	} else {
		bmc_Go("Location: {$bmc_vars['site_url']}/".BLOG_FILE); // The user
	}

?>