<div class="form_fields">
<form method="post" name="user_reg" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="action" value="user_reg" />
<?php echo $lang['user_login']; ?> : <input type="text" name="user_login" /><br />
<?php echo $lang['user_name']; ?> : <input type="text" name="full_name" /><br />
<?php echo $lang['user_pass']; ?> : <input type="password" name="password" /><br />
<?php echo $lang['user_pass']; ?> #2 : <input type="password" name="password2" /><br /><br />

<?php echo $lang['user_email']; ?> : <input type="text" name="email" /><br />
<?php echo $lang['user_url']; ?> : <input type="text" name="url" value="http://" /><br /><br />

<?php echo $lang['user_blogs']; ?><br />
<select name="blogs[]" size="5" multiple>

<?php

	// The blog list. Exclude frozen and exclusive blogs
	$blogs=$db->query("SELECT id,blog_name FROM ".MY_PRF."blogs WHERE frozen='0' AND user_registrations='1' ORDER BY blog_name");
	foreach($blogs as $blog) {
			echo "<option value=\"{$blog['id']}\">{$blog['blog_name']}</option>\n";
			$blogged=true;
	}
?>

</select>
<br /><br />

<?php
	if(!isset($blogged)) echo $lang['user_blog_no']."<br />"; // There are no open blogs!
?>
<input type="button" onClick="javascript:validate()" value="<?php echo $lang['user_reg_but']; ?>" /><br />
</form>
</div>

<div>
<script type="text/javascript">
<!--
	function validate() {

		if(document.user_reg.password.value.length < 5) {
		alert("<?php echo $lang['user_short_pass']; ?>"); return false;
		}

		if(document.user_reg.password.value != document.user_reg.password2.value) {
		alert("<?php echo $lang['user_pass_nomatch']; ?>"); return false;
		}

		if(!document.user_reg.user_login.value || !document.user_reg.full_name.value || !document.user_reg.email.value) {
		alert("<?php echo $lang['empty_fields']; ?>"); return false;
		}

	document.user_reg.submit();

	}

//-->
</script>
</div>