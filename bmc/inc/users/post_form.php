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

	$user=bmc_isLogged();

	// The owner blog
	if(defined('BLOG') && BLOG) {
		$blog=BLOG;
	} else {
		// There's some serious problem! The constant 'BLOG' is empty!
		bmc_template('error_page', $lang['post_no_blog']); exit;
	}

?>

<script type="text/javascript">
<!--

// Insert bb_code
function bbcode(obj,c1,c2,frm) {
var code,frm;


if(obj.tmp_str) {
	obj.value=obj.tmp_name;
	obj.tmp_str="";
	code=c2;
}
else {
	obj.tmp_name=obj.value;
	obj.tmp_str="1";
	obj.value="/"+obj.value;
	code=c1;
}

var newMessage; 
var oldMessage = frm.value; 
newMessage = oldMessage+ code; 
frm.value=newMessage;
}

// Insert smiley
function smil(obj, sm) {

var sm; 
var newMessage; 
var oldMessage = eval("document.newpost."+obj+".value");
newMessage = oldMessage+ " " + sm + " "; 
eval("document.newpost."+obj+".value=newMessage");
}

function popWin(ul) {
	var theURL = ul;
	newWin = window.open(theURL,'smile','toolbar=No,menubar=No,left=200,top=200,resizable=No,scrollbars=Yes,status=No,location=No,width=350,height=400');
}

function clearFiles() {
	document.getElementById('file_div').innerHTML="";
	document.newpost.files.value="";
}

//-->
</script>

<div>
<strong><?php echo $lang['blog']; ?> : </strong><?php echo $i_blog['blog_name']; ?><br /><br />
<form name="newpost" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="action" value="save_post" />
<input type="hidden" name="blog" value="<?php echo BLOG; ?>" />
<input type="hidden" name="files" value="" />
<strong><?php echo $lang['post_title']; ?></strong><br /><input type="text" name="title" maxlength="75" size="46" /><br /><br />

<strong><?php echo $lang['post_cat']; ?></strong><br />
<select name="cat" size="1">
<?php
// Print the category list

		$cats=$db->query("SELECT * FROM ".MY_PRF."cats WHERE blog='".BLOG."'");
		foreach($cats as $cat) {
		echo "<option value=\"{$cat['id']}\">{$cat['cat_name']}</option>\n";
		}


?>

</select><br /><br />

<strong><?php echo $lang['post_keys']; ?></strong><br />
<input type="text" name="keywords" maxlength="200" size="46" /><br /><br />


<strong><?php echo $lang['post_format']; ?></strong><br />
<select name="format" size="1">
<option value="text">Text</option>

<?php if($bmc_vars['post_html']) { ?>
<option value="html">HTML</option>
<?php } ?>
</select><br /><br />

<strong><?php echo $lang['post_attach']; ?></strong> : <a href="javascript:popWin('<?php echo $bmc_vars['site_url']."/".BMC_DIR; ?>/files.php?form_id=new');" title="<?php echo $lang['post_attach_mg']; ?>"><?php echo $lang['post_attach_mg']; ?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:clearFiles();" title="<?php echo $lang['post_attach_clear']; ?>">x</a>
<div id="file_div"></div>
<br />

<strong><?php echo $lang['post_smr']; ?></strong> <?php echo $lang['post_content_note']; ?><br />

<?php

	if(!$bmc_vars['post_html']) {
		// Generate the bbCode buttons
		$form_name="document.newpost.smr"; // The summary form (form_name.field_name)
		include CFG_ROOT."/inc/users/bbcode_bar.php";
	} else {
?>
		<a href="javascript:loadEditor('smr');"><?php echo $lang['post_html_toolbar']; ?></a>
<?php
	}
?>
<br />
<textarea name="smr" id="smr" rows="25" cols="100"></textarea><br /><br />
<?php bmc_getsmiles('smr'); ?>
<br />


<?php echo $lang['post_note']; ?>

<br /><strong><?php echo $lang['post_content']; ?></strong>
<?php echo $lang['post_content_note']; ?>
<br />
<?php

	if(!$bmc_vars['post_html']) {
		// Generate the bbCode buttons
		$form_name="document.newpost.smr"; // The summary form (form_name.field_name)
		include CFG_ROOT."/inc/users/bbcode_bar.php";
	} else {
?>
		<a href="javascript:loadEditor('msg');"><?php echo $lang['post_html_toolbar']; ?></a>
<?php
	}
?>

<br />
<textarea name="msg" id="msg" rows="25" cols="100"></textarea><br />
	<?php bmc_getsmiles('msg'); ?>


<br /><br />

<input type="radio" name="status" value="normal" checked /> <?php echo $lang['post_normal']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="status" value="hidden" /> <?php echo $lang['post_hidden']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="status" value="draft" /> <?php echo $lang['post_draft']; ?><br />

<?php
// Generate the day/month/year list for the 'draft' option

// Days
echo "<select name=\"draft_day\">\n";
for($n=1;$n<=31;$n++) {
	echo "<option value=\"{$n}\">$n</option>\n";
}
echo "</select>\n\n";


// Months
echo "<select name=\"draft_month\">\n";
for($n=1;$n<=12;$n++) {
	echo "<option value=\"{$n}\">".bmc_Date(mktime(1,1,1,$n,1,bmc_Date(0,"Y")), "M")."</option>\n";
}
echo "</select>\n\n";


// Years
$now=bmc_Date(0,"Y");
echo "<select name=\"draft_year\">\n";
for($n=0;$n<=10;$n++) {
	$year=$now+$n;
	echo "<option value=\"".$year."\">".$year."</option>\n";
}
echo "</select>\n\n";
?>

<br /><br />
<?php echo $lang['post_protected']; ?><br /><input type="password"  name="password" /><br /><br />

<strong><?php echo $lang['post_track_urls']; ?></strong><br />
<?php echo $lang['post_track_urls_info']; ?>
<br />
<textarea name="track_urls" rows="6" cols="30">
</textarea>

<br /><br />
<input type="checkbox" name="post_autobr" checked value="1" /> <?php echo $lang['post_autobr']; ?>

<?php

	// Allow commenting?
	if($bmc_vars['user_comment']) {
		echo "<br /><input checked type=\"checkbox\" name=\"user_comment\" value=\"1\" /> ".$lang['admin_sett_cmt'];
	}

	// Enable comment notification ?
	if($bmc_vars['user_comment_notify']) {
		echo "<br /><input type=\"checkbox\" name=\"user_comment_notify\" value=\"1\" /> ".$lang['admin_sett_cmt_notify'];
	}

	// Allow voting/rating?
	if($bmc_vars['user_vote']) {
		echo "<br /><input checked type=\"checkbox\" name=\"user_vote\" value=\"1\" /> ".$lang['admin_sett_vote'];
	}

	// Send pings for this post?
	if($bmc_vars['send_ping']) {
		echo "<br /><input checked type=\"checkbox\" name=\"send_ping\" value=\"1\" /> ".$lang['admin_sett_ping'];
	}

	// Accept trackbacks for this post? (3.1)
	if($bmc_vars['trackbacks']) {
		echo "<br /><input type=\"checkbox\" name=\"accept_trackback\" checked value=\"1\" />".$lang['post_track'];
	}


?>

<br /><br />
<input type="button" value="<?php echo $lang['post_post_but']; ?>"  onClick="javascript:doForm('submit');" />
<input type="button" value="<?php echo $lang['post_post_preview']; ?>"  onClick="javascript:doForm('preview');" />
<input type="reset" value="<?php echo $lang['post_clear_but']; ?>" />
</form>

<script type="text/javascript">
<!--

		document.newpost.action.value="save_post";

	function doForm(what) {
		if(what == "submit") {
			document.newpost.target="_self";
			document.newpost.action.value="save_post";
			document.newpost.submit();
		} else {
			document.newpost.target="_blank";
			document.newpost.action.value="preview_post";
			document.newpost.submit();
		}

	}

function loadEditor(txt_box) {

	// Cant load toolbar in TEXT mode
	if(document.newpost.format.value=="text") {
		alert("<?php echo $lang['post_html_toolbar_no']; ?>");
		return;
	}

	var theURL = "<?php echo $bmc_vars['site_url']; ?>/bmc/wysiwyg/?form=newpost&box="+txt_box;
	newWin = window.open(theURL,'editor_'+txt_box,'toolbar=No,menubar=No,left=200,top=200,resizable=No,scrollbars=Yes,status=No,location=No,width=600,height=350');
}

//-->
</script>

</div>