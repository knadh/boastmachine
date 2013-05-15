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

// ====================
// Category management

if(!defined('BLOG')) { bmc_Go("?null"); }

if(!isset($_REQUEST['action']) && $_REQUEST['action'] != "cats") {
	return false;
}

// The subAction, i.e., list cats, add cat, delete cat..
if(isset($_POST['what'])) {
	$do=$_POST['what'];
} else {
	$do="";
}



if(isset($_POST['new_cat']) && $_POST['new_cat'] == "1")
	$new_cat=true; // Add a new category
else
	$new_cat=false; // No addition, modify an existing category


// ====================
// Add
if($do=="mod_cat" && $new_cat) {

	$result=$db->row_count("SELECT id FROM ".MY_PRF."cats where cat_name='{$_POST['cat_name']}' AND blog='".BLOG."'");

	// A category with the same name exists!
	if($result) {
		bmc_template('error_admin', $lang['admin_cat_exist']);
		exit();
	}

	$db->query("INSERT INTO ".MY_PRF."cats (cat_name,cat_info,blog) VALUES('{$_POST['cat_name']}','{$_POST['cat_info']}','".BLOG."')");

	bmc_updateCache('cats'); // Update the cache

	bmc_Go("?action=cats&blog=".BLOG);
}


// ====================
// Delete

if($do=="del_cat") {

	$count=$db->row_count("SELECT id FROM ".MY_PRF."cats WHERE blog='".BLOG."'");

	// There's only one category. so don't delete it
	if($count <= 1) {
		bmc_template('error_admin', $lang['admin_cat_least']);
		exit();
	}

	// Get all the posts in that category
	$posts=$db->query("SELECT id FROM ".MY_PRF."posts WHERE cat='{$_POST['cats']}' AND blog='".BLOG."'");

	// Delete all the related comments
	foreach($posts as $p) {
		$db->query( "DELETE FROM ".MY_PRF."comments WHERE post='{$p['id']}' AND blog='".BLOG."'" );
	}

	// Delete all the posts in the cat
	$db->query( "DELETE FROM ".MY_PRF."posts WHERE cat='{$_POST['cats']}' AND blog='".BLOG."'" ); // Delete all the posts in that category

	// Delete the category
	$db->query( "DELETE FROM ".MY_PRF."cats WHERE id='{$_POST['cats']}' AND blog='".BLOG."'" ); // Delete the ctegory


	bmc_updateCache('cats'); // Update the cache

	// Rebuild the RSS feeds
	include CFG_ROOT."/inc/core/rss.build.php";

	bmc_Go("?action=cats&blog=".BLOG);
	exit();
}


// ====================
// Modify/Edit

if($do=="mod_cat" && !$new_cat) {

	if(!isset($_POST['cat_name']) || !isset($_POST['id'])) { return; }

	$db->query("UPDATE ".MY_PRF."cats SET cat_name='{$_POST['cat_name']}', cat_info='{$_POST['cat_info']}' WHERE id='{$_POST['id']}'");

	bmc_updateCache('cats'); // Update the cache

	bmc_Go("?action=cats&blog=".BLOG);
	exit();
}



// ====================
// No arguments posted, So display the page

	bmc_Template('admin_header', str_replace("%blog%",BLOG_NAME,$lang['admin_cat_title'])); // Page header

?>
<script type="text/javascript">
<!--

function jsDelCat() {

	if(!document.cat_list.cats.value.length) { return false; }

	var m=confirm("<?php echo $lang['admin_cat_msg']; ?>");
	if(!m) {
		return false;
	}

	document.cat_list.submit();
}


function jsClearCat() {

	document.mod_cat.id.value="";
	document.mod_cat.cat_name.value="";
	document.mod_cat.cat_info.value="";

}


function jsDoCat() {

	var selectedItem = document.cat_list.cats.selectedIndex;
	var selectedText = document.cat_list.cats.options[selectedItem].text;
	var selectedValue = document.cat_list.cats.options[selectedItem].value;

	document.mod_cat.id.value=selectedValue;
	document.mod_cat.cat_name.value=selectedText;
	
	var info=eval("document.cat_list.info_"+selectedValue+".value");

	document.mod_cat.cat_info.value=info;

}

//-->
</script>
<h1><?php echo $lang['cats']; ?></h2>

<div>
<table width="100%" border="0" cellpadding="3" cellspacing="0" summary="User list">
	<thead>
		<tr>
			<th id="th0388AFB80000" valign="top" align="left" bgcolor="#F3F3F3" width="40%">
			</th>
		</tr>
	</thead>

	<tbody>
	<tr>
			<td headers="th0388AFB80000" valign="top" align="left" width="40%">
			<div>
			<strong><?php echo $lang['cats']; ?></strong>
			<form method="post" name="cat_list" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="action" value="cats" />
			<input type="hidden" name="what" value="del_cat" />
			<input type="hidden" name="blog" value="<?php echo BLOG; ?>" />
			<div>

		<select name="cats" size="10" onChange="javascript:jsDoCat();">

	<?php

		$hidden=""; // Hidden form fields for storing category descriptions

		// Print the category list
		$cats=$db->query("SELECT * FROM ".MY_PRF."cats WHERE blog='".BLOG."'");

		foreach($cats as $cat) {
		echo "<option value=\"{$cat['id']}\">{$cat['cat_name']}</option>\n";
		$hidden.="<input type=\"hidden\" name=\"info_{$cat['id']}\" value=\"".addSlashes($cat['cat_info'])."\" />\n\n";
		}

	?>
		</select>
		<?php echo $hidden; ?>
		</div>
		<input type="button" onClick="javascript:jsDelCat();" value="<?php echo $lang['admin_but_del']; ?>" />
		</form>

		</div>
			</td>

			<td headers="th0388AFB80002" valign="top" width="60%">
			<div class="form_fields">
			<strong><?php echo $lang['admin_cat_mod']; ?></strong>
			<input type="hidden" name="do" value="add_cat" />
			<form method="post" name="mod_cat" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="what" value="mod_cat" />
			<input type="hidden" name="action" value="cats" />
			<input type="hidden" name="blog" value="<?php echo BLOG; ?>" />
			<input type="hidden" name="id" value="" />
			<?php echo $lang['admin_cat_name']; ?> : <br /><input type="text" name="cat_name" size="30" /><br />
			<?php echo $lang['admin_cat_info']; ?> : <br /><input type="text" name="cat_info" size="30" /><br />
			<?php echo $lang['admin_cat_new']; ?> <input type="checkbox" name="new_cat" value="1" /><br /><br />
			<input type="submit" name="submit_button" value="<?php echo $lang['admin_cat_mod']; ?>" />
			<input type="button" onClick="javascript:jsClearCat();" value="<?php echo $lang['post_clear_but']; ?>" />
			</form>
			</div>
			</td>

		</tr>
	</tbody>
</table>
</div>

<div>
<strong><?php echo $lang['admin_cat_stats']; ?></strong><br /><br />
<?php

	foreach($cats as $cat) {
		$count=$db->row_count("SELECT id FROM ".MY_PRF."posts WHERE cat='{$cat['id']}'");
		echo str_replace("%cat%",$cat['cat_name'], $lang['admin_cat_total'])." : ".$count."<br />";
	}

?>
</div>


<?php

bmc_Template('admin_footer');
exit();

?>