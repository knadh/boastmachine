<div class="page_num">
<?php echo $lang['page']; ?> :&nbsp;

<?php

// Page number generation

	if($x<1) { $x=0; }
	if($nm%$per_page == 0) { $x=$x-1; }


	// Generate the page numbers
	for($n=1;$n<=$x+1;$n++) {
		echo "<a href=\"".$bmc_vars['site_url']."/".BLOG_FILE."?p={$n}{$query_str}\" title=\"{$lang['page']} {$n}\">{$n}</a>&nbsp;";
	}

?>
</div> <!-- end entry_info //-->