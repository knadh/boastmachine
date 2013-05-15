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


// ====================== ATTACH FILES ==================

if($_POST['action'] == "attach_files") {

// The edit form? or the post form ?
if(isset($_POST['form_id']) && $_POST['form_id']=="edit") {
	$form="modpost";
} else {
	$form="newpost";
}

?>
<HTML>
<HEAD>
<TITLE>Closing..</TITLE>
</HEAD>
<BODY>
<script type="text/javascript">
<!--

window.opener.document.getElementById('file_div').innerHTML="";
window.opener.document.<?php echo $form; ?>.files.value="";

<?php
	for($n=0;$n<count($_POST['files']);$n++) {
	echo "window.opener.document.getElementById('file_div').innerHTML=window.opener.document.getElementById('file_div').innerHTML+\"<a href=\\\"{$bmc_vars['site_url']}/files/{$_POST['files'][$n]}\\\" target=\\\"_blank\\\">{$_POST['files'][$n]}</a>, \"\n";
	echo "window.opener.document.{$form}.files.value=window.opener.document.{$form}.files.value+\"|{$_POST['files'][$n]}\"\n";
	}
?>

window.close();

//-->
</script>
</BODY>
</HTML>
<?php
exit;
}






// ====================== INSERT IMAGES ==================

if($_POST['action'] == "attach_images" && isset($_POST['target_box'])) {

// The edit form? or the post form ?
if(isset($_POST['form_id']) && $_POST['form_id']=="edit") {
	$form="modpost";
} else {
	$form="newpost";
}

?>
<HTML>
<HEAD>
<TITLE>Closing..</TITLE>
</HEAD>
<BODY>
<script type="text/javascript">
<!--

<?php

	$target_box=$_POST['target_box'];	// The target textarea

	for($n=0;$n<count($_POST['files']);$n++) {

		$filename=$_POST['files'][$n];


		$file_specs=explode(".", $filename);	// Check whether it is an image itself
		$file_ext=$file_specs[count($file_specs)-1];
		$file_ext=strtolower($file_ext);

		if($file_ext == "jpg") {	// Yes

			if(isset($_POST['img_resize'])) {	// Wheter the file is to be thumbnailed
				include CFG_ROOT."/inc/users/image_resize.php";	// Resize to thumbnail

				$bbcode="[url={$bmc_vars['site_url']}/files/{$filename}][img]{$bmc_vars['site_url']}/files/{$target}[/img][/url]";
			} else {
				$bbcode="[img]{$bmc_vars['site_url']}/files/{$filename}[/img]";
			}

			// Necessary javascript to insert image bbCode

			echo "window.opener.document.{$form}.{$target_box}.value=window.opener.document.{$form}.{$target_box}.value+\"\\n{$bbcode}\\n\";\n";

		}

	}
?>

window.close();

//-->
</script>
</BODY>
</HTML>
<?php
exit;
}






// ====================== Upload the files ==================
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
}

input {
	font-family: Verdana;
	font-size: 10px;
}
//-->
</style>

</head>

<body>
<div>
<?php



//===== Upload the files

for($n=1;$n<=count($_FILES);$n++) {
	$str="file".$n;
	$file=$_FILES[$str];
	$file_ok=false;
	$ok=false;

	if(!empty($file['name']) && !empty($file['tmp_name'])) {
	$file_ok=true;

			// Check or valid filesize
			if(!isset($file['size']) || $file['size'] > ($max_size*1024) && $file_ok) {
				echo "<strong>".$file['name']."</strong><br />".str_replace("%num%",$n,$lang['file_fail_size'])."<br />";
				$file_ok=false;
			}

			// Check whether the file has a valid extension
			$ext=explode(".",$file['name']);
			$ext=trim($ext[count($ext)-1]);

			// no extension
			if(!isset($ext) && $file_ok) {
				echo "<strong>".$file['name']."</strong><br />".str_replace("%num%",$n,$lang['file_fail_ext'])."<br /><br />";
				$file_ok=false;
			}

			if($file_ok) {
				$file_ok=false; // Set the flag to false

				$exts=explode(",",$ok_exts);
				for($i=0;$i<count($exts);$i++) {
					// If any one extension from the list matches the current file's extension, then set the flag to true
					// and break out from the loop
					if(isset($exts[$i]) && trim($exts[$i])) {
						if(trim($exts[$i]) == $ext) {
							$file_ok=true; break;
						}
					}
				}

				if(!$file_ok) {
					echo "<strong>".$file['name']."</strong><br />".str_replace("%num%",$n,$lang['file_fail_ext'])."<br /><br />";
				}
			}

			// Upload the file if everything was ok
			if($file_ok) {
				$target_file=str_replace(" ","_",trim(ereg_replace("[^[:space:].a-zA-Z0-9]", "", $_FILES[$str]['name'])));
				if(!@move_uploaded_file($_FILES[$str]['tmp_name'], CFG_PARENT."/files/".$user."_".$target_file)) {
					echo "<strong>".$file['name']."</strong><br />".str_replace("%num%",$n,$lang['file_fail'])."<br /><br />";
					$file_ok=false;
				}
			}

	if($file_ok) {
		echo "<strong>".$file['name']."</strong><br />".str_replace("%num%", $n, $lang['file_done'])."<br /><br />";
	}

	}

}


?>
<br /><br /><br />
<a href="<?php echo $bmc_vars['site_url']."/bmc/files.php?form={$_POST['form_id']}"; ?>"><?php echo $lang['back']; ?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:window.close();"><?php echo $lang['close']; ?></a>
</div>
</body>
</html>