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

echo <<<EOF

<div class="form_fields">
<form method="post" name="user_mod" action="{$_SERVER['PHP_SELF']}" ENCTYPE="multipart/form-data">
<input type="hidden" name="action" value="edit_user" />
<input type="hidden" name="do" value="edit" />
<input type="hidden" name="user_login_real" value="{$user['user_login']}" />
<input type="hidden" name="id" value="{$user['id']}" />
<input type="hidden" name="date" value="{$user['date']}" />
EOF;

// Only for admins
if(defined('IN_ADMIN')) {
echo <<<EOF
{$lang['user_login']} :  <input type="text" name="user_login" value="{$user['user_login']}" /><br />

{$lang['user_blogs']}<br />
<select name="blogs[]" size="5" multiple>
EOF;

	$blogs=$db->query("SELECT id,blog_name FROM ".MY_PRF."blogs ORDER BY blog_name");

	$user_blogs=unserialize($user['blogs']); // Get the user's associated blog list

	$user_blogs=array_flip($user_blogs);

	foreach($blogs as $blog) {

		if(isset($user_blogs[$blog['id']])) {
			$sel="selected";
		} else {
			$sel="";
		}

		echo "<option $sel value=\"{$blog['id']}\">{$blog['blog_name']}  ({$blog['id']})</option>\n";
	}

echo <<<EOF
</select><br />
EOF;
}

echo <<<EOF
{$lang['user_name']} :  <input type="text" name="user_name" value="{$user['user_name']}" /><br /><br />
({$lang['user_pass_need']})<br />
{$lang['user_pass']} :  <input type="password" name="user_pass" /><br />
{$lang['user_pass']} #2 :  <input type="password" name="user_pass2" value="" /><br /><br />
{$lang['user_email']} :  <input type="text" name="user_email" value="{$user['user_email']}" /><br />
{$lang['user_url']} :  <input type="text" name="user_url" value="{$user['user_url']}" /><br />
{$lang['user_nick']} :  <input type="text" name="user_nick" value="{$user['user_nick']}" /><br />
{$lang['user_location']} :  <input type="text" name="user_location" value="{$user['user_location']}" /><br />
EOF;



// rest of the form
echo <<<EOF
{$lang['user_yim']} :  <input type="text" name="user_yim" value="{$user['user_yim']}" /><br />
{$lang['user_msn']} :  <input type="text" name="user_msn" value="{$user['user_msn']}" /><br />
{$lang['user_icq']} :  <input type="text" name="user_icq" value="{$user['user_icq']}" /><br /><br />
{$lang['user_birth']} : <select name="user_birth_day">
EOF;

list($birth_day, $birth_month, $birth_year)=explode("/",$user['user_birth']);


$sel="";

// Print the day list
for($n=1;$n<=31;$n++) {
	if($n==$birth_day) { $sel=" selected"; } else { $sel=""; }
	echo "<option value=\"{$n}\"$sel>{$n}</option>\n";
}
echo "</select>/";

$sel="";

// Print the month list
echo "<select name=\"user_birth_month\">\n";
for($n=1;$n<=12;$n++) {
	if($n==$birth_month) { $sel=" selected"; } else { $sel=""; }
	echo "<option value=\"{$n}\"$sel>{$n}</option>\n";
}
echo "</select>/";

$sel="";

// Print the year list
echo "<select name=\"user_birth_year\">\n";
for($n=1910;$n<=bmc_Date(0,"Y");$n++) {
	if($n==$birth_year) { $sel=" selected"; } else { $sel=""; }
	echo "<option value=\"{$n}\"$sel>{$n}</option>\n";
}

echo "</select><br />";

if($user['user_show_email']) $chkd_show_email="checked";
if($user['public_profile']) $chkd_show_profile="checked";
if($user['user_show_pic']) $chkd_show_pic="checked";

echo <<<EOF
{$lang['user_disp_id']} :  <select name="user_showid">
<option value="user_login">{$lang['user_login']}</option>
<option value="user_nick">{$lang['user_nick']}</option>
<option value="user_name">{$lang['user_name']}</option>
</select><br /><br />
EOF;

if($user['user_pic']) {
echo <<<EOF
<a href="javascript:popWin('{$bmc_vars['site_url']}/files/{$user['user_pic']}');"><span style="font-size: 10px; font-family: Verdana;">{$user['user_pic']}</span></a>

EOF;
}

echo <<<EOF
<br />
{$lang['user_pic']} : <input type="file" name="user_pic" /><br />
{$lang['user_pic_show']} : <input type="checkbox" name="user_show_pic" value="true"$chkd_show_pic /><br /><br />
{$lang['user_disp_email']} : <input type="checkbox" name="user_show_email" value="true"$chkd_show_email /><br />
{$lang['user_disp_profile']} : <input type="checkbox" name="user_show_profile" value="true"$chkd_show_profile /><br />

EOF;


// SPECIAL FORM FIELD FOR ADMINS :) . Allows to change the user level
// Also, if this is the SUPER admin, dont allow to change the level

if(defined('IN_ADMIN')) {

$chk_level=array('','','','','');

$chk_level[$user['level']]=" selected";


echo <<<EOF
{$lang['admin_user_level']} : <select name="user_level" size="1">
<option value="0"{$chk_level[0]}>0</option>
<option value="1"{$chk_level[1]}>1</option>
<option value="2"{$chk_level[2]}>2</option>
<option value="3"{$chk_level[3]}>3</option>
<option value="4"{$chk_level[4]}>4</option>
</select>
EOF;
}



echo <<<EOF
<br />
{$lang['user_profile']} : <br /><textarea name="user_profile" rows="10" cols="40">{$user['user_profile']}</textarea>
<br />
<input type="submit" value="{$lang['admin_user_save_bt']}" />
</form>
</div>
EOF;
?>