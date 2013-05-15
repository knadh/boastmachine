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

	// Send the mail
	if(isset($_POST['action']) && $_POST['action']=="mail_users") {

		// The user level
		if(isset($_POST['level'])) {
			switch ($_POST['level']) {
				case '0':
				case '1':
				case '2':
				case '3':
				case '4':
				$level=" WHERE level='{$_POST['level']}'";
				break;

				case 'all'	:
				$level="";
				break;
			}

		} else {
			$level="";
		}


		$users=$db->query("SELECT user_name,user_login,level,user_nick,user_email,user_url FROM ".MY_PRF."users{$level}");

			$message=$_POST['message']; // The message

			foreach($users as $user) {
	
				$message=str_replace("[USER_NAME]", $user['user_login'], $message);
				$message=str_replace("[FULL_NAME]", $user['user_name'], $message);
				$message=str_replace("[NICK_NAME]", $user['user_nick'], $message);
				$message=str_replace("[EMAIL]", $user['user_email'], $message);
				$message=str_replace("[URL]", $user['user_url'], $message);
				$message=str_replace("[LEVEL]", $user['level'], $message);

				$message=stripslashes($message);

				bmc_Mail($user['user_email'], $_POST['subject'], $message);
			}

		bmc_template('admin_header', str_replace("%num%", count($users), $lang['admin_mail_success']));
		echo "<strong>".str_replace("%num%", count($users), $lang['admin_mail_success'])."</strong>";
		bmc_template('admin_footer');
		exit;

	}



bmc_template('admin_header', $lang['admin_user_mail']);

?>
<strong><?php echo $lang['admin_user_mail']; ?></strong><br /><br />

<div>
<form name="mail_all" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="action" value="mail_users" />
<?php echo $lang['admin_mail_subj']; ?> <br />
<input type="text" name="subject" /><br /><br />
<?php echo $lang['admin_mail_level']; ?><br />
<select name="level">
<option value="all"><?php echo $lang['str_all']; ?></option>
<option value="1">0</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
</select>
<br /><br />
<?php echo $lang['admin_mail_msg']; ?><br />
<textarea name="message" cols="75" rows="25"></textarea><br />
<input type="button" onClick="javascript:validateForm();" value="<?php echo $lang['admin_mail_send']; ?>" />
</form>

<script type="text/javascript">
<!--

	function validateForm() {
		if(!document.mail_all.message.value || !document.mail_all.subject.value) {
			alert("<?php echo $lang['empty_fields']; ?>");
			return false;
		} else {
			document.mail_all.submit();
		}
	}

//-->
</script>

</div><br /><br />

<strong><?php echo $lang['admin_mail_keywords']; ?></strong><br />
[USER_NAME] - <?php echo $lang['admin_mail_key_login']; ?><br />
[FULL_NAME] - <?php echo $lang['admin_mail_key_name']; ?><br />
[NICK_NAME] - <?php echo $lang['admin_mail_key_nick']; ?><br />
[EMAIL] - <?php echo $lang['admin_mail_key_email']; ?><br />
[URL] - <?php echo $lang['admin_mail_key_url']; ?><br />
[LEVEL] - <?php echo $lang['admin_mail_key_level']; ?><br />


<?php

bmc_template('admin_footer');

?>