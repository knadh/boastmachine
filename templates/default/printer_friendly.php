<HTML>
<HEAD>
<TITLE><?php echo $i_post['title']; ?></TITLE>

	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['ENCODING']; ?>" /> 
	<style type="text/css">
	<!--
	<?php include dirname(__FILE__)."/printer_friendly.css"; ?>
	//-->
	</style>


</HEAD>
<BODY>

<h1 class="title"><?php echo $i_post['title']; ?></h1><br />

<?php echo $lang['posted_by']; ?> <strong><?php echo $user_name; ?></strong> 
<?php echo $lang['str_on']; ?> <strong><?php echo $date; ?></strong><br />
<?php echo $lang['str_in']; ?> <strong><?php echo $cat; ?></strong> ( <strong><?php echo $i_blog['blog_name']; ?></strong>)</span>
<br /><br />

<div id="summary">
<?php echo $summary; ?>
</span>

<?php
	if($body) {
?>
	<div class="line_hr"></div>
	<div id="body">
	<?php echo $body; ?>
	</span>
<?php } ?>


<div class="line_hr"></div>


<span class="t_small"><?php echo $lang['print_from']; ?> : <a href="<?php echo $bmc_vars['site_url']."/".BLOG_FILE; ?>"><?php echo $bmc_vars['site_url']."/".BLOG_FILE; ?></a></span><br>
<span class="t_small"><?php echo $lang['printed_from']; ?> : <a href="<?php echo $bmc_vars['site_url']."/".BLOG_FILE; ?>/?id=<?php echo $i_post['id']; ?>"><?php echo $bmc_vars['site_url']."/".BLOG_FILE; ?>?id=<?php echo $i_post['id']; ?></a></span>

</BODY>
</HTML>