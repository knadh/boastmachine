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

// ==============================
// Save the Settings


if(isset($_POST['action']) && isset($_POST['settings_type']) && ($_POST['action'] == "save_setts")) {

	switch($_POST['settings_type']) {

		case 'settings_site':
			bmc_setVar("site_email", $_POST['admin_email']);
			bmc_setVar("site_url", $_POST['admin_blog']);
			bmc_setVar("site_title", $_POST['admin_title']);
			bmc_setVar("site_desc", $_POST['admin_desc']);
			bmc_setVar("date_str", $_POST['admin_date']);
			// (Added in 3.1 )
			if(isset($_POST['admin_gmt_diff']) && is_numeric($_POST['admin_gmt_diff'])) {
				bmc_setVar("gmt_diff", trim($_POST['admin_gmt_diff']));
			} else {
				bmc_setVar("gmt_diff", "0.00");
			}

			bmc_setVar("time_zone", $_POST['admin_time_zone']); // (Added in 3.1 )
		break;



		case 'settings_system':
			bmc_setVar("user_registration", $_POST['admin_users']);
			bmc_setVar("user_new_welcome", $_POST['admin_new_welcome']);
			bmc_setVar("user_new_notify", $_POST['admin_new_notify']);
			bmc_setVar("user_default_level", $_POST['admin_default_level']);
			bmc_setVar("archive", $_POST['admin_archive']);
			bmc_setVar("user_comment", $_POST['admin_cmt']);
			bmc_setVar("user_comment_guests", $_POST['admin_cmt_guests']);
			bmc_setVar("user_comment_threading", $_POST['admin_cmt_threading']);
			bmc_setVar("user_comment_notify", $_POST['admin_cmt_notify']);
			bmc_setVar("user_comment_session", $_POST['admin_cmt_sess']);
			bmc_setVar("image_verify", $_POST['admin_cmt_verify']); // (Added in 3.1)
			bmc_setVar("user_vote", $_POST['admin_vote']);
			bmc_setVar("user_send_post", $_POST['admin_send']);
			bmc_setVar("user_search", $_POST['admin_search']);
			bmc_setVar("rss_feed", $_POST['admin_rss']);
		break;


		case 'settings_misc':
			bmc_setVar("p_total", $_POST['admin_total']);
			bmc_setVar("p_page", $_POST['admin_ppage']);
			bmc_setVar("title_wrap", $_POST['admin_xwrap']);
			bmc_setVar("summary_wrap", $_POST['admin_cwrap']);	
			bmc_setVar("send_ping", $_POST['admin_ping']);
			bmc_setVar("post_send_subject", $_POST['admin_send_subj']);
			bmc_setVar("auto_convert_link", $_POST['admin_cnv']);
			bmc_setVar("post_html", $_POST['admin_html']);
			bmc_setVar("user_files", $_POST['admin_files']);
			bmc_setVar("ping_urls",$_POST['ping_urls']);
			bmc_setVar("trackbacks", $_POST['admin_trackbacks']); // (Added in 3.1 )
		break;

	}

	bmc_Go("?action=settings"); exit;
}

// ==============================
// Settings page

	bmc_Template('admin_header', $lang['admin_sett_title']);


echo <<<EOF
<script type="text/javascript">
<!--


	function switch_layer(layer) {


		var fl = new Array;

		// System settings
		fl[0]={$bmc_vars['user_registration']}; //admin_users
		fl[1]={$bmc_vars['user_new_welcome']}; // admin_new_welcome
		fl[2]={$bmc_vars['user_new_notify']}; // admin_new_notify
		fl[3]={$bmc_vars['user_default_level']}; // admin_default_level
		fl[4]={$bmc_vars['archive']}; //admin_archive
		fl[5]={$bmc_vars['user_comment']}; //admin_cmt
		fl[6]={$bmc_vars['user_comment_guests']}; //admin_cmt_guests
		fl[7]={$bmc_vars['user_comment_threading']}; //admin_cmt_threading
		fl[8]={$bmc_vars['user_comment_notify']}; //admin_cmt_notify
		fl[9]={$bmc_vars['user_comment_session']}; //admin_cmt_sess
		fl[10]={$bmc_vars['image_verify']}; //admin_cmt_verify
		fl[11]={$bmc_vars['user_vote']}; //admin_vote
		fl[12]={$bmc_vars['user_send_post']}; //admin_send
		fl[13]={$bmc_vars['user_search']}; //admin_search
		fl[14]={$bmc_vars['rss_feed']}; //admin_rss


		// Misc settings
		fl[15]={$bmc_vars['send_ping']}; //admin_ping
		fl[16]={$bmc_vars['auto_convert_link']}; //admin_cnv
		fl[17]={$bmc_vars['post_html']}; //admin_html
		fl[18]={$bmc_vars['user_files']}; //admin_files
		fl[19]={$bmc_vars['trackbacks']}; //admin_trackbacks


	var container=document.getElementById('settings_container');	// The container
	var frm=document.sets;

		switch(layer) {
			case 'settings_site':
				container.innerHTML="...";
				container.innerHTML=document.getElementById('settings_site').innerHTML;

			break;

			case 'settings_system':
				container.innerHTML="...";
				container.innerHTML=document.getElementById('settings_system').innerHTML;

						// System settings
						if(!fl[0]) frm.admin_users.selectedIndex=1;
						if(!fl[1]) frm.admin_new_welcome.selectedIndex=1;
						if(!fl[2]) frm.admin_new_notify.selectedIndex=1;
						frm.admin_default_level.options[fl[3]].selected=true;
						if(!fl[4]) frm.admin_archive.selectedIndex=1;
						if(!fl[5]) frm.admin_cmt.selectedIndex=1;
						if(!fl[6]) frm.admin_cmt_guests.selectedIndex=1;
						if(!fl[7]) frm.admin_cmt_threading.selectedIndex=1;
						if(!fl[8]) frm.admin_cmt_notify.selectedIndex=1;
						if(!fl[9]) frm.admin_cmt_sess.selectedIndex=1;
						if(!fl[10]) frm.admin_cmt_verify.selectedIndex=1;
						if(!fl[11]) frm.admin_vote.selectedIndex=1;
						if(!fl[12]) frm.admin_send.selectedIndex=1;
						if(!fl[13]) frm.admin_search.selectedIndex=1;
						if(!fl[14]) frm.admin_rss.selectedIndex=1;
			break;

			case 'settings_misc':
				container.innerHTML="...";
				container.innerHTML=document.getElementById('settings_misc').innerHTML;

					// Misc settings
					if(!fl[15]) frm.admin_ping.selectedIndex=1;
					if(!fl[16]) frm.admin_cnv.selectedIndex=1;
					if(!fl[17]) frm.admin_html.selectedIndex=1;
					if(!fl[18]) frm.admin_files.selectedIndex=1;
					if(!fl[19]) frm.admin_trackbacks.selectedIndex=1;
			break;
		}

	}

//-->
</script>

<div id="settings">
<form method="post" name="sets" action="{$_SERVER['PHP_SELF']}">
<input type="hidden" name="action" value="save_setts" />
<input type="hidden" name="admin_time_zone" value="" />
<h2>{$lang['admin_sett_title']}</h2>

<div id="settings_links">
<a href="javascript:switch_layer('settings_site');"><strong>{$lang['admin_sett_site_sett']}</strong></a>&nbsp;&nbsp;&nbsp;
<a href="javascript:switch_layer('settings_system');"><strong>{$lang['admin_sett_system_sett']}</strong></a>&nbsp;&nbsp;&nbsp;
<a href="javascript:switch_layer('settings_misc');"><strong>{$lang['admin_sett_misc_sett']}</strong></a>&nbsp;&nbsp;&nbsp;
</div>
<br /><br /><br />

<center><input type="submit" id="settings_submit" value="{$lang['admin_sett_save_but']}" /></center>


<div id="settings_container">
Loading..
</div>
</div>
</form>

<div id="settings_site">
<input type="hidden" name="settings_type" value="settings_site" />
<h3>{$lang['admin_sett_site_sett']}</h3>
<br />

{$lang['admin_sett_aemail']}
<br />
<input type="text" title="{$lang['admin_sett_tip_email']}" value="{$bmc_vars['site_email']}" name="admin_email" size="27" .>
&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_email']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_burl']}
<br />
<input type="text" title="{$lang['admin_sett_tip_burl']}" value="{$bmc_vars['site_url']}" name="admin_blog" size="27" />
&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_burl']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_site_title']}
<br />
<input type="text" title="{$lang['admin_sett_tip_site_title']}" value="{$bmc_vars['site_title']}" name="admin_title" size="27" />
&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_site_title']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_desc']}
<br />
<input type="text" title="{$lang['admin_sett_tip_desc']}" value="{$bmc_vars['site_desc']}" name="admin_desc" size="27" />
&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_desc']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_datestr']}
<br />
<input type="text" title="{$lang['admin_sett_tip_datestr']}" value="{$bmc_vars['date_str']}" name="admin_date" size="27" />
&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_datestr']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_tip_tzone']}
<br />

EOF;

	// This script generates the timezone list
	include CFG_ROOT."/inc/core/admin/timezones.settings.admin.php";

echo <<<EOF
&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_timezone']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_gmtdiff']}
<br />
<input type="text" onBlur="javascript:chkGmtDiff();" title="{$lang['admin_sett_tip_datestr']}" value="{$bmc_vars['gmt_diff']}" name="admin_gmt_diff" size="27" />&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_gmtdiff']}');"><strong>?</strong></a>
</div>


<div id="settings_system">
<input type="hidden" name="settings_type" value="settings_system" />

<h3>{$lang['admin_sett_system_sett']}</h3>


{$lang['admin_sett_users']}
<br />
<select title="{$lang['admin_sett_tip_users']}" name="admin_users" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_users']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_new_welcome']}
<br />
<select title="{$lang['admin_sett_tip_new_welcome']}" name="admin_new_welcome" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_new_welcome']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_new_notify']}
<br />
<select title="{$lang['admin_sett_tip_new_notify']}" name="admin_new_notify" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_new_notify']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_default_level']}
<br />
<select name="admin_default_level" size="1">
<option value="0">0</option>
<option value="1">1</option>
<option value="2"selected>2</option>
<option value="3">3</option>
<option value="4">4</option>
</select>
&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_default_level']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_archive']}
<br />
<select title="{$lang['admin_sett_tip_archive']}" name="admin_archive" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_archive']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_cmt']}
<br />
<select title="{$lang['admin_sett_tip_cmt']}" name="admin_cmt" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_cmt']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_cmt_guests']}
<br />
<select title="{$lang['admin_sett_tip_cmt']}" name="admin_cmt_guests" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_cmt_guests']}');"><strong>?</strong></a>
<br /><br />

{$lang['admin_sett_cmt_thread']}
<br />
<select title="{$lang['admin_sett_tip_cmt_thread']}" name="admin_cmt_threading" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_cmt_thread']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_cmt_notify']}
<br />
<select title="{$lang['admin_sett_tip_cmt_notify']}" name="admin_cmt_notify" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_cmt_notify']}');"><strong>?</strong></a>
<br /><br />

{$lang['admin_sett_cmtsess']}
<br />
<select title="{$lang['admin_sett_tip_cmt_sess']}" name="admin_cmt_sess" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_cmt_sess']}');"><strong>?</strong></a>
<br /><br />

{$lang['admin_sett_cmt_verify']}
<br />
<select title="{$lang['admin_sett_tip_cmt_verify']}" name="admin_cmt_verify" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_cmt_verify']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_vote']}
<br />
<select title="{$lang['admin_sett_tip_vote']}" name="admin_vote" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_vote']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_send']}
<br />
<select name="admin_send" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_chk_yes']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_search']}
<br />
<select name="admin_search" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_search']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_xml']}
<br />
<select name="admin_rss" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_xml']}');"><strong>?</strong></a>
</div>

<div id="settings_misc">
<input type="hidden" name="settings_type" value="settings_misc" />

<h3>{$lang['admin_sett_misc_sett']}</h3>


{$lang['admin_sett_total']}
<br />
<input type="text" title="{$lang['admin_sett_tip_total']}" value="{$bmc_vars['p_total']}" name="admin_total" size="7" />
&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_total']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_ppage']}
<br />
<input type="text" title="{$lang['admin_sett_tip_ppage']}" value="{$bmc_vars['p_page']}" name="admin_ppage" size="7" />
&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_ppage']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_titlewrap']}
<br />
<input type="text" title="{$lang['admin_sett_tip_twrap']}" value="{$bmc_vars['title_wrap']}" name="admin_xwrap" size="7" />
&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_twrap']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_smrwrap']}
<br />
<input type="text" title="{$lang['admin_sett_tip_swrap']}" value="{$bmc_vars['summary_wrap']}" name="admin_cwrap" size="7" />
&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_swrap']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_mail_subj']}
<br />
<input type="text" title="{$lang['admin_sett_tip_sendm']}" value="{$bmc_vars['post_send_subject']}" name="admin_send_subj" size="27" />
&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_sendm']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_autlink']}
<br />
<select name="admin_cnv" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_autolink']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_html']}
<br />
<select name="admin_html" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_html']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_files']}
<br />
<select name="admin_files" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_files']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_ping']}
<br />
<select name="admin_ping" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_ping']}');"><strong>?</strong></a>
<br /><br />

{$lang['admin_sett_trackbacks']}
<br />
<select name="admin_trackbacks" size="1">
<option value="1" selected>{$lang['admin_sett_chk_yes']}</option>
<option value="0">{$lang['admin_sett_chk_no']}</option>
</select>&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_trackbacks']}');"><strong>?</strong></a>
<br /><br />


{$lang['admin_sett_ping_urls']}&nbsp;<a href="javascript:alert('{$lang['admin_sett_tip_ping']}');"><strong>?</strong></a>
<br />
<textarea name="ping_urls" rows="10" cols="50">
{$bmc_vars['ping_urls']}</textarea>
</div>

<script type="text/javascript">
<!--

function chkGmtDiff() {
	var current_time="{$bmc_vars['gmt_diff']}";
	var current_time_zone="{$bmc_vars['time_zone']}";

	if(document.sets.admin_gmt_diff.value != current_time) {
		if((current_time_zone != document.sets.admin_time_zone.value) && (document.sets.timezones.value != document.sets.admin_gmt_diff.value)) {
			document.sets.timezones.selectedIndex=0;
		}
	}

}

// Load the Site Settinsg into the container
document.getElementById('settings_container').innerHTML=document.getElementById('settings_site').innerHTML;

//-->
</script>
EOF;

	// ===========
	// This function sets the values on the settings page for Yes/No select boxes
	function chk_YesNo($var) {
		echo !$bmc_vars[$var] ? '' : 'selected';
	}

	bmc_Template('admin_footer');
	exit;

?>