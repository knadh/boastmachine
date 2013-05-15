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


// If the form was posted, carry out the deletion.
if(isset($_POST['action']) && $_POST['action'] == "file_manager" && isset($_POST['files'])) {
	for($n=0;$n<count($_POST['files']);$n++) {

		$file=str_replace("\\","",$_POST['files'][$n]);
		$file=str_replace("/","",$file);
		$file=str_replace("..","",$file); // Remove . and / for security

		@unlink(CFG_PARENT."/files/".$file);
	}

	bmc_Go("?action=file_manager");
}


bmc_Template('admin_header', $lang['admin_file']);

echo "<h1>".$lang['admin_file']."</h1>";
?>

<form name="file_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="action" value="file_manager" />
<input type="hidden" name="sel_file" value="" />

<select name="files[]" multiple size="15" onChange="javascript:viewFile(this);">

<?php

	$file_list=array();

		// Collect the file list
		$handle = opendir(CFG_PARENT."/files");
			while($file_name = readdir($handle)) {
				if($file_name != "." && $file_name != "..") {
					$file_list[]=$file_name;
				}
			}
		closedir($handle);

		sort($file_list);

	// Print the list
	for($n=0;$n<count($file_list);$n++) {
		echo "<option value=\"{$file_list[$n]}\">{$file_list[$n]}</option>\n";
	}

?>


</select><br /><br />
<input type="button" onClick="javascript:window.open('<?php echo $bmc_vars['site_url'];?>/files/'+document.file_form.sel_file.value);" value="<?php echo $lang['file_but_run']; ?>" />
&nbsp;&nbsp;&nbsp;
<input type="button" onClick="javascript:delFile();" value="<?php echo $lang['file_but_del']; ?>" />
</form>

<script type="text/javascript">
<!--

	function delFile() {
		var msg=confirm("<?php echo $lang['file_del_msg']; ?>");

		if(msg) {
			document.file_form.submit();
		}

	}


function viewFile(sel)
{
  var index = sel.selectedIndex;

  if (sel.options[index].value != '') {
       document.file_form.sel_file.value=sel.options[index].value;
  }

}

//-->
</script>

<?php
	bmc_Template('admin_footer');
?>