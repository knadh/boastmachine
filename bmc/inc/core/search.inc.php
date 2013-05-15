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


	if(!$bmc_vars['user_search'] || !defined('BLOG')) { bmc_Go(BLOG_FILE."?action=search_false"); } // Searching is disabled, redirect


	$search_key="";

	// No search keywords, so display the search box
	if(empty($_REQUEST['key']) || strlen(trim($key=$_REQUEST['key'])) < 3) {

		bmc_Template('page_header', $lang['search'],"");
		$box=true; // the search box flag
		include CFG_PARENT."/templates/".CFG_THEME."/search.php";

		if(strlen(trim($key=$_REQUEST['key'])) < 3) {
			echo "<div class=\"bold_red\">".$lang['search_resut_no']."</div>";
		}

		bmc_Template('page_footer'); exit();

	} else {
		$key=$_REQUEST['key'];
	}



	switch($_REQUEST['item']) {
		case 'title':
		$item="title";
		break;

		case 'content':
		$item="summary";
		break;

		default:
		$item="title";
		break;
	}


	// Do some formatting
	$search_key=trim(substr($key,0,75));	// The untouched search keyword. Will be displayed on the search page
	$key=str_replace(" ","+", $search_key);
	$key=addslashes($key);
	$searchstring="";
	$querystring="";

		// Prepare the query string
		$parts = explode("+",$key);
		foreach ($parts as $section)
		{
		    if (!empty($querystring)) $querystring .= " AND ";

		    // split words 
		    $keywords = explode(" ",trim($section));
		    foreach ($keywords as $chunk)
		    {
		        if (!empty($searchstring)) $searchstring.= " OR ";
		        // this searches 'description' - you probably want to change this
		        $searchstring .= " $item LIKE '%{$chunk}%' ";
		    }

		    $querystring .= " ($searchstring) ";
		    $searchstring = '';
		}

		$result=$db->query("SELECT title,id,author,date,cat FROM ".MY_PRF."posts WHERE status='1' AND blog='".BLOG."' AND {$querystring} ORDER by date DESC");

		$srch_msg=str_replace("%key%",$search_key,$lang['search_reslut_msg']); // Display message


		bmc_Template('page_header', $srch_msg);

		$box=true; // the search box
		include CFG_PARENT."/templates/".CFG_THEME."/search.php"; // Search box

		// No results
		if(!$result) {
			echo "<div class=\"bold_red\">".$lang['search_resut_no']."</div>";
		}
		else {
			foreach($result as $i_post) {
				$box=false;
				$date=bmc_Date($i_post['date']); // Date
				$title=noSlash($i_post['title']); // post title
				$user_name=bmc_dispUser($i_post['author']); // User's display name

				// Get the category name
				$cat_name=$db->query("SELECT cat_name FROM ".MY_PRF."cats WHERE id='{$i_post['cat']}'", false);
				$cat_name=$cat_name['cat_name'];

				include CFG_PARENT."/templates/".CFG_THEME."/search.php"; // Search box
			}
		}

		bmc_Template('page_footer');
		exit();

?>