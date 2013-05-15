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

// MAIL ARTICLE TO A FRIEND SCRIPT

	include_once dirname(__FILE__)."/config.php";
	include_once dirname(__FILE__)."/$bmc_dir/main.php";



	if(empty($_POST['action']) && empty($_REQUEST['id']) || empty($_REQUEST['blog']) || !is_numeric($_REQUEST['blog'])) { bmc_Go("index.php?mail=false"); }

	// Invalid blog id
	if(!$i_blog['blog_name']) {
		bmc_template('error_page',$lang['no_blog']);
	}

	// Check whether sending posts is enabled
	if(!$bmc_vars['user_send_post']) {
		bmc_Template('error_page',$lang['snd_no']);
	}

	$i_post=$db->query("SELECT id,author,title,date FROM ".MY_PRF."posts where id='{$_REQUEST['id']}' AND status='1' AND blog='{$i_blog['id']}'", false);

	// Invalid post id
	if(!$i_post) {
		bmc_template('error_page',$lang['no_id']);
	}


	$author=bmc_dispUser($i_post['author']);


// Send to friend Form

if(!isset($_POST['action'])) {
	$title_str=str_replace("%title%",$i_post['title'],$lang['snd_title']);
	bmc_Template('page_header',$title_str,"");
	include CFG_PARENT."/templates/".CFG_THEME."/mail_friend.php";
	bmc_Template('page_footer'); exit();
}

////////////////////

// Some Form validation

if((empty($_POST['e1']) && empty($_POST['e2']) && empty($_POST['e3']) && empty($_POST['e4']) && empty($_POST['e5'])) || (empty($_POST['email']) || !strpos($_POST['email'], "@")) || ($_POST['e1'] && !strpos($_POST['e1'], "@")) || ($_POST['e2'] && !strpos($_POST['e2'], "@")) || ($_POST['e3'] && !strpos($_POST['e3'], "@")) || ($_POST['e4'] && !strpos($_POST['e4'], "@")) || ($_POST['e5'] && !strpos($_POST['e5'], "@"))) {
	bmc_template('error_page', $lang['snd_inv_email'],$lang['snd_inv_email_msg']);

}

///////////////////////////////////////////////////

	$subject=str_replace("[NAME]", $_POST['name'], $bmc_vars['post_send_subject']);

	// Open the mail.txt template file and replace keywords
	// with appropriate data

	$message=@fread(fopen(CFG_PARENT."/templates/send_post.txt", "r"), filesize(CFG_PARENT."/templates/send_post.txt"));

	$message=str_replace("[NAME]", $_POST['name'], $message);
	$message=str_replace("[SITE]", $bmc_vars['site_url']."/".BLOG_FILE, $message);
	$message=str_replace("[URL]", $bmc_vars['site_url']."/".BLOG_FILE."?id=".$_REQUEST['id'], $message);
	$message=str_replace("[EMAIL]", $_POST['email'], $message);
	$message=str_replace("[TITLE]", $i_post['title'], $message);
	$message=str_replace("[AUTHOR]", $author, $message);
	$message=str_replace("[AUTHOR_PAGE]", $bmc_vars['site_url']."/profile.php?id=".$i_post['author'] , $message);
	$message=str_replace("[COMMENTS]", $_POST['comments'], $message);
	$message=str_replace("[DATE]", bmc_Date($i_post['date']), $message);
	$message=stripslashes($message);


	// Send to the emails, if entered
	if(!empty($_POST['e1'])) { bmc_Mail($_POST['e1'], $subject, $message); }
	if(!empty($_POST['e2'])) { bmc_Mail($_POST['e2'], $subject, $message); }
	if(!empty($_POST['e3'])) { bmc_Mail($_POST['e3'], $subject, $message); }
	if(!empty($_POST['e4'])) { bmc_Mail($_POST['e4'], $subject, $message); }
	if(!empty($_POST['e5'])) { bmc_Mail($_POST['e5'], $subject, $message); }


// Log the mail
$a = fopen(CFG_ROOT."/inc/vars/mail_log.txt", "a") or bmc_template('error_page', $lang['admin_log_write_msg'], $lang['admin_clr_log_msg']);
$write = fputs($a, bmc_Date($i_post['date'])."|{$_POST['name']}|{$_POST['email']}|{$i_post['title']}|{$_REQUEST['id']}|{$_SERVER['REMOTE_ADDR']}|");
	if(isset($_POST['e1'])) { fputs($a, $_POST['e1']."|"); }
	if(isset($_POST['e2'])) { fputs($a, $_POST['e2']."|"); }
	if(isset($_POST['e3'])) { fputs($a, $_POST['e3']."|"); }
	if(isset($_POST['e4'])) { fputs($a, $_POST['e4']."|"); }
	if(isset($_POST['e5'])) { fputs($a, $_POST['e5']."|"); }
	fputs($a, "\n");
fclose($a);


	bmc_Template('page_header', str_replace("%article%",$i_post['title'],$lang['snd_success']),"");
	$sent=str_replace("%article%","<a href=\"".BLOG_FILE."?id={$_REQUEST['id']}\">{$i_post['title']}</a>",$lang['snd_success']);

	echo $sent."<br /><br />";

	if(isset($_POST['e1'])) { echo "<a href=\"mailto:{$_POST['e1']}\">{$_POST['e1']}</a><br />"; }
	if(isset($_POST['e2'])) { echo "<a href=\"mailto:{$_POST['e1']}\">{$_POST['e2']}</a><br />"; }
	if(isset($_POST['e3'])) { echo "<a href=\"mailto:{$_POST['e1']}\">{$_POST['e3']}</a><br />"; }
	if(isset($_POST['e4'])) { echo "<a href=\"mailto:{$_POST['e1']}\">{$_POST['e4']}</a><br />"; }
	if(isset($_POST['e5'])) { echo "<a href=\"mailto:{$_POST['e1']}\">{$_POST['e5']}</a><br />"; }

	bmc_Template('page_footer');

?> 