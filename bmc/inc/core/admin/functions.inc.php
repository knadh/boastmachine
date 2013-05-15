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


// =======================
// Show the referers page

if(isset($_GET['action']) && $_GET['action'] =="refs") {

	// Read the referrer log
	bmc_Template('admin_header', $lang['admin_ref']);

echo <<<EOF
<table border="0" cellpadding="7" cellspacing="0" width="100%">
<tr>
<td width="170">
<p><strong>{$lang['admin_ref_time']}</strong></p>
</td>
<td width="110">
<p><strong>{$lang['admin_ref_ip']}</strong></p>
</td>
<td width="*">
<p><strong>{$lang['admin_ref_url']}</strong></p>
</td>
</tr>
EOF;

$rf_done=false;	$ref="";
$fp = @fopen(CFG_ROOT."/inc/vars/ref.log", "r");
	if($fp) {
		while (!feof($fp)) {
			$ref.=fgets($fp, 4096);
		}
	fclose($fp);
	}

$ref_data=@unserialize($ref); // convert the data into array


// No logs
if(!isset($ref_data['url'])) {
echo <<<EOF
<tr>
<td><br /><br /><strong>{$lang['no_logs']}</strong></td></tr></table>
EOF;
bmc_Template('admin_footer');
}

for($n=0;$n<count($ref_data['url']);$n++) {

$ref_time=bmc_Date($ref_data['time'][$n]);

echo <<<EOF
<tr>
<td>
<p>$ref_time</p>
</td>
<td>
<p><a href="http://network-tools.com/default.asp?host={$ref_data['ip'][$n]}">{$ref_data['ip'][$n]}</a></p>
</td>
<td>
<p><a href="{$ref_data['url'][$n]}" target="_blank">{$ref_data['url'][$n]}</a></p>
</td>
</tr>
EOF;
}

	echo "</table>";

	bmc_Template('admin_footer');
	exit;
}


// =======================
// Mail Logs ( sent from 'Send to Friend' page )

if(isset($_GET['action']) && $_GET['action']=="mail_logs") {

	// Read the mail log file
	$log=null;
	$fp = @fopen(CFG_ROOT."/inc/vars/mail_log.txt", "r");
	if ($fp) {
		while (!feof($fp)) {
			$log.= fgets($fp, 4096);
		}
	fclose ($fp);
	} else {
		bmc_template('error_admin', $lang['admin_mail_logs_no']);
	}
		// No Logs were found
	if(empty($log) || !trim($log)) {
		bmc_template('error_admin', $lang['admin_mail_logs_no']);
	}

	bmc_Template('admin_header', $lang['admin_mail_logs']);

echo <<<EOF
<a href="?action=clear_mlog">{$lang['admin_mail_clear']}</a><br /><br />\n\n
EOF;

	// Parse the logs, print them in a neat format
	$log=explode("\n", $log);

	for($n=0;$n<count($log);$n++) {

		if(isset($log[$n]) && !empty($log[$n])) {

			$i_log=explode("|",$log[$n]);

			$str=str_replace("%email%","<a href=\"mailto:{$i_log[2]}\"><strong>{$i_log[1]}</strong></a> (<a href=\"http://network-tools.com/default.asp?host={$i_log[5]}\">ip</a>)",$lang['admin_mail_logs_by']);
			echo "{$str} {$i_log[0]}</strong>, <a href=\"{$bmc_vars['site_url']}/?id={$i_log[4]}\">{$i_log[3]}</a><br />\n";

			unset($i_log[0]); unset($i_log[1]); unset($i_log[2]); unset($i_log[3]); unset($i_log[4]); unset($i_log[5]);
			$i_log=explode("|",implode("|",$i_log));

			for($i=0;$i<count($i_log);$i++) {
				if(isset($i_log[$i]) && trim($i_log[$i])) {
					echo "<a href=\"mailto:{$i_log[$i]}\">{$i_log[$i]}</a>, ";
				}
			}
			echo "\n<br /><br />\n\n";
		}
	}

	bmc_Template('admin_footer');
	exit();
}

// Clear the mail Log file
if(isset($_GET['action']) && $_GET['action']=="clear_mlog") {
	$fp = fopen(CFG_ROOT."/inc/vars/mail_log.txt", "w+") or bmc_template('error_admin', $lang['admin_log_write_msg'], $lang['admin_clr_log_msg']);
	fputs($fp, " ");
	fclose($fp);

	bmc_Go("?done=true");
	exit();
}


// =======================
// The themes page

if(isset($_GET['action']) && $_GET['action']=="themes") {
	bmc_Template('admin_header', $lang['admin_theme']);

	$themes=bmc_getThemeList(); // Get the list of theme directories in the /templates directory
	$theme=$bmc_vars['theme']; // Get the current theme
	$theme_name=$bmc_vars['theme_name']; // Get its formal name

echo <<<EOF
<p><strong>{$lang['admin_theme_title']}</strong></p>
{$lang['admin_theme_info']}
<p>{$lang['admin_theme_current']} : <strong>{$theme_name}</strong></p>
EOF;

	for($n=0;$n<=count($themes['id'])-1;$n++) {
		if(trim($themes['id'][$n])) {
echo ($n+1).". ";
echo <<<EOF
<strong>{$themes['name'][$n]}</strong>&nbsp;&nbsp;( <a href="?action=set_theme&theme={$themes['id'][$n]}">{$lang['admin_theme_apply_but']}</a> )
&nbsp;&nbsp;( <a href="javascript:remTheme('{$themes['id'][$n]}');">{$lang['admin_theme_del_but']}</a> )
<br />{$lang['str_by']} <a href="mailto:{$themes['author_email'][$n]}">{$themes['author_name'][$n]}</a> (<a href="{$themes['author_url'][$n]}" target="_blank">www</a>)
<br /><br />
EOF;
		}
	}

echo <<<EOF

<script type="text/javascript">
<!--
function remTheme(theme) {
	var m=confirm("{$lang['admin_theme_del_msg']}");
	if(!m) {
		return;
	}
	document.location="?action=theme_rem&theme="+theme;
}

//-->
</script>

EOF;
bmc_Template('admin_footer'); exit;
}

// Save a theme
if(isset($_GET['action']) && $_GET['action']=="set_theme" && isset($_GET['theme'])) {


	// Get the theme name and the theme's directory name
	include CFG_PARENT."/templates/".$_GET['theme']."/theme.info.php";

	if(empty($theme_name) || !trim($theme_name)) {
		bmc_Go("?action=themes&done=false");
	}

	// Set the theme
	bmc_setVar("theme","{$_GET['theme']}");
	bmc_setVar("theme_name","{$theme_name}");
	bmc_Go("?action=themes&done=true");
}


// Delete a theme
if(isset($_GET['action']) && $_GET['action']== "theme_rem" && isset($_GET['theme'])) {

	// Get the details of the current theme
	$theme=$bmc_vars['theme'];

	// If the user is trying to delete the current theme, dont allow it
	if(strtolower($theme) == strtolower($_GET['theme'])) {
		bmc_template('error_admin', $lang['admin_theme_del_no']);
	}

	bmc_remDir(CFG_PARENT."/templates/{$_GET['theme']}"); // Delete the whole theme directory
	bmc_Go("?action=themes&done=true");
}




// ====================
// Language packs

if(isset($_GET['action']) && $_GET['action']=="lang") {
	bmc_Template('admin_header', $lang['admin_lang_title']);

echo <<<EOF

<script type="text/javascript">
<!--

function delPack() {
	var msg=confirm('{$lang['admin_lang_del_msg']}');
	if(!msg) {
		return false;
	}

	document.lang.action.value="del_lang";
	document.lang.submit();
}

function setPack() {
	document.lang.action.value="set_lang";
	document.lang.submit();
}

//-->
</script>

<strong>{$lang['admin_lang_title']}</strong><br /><br />
{$lang['admin_lang_current']}: ' {$lang['name']} '
<form name="lang" method="POST" action="{$_SERVER['PHP_SELF']}">
<input type="hidden" name="action" value="set_lang" />
<select name="lang_file" size="1">
EOF;

	$handle = opendir(CFG_ROOT."/inc/lang");
	while($file = readdir($handle)) {
		if( $file != "." && $file != "..") {

		$load_lang_pack=true; // Set this variable to true.
							  // This will be detected by the lang file and it will load only the necessary variables

		include CFG_ROOT."/inc/lang/$file";

echo <<<EOF
<option value="$file">{$lang['name']}</option>\n
EOF;
		}
	}

	closedir($handle);


echo <<<EOF
	</select>
<br /><br />
<input type="button" onClick="javascript:setPack();" value="{$lang['admin_lang_but']}" />&nbsp;&nbsp;&nbsp;
<input type="button" onClick="javascript:delPack();" value="{$lang['admin_lang_del_but']}" />
</form><br /><br />


<form name="lang_upload" method="POST" action="{$_SERVER['PHP_SELF']}" ENCTYPE="multipart/form-data">
<input type="hidden" name="action" value="upload_lang" />
<strong>{$lang['admin_lang_up']}</strong><br />
<input type="file" name="lang_file" /><br />
{$lang['admin_lang_up_ow']} <input type="checkbox" name="file_ow" value="true" /><br /><br />
<input type="submit" value="{$lang['admin_lang_up_but']}" />
</form>
EOF;

	bmc_Template('admin_footer'); exit;
}

// Set the language pack
if(isset($_POST['action']) && $_POST['action']=='set_lang' && isset($_POST['lang_file']) && file_exists(CFG_ROOT."/inc/lang/".$_POST['lang_file'])) {
	clearstatcache(); // Clear the file stat cache
	bmc_setVar("lang", $_POST['lang_file']);
	bmc_Go("?action=lang"); exit;
}

// Delete a language pack
if(isset($_POST['action']) && $_POST['action']=='del_lang' && isset($_POST['lang_file']) && file_exists(CFG_ROOT."/inc/lang/".$_POST['lang_file'])) {

		clearstatcache(); // Clear the file stat cache

		// Check whether the user is trying to delete the ONLY remaining lang pack
		// or a language pack that is in use

		$num=0;
		$handle = opendir(CFG_ROOT."/inc/lang");
			while($file = readdir($handle)) {
				if( $file != "." && $file != "..") {
					$load_lang_pack=true; // Set the loader flag to true

					if(isset($lang['name'])) { $n=0;$n+1; }

				}
			}
		closedir($handle);

	if(($lang <= 1) || $bmc_vars['lang'] == $_POST['lang_file']) {
		bmc_template('error_admin', $lang['admin_lang_del_no']);
	}

	// Delete the pack
	@unlink(CFG_ROOT."/inc/lang/".$_POST['lang_file']);

	bmc_Go("?action=lang");
}

// Upload a new pack
if(isset($_POST['action']) && $_POST['action'] == "upload_lang" && isset($_FILES['lang_file']['name']) && !empty($_FILES['lang_file']['name'])) {
	$file=$_FILES['lang_file'];

	$file_exists=false;
	// Check whether the file exists
	if(file_exists(CFG_ROOT."/inc/lang/".$file['name'])) {
		clearstatcache(); $file_exists=true;
	}


	// File exists and overwriting is not selected. So the upload stops
	if(!isset($_POST['file_ow']) && $file_exists) {
		bmc_template('error_admin', $lang['admin_lang_up_ow_no']);
	}


	if(!@move_uploaded_file($file['tmp_name'], CFG_ROOT."/inc/lang/".$file['name'])) {
		bmc_template('error_admin', $lang['admin_lang_up_no']); 
	}

	bmc_Go("admin.php?action=lang");

}


// ====================
// IP banning

if(isset($_GET['action']) && $_GET['action']=="ban") {
	bmc_Template('admin_header', $lang['admin_block_title']);

echo <<<EOF
<form name="ips" method="POST" action="{$_SERVER['PHP_SELF']}">
<input type="hidden" name="action" value="ban_ip" />
{$lang['admin_block_ip']}<br />
<textarea name="ips" rows="15" cols="34">
EOF;

	$ipdat=unserialize($bmc_vars['ips']);
	if(!empty($ipdat)) {
		foreach($ipdat as $ip) {
			echo $ip."\n";
		}
	}

echo <<<EOF
</textarea>
<br /><input type="submit" value="{$lang['admin_blck_but']}" />
</form>
EOF;
	bmc_Template('admin_footer');
	exit;
}

// Save the IPs
if(isset($_POST['action']) && $_POST['action']=='ban_ip') {
	bmc_setVar("ips",serialize(explode("\n",trim($_POST['ips']))));
	bmc_Go("?null"); exit;
}



// ====================
// Link Manager (3.1)

if(isset($_REQUEST['action']) && $_REQUEST['action']=="link_manager") {

	// Add a link
	if(isset($_POST['new_link'])) {
		if(!empty($_POST['link_title']) && !empty($_POST['link_url']) && isset($_POST['link_blog'])) {
			$db->query("INSERT INTO ".MY_PRF."links (title,url,description,blog) VALUES('{$_POST['link_title']}','{$_POST['link_url']}','{$_POST['link_desc']}','{$_POST['link_blog']}') ");;
			bmc_Go("?action=link_manager");
		}
	} else {
		// Modify a link
		if(isset($_POST['id'])) {
			if(!empty($_POST['link_title']) && !empty($_POST['link_url']) && isset($_POST['link_blog'])) {
				$db->query("UPDATE ".MY_PRF."links SET title='{$_POST['link_title']}',url='{$_POST['link_url']}',description='{$_POST['link_desc']}',blog='{$_POST['link_blog']}' WHERE id='{$_POST['id']}' ");;
				bmc_Go("?action=link_manager");
			}
		}
	}

	// Delete a link
	if(isset($_POST['del_link']) && isset($_POST['links']) ) {
		$db->query("DELETE FROM ".MY_PRF."links WHERE id='{$_POST['links']}'");
		bmc_Go("?action=link_manager");
	}


	bmc_Template('admin_header', $lang['admin_link_manager']);

?>
<script type="text/javascript">
<!--

function jsClearLink() {

	document.mod_link.id.value="";
	document.mod_link.link_title.value="";
	document.mod_link.link_desc.value="";
	document.mod_link.link_url.value="http://";

}


function jsDoLink() {

	var selectedItem = document.link_list.links.selectedIndex;
	var ID = document.link_list.links.options[selectedItem].value;

	document.mod_link.id.value=ID;
	document.mod_link.link_title.value=document.link_list.links.options[selectedItem].text;
	document.mod_link.link_desc.value=eval("document.link_list.desc_"+ID+".value");
	document.mod_link.link_url.value=eval("document.link_list.url_"+ID+".value");

	var n=0;
	for(n=0;n<document.mod_link.link_blog.options.length;n++) {
		if(document.mod_link.link_blog.options[n].value == eval("document.link_list.blog_"+ID+".value")) {
			document.mod_link.link_blog.options[n].selected="true";
			break;
		}
	}

}

//-->
</script>
<h1><?php echo $lang['admin_link_manager']; ?></h2>

<div>
<table width="100%" border="0" style="float:right" cellpadding="3" cellspacing="0" summary="User list">
	<thead>
		<tr>
			<th id="th0388AFB80000" valign="top" align="left" bgcolor="#F3F3F3" width="40%">
			</th>
			<th id="th0388AFB80002" valign="top" align="left" bgcolor="#F3F3F3" width="60%">
			</th>
		</tr>
	</thead>

	<tbody>
	<tr>
			<td headers="th0388AFB80000" valign="top" align="left" width="40%">
			<div>
			<strong><?php echo $lang['admin_link_links']; ?></strong>

			<form method="post" name="link_list" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="action" value="link_manager" />
			<input type="hidden" name="del_link" value="true" />
			<div>

		<select name="links" size="10" onChange="javascript:jsDoLink();">

	<?php

		$hidden=""; // Hidden form fields for storing category descriptions

		// Print the category list
		$links=$db->query("SELECT * FROM ".MY_PRF."links");

		foreach($links as $link) {
			echo "<option value=\"{$link['id']}\">{$link['title']}</option>\n";
			$hidden.="<input type=\"hidden\" name=\"desc_{$link['id']}\" value=\"".addSlashes($link['description'])."\" />\n";
			$hidden.="<input type=\"hidden\" name=\"url_{$link['id']}\" value=\"".addSlashes($link['url'])."\" />\n";
			$hidden.="<input type=\"hidden\" name=\"blog_{$link['id']}\" value=\"".$link['blog']."\" />\n\n";
		}

	?>
		</select>
		<?php echo $hidden; ?>
	
		</div>
		<input type="submit" value="<?php echo $lang['admin_link_but_del']; ?>" />
		</form>

		</div>
			</td>

			<td headers="th0388AFB80002" valign="top" width="60%">
			<div class="form_fields">
			<strong><?php echo $lang['admin_link_but_mod']; ?></strong>

			<form method="post" name="mod_link" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="action" value="link_manager" />
			<input type="hidden" name="id" value="" />
			<?php echo $lang['admin_link_title']; ?> : <br /><input type="text" name="link_title" size="30" /><br />
			<?php echo $lang['admin_link_desc']; ?> : <br /><input type="text" name="link_desc" size="30" /><br />
			<?php echo $lang['admin_link_url']; ?> : <br /><input type="text" name="link_url" size="30" value="http://" /><br />
			<?php echo $lang['admin_link_add_to']; ?> : <br />
			<select name="link_blog">
			<option value="0"><?php echo $lang['admin_link_add_all']; ?></option>
			<?php
				$blogs=$db->query("SELECT id,blog_name FROM ".MY_PRF."blogs");
				foreach($blogs as $this_blog)
					echo "<option value=\"{$this_blog['id']}\">{$this_blog['blog_name']}</option>\n";
			?>
			</select><br /><br />
			<?php echo $lang['admin_link_new']; ?> <input type="checkbox" name="new_link" value="1" /><br /><br />
			<input type="submit" name="submit_button" value="<?php echo $lang['admin_link_but_mod']; ?>" />
			<input type="button" onClick="javascript:jsClearLink();" value="<?php echo $lang['post_clear_but']; ?>" />
			</form>
			</div>
			</td>

		</tr>
	</tbody>
</table>
</div>


<?php
	bmc_Template('admin_footer');
	exit;
}

// Save the links
if(isset($_POST['action']) && $_POST['action']=='ban_ip') {
	bmc_setVar("ips",serialize(explode("\n",$_POST['ips'])));
	bmc_Go("?null"); exit;
}





// ====================
// Comments SPAM filter	(3.1)

if(isset($_GET['action']) && $_GET['action']=="spam") {
	bmc_Template('admin_header', $lang['admin_spam_title']);

echo <<<EOF
<strong>{$lang['admin_spam_title']}</strong>
<form name="spam_words" method="POST" action="{$_SERVER['PHP_SELF']}">
<input type="hidden" name="action" value="spam" />
{$lang['admin_spam_info']}<br />
<textarea name="spam_words" rows="15" cols="34">
EOF;

	$spam_words=unserialize($bmc_vars['spam_words']);
	if(!empty($spam_words)) {
		foreach($spam_words as $spam_word) {
			echo $spam_word."\n";
		}
	}

	if($bmc_vars['ban_spammer']) {
		$ban_spammer="checked";
	} else {
		$ban_spammer="";
	}

echo <<<EOF
</textarea>
<br /><input type="checkbox" value="1" name="ban_spammer" {$ban_spammer} /> {$lang['admin_spam_ban']}<br />
<br /><input type="submit" value="{$lang['admin_spam_but']}" />
</form>
EOF;
	bmc_Template('admin_footer');
	exit;
}

// Save the SPAM keywords (3.1)
if(isset($_POST['action']) && $_POST['action']=='spam') {

	if(isset($_POST['ban_spammer'])) {
		$ban_spammer=1;
	} else {
		$ban_spammer=0;
	}

	bmc_setVar("spam_words",serialize(explode("\n",trim($_POST['spam_words']))));
	bmc_setVar("ban_spammer", $ban_spammer);
	bmc_Go("?null"); exit;
}



// ====================
// The bad words page

if(isset($_GET['action']) && $_GET['action']=="word") {
	bmc_Template('admin_header', $lang['admin_bad_title']);

echo <<<EOF
<strong>{$lang['admin_bad_words']}</strong>
<form method="POST" action="{$_SERVER['PHP_SELF']}">
<input type="hidden" name="action" value="save_words" />
<textarea name="words" rows="15" cols="46">
EOF;

	$words=unserialize($bmc_vars['words']);
	if(!empty($words)) {
		foreach($words as $word) {
			echo $word."\n";
		}
	}

echo <<<EOF
</textarea><br />
<input type="submit" value="{$lang['admin_bad_but']}"><input type="hidden" name="act" value="word" />
</form>
EOF;
	bmc_Template('admin_footer');
	exit;
}

// Save the badwords :)
if(isset($_POST['action']) && $_POST['action']=="save_words") {
	bmc_setVar("words",serialize(explode("\n",trim($_POST['words']))));
	bmc_Go("admin.php?action=word");
}


?>