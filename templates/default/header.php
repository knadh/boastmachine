<?php global $bmc_vars; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php echo $title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['ENCODING']; ?>" />
	<meta name="description" content="<?php echo $title; ?>" />
	<meta name="keywords" content="<?php echo $keys; ?>" />

	<style type="text/css">
	<!--
	<?php include dirname(__FILE__)."/bstyle.css"; ?>
	//-->
	</style>

<link rel="alternate" type="application/rss+xml" title="RSS 2.0 syndication" href="<?php echo $bmc_vars['site_url']."/" ; ?>rss" />

<script type="text/javascript">
<!-- 
function popWin(url) {
	var theURL=url;
	newWin = window.open(theURL,'win','toolbar=No,menubar=No,left=200,top=200,width=350,resizable=yes,scrollbars=yes,status=No,location=No,height=400');
}
//-->
</script>
<?php if(isset($bmc_vars['logged_in_user']['id'])) { ?>
<script type="text/javascript" src="<?php echo $bmc_vars['site_url']."/templates/".CFG_THEME."/floating_menu.js"; ?>"></script>
<? } ?>

</head>

<body>
<div id="wrap">
<div id="header">
	<div id="header_title">
	<?php
	// This might seem crazy, but its simple :) If BLOG_NAME is empty, then display the default site title
	if(defined('BLOG_NAME')) { ?>
	<a href="<?php echo $bmc_vars['site_url']; ?>/<?php echo BLOG_FILE; ?>" title="<?php echo BLOG_NAME; ?>"><?php echo BLOG_NAME; ?></a>
	<?php
	} else {
	// Default site title
	?>
	<a href="<?php echo $bmc_vars['site_url']; ?>" title="<?php echo $bmc_vars['site_title']; ?>"><?php echo $bmc_vars['site_title']; ?></a>
	<?php
	}
	?>
	</div> <!-- end header_title //-->
</div> <!-- end header //-->

<div id="curve_top"></div> <!-- end curve_top //-->

<div id="main">
<div id="container">

<?php
	// Only show the menu if the page_menu flag is set to true
	if($page_menu) {
		include dirname(__FILE__)."/side_menu.php";
	}
		// End if for page_menu
?>


<div id="content">
