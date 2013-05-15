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


	define('BLOG', $blog_id);
	include_once dirname(__FILE__)."/main.php";

	// Load the search
	if(isset($_REQUEST['action']) && $_REQUEST['action']=="search" && defined('BLOG')) {
		include CFG_ROOT."/inc/core/search.inc.php";
		exit;
	}

	// Produce Printer friendly page
	if(isset($_GET['print']) && is_numeric($_GET['print']) && defined('BLOG')) {
		include CFG_ROOT."/inc/core/printer.inc.php";
		exit;
	}

	// The posts processor
	include_once CFG_ROOT."/inc/core/main.inc.php";

?>