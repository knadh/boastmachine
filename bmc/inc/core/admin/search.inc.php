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
	// Search Posts/Users

	// The search subject. Users or posts
	if(isset($_REQUEST['entity']))
		$entity=$_REQUEST['entity'];
	else
		$entity="";

	// The search query
	if(isset($_REQUEST['q']))
		$query=$_REQUEST['q'];
	else
		$query="";

	// The action (search or show  range)
	if(isset($_REQUEST['do']))
		$do=$_REQUEST['do'];
	else
		$do="";

	// Get the blog name
	if(isset($_REQUEST['blog']))
		$blog=$_REQUEST['blog'];
	else
		$blog="1";


if(empty($entity) || empty($_REQUEST['action']) || empty($do)) {

	bmc_Template('admin_header', $lang['search']);

	// Prepare the blog list
	$blogs=$db->query("SELECT id,blog_name FROM ".MY_PRF."blogs ORDER BY blog_name");
	$blog_list="";
	foreach($blogs as $blog) {
		$blog_list.= "<option value=\"{$blog['id']}\">{$blog['blog_name']}</option>\n";
	}


if(isset($_REQUEST['blog'])) {
	// Prepare the cats list
	$cats=$db->query("SELECT id,cat_name FROM ".MY_PRF."cats WHERE blog='{$_REQUEST['blog']}' ORDER by cat_name");
	foreach($cats as $cat) {
		$cat_list.= "<option value=\"{$cat['id']}\">{$cat['cat_name']}</option>\n";
	}
}



// Print the search boxes
echo <<<EOF
<div>
<form name="search_users" method="post" action="{$_SERVER['PHP_SELF']}">
<input type="hidden" name="entity" value="users" />
<input type="hidden" name="do" value="search">
<input type="hidden" name="action" value="search">

<p><strong>{$lang['admin_search_users']}</strong></p>
<p>{$lang['search']} <input type="text" name="q"> {$lang['str_in']} <select name="field" size="1">
<option value="user_login">{$lang['user_login']}</option>
<option value="full_name">{$lang['user_name']}</option>
<option value="email">{$lang['user_email']}</option>
</select> <input type="submit" value="{$lang['search']}"></p>
</form></div>
<br />

<div>
<form name="show_users" method="post" action="{$_SERVER['PHP_SELF']}">
<input type="hidden" name="entity" value="users" />
<input type="hidden" name="do" value="show">
<input type="hidden" name="action" value="search">

<p><strong>{$lang['admin_search_users_show']}</strong></p>
<p>{$lang['admin_user_level']} : <select name="level" size="1">
<option value="all">{$lang['str_all']}</option>
<option value="0">0</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
</select> {$lang['admin_search_user_regd']} <select name="range" size="1">
<option selected value="this_week">{$lang['admin_post_week']}</option>
<option value="this_month">{$lang['admin_post_month']}</option>
<option value="last_month">{$lang['admin_post_last_month']}</option>
<option value="last_six">{$lang['admin_post_last_6month']}</option>
<option value="last_year">{$lang['admin_post_last_year']}</option>
</select>
&nbsp;&nbsp;<input type="submit" value="{$lang['show']}" /></p>
</form></div>

<br />

<div>
<form name="search_posts" method="post" action="{$_SERVER['PHP_SELF']}">
<input type="hidden" name="entity" value="posts" />
<input type="hidden" name="do" value="search">
<input type="hidden" name="action" value="search">

<p><strong>{$lang['admin_search_posts']}</strong></p>

{$lang['blog']} : <select name="blog" size="1" onChange="javascript:document.location='?action=search&blog='+this.value;">
$blog_list
</select> ({$lang['admin_search_posts_selblog']})<br /><br />
EOF;

if(!empty($_REQUEST['blog'])) {
echo <<<EOF
{$lang['cat']} : <select name="cat" size="1">
<option selected value="all">{$lang['str_all']}</option>
$cat_list
</select>
EOF;
}

echo <<<EOF
<p>{$lang['search']} <input type="text" name="q"> in <select name="field" size="1">
<option value="title">{$lang['title']}</option>
<option value="summary">{$lang['summary']}</option>
<option value="body">{$lang['blog']}</option>
<option value="author">{$lang['author']}</option>
</select> <input type="submit" value="{$lang['search']}" /></p>
</form>
</div>
<br />

<div>
<form name="show_posts" method="post" action="{$_SERVER['PHP_SELF']}">
<input type="hidden" name="entity" value="posts" />
<input type="hidden" name="do" value="show">
<input type="hidden" name="action" value="search">

<p><strong>{$lang['admin_search_posts_show']}</strong></p>
{$lang['blog']} : <select name="blog" size="1">
$blog_list
</select> ({$lang['admin_search_posts_selblog']})<br /><br />

EOF;

if(!empty($_REQUEST['blog'])) {
echo <<<EOF
{$lang['cat']} : <select name="cat" size="1">
<option selected value="all">{$lang['str_all']}</option>
$cat_list
</select>
EOF;
}

echo <<<EOF
<p>{$lang['admin_status']} : <select name="status" size="1">
<option value="all">{$lang['str_all']}</option>
<option value="0">{$lang['hidden']}</option>
<option value="1">{$lang['open']}</option>
<option value="2">{$lang['draft']}</option>
</select> and posted &nbsp;<select name="range" size="1">
<option selected value="this_week">{$lang['admin_post_week']}</option>
<option value="this_month">{$lang['admin_post_month']}</option>
<option value="last_month">{$lang['admin_post_last_month']}</option>
<option value="last_six">{$lang['admin_post_last_6month']}</option>
<option value="last_year">{$lang['admin_post_last_year']}</option>
</select>
&nbsp;&nbsp;<input type="submit" value="{$lang['show']}" /></p>
</form>
</div>
EOF;
bmc_Template('admin_footer'); exit;
} // End printing


	// =======================================
	// Do the searching

	if($do=="search") {

		if(strlen($query) < 4) {
			bmc_template('error_admin', $lang['admin_search_short'],$lang['admin_search_short']);
			exit;
		}


switch($entity) {

	case 'users':

		switch($_REQUEST['field']) {
			case 'user_login':
			$field="user_login";
			break;

			case 'full_name':
			$field="user_name";
			break;

			case 'email':
			$field="user_email";
			break;
		} // end switch

	$sql="SELECT user_name,user_login,id,date,level FROM ".MY_PRF."users";
	break; // end 'users'


	case 'posts':

		switch($_REQUEST['field']) {
			case 'title':
			$field="title";
			break;

			case 'author':
			$field="author";
			break;

			case 'summary':
			$field="summary";
			break;

			case 'body':
			$field="data";
			break;
		} // end switch

	$sql="SELECT id,title,date,author,status FROM ".MY_PRF."posts";
	break;

}

	$data=bmc_mysql_search($query, $field , $sql); // Do the mysql query

	// No results
	if(empty($data)) {
		bmc_template('error_admin', $lang['admin_search_no']); exit;
	}

} // End parent if


// Show the data based on specific criteria
if($do=="show") {

		// Being a time range, the sql queries can be common for both posts and users
		switch($_REQUEST['range']) {
			case 'this_week':
			$range_sql="date >= '".(time()-604800)."'";
			break;

			case 'this_month':
			$start_time=mktime('0','0','0',bmc_Date(0,"m"),1,bmc_Date(0,"Y")); // Time stamp of the beginning day of this month
			$end_time=mktime('12','59','59',bmc_Date(0,"m"),bmc_Date(0,"t"),bmc_Date(0,"Y")); // Time stamp of the last day of this month
			$range_sql="date >= '$start_time' and date <= '$end_time'";
			break;

			case 'last_month':
				if(bmc_Date(0,"m") == 1) {
					$last_month=12;
				} else {
					$last_month=bmc_Date(0,"m")-1;
				}

			$start_time=mktime('0','0','0',$last_month,1,bmc_Date(0,"Y")); // Time stamp of the beginning day of this month
			$end_time=mktime('12','59','59',$last_month,bmc_Date(0,"t"),bmc_Date(0,"Y")); // Time stamp of the last day of this month
			$range_sql="date >= '$start_time' and date <= '$end_time'";
			break;

			case 'last_six':
			$range_sql="date >= '".(time()-15552000)."'";
			break;

			case 'last_year':
			$range_sql="date >= '".(time()-31104000)."'";
			break;

		} // end switch



		if($entity=="posts") {

			$status=$_REQUEST['status']; // Post status
			if(!isset($status))	$status="1";
			if($status == "all") $status=""; else $status="AND status='$status'";

			if(!empty($_POST['cat'])) {
				if($_POST['cat'] == "all") {
					$cat_sql="";
				}
				else {
					$cat_sql=" AND cat='{$_POST['cat']}'";
				}
			}


			$sql="SELECT id,title,date,author,status FROM ".MY_PRF."posts WHERE $range_sql AND blog='$blog' $status $cat_sql";
		} else {

			$level=$_REQUEST['level']; // Post status
			if(!isset($level))	$level="1";
			if($level == "all") $level=""; else $level="AND level='$level'";

			$sql="SELECT user_name,user_login,id,date,level FROM ".MY_PRF."users WHERE $range_sql $level";
		}

	$data=$db->query($sql);

	// No results
	if(empty($data)) {
		bmc_template('error_admin', $lang['admin_search_no']); exit;
	}

}


// Time to print the results!
bmc_Template('admin_header', str_replace("%num%",count($data),$lang['admin_search_results']));
?>
<br /><h1><?php echo str_replace("%num%",count($data), $lang['admin_search_results']); ?></h1><br /><br />
<?php
						// Print the POSTS list
if($entity=="posts") {

	$blog=BLOG;
	include CFG_ROOT."/inc/core/admin/posts_list_table.php"; // Load the post list table template

bmc_Template('admin_footer');

} // End of if($entity





// Print the User list

if($entity=="users") {

echo <<<EOF
<div>
<script type="text/javascript">
<!--

function deluser(id) {
	var id;

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
			{$lang['user_joined']}
			</th>
			<th id="th0388AFB80003" valign="top" align="left">
			</th>
		</tr>
	</thead>
	<tbody>
EOF;


	foreach($data as $results) { 
	$date=bmc_Date($results['date'], "d.m.Y");

echo <<<EOF
		<tr>
			<td headers="th0388AFB80000" valign="top" align="left">
			<a href="?action=edit_user&amp;user={$results['id']}" title="{$lang['admin_user_edit']}">{$results['user_login']}</a>
			</td>
			<td headers="th0388AFB80001" valign="top" align="left">
			{$results['user_name']}
			</td>
			<td headers="th0388AFB80002" valign="top" align="left">
			{$results['level']}
			</td>
			<td headers="th0388AFB80003" valign="top" align="left">
			$date
			</td>
			<td headers="th0388AFB80003" valign="top" align="left">
			<a href="javascript:deluser('{$results['id']}');" title="{$lang['admin_user_del']}"><strong>x</strong></a>
			</td>
		</tr>
EOF;

	}

echo "	</tbody></table>"; bmc_Template('admin_footer'); exit;

} // End of if($entity



// =======================
// Searches the Table and returns the rows with 'Like' strings

function bmc_mysql_search($query, $field , $sql) {
	global $db, $entity, $blog;

	$querystring = "";
	$searchstring = "";

	// split on + "
	$parts = explode("+",$query);
	foreach ($parts as $section)
	{
	    if (!empty($querystring)) $querystring .= " AND ";

	    // split words 
	    $keywords = explode(" ",trim($section));
	    foreach ($keywords as $chunk)
	    {
	        if (!empty($searchstring)) $searchstring.= " OR ";
	        // this searches 'description' - you probably want to change this
	        $searchstring .= " $field LIKE '%{$chunk}%' ";
	    }
	    $querystring .= " ($searchstring) ";
	    $searchstring = '';
	}


	// Limit the search to a category in a particult blog if necessary
	if(!empty($_POST['cat'])) {
		if($_POST['cat'] == "all") {
			$cat_sql="";
		}
		else {
			$cat_sql=" AND cat='{$_POST['cat']}'";
		}
	}


	// Special search if its the 'Author'
	if($field=="author") {
		$author=$db->query("SELECT id from ".MY_PRF."users WHERE  user_login='$query'", false);
		$author=$author['id'];
		return $db->query("$sql WHERE author='$author' AND blog='".$blog."' $cat_sql");
	}

	if($entity == "users") {
		$sql = "$sql WHERE ({$querystring})";
	} else {
		$sql = "$sql WHERE ({$querystring}) AND blog='".$blog."' $cat_sql";
	}

	return $db->query($sql);
}

?>