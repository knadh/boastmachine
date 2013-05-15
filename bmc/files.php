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

include_once dirname(__FILE__)."/main.php";

// ============================ USER CONFIGURATION ==================================

$max_size="500"; //KB
	// Maximum permitted file upload size IN KILO BYTES (KB)

$ok_exts="jpg,jpeg,zip,gif,png,html,htm,txt,gz,rar,doc,pdf,mp3,wav,wma,wmv,mpg,3gp,rm,ra,ppt,xls,avi,au";
	// Permitted file extensions . Each separated by a comma. No spaces please

// ==================================================================================



// Check whether the user is logged in
$user=bmc_isLogged();

// Its not a valid user, so close the window
if(!$user || !$bmc_vars['user_files']) {
echo <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>{$lang['admin_file_no']}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style type="text/css">
<!--
body, html {
	font-family: Verdana;
	font-size: 10px;
	color: #333333;
}

input,select {
	font-family: Verdana;
	font-size: 10px;
	background: #F6F6F6;
}

//-->
</style>

</head>

<body>

<h4>{$lang['admin_file_no']}</h4>



</body>
</body>

EOF;
exit;
}

// The edit form? or the post form ?
if(isset($_POST['form_id']) && $_POST['form_id']=="edit") {
	$form="modpost";
} else {
	$form="newpost";
}

// Delete a file?
if(isset($_POST['action']) && $_POST['action'] == "delete_files") {

	// No files selected.
	if(!isset($_POST['files']) || !$_POST['files']) {
		bmc_Go("files.php?form_id=".$form); exit;
	}

	for($n=0;$n<count($_POST['files']);$n++) {
		$file=str_replace("\\","",$_POST['files'][$n]);
		$file=str_replace("/","",$file);
		$file=str_replace("..","",$file); // Remove . and / for security
		$file_spec=explode("_", $file);

		// Check whether that attached file is this person's itself
		if($file_spec[0] == $user) {
			@unlink(CFG_PARENT."/files/".$file); // Delete
		}

	}
		bmc_Go("files.php?form_id=".$form); exit;
}

// File uploading is not permitted
if(!$bmc_vars['user_files']) {
	bmc_template('error_page', $lang['file_no']); exit;
}

// Upload the files
if(isset($_POST['action']) && ($_POST['action'] == "upload_files" || $_POST['action'] == "attach_files" || $_POST['action'] == "attach_images")) {
	include CFG_ROOT."/inc/users/files.inc.php";
	exit;
}

// Is it the edit form?
if(isset($_GET['form_id']) && $_GET['form_id']=="edit") {
	$form="edit";
} else {
	$form="new";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php echo $lang['file_title']; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style type="text/css">
<!--
body, html {
	font-family: Verdana;
	font-size: 10px;
	color: #333333;
}

input,select {
	font-family: Verdana;
	font-size: 10px;
	background: #F6F6F6;
}

#img_attach {
	visibility: hidden;
	background: #EBEBEB;
	padding: 10px;
}


//-->
</style>

</head>

<body>
<div>
<form name="files" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" ENCTYPE="multipart/form-data">
<input type="hidden" name="action" value="upload_files" />
<input type="hidden" name="form_id" value="<?php echo $form; ?>" />
<strong><?php echo $lang['file_title']; ?></strong><br /><br />
<?php echo $lang['file_fl']; ?>#1 : <input type="file" name="file1" /><br />
<?php echo $lang['file_fl']; ?>#2 : <input type="file" name="file2" /><br />
<?php echo $lang['file_fl']; ?>#3 : <input type="file" name="file3" /><br />
<?php echo $lang['file_fl']; ?>#4 : <input type="file" name="file4" /><br />
<?php echo $lang['file_fl']; ?>#5 : <input type="file" name="file5" /><br />
<br /><br /><input type="submit" value="<?php echo $lang['file_but']; ?>" />
</form>
<br /><br />




<strong><?php echo $lang['post_attach']; ?></strong><br /><br />
<form name="attach" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" ENCTYPE="multipart/form-data">
<input type="hidden" name="action" value="attach_files" />
<input type="hidden" name="form_id" value="<?php echo $form; ?>" />
<select name="files[]" size="10" multiple="true" onChange="javascript:goChkImg(this);">
<?php

// Read the file upload list from the file storage

	$handle = opendir(CFG_PARENT."/files");
	$i=0; $j=0;

while($filename = readdir($handle)) 
{
	if($filename != "." && $filename != ".." && trim($filename)) { 
		$file_spec=explode("_", $filename);
		if($file_spec[0] == $user) {
			echo "<option value=\"{$filename}\">{$filename}</option>";
		}
	}
}
	closedir($handle);

?>
</select><br /><br />

<div id="img_attach">
<?php echo $lang['file_img_insert_target']; ?><br />
<?php echo $lang['post_smr']; ?><input type="radio" name="target_box" value="smr" checked />  
<?php echo $lang['file_img_insert_body']; ?><input type="radio" name="target_box" value="msg" /><br />
<input type="checkbox" name="img_resize" value="1" checked /> <?php echo $lang['file_img_resize']; ?><br />
<input type="button" onClick="javascript:goImgAttach();" value="<?php echo $lang['file_img_insert']; ?>" />  
</div>

<br /><br />
<input type="button" onClick="javascript:goAttach();" value="<?php echo $lang['file_add_but']; ?>" />  
<input type="button" onClick="javascript:goDel();" value="<?php echo $lang['file_but_del']; ?>" /></form>

<br /><br />
<a href="javascript:window.close();"><?php echo $lang['close']; ?></a>

<script type="text/javascript">
<!--

	// Delete files
	function goDel() {
		var msg=confirm("<?php echo $lang['file_del_msg']; ?>");

		if(msg) {
			document.attach.action.value="delete_files";
			document.attach.submit();
		}
	}


	// Attach files to the post
	function goAttach() {
			document.attach.action.value="attach_files";
			document.attach.submit();
	}

	// Show/Hide Image buttons
	function goChkImg(selBox) {
			// Get the file extension
			var file_info=selBox.value.split(".");
			var file_ext=file_info[file_info.length-1];
			file_ext=file_ext.toLowerCase();

			var lyr=document.getElementById('img_attach');

		// If its a jpg file, show the img buttons
		if(file_ext == "jpg")	{
			lyr.style.visibility="visible";
		} else {
			lyr.style.visibility="hidden";
		}

	}


	function goImgAttach() {
		document.attach.action.value="attach_images";
		document.attach.submit();
	}


//-->
</script>

</div>
</body>
</html>