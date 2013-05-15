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

	if(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) {
		$flag=false; // No post id was specified, so set the flag to false
	} else {
		$post_data=null;
		$post_data=$db->query("SELECT id,title FROM ".MY_PRF."posts WHERE status='1' AND user_vote='1' AND id='{$_REQUEST['id']}'", false);
		// Get some post data

		if(!empty($post_data['id'])) {
			$flag=true;
		}

	}


	if($flag && isset($_POST['action']) && $_POST['action']=="rate" && is_numeric($_POST['vote'])) {

		if($_POST['vote'] < 1 || $_POST['vote'] > 5) {
			$vote=5;
		} else {
			$vote=$_POST['vote'];
		}

		if(isset($_COOKIE["bmc_Votes"])) {
			$voted=unserialize($_COOKIE["bmc_Votes"]); // Get the list of posts on which the user has voted
		}

		if(!isset($voted[$post_data['id']])) {
			$votes=$db->query("SELECT total,number FROM ".MY_PRF."votes WHERE post='{$post_data['id']}'", false);

			if(isset($votes['number'])) {
				$total=$votes['total']+$vote; // Total rating
				$number=$votes['number']+1; // Total number of votes
				// Update the database
				$db->query("UPDATE ".MY_PRF."votes SET number='$number',total='$total' WHERE post='{$post_data['id']}'");
			} else {
				// Add the vote data to the database
				$db->query("INSERT INTO ".MY_PRF."votes (post,total,number) VALUES('{$post_data['id']}', '{$vote}', '1')");
			}

			// Set the cookie so that the user cant vote again for this article
			$voted[$post_data['id']]=1;
			setcookie("bmc_Votes", serialize($voted) ,time()+172800,BMC_COOKIE,BMC_COOKIE_DOMAIN);
		}

		// Set the flag to false so that the window closes
		$flag=false;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php echo $lang['votes']; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<style type="text/css">
	<!--
	body, br, p, html {
		font-family: verdana;
		font-size: 10px;
		background: #F7F7F7;
	}
	//-->
	</style>

</head>
<body>

<?php
	// The flag is false, close the window
	if(!$flag || !$post_data) {
?>
<script type="text/javascript">
<!--
	opener.history.go(0);
	window.close();
//-->
</script>
</body>
</html>
<?php
exit;
	}
?>

<?php echo $lang['rate_this']; ?> : <strong>&quot;<?php echo $post_data['title']; ?>&quot;</strong><br /><br />
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="action" value="rate" />
<input type="hidden" name="id" value="<?php echo $post_data['id']; ?>" />
<select name="vote">
<option value="5">5</option>
<option value="4">4</option>
<option value="3">3</option>
<option value="2">2</option>
<option value="1">1</option>
</select><br /><br />
<input type="submit" value="<?php echo $lang['rate']; ?>" />
</form>

</body>
</html>