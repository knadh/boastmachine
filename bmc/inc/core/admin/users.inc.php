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

	global $lang,$bmc_vars;
	$blog=BLOG;


// =======================
// ========== USER MANAGEMENT

// Add user form
if($_GET['action'] == "add_user" && !isset($_POST['do'])) {
bmc_Template('admin_header', $lang['admin_user_add']);

echo <<<EOF
<strong>{$lang['admin_user_add']}</strong>

<div class="form_fields">
<form method="post" action="{$_SERVER['PHP_SELF']}">
<input type="hidden" name="action" value="add_user" />
<input type="hidden" name="do" value="add" />
{$lang['user_login']} : <input type="text" name="user_login" /><br />
{$lang['user_pass']} : <input type="password" name="user_pass" /><br />
{$lang['user_name']} : <input type="text" name="user_name" /><br />
{$lang['user_email']} : <input type="text" name="user_email" /><br />
{$lang['user_blogs']}<br />
<select name="blogs[]" size="5" multiple>
EOF;

	$blogs=$db->query("SELECT id,blog_name FROM ".MY_PRF."blogs ORDER BY blog_name");
	foreach($blogs as $blog) {
		echo "<option value=\"{$blog['id']}\">{$blog['blog_name']}  ({$blog['id']})</option>\n";
	}

echo <<<EOF
</select>
<br />
<div>
{$lang['admin_user_level']} : <select name="user_level" size="1">
<option value="0">0</option>
<option value="1" selected>1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
</select>
</div>
<br /><br /><input type="submit" value="{$lang['admin_but_add']}" /><br />
</form>
</div>
EOF;

bmc_Template('admin_footer'); exit;
}


// ========== Add a user
if(isset($_POST['action']) && $_POST['action'] == "add_user" && isset($_POST['do']) && $_POST['do']=="add") {

	// Empty fields!
	if(empty($_POST['user_login']) || empty($_POST['user_name']) || empty($_POST['user_email']) || empty($_POST['user_pass']) || empty($_POST['user_level']) || empty($_POST['blogs'])) {
		bmc_template('error_admin', $lang['empty_fields']);
	}

	// Username, password too short
	if(strlen(trim($_POST['user_login'])) < 3) bmc_template('error_admin', $lang['user_short_pass']);

	if(strlen($_POST['user_pass']) < 5) bmc_template('error_admin', $lang['user_short_pass']);

	$result=$db->row_count("SELECT id FROM ".MY_PRF."users where user_login='{$_POST['user_login']}' OR user_email='{$_POST['user_email']}'"); // Check whether the user already exists

	if($result) {
		bmc_template('error_admin', $lang['admin_user_exist']);
	}


	// Prepare the associated blog list
	$blog_list=serialize($_POST['blogs']);

	$db->query("INSERT INTO ".MY_PRF."users (user_login,user_email,user_pass,user_name,date,level,blogs) VALUES('{$_POST['user_login']}','{$_POST['user_email']}','".md5($_POST['user_pass'])."','{$_POST['user_name']}','".time()."', '{$_POST['user_level']}', '{$blog_list}')");

	bmc_Go("Location: ?action=list_users");
}


// ========== Delete a user
if($_GET['action'] == "delete_user" && isset($_GET['user'])) {
	if($_GET['user'] == "1") bmc_template('error_admin', $lang['admin_user_nodel']);

	$db->query("DELETE FROM ".MY_PRF."users WHERE id='{$_GET['user']}'");
	$db->query("DELETE FROM ".MY_PRF."posts WHERE author='{$_GET['user']}'");
	$db->query("DELETE FROM ".MY_PRF."comments WHERE author='{$_GET['user']}'");

	bmc_Go("Location: ?action=list_users"); exit;
}


// ========== Edit user form
if($_GET['action']=="edit_user" && isset($_GET['user'])) {

	// Check the user
	$user=$db->query("SELECT * FROM ".MY_PRF."users WHERE id='{$_GET['user']}'", false);

	// User with that ID doesn't exist;
	if(!$user) {
	bmc_template('error_admin', $lang['admin_user_no']);
	}

	bmc_Template('admin_header', $lang['admin_user_edit']." :: \"".$user['user_login']."\"");

// ========== The FORM
echo "<strong>{$lang['admin_user_edit']} :: \"{$user['user_login']}\"</strong>";
if($user['level']=="4") { echo " ({$lang['admin']}) "; }
echo "<br /><br />";


// Include the user info Form
include CFG_ROOT."/inc/users/user_form.php";

	bmc_Template('admin_footer');
	exit;
}




// ========== Save the edited the user data
if(isset($_POST['action']) && $_POST['action'] == "edit_user" && isset($_POST['do']) && $_POST['do'] == "edit") {

	// Do some checking and validation
	if(empty($_POST['user_login']) || empty($_POST['user_name']) || empty($_POST['user_email'])) {
	bmc_template('error_admin', $lang['empty_fields']);
	}


	if(!empty($_POST['user_pass']) && !empty($_POST['user_pass2'])) {

		// Passwords too short
		if(strlen($_POST['user_pass']) < 5) {
		bmc_template('error_admin', $lang['user_short_pass']);
		}

		// Passwords dont match
		if($_POST['user_pass'] != $_POST['user_pass2']) {
		bmc_template('error_admin', $lang['user_pass_nomatch']);
		} else { $pass_changed=true; }
	}

	// Username too short
	if(strlen($_POST['user_login']) < 3) bmc_template('error_admin', $lang['user_short_user']);

	$real_login=$_POST['user_login_real']; // The username of the person who is being editted, in case if the admin needs to change the username :)
	$id=$_POST['id'];

	// Check whether the username has been changed and whether it already exists

	$result=$db->query("SELECT id,user_login FROM ".MY_PRF."users WHERE user_login='{$real_login}'", false);

		if(!isset($result['id']) || $result['id'] != $id) {
		// There's some problem. Ther username and its ID doesn't match
		bmc_Go("Location: ?action=list_users"); exit;
		}

	if($real_login != $_POST['user_login']) {
		$result=$db->row_count("SELECT id,user_login FROM ".MY_PRF."users WHERE user_login='{$_POST['user_login']}'");
		if($result) {
			bmc_template('error_admin', $lang['user_exists_msg']);
		}
	}

	// Yes, the password has been changed
	if(isset($pass_changed) && $pass_changed == true) {
		$password_sql=",user_pass='".md5($_POST['user_pass'])."' ";
	} else {
		$password_sql="";
	}


	// The birth date
	$birth=$_POST['user_birth_day']."/".$_POST['user_birth_month']."/".$_POST['user_birth_year'];


	// The display id
	if(isset($_POST['user_showid'])) {
		switch ($_POST['user_showid']) {
			case 'user_name':
			$show_id="user_name";
			break;

			case 'user_nick':
			$show_id="user_nick";
			break;

			case 'user_login':
			$show_id="user_login";
			break;

			default:
			$show_id="user_name";
			break;
		}
	}

	// Display email?
	if(isset($_POST['user_show_email']) && $_POST['user_show_email']=="true") {
		$show_email=1;
	} else {
		$show_email=0;
	}


	// Display profile?
	if(isset($_POST['user_show_profile']) && $_POST['user_show_profile']=="true") {
		$show_profile=1;
	} else {
		$show_profile=0;
	}

	// The user level
	if(isset($_POST['user_level'])) {
		switch ($_POST['user_level']) {
			case '0':
			case '1':
			case '2':
			case '3':
			case '4':
			$level=$_POST['user_level'];
			break;			

			default:
			$level='1';
		}
	}


	// the users's 'Associated' blog list
	$blog_list=serialize($_POST['blogs']);

	$db->query("UPDATE ".MY_PRF."users SET user_login='".trim($_POST['user_login'])."',user_name='{$_POST['user_name']}',user_email='{$_POST['user_email']}',user_nick='{$_POST['user_nick']}',user_url='{$_POST['user_url']}', user_location='{$_POST['user_location']}',user_birth='{$birth}',user_yim='{$_POST['user_yim']}',user_msn='{$_POST['user_msn']}',user_icq='{$_POST['user_icq']}',user_profile='{$_POST['user_profile']}',user_showid='{$show_id}',user_show_email='$show_email',public_profile='{$show_profile}',level='{$level}',blogs='{$blog_list}' $password_sql WHERE id='{$id}'");

	bmc_Go("Location: ?action=list_users"); exit;

}



// ===================================================
// ========== Show the user list

if($_GET['action']=="list_users") {

	bmc_Template('admin_header', $lang['admin_user_list']);

	// Get the user list
	$result=$db->query("SELECT id,user_login,user_name,date,level FROM ".MY_PRF."users ORDER BY date DESC");
	$total=count($result);

	$suspended=$db->row_count("SELECT id FROM ".MY_PRF."users WHERE level='0'"); // total suspended users
	$admins=$db->row_count("SELECT id FROM ".MY_PRF."users WHERE level='4'"); // total admins
?>

<h1><?php echo $lang['admin_user_list']; ?></h1>
<?php

echo <<<EOF
<div>
{$lang['admin_user_total']} : $total &nbsp;&nbsp;&nbsp;
{$lang['admin_user_admin_total']} : $admins &nbsp;&nbsp;&nbsp;
{$lang['admin_user_suspended']} : $suspended &nbsp;&nbsp;&nbsp;
<script type="text/javascript">
<!--
function chk_count() {
	return $total;
}

function deluser(id) {
	var id;

	if(chk_count() <= 1) {
		alert("{$lang['admin_user_atleast']}");
		return;
	}

	if(confirm("{$lang['admin_user_del_msg']}")) {
		document.location="?action=delete_user&user="+id;
	}
	else {
		return;
	}
}
//-->
</script>
</div>

<br /><br />
<table width="100%" border="0" style="float:right" cellpadding="3" cellspacing="0" summary="User list">
	<thead>
		<tr>
			<th id="th0388AFB80000" valign="top" align="left">
			{$lang['user_login']}
			</th>
			<th id="th0388AFB80001" valign="top" align="left">
			{$lang['user_name']}
			</th>
			<th id="th0388AFB80002" valign="top" align="left">
			{$lang['admin_user_level']}
			</th>
			<th id="th0388AFB80003" valign="top" align="left">
			{$lang['date']}
			</th>
			<th id="th0388AFB80003" valign="top" align="left">
			</th>
		</tr>
	</thead>
	<tbody>
EOF;

	$n=1;
	// Print the list
	foreach($result as $data) { 
		$date=bmc_Date($data['date']);

echo <<<EOF
		<tr>
			<td headers="th0388AFB80000" valign="top" align="left">
			<a href="?action=edit_user&user={$data['id']}" title="{$lang['admin_user_edit']}">{$data['user_login']}</a>
			</td>
			<td headers="th0388AFB80001" valign="top" align="left">
			{$data['user_name']}
			</td>
			<td headers="th0388AFB80002" valign="top" align="left">
			{$data['level']}
			</td>
			<td headers="th0388AFB80003" valign="top" align="left">
			$date
			</td>
			<td headers="th0388AFB80003" valign="top" align="left">
			<a href="javascript:deluser('{$data['id']}');" title="{$lang['admin_user_del']}"><strong>x</strong></a>
			</td>
		</tr>
EOF;

	$n++;

	}

echo "	</tbody></table>";


	bmc_Template('admin_footer');
	exit;
}


?>