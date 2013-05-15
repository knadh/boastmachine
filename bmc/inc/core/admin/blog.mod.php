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

	if(defined('BLOG') && BLOG != false) {
		$blog=BLOG;
	} else {
		bmc_Go("admin.php");
	}

	// Blog info from DB
	$i_blog=$db->query("SELECT * FROM ".MY_PRF."blogs WHERE id='$blog'",false);


	if(!$i_blog) {
		bmc_template('error_admin', $lang['amin_blog_no']);
	}

	$date=bmc_Date($i_blog['blog_date']);


// ==============================
// Save the modifications

if(isset($_POST['action']) && $_POST['action'] == "mod_blog") {


		 // The static file for the blog
		if(empty($_POST['static_file'])) {
			$file=$_POST['blog_name'];
		}
		else {
			$file=$_POST['static_file'];
		}
		$file=str_replace(" ","_", $file).".php";



		if($file != $i_blog['blog_file']) {
			// The static file already exists
			if(file_exists(CFG_PARENT."/$file")) {
				bmc_template('error_admin', $lang['admin_blog_new_file_no']);
			}
		}


		// Blog status, Frozen/Active
		if(isset($_POST['blog_frozen'])) {
			$frozen=1;
		} else {
			$frozen=0;
		}

		// Allow users to post?
		if(isset($_POST['user_registration'])) {
			$user_registration=1;
		} else {
			$user_registration=0;
		}

		// Allow RSS syndication
		if(isset($_POST['rss_feed'])) {
			$rss_feed=1;
		} else {
			$rss_feed=0;
		}

		// Blog theme
		if(!empty($_POST['blog_theme'])) {
			include CFG_PARENT."/templates/".$_POST['blog_theme']."/theme.info.php"; // Get the theme info
		} else {
			$theme_name="";
		}

		$db->query("UPDATE ".MY_PRF."blogs SET blog_name='{$_POST['blog_name']}', theme='{$_POST['blog_theme']}', theme_name='$theme_name',user_registrations='$user_registration',rss_feed='$rss_feed', blog_file='$file', blog_info='{$_POST['blog_info']}', frozen='$frozen' WHERE id='{$i_blog['id']}'");


if($file != $i_blog['blog_file']) {

// Kill the existing static file
@unlink(CFG_PARENT."/".$i_blog['blog_file']);

// Write static php script for the blog
$blog_data=<<<EOF
<?php
	// Static loader for blog '{$_POST['blog_name']}' (Blog created Created on {$date}
	\$blog_id={$i_blog['id']};
	include dirname(__FILE__)."/{$bmc_path}/start.php";
?>
EOF;

		$fp=fopen(CFG_PARENT."/$file", "w+");
		fputs($fp, $blog_data);
		fclose($fp);

}

		bmc_updateCache('blogs'); // Update the cache
		bmc_Go("admin.php");
}



// ==============================
// Blog modification page


	bmc_Template('admin_header', $lang['admin_blog_mod']." :: '{$i_blog['blog_name']}'");

	$file=str_replace(".php","",$i_blog['blog_file']);

echo <<<EOF
<br /><br />

<div class="form_fields">
<h3>{$lang['admin_blog_mod']} :: '{$i_blog['blog_name']}'</h3>
{$lang['admin_blog_date']} {$date}
<form method="post" action="{$_SERVER['PHP_SELF']}" name="blog_form">
<input type="hidden" name="action" value="mod_blog" />
<input type="hidden" name="blog" value="$blog" />
{$lang['admin_blog_new_name']} : <input type="text" name="blog_name" value="{$i_blog['blog_name']}" size="25" />
<br />{$lang['admin_blog_info']} : <input type="text" name="blog_info" value="{$i_blog['blog_info']}" size="25" />
<br /><a href="javascript:alert('{$lang['admin_blog_new_file_help']}')">?</a>&nbsp;{$lang['admin_blog_new_file']} : <input type="text" name="static_file" value="{$file}" size="25" /><br />
{$lang['admin_blog_theme']} : <br /><select name="blog_theme">
EOF;

	$i_themes=bmc_getThemeList(); // Get the list of theme directories in the templates directory

	echo "<option value=\"\">---</option>\n";

	for($n=0;$n<count($i_themes['id']);$n++) {
		if(trim($i_themes['id'][$n])) {
			echo "<option value=\"".$i_themes['id'][$n]."\">".$i_themes['name'][$n]."</option>\n";
		}
	}

echo <<<EOF
</select>

<br />{$lang['admin_blog_frozen']} : <input type="checkbox" name="blog_frozen" value="1" />
<br />{$lang['admin_blog_users']} :  <input type="checkbox" name="user_registration" value="1" />
<br />{$lang['admin_sett_xml']} :  <input type="checkbox" name="rss_feed" value="1" />

<br /><br />

<input type="submit" value="{$lang['admin_blog_update']}" />
</form>
</div>
EOF;

?>

<script type="text/javascript">
<!--

var frozen=<?php echo $i_blog['frozen']; ?>;
if(frozen) {
	document.blog_form.blog_frozen.checked=true;
} else {
	document.blog_form.blog_frozen.checked=false;
}

var user_registration=<?php echo $i_blog['user_registrations']; ?>;
if(user_registration) {
	document.blog_form.user_registration.checked=true;
} else {
	document.blog_form.user_registration.checked=false;
}

var rss_feed=<?php echo $i_blog['rss_feed']; ?>;
if(rss_feed) {
	document.blog_form.rss_feed.checked=true;
} else {
	document.blog_form.rss_feed.checked=false;
}

var n=0;

for(n=0;n<=document.blog_form.blog_theme.options.length;n++) {

		if(document.blog_form.blog_theme.options[n].value == "<?php if($i_blog['theme']) echo $i_blog['theme']; ?>") {
			document.blog_form.blog_theme.options[n].selected=true;
			break;
		}
}

//-->
</script>

<?php
bmc_Template('admin_footer');
?>