<div class="page_num">
<?php echo $lang['page']; ?> :&nbsp;

<?php

// Page number generation

	if($x<1) { $x=0; }
	if($nm%$per_page == 0) { $x=$x-1; }

	if(empty($query_str)) {
		$query_str="";
	}

	// Generate the page numbers
	for($n=1;$n<=$x+1;$n++) {
		echo "<a href=\"".$bmc_vars['site_url']."/".$bmc_dir."/admin.php?p={$n}&blog={$blog}&action=list_posts\"".$query_str." title=\"{$lang['page']} {$n}\">{$n}</a>&nbsp;";
	}

?>
</div> <!-- end page_num //-->