<div class="form_fields">

<div class="bold_red"><?php echo $user_message; ?></div>

<form method="post" name="user_login" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="action" value="forgot_pass" />
<?php echo $lang['user_email']; ?> : <input type="text" name="email" /><br />
<br />
<input type="button" onClick="javascript:validate()" value="<?php echo $lang['user_forgot_but']; ?>" /><br /><br />
<a href="register.php" title="<?php echo $lang['user_new']; ?>"><?php echo $lang['user_new']; ?></a><br />
<a href="login.php" title="<?php echo $lang['user_login_but']; ?>"><?php echo $lang['user_login_but']; ?></a><br />
</form>
</div>

<div>
<script type="text/javascript">
<!--
	function validate() {

		if(!document.user_login.email.value) {
		alert("<?php echo $lang['empty_fields']; ?>"); return false;
		}

	document.user_login.submit();

	}

//-->
</script>
</div>