<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>Shout Box</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['ENCODING']; ?>" />
<link href="<?php echo $bmc_vars['site_url']."/templates/".CFG_THEME."/shout.css"; ?>" rel="stylesheet" type="text/css" />
</head>

<body>

<script type="text/javascript">
<!--
	function chkPost() {
		if(!document.shout.name.value || !document.shout.name.value || !document.shout.msg.value) {
			alert("Empty fields!"); return false;
		}
		document.shout.submit();
	}
//-->
</script>

<?php
// Print the ShoutBox' posting form
if(isset($_GET['action']) && $_GET['action']=="add") {

?>
<form name="shout" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="action" value="save" />
<input type="hidden" name="blog" value="<?php echo $the_blog; ?>" />

<?php echo $lang['user_name']; ?><br />
<input type="text" name="name" /><br />

<?php echo $lang['user_url']; ?><br />
<input type="text" name="url" value="http://" /><br />

<?php echo $lang['comments']; ?><br />
<textarea name="msg" rows="6" cols="17"></textarea><br />
<input type="button" value="<?php echo $lang['post_post_but']; ?>" onClick="chkPost()" />

</form>
<?php
} else {

	for($n=0;$n<count($posts['name']);$n++) {

			$name=bmc_blockWords($posts['name'][$n]); // Filter bad words

			$url=$posts['url'][$n];
			if(empty($url) || $url == "http://" ) {
				$url="";
			}

			$msg=bmc_blockWords($posts['msg'][$n]); // Filter bad words
			$ip=$posts['ip'][$n];
			$date=bmc_Date($posts['date'][$n]);

			if(!empty($name)) {
				if($smile) { $msg=bmc_Smilify($msg); }

				echo	"<div class=\"entry\">\n";
				echo	"<!-- $ip //-->\n";
					if($url) {
						echo "<a href=\"$url\" title=\"{$date}\" target=\"_blank\"><strong>$name</strong></a> : $msg<br />\n";
					} else {
						echo "<strong>$name</strong> : $msg<br />\n";
					}
				echo	"</div>\n";
			}
	}
}
?>
</body></html>