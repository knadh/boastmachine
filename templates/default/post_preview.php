<!-- Begin post //-->

<h1 class="post_title"><a href="javascript:window.close();"><?php echo $_POST['title']; ?></a></h1>
<small class="post_date"><?php echo bmc_Date(); ?></small>
	<div class="post_text">
	<?php echo $summary; ?>
	</div> <!-- end post_text //-->

<?php
	// If there are attached files, print them in a box

if(!empty($_POST['files'])) {
?>
	<div class="file_list">
	<strong><?php echo $lang['att_file']; ?></strong><br />

<?php
	$file_list=explode("|",$_POST['files']);

	for($n=0;$n<count($file_list);$n++) {
		echo "<a href=\"javascript:window.close();\">".$file_list[$n]."</a><br />\n";
	}
?>
	</div>
<?php
}
?>

<br />
<div class="post_info">
<?php echo $lang['posted_by']; ?> <a href="javascript:window.close();"><?php echo bmc_isLogged(); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript:window.close();"><?php echo $lang['comments']; ?> (0)</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript:window.close();"><?php echo $lang['trackbacks']; ?> (0)</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript:window.close();"><?php echo $lang['send']; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

</div><!-- end entry_info //-->

<!-- End post //-->
