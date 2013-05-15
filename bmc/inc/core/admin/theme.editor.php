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


// Check whether a directory has been selected
if(isset($_REQUEST['dir'])) {
	$dir=str_replace("\\","",$_REQUEST['dir']);
	$dir=str_replace("/","",$dir);
	$dir=str_replace("..","",$dir); // Remove . and / for security

	if(!is_dir(CFG_PARENT."/templates/".$dir)) {
		$dir=false;
	}

} else {
	$dir=false;
}


// Check whether a file has been selected
if(isset($_REQUEST['file'])) {
	$file=str_replace("\\","",$_REQUEST['file']);
	$file=str_replace("/","",$file);
	$file=str_replace("..","",$file); // Remove . and / for security

	if(!file_exists(CFG_PARENT."/templates/".$dir."/".$file)) {
		clearstatcache();
		$file=false;
	}

} else {
	$file=false;
}

// Save an edited file
if(isset($_POST['action']) && $_POST['action']=="theme_editor") {
	$fp=@fopen(CFG_PARENT."/templates/".$dir."/".$file, "w+");

	if(!@fputs($fp, stripslashes($_POST['text']))) {
		$flag=false; // The file writing fails
	} else {
		$flag=true;
	}

	@fclose($fp);

	if(!$flag) {
		// The file writing may have failed due to incorrect file perms
		// Try to change the file permission
		if(!@chmod(CFG_PARENT."/templates/".$dir."/".$file,0777)) {
			// Everything fails. produce error
			bmc_Template('error_admin', $lang['admin_editor_error']);
		}
		else {
			$fp=@fopen(CFG_PARENT."/templates/".$dir."/".$file, "w+"); // The chmod was successful, now write the file
			@fputs($fp, $_POST['text']);
			@fclose($fp);
		}

	} else {
		bmc_Go("?action=theme_editor");
	}

}


// Load the theme editor

bmc_Template('admin_header',$lang['admin_theme_editor']);

	// Get the directory list

echo "<h1>".$lang['admin_theme_editor']."</h1>\n\n";
?>


<table width="100%" border="0" cellpadding="3" cellspacing="0" summary="User list">
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
			<br />
<strong><?php echo $lang['admin_editor_dir']; ?></strong></br ><br />
<select name="dir" size="10" onChange="javascript:goTheme(this);">
<?php
	// Print the theme directory list
	$handle = opendir(CFG_PARENT."/templates");
		while($dir_name = readdir($handle)) {
			if($dir_name != "." && $dir_name != ".." && is_dir(CFG_PARENT."/templates/".$dir_name)) {
				echo "<option value=\"{$dir_name}\">{$dir_name}</option>\n";
			}
		}
	closedir($handle);
?>
</select>
<br /><?php echo $lang['admin_editor_sel_dir']; ?>
			</td>

			<td headers="th0388AFB80002" valign="top" width="60%">
<br />
<strong><?php echo $lang['admin_editor_files']; ?></strong></br ><br />
<select name="files" size="10" onChange="javascript:goThemeFile(this);">
<?php
	// print the file list in a selcted theme dir
	if($dir) {
		$handle = opendir(CFG_PARENT."/templates/".$dir);
			while($file_name = readdir($handle)) {
				if($file_name != "." && $file_name != ".." && !is_dir(CFG_PARENT."/templates/".$dir."/".$file_name)) {
					echo "<option value=\"{$file_name}\">{$file_name}</option>\n";
				}
			}
		closedir($handle);
	}
?>
</select>
<br />
<?php echo $lang['admin_editor_sel_file']; ?>

			</td>
		</tr>
	</tbody>
</table>


<script type="text/javascript">
<!--

function goTheme(dir) {
	if(dir) {
		document.location="?action=theme_editor&dir="+dir.value;
	}
}

function goThemeFile(file) {

	var dir="<?php echo $dir; ?>";

	if(file) {
		document.location="?action=theme_editor&dir="+dir+"&file="+file.value;
	}
}

//-->
</script>

<?php

// If both the directory and theme file has been selected, load the file for editing

if($dir && $file) {
?>
<br /><br />
<strong><?php echo str_replace("%title%",$file,$lang['post_edit']); ?></strong><br />
<?php echo $lang['admin_editor_path']; ?> : <?php echo CFG_PARENT."/templates/".$dir."/".$file; ?>
<form name="edit_file" action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post">
<input type="hidden" name="action" value="theme_editor" />
<input type="hidden" name="dir" value="<?php echo $dir; ?>" />
<input type="hidden" name="file" value="<?php echo $file; ?>" />
<textarea name="text" rows="40" cols="100" style="font-size: 10px;">
<?php
	$text=fread(fopen(CFG_PARENT."/templates/".$dir."/".$file, "r"), filesize(CFG_PARENT."/templates/".$dir."/".$file));
	echo bmc_htmlentities($text);
?>
</textarea><br />

<input type="submit" value="<?php echo $lang['admin_user_save_bt']; ?>" />
</form>
<?php
}
	bmc_Template('admin_footer');
?>