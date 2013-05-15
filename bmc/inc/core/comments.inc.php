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

	// Show the comments for this post

	if($bmc_vars['user_comment_threading']) {
		// Threading is enabled

		// Get the list of all TOP LEVEL comments
		$comments=$db->query("SELECT * FROM ".MY_PRF."comments WHERE post='{$post_data['id']}' AND parent_id='0' ORDER BY date");


		// Pass each TOP level comment to the threading function
		foreach($comments as $cmt) {
			bmc_ThreadComment($cmt['id'], $post_data['id']);
		}

	}
	else {

		// No THREADING

		$comments=$db->query("SELECT * FROM ".MY_PRF."comments WHERE post='{$post_data['id']}' ORDER BY date");

		// Loop through the comments and display them
		foreach($comments as $cmt) {

			if($cmt['author']) {
				$author=bmc_dispUser($cmt['author']);
			} // end if


			$date=bmc_Date($cmt['date']);
			$comment=bmc_htmlentities($cmt['data']);
			$comment=bmc_BlockWords($comment);

			if($bmc_vars['auto_convert_link']) $comment=bmc_cnvall($comment); // Autoconvert the links

			$comment=bmc_Smilify($comment);

			$comment=bmc_wordwrap($comment);
			$comment=nl2br($comment);

			include CFG_PARENT."/templates/".CFG_THEME."/comment.php";
		}
	}



	// ================= Comments 'Threader' (3.1) ================= 

	function bmc_ThreadComment($cmt_id, $post_id)	{
		global $db,$bmc_vars,$lang,$post_data,$total_threaded_comments;

		$thread_depth=7;	// Number of levels deep to which comments can be replied to

		// Get the current comment details
		$cmt = $db->query("SELECT * FROM ".MY_PRF."comments WHERE id = '$cmt_id'", false);

		if($cmt['author']) {
				$author=bmc_dispUser($cmt['author']);
		} // end if


			$date=bmc_Date($cmt['date']);
			$comment=bmc_htmlentities($cmt['data']);
			$comment=bmc_BlockWords($comment);

			if($bmc_vars['auto_convert_link']) $comment=bmc_cnvall($comment); // Autoconvert the links

			$comment=bmc_Smilify($comment);

			$comment=bmc_wordwrap($comment);
			$comment=nl2br($comment);


		include CFG_PARENT."/templates/".CFG_THEME."/comment_thread.php";
	}

	// =============================================================



	// Load the comment form
	if($bmc_vars['user_comment'] && $post_data['user_comment']) {
		include CFG_PARENT."/templates/".CFG_THEME."/comment_form.php";
	}
?>