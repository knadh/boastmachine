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


	//================ Delete a comment / all comments

	if(isset($_REQUEST['action']) && $_REQUEST['action']=="delete_comment" && isset($_REQUEST['id'])) {

		// Delete a specific comment and all threaded comments

		if(is_numeric($_REQUEST['id'])) {

			$delete_threads="";

			if($bmc_vars['user_comment_threading']) {
				$delete_threads="AND parent_id='{$_REQUEST['id']}'";	// Threading is ON, so delete all the threads of this comment
			}

			$db->query("DELETE FROM ".MY_PRF."comments WHERE id='{$_REQUEST['id']}' {$delete_threads}");
		} else {
			// Delete all comments
			if(isset($_REQUEST['post']) && is_numeric($_REQUEST['post'])) {
				$db->query("DELETE FROM ".MY_PRF."comments WHERE post='{$_REQUEST['post']}'");
			}
		}


		// Redirect the user
		if(defined('IN_ADMIN')) {
			bmc_Go("admin.php?action=edit_comments&blog=".BLOG."&id=".$_REQUEST['post']);
		} else {
			bmc_Go("Location: {$bmc_vars['site_url']}/user.php?action=edit_comments&blog=".BLOG."&id=".$_REQUEST['post']);
		}
	}

	//=============== Modify a comment =============

	if(isset($_POST['action']) && $_POST['action']=="mod_comment" && isset($_POST['id']) && is_numeric($_POST['id']) && defined('BLOG')) {

		// Do some validations
		if(empty($_POST['comments'])) {
			bmc_template('error_admin',$lang['empty_fields']);
		}

		// If the comment was not by a registered user, then check whether the guest's name is filled in
		if(empty($_POST['author'])) {
			if(empty($_POST['auth_name'])) {
				bmc_template('error_admin',$lang['empty_fields']);
			}
		}

		// Do the MySQL updation


		if(empty($_POST['author'])) {
			$db->query("UPDATE ".MY_PRF."comments SET auth_name='{$_POST['auth_name']}',auth_email='{$_POST['auth_email']}',auth_url='{$_POST['auth_url']}',data='{$_POST['comments']}' WHERE id='{$_POST['id']}' AND post='{$_POST['post']}'");
		} else {
			$db->query("UPDATE ".MY_PRF."comments SET data='{$_POST['comments']}' WHERE id='{$_POST['id']}' AND post='{$_POST['post']}' AND author='{$_POST['author']}'");
		}

		if(defined('IN_ADMIN')) {
			bmc_Go("admin.php?action=edit_comments&blog=".BLOG."&id=".$_POST['post']);
		} else {
			bmc_Go("Location: {$bmc_vars['site_url']}/user.php");
		}

	}


	if(!isset($_REQUEST['blog']) || !isset($_REQUEST['id'])) {
		// No blog!
		bmc_Go("Location: ?null");
	}

	// Check whether commenting is enabled for this post
	if(defined('IN_ADMIN')) {
		$result=$db->query("SELECT user_comment FROM ".MY_PRF."posts WHERE id='{$_REQUEST['id']}'", false);
	} else {
		$result=$db->query("SELECT user_comment FROM ".MY_PRF."posts WHERE id='{$_REQUEST['id']}' AND author='{$logged_user_id}'", false);
		// An extra check to verify whether the current user is the actual poster
	}


	if(!$bmc_vars['user_comment'] || !$result['user_comment']) {
		bmc_template('error_admin', $lang['cmt_no_comment']);
	}



	// Get the comments
	$result=null;

		$result=$db->query("SELECT * FROM ".MY_PRF."comments WHERE post='{$_GET['id']}' ORDER by date DESC");


	// No comments have ben made yet.
	if(!$result) {
		if(defined('IN_ADMIN')) bmc_template('error_admin', $lang['cmt_no_one']);
		else  bmc_template('error_page', $lang['cmt_no_one']);
	}

	// Page header
		if(defined('IN_ADMIN')) bmc_Template('admin_header', $lang['admin_cmts']);
		else bmc_Template('page_header', $lang['admin_cmts']);

echo <<<EOF
	<br /><br />
	<input type="button" value="{$lang['del_cmts_del_but']}" onClick="javascript:delCmt(0,1);" />
	<br /><br />

EOF;

	foreach($result as $comment) {

	?>

	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
	<input type="hidden" name="action" value="mod_comment" />
	<input type="hidden" name="post" value="<?php echo $_GET['id']; ?>" />
	<input type="hidden" name="id" value="<?php echo $comment['id']; ?>" />
	<input type="hidden" name="blog" value="<?php echo $_GET['blog']; ?>" />
	<?php

	if($comment['author']) { ?>
	<input type="hidden" name="author" value="<?php echo $comment['author']; ?>" />
	<?php
	}

		// The comment was made by a registered user
		if($comment['author']) {
			$user_name=$db->query("SELECT user_login,user_name FROM ".MY_PRF."users WHERE id='{$comment['author']}'", false);
			echo "{$lang['posted_by']} : <a href=\"?action=edit_user&amp;user={$comment['author']}\" title=\"{$user_name['user_name']}\"><strong>{$user_name['user_login']}</strong></a>\n";
			echo " {$lang['str_on']} ".bmc_Date($comment['date']);;
		}
	else {

?>

<?php echo $lang['posted_by']; ?><br />
<input type="text" name="auth_name" value="<?php echo bmc_htmlentities($comment['auth_name']); ?>" /><br />
<?php echo $lang['user_email']; ?><br />
<input type="text" name="auth_email" value="<?php echo bmc_htmlentities($comment['auth_email']); ?>" /><br />
<?php echo $lang['user_url']; ?><br />
<input type="text" name="auth_url" value="<?php echo bmc_htmlentities($comment['auth_url']); ?>" /><br />

<?php
	}
?>

<br />
<textarea name="comments" rows="10" cols="50">
<?php echo bmc_htmlentities($comment['data']); ?>
</textarea><br />
<input type="submit" value="<?php echo $lang['del_cmts_save_but']; ?>" />  &nbsp;&nbsp;
<input type="button" value="<?php echo $lang['admin_but_del']; ?>" onClick="javascript:delCmt('<?php echo $comment['id']; ?>');" />
</form><br /><br />

<?php
	}
?>

<script type="text/javascript">
<!--

function delCmt(id,all) {

	// Delete all comments
	if(all) {
		var msg=confirm("<?php echo $lang['del_cmt_msg']; ?>");
		if(!msg) return false;
		document.location="admin.php?action=delete_comment&id=x&post=<?php echo $_REQUEST['id']; ?>&blog=<?php echo BLOG; ?>";
		return false;
	}

	var msg=confirm("<?php echo $lang['del_cmt_one']; ?>");
	if(!msg) return false;
	document.location="admin.php?action=delete_comment&id="+id+"&blog=<?php echo BLOG; ?>&post=<?php echo $_REQUEST['id']; ?>";

}


//-->
</script>

<?php
	// Page header
		if(defined('IN_ADMIN')) bmc_Template('admin_footer');
		else bmc_Template('page_footer');

?>