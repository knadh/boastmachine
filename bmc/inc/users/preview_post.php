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

// Invalid call
if(!isset($_POST['action']) || $_POST['action'] != "preview_post") {
	bmc_Go($bmc_vars['site_url']); exit;
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
		$status="1";
		break;
	}
}


$summary=stripslashes($_POST['smr']);

$summary=bmc_smilify($summary);	// Smilify the text



// Load the bbCode parser
include CFG_ROOT."/inc/users/bbcode.php";
$summary=bmc_bbCode($summary);

$summary=bmc_wordwrap($summary);
$summary=nl2br($summary);
$_POST['title']=stripslashes($_POST['title']);

bmc_template('page_header', $lang['post_post_preview']);
include CFG_PARENT."/templates/default/post_preview.php";
bmc_template('page_footer');

?>