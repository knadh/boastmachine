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



	if( !isset($_GET['print']) || !is_numeric($_GET['print']) ) {
		bmc_Go($bmc_vars['site_url']);
	}

	if(!defined('BLOG')) {
		bmc_Go($bmc_vars['site_url']);
	}

		$blog_id=BLOG;


		// Check the blog whether its frozen or not
		if(empty($i_blog['blog_name'])) {
			bmc_template('error_page', $lang['amin_blog_no']);		
		}


		$i_post=$db->query("SELECT author,title,summary,data,date,post_autobr,id,cat FROM ".MY_PRF."posts WHERE id='{$_GET['print']}' AND status='1' AND blog='{$blog_id}'", false);

		if(empty($i_post['title'])) {
			bmc_template('error_page', $lang['no_id']);		
		}


	if(!empty($i_post['password'])) {
		// Send the auth interface
		if( !isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW'] != $post['password']) {
			header('WWW-Authenticate: Basic realm="Password protected post @ '.BLOG_NAME.'"');
			header('HTTP/1.0 401 Unauthorized');
			bmc_Template('error_page', $lang['post_pass_invalid']);
		}
	}


		// bbCode parser
		include CFG_ROOT."/inc/users/bbcode.php";

		// The post body
		if(!empty($i_post['data'])) {
			$body=$i_post['data'];
			$body=bmc_wordwrap($body, 65);

			$body=bmc_blockWords($body);	// Filter bad words
			$body=bmc_Smilify($body); // Smilies
			$body=bmc_bbCode($body); // bbCode

			if($i_post['post_autobr']) {
					$body=nl2br($body);
				}

		} else {
			$body=false;
		}



		// The post summary
		$summary=$i_post['summary'];
		$summary=bmc_blockWords($summary);	// Filter bad words
		$summary=bmc_Smilify($summary); // Smilies
		$summary=bmc_bbCode($summary); // bbCode

		$summary=bmc_wordwrap($summary, 65);

		if($i_post['post_autobr']) {
			$summary=nl2br($summary);
		}


		$date=bmc_Date($i_post['date']);



		$user_name=bmc_dispUser($i_post['author']);

		$cat=$db->query("SELECT cat_name FROM ".MY_PRF."cats WHERE id='{$i_post['cat']}'", false);
		$cat=$cat['cat_name'];

		// Include the printer friendly page template
		include CFG_PARENT."/templates/".CFG_THEME."/printer_friendly.php";
		exit;



?>