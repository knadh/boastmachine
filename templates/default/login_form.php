<div class="form_fields">

<div class="bold_red"><?php echo $user_message; ?></div>

<form method="post" name="user_login" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php echo $lang['user_login']; ?> : <input type="text" name="user_login" /><br />
<?php echo $lang['user_pass']; ?> : <input type="password" name="password" /><br />
<?php echo $lang['user_login_remember']; ?> <input type="checkbox" name="remember" value="1" />
<br /><br />
<input type="button" onClick="javascript:validate()" value="<?php echo $lang['user_login_but']; ?>" /><br /><br />
<a href="register.php" title="<?php echo $lang['user_new']; ?>"><?php echo $lang['user_new']; ?></a><br />
<a href="login.php?action=forgot_pass" title="<?php echo $lang['user_forgot_pass']; ?>"><?php echo $lang['user_forgot_pass']; ?></a><br />
</form>
</div> <!-- End form_fields //-->

<div>
<script type="text/javascript">
<!--
	function validate() {

		if(!document.user_login.user_login.value || !document.user_login.password.value) {
		alert("<?php echo $lang['empty_fields']; ?>"); return false;
		}

	document.user_login.submit();

	}

//-->
</script>
</div>
<br /><br /><br /><br /><br /><br /><br />