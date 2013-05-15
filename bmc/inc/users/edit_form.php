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


	// The owner blog
	if(defined('BLOG')) {
		$blog=BLOG;
	} else {
		if(!isset($blog)) {
			// There's some serious problem! The constant 'BLOG' is empty!
			bmc_template('error_page', $lang['post_no_blog']); exit;
		}
	}

	// Get the post info
	if(isset($_GET['id']) && is_numeric($_GET['id'])) {
		$post=$db->query("SELECT * FROM ".MY_PRF."posts WHERE id='{$_GET['id']}' AND blog='{$blog}'", false);
	}

		if(!isset($post)) {
			bmc_template('error_page', $lang['post_edit_no']);
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
var oldMessage = eval("document.modpost."+obj+".value");
newMessage = oldMessage+ " " + sm + " "; 
eval("document.modpost."+obj+".value=newMessage");
}

function popWin(ul) {
	var theURL = ul;
	newWin = window.open(theURL,'smile','toolbar=No,menubar=No,left=200,top=200,resizable=No,scrollbars=Yes,status=No,location=No,width=350,height=400');
}

function clearFiles() {
	document.getElementById('file_div').innerHTML="";
	document.modpost.files.value="";
}

//-->
</script>
<div>
<strong><?php echo $lang['blog']; ?> : </strong><?php echo $i_blog['blog_name']; ?><br /><br />
<form name="modpost" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="action" value="mod_post" />
<input type="hidden" name="blog" value="<?php echo $blog; ?>" />
<input type="hidden" name="id" value="<?php echo $post['id']; ?>" />
<input type="hidden" name="files" value="<?php echo $post['file']; ?>" />
<input type="hidden" name="author" value="<?php echo $post['author']; ?>" />
<strong><?php echo $lang['post_title']; ?></strong><br />
<input type="text" name="title" maxlength="75" size="46" value="<?php echo bmc_htmlentities($post['title']); ?>" /><br /><br />

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
<input type="text" name="keywords" maxlength="200" size="46" value="<?php echo bmc_htmlentities($post['keyws']); ?>" /><br /><br />


<strong><?php echo $lang['post_format']; ?></strong><br />
<select name="format" size="1">
<option value="text">Text</option>
<?php if($bmc_vars['post_html']) { ?>
<option value="html">HTML</option>
<?php } ?>
</select><br /><br />

<strong><?php echo $lang['post_attach']; ?></strong> : <a href="javascript:popWin('<?php echo $bmc_vars['site_url']."/".BMC_DIR; ?>/files.php?form_id=edit');" title="<?php echo $lang['post_attach_mg']; ?>"><?php echo $lang['post_attach_mg']; ?></a>&nbsp;&nbsp;&nbsp;<a href="javascript:clearFiles();" title="<?php echo $lang['post_attach_clear']; ?>">x</a>
<div id="file_div"><?php echo str_replace("|",", ",$post['file']); ?></div>
<br />


<strong><?php echo $lang['post_smr']; ?></strong> <?php echo $lang['post_content_note']; ?><br />

<?php

	if(!$bmc_vars['post_html']) {
		// Generate the bbCode buttons
		$form_name="document.modpost.smr"; // The summary form (form_name.field_name)
		include CFG_ROOT."/inc/users/bbcode_bar.php";
	} else {
?>
		<a href="javascript:loadEditor('smr');"><?php echo $lang['post_html_toolbar']; ?></a>
<?php
	}
?>
<br />
<textarea name="smr" rows="25" cols="70"><?php echo bmc_htmlentities($post['summary']); ?></textarea><br /><br />
<?php bmc_getsmiles('smr'); ?>
<br />


<?php echo $lang['post_note']; ?>

<br /><strong><?php echo bmc_htmlentities($lang['post_content']); ?></strong>
<?php echo $lang['post_content_note']; ?>
<br />

<?php

	if(!$bmc_vars['post_html']) {
		// Generate the bbCode buttons
		$form_name="document.modpost.msg"; // The summary form (form_name.field_name)
		include CFG_ROOT."/inc/users/bbcode_bar.php";
	} else {
?>
		<a href="javascript:loadEditor('msg');"><?php echo $lang['post_html_toolbar']; ?></a>
<?php
	}
?>
<br />
<textarea name="msg" rows="25" cols="70"><?php echo $post['data']; ?></textarea><br />
<?php bmc_getsmiles('msg'); ?>

<br /><a href="javascript:popWin('<?php echo $bmc_vars['site_url']."/".BMC_DIR; ?>/smile.php');"><?echo $lang['post_smile']; ?></a>
<br /><br />
<?php echo $lang['post_date']; ?><br />
<input type="text" name="date" maxlength="70" size="46" value="<?php echo bmc_Date($post['date'], "m/d/Y h:i:s a"); ?>" />
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
	echo "<option value=\"{$n}\">".bmc_Date(mktime(1,1,1,$n,1,date("Y")), "M")."</option>\n";
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
<?php echo $lang['post_protected']; ?><br /><input type="password"  name="password" value="<?php echo $post['password']; ?>" /><br /><br />
<input type="checkbox" name="post_autobr" checked value="1" /> <?php echo $lang['post_autobr']; ?>

<?php

	// Allow commenting?
	if($bmc_vars['user_comment']) {
		echo "<br /><input checked type=\"checkbox\" name=\"user_comment\" value=\"1\" /> ".$lang['admin_sett_cmt'];
	}

	// Enable comment notification ?
	if($bmc_vars['user_comment_notify']) {
		echo "<br /><input checked type=\"checkbox\" name=\"user_comment_notify\" value=\"1\" /> ".$lang['admin_sett_cmt_notify'];
	}

	// Allow voting/rating?
	if($bmc_vars['user_vote']) {
		echo "<br /><input checked type=\"checkbox\" name=\"user_vote\" value=\"1\" /> ".$lang['admin_sett_vote'];
	}

	// Accept trackbacks for this post? (3.1)
	if($bmc_vars['send_ping']) {
		echo "<br /><input type=\"checkbox\" name=\"accept_trackback\" checked value=\"1\" />".$lang['post_track'];
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


		document.modpost.action.value="mod_post";

	function doForm(what) {
		if(what == "submit") {
			document.modpost.target="_self";
			document.modpost.action.value="mod_post";
			document.modpost.submit();
		} else {
			document.modpost.target="_blank";
			document.modpost.action.value="preview_post";
			document.modpost.submit();
		}

	}



function loadEditor(txt_box) {

	// Cant load toolbar in TEXT mode
	if(document.modpost.format.value=="text") {
		alert("<?php echo $lang['post_html_toolbar_no']; ?>");
		return;
	}

	var theURL = "<?php echo $bmc_vars['site_url']; ?>/bmc/wysiwyg/?form=modpost&box="+txt_box;
	newWin = window.open(theURL,'editor_'+txt_box,'toolbar=No,menubar=No,left=200,top=200,resizable=No,scrollbars=Yes,status=No,location=No,width=600,height=350');
}


<?php


?>

var n=0;

for(n=0;n<=document.modpost.cat.options.length;n++) {
	if(document.modpost.cat.options[n].value == "<?php echo $post['cat']; ?>") {
		document.modpost.cat.options[n].selected=true;
		break;
	}
}


	var format="<?php echo $post['format']; ?>";

	if(format == "text") {
		document.modpost.format.options[0].selected=true;
	} else {
		document.modpost.format.options[1].selected=true;
	}


	var status="<?php echo $post['status']; ?>";
	switch(status) {
		case '0':
		document.modpost.status[1].checked="true"; // Hidden post
		break;

		case '1':
		document.modpost.status[0].checked="true"; // Normal post
		break;

		case '2':
		document.modpost.status[2].checked="true"; // Draft
		break;

	}

<?php
	if(isset($post['draft_date']) && strlen($post['draft_date']) > 5) {
?>
	var d_day=<?php echo bmc_Date($post['draft_date'], "j"); ?>;
	var d_month=<?php echo bmc_Date($post['draft_date'], "m"); ?>;
	var d_year=<?php echo bmc_Date($post['draft_date'], "Y"); ?>;


	d_day=d_day-1;
	document.modpost.draft_day.options[d_day].selected="true";

	d_month=d_month-1;
	document.modpost.draft_month.options[d_month].selected="true";

	var year=<?php echo bmc_Date(0,"Y"); ?>;
	var year_chk=d_year-year;
	document.modpost.draft_year.options[year_chk].selected="true";

<?php
	}

	if($bmc_vars['user_comment']) { ?>
	var user_comment=<?php echo $post['user_comment']; ?>;
	if(user_comment) { document.modpost.user_comment.checked=true; } else { document.modpost.user_comment.checked=false; }

<?php }

	if($bmc_vars['user_comment_notify']) { ?>
	var user_comment_notify=<?php echo $post['user_comment_notify']; ?>;
	if(user_comment_notify) { document.modpost.user_comment_notify.checked=true; } else { document.modpost.user_comment_notify.checked=false; }

<?php }

	if($bmc_vars['user_vote']) { ?>
	var user_vote=<?php echo $post['user_vote']; ?>;
	if(user_vote) { document.modpost.user_vote.checked=true; } else { document.modpost.user_vote.checked=false; }
<?php } ?>

	var post_autobr=<?php echo $post['post_autobr']; ?>;
	if(post_autobr) { document.modpost.post_autobr.checked=true; } else { document.modpost.post_autobr.checked=false; }

<?php if($bmc_vars['trackbacks']) { ?>
	var accept_trackback=<?php echo $post['accept_trackback']; ?>;
	if(accept_trackback) { document.modpost.accept_trackback.checked=true; } else { document.modpost.accept_trackback.checked=false; }
<?php } ?>

//-->
</script>

</div>