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

	include_once dirname(__FILE__)."/../main.php";

	if(!$bmc_vars['post_html']) {
		die("<script>window.close()</script>");
	}

	if(!isset($_GET['box']) || !isset($_GET['form'])) {
		die("<script>window.close()</script>");
	}

		$box=$_GET['box'];
		$form=$_GET['form'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php echo $lang['post_html_toolbar']; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

	<style type="text/css">
	<!--
	body, br, p, html {
		font-family: verdana;
		font-size: 10px;
		background: #F7F7F7;
	}
	//-->
	</style>

	<script type="text/javascript" src="html2xhtml.js"></script>
	<!-- To decrease bandwidth, use richtext_compressed.js instead of richtext.js //-->
	<script type="text/javascript" src="richtext_compressed.js"></script>

</head>

<body>

<form name="toolbar">
<script type="text/javascript">
<!--
//Usage: initRTE(imagesPath, includesPath, cssFile, genXHTML)
initRTE("images/", "", "", true);
//-->
</script>



<script type="text/javascript">
<!--
//Usage: writeRichText(fieldname, html, width, height, buttons, readOnly)
writeRichText('<?php echo $box; ?>', opener.document.<?php echo $form;?>.<?php echo $box; ?>.value, 400, 200, true, false);
//-->
</script>


<br /><br />
<input type="button" onClick="javascript:window.close();" value="<?php echo $lang['post_html_cancel']; ?>" />&nbsp;&nbsp;
<input type="button" onClick="javascript:submitForm();" value="<?php echo $lang['post_post_but']; ?>" />
</form>

<script language="JavaScript" type="text/javascript">
<!--
	function submitForm() {
		updateRTEs();
		opener.document.<?php echo $form; ?>.<?php echo $box; ?>.value=document.toolbar.<?php echo $box; ?>.value;
		window.close();
	}
//-->
</script>

</body>
</html>
