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
<div id="post_list">
<form name="list_posts" method="post" action="{$_SERVER['PHP_SELF']}">
<input type="hidden" name="action" value="delete_posts" />
<div id="posts_list_table">
<table width="99%" border="0" cellpadding="0" cellspacing="0" summary="User list">
	<thead>
		<tr>
			<th id="th0388AFB80000" valign="top" align="left" width="25%">
			<a href="?action=list_posts&amp;sort=title">{$lang['admin_post_title']}</a>
			</th>
			<th id="th0388AFB80002" valign="top" align="left" width="18%">
			<a href="?action=list_posts&amp;sort=blog">{$lang['blog']}</a>
			</th>
			<th id="th0388AFB80001" valign="top" align="left" width="15%">
			<a href="?action=list_posts&amp;sort=date">{$lang['admin_post_date']}</a>
			</th>
			<th id="th0388AFB80003" valign="top" align="left">
			{$lang['admin_post_act']}
			</th>
			<th id="th0388AFB80004" valign="top" align="left">
			<a href="javascript:chkAll('chk_delete');" title="{$lang['admin_del_chk']}">X</a>
			</th>
		</tr>
	</thead>
	<tbody>
EOF;

	$n=1;
	// Print the list
	foreach($data as $result) { 

		$date=bmc_Date($result['date'], "d.m.Y");

		$result['title']=wordwrap($result['title'], 25, "\n", 1);

		// Get the number of comments
		$cmt_count=$db->row_count("SELECT id FROM ".MY_PRF."comments WHERE post='{$result['id']}'");

		// Is this a hidden post or a draft? If yes, assign 'hidden' or greyed colors
		if($result['status']!="1") {
			$hidden="bgcolor=\"#DCEBDC\" ";
			$hidden_chk="checked";
		} else {
			$hidden="";
			$hidden_chk="";
		}

		$i_blog=$db->query("SELECT blog_file,blog_name FROM ".MY_PRF."blogs WHERE id='{$result['blog']}'", false);

echo <<<EOF
		<tr>
			<td {$hidden}headers="th0388AFB80000" valign="top" align="left" width="25%">
			<a href="{$bmc_vars['site_url']}/{$i_blog['blog_file']}/?id={$result['id']}" title="{$lang['admin_post_view']}">{$result['title']}</a>
			</td>
			<td {$hidden}headers="th0388AFB80002" valign="top" align="left" width="18%">
			<a href="{$bmc_vars['site_url']}/{$i_blog['blog_file']}">{$i_blog['blog_name']}</a>
			</td>
			<td {$hidden}headers="th0388AFB80001" valign="top" align="left" width="15%">
			$date
			</td>
			<td {$hidden}headers="th0388AFB80003" valign="top" align="left">
			<a href="?action=edit_post&amp;blog={$result['blog']}&amp;id={$result['id']}" title="{$lang['admin_edit_post']}">{$lang['admin_post_edit']}</a> / <a href="?action=edit_comments&amp;blog={$result['blog']}&amp;id={$result['id']}">{$lang['admin_post_cmt']}&nbsp;($cmt_count)</a>
			</td>
			<td {$hidden}headers="th0388AFB80004" valign="top" align="left">
			<input type="checkbox" value="{$result['id']}" name="chk_delete[]" />
			</td>
		</tr>
EOF;

	$result=null;

	}
?>
	</tbody>
</table>
</div>
<br /><br />
<input type="button" onClick="javascript:chPosts('delete');" value="<?php echo $lang['admin_del_sel_but']; ?>" />&nbsp;&nbsp;
</form>
</div>

<script>
<!--

// Check all chekboxes
function chkAll(id) {
		for (var i=0;i<document.list_posts.elements.length;i++)
		{
			var e=document.list_posts.elements[i];
			if ((e.name == id+"[]") && (e.type=='checkbox')) { 
				if(e.checked == true) { e.checked=false; } else { e.checked=true; }
			}
		}
}

// Confirmation before post deletion
function chPosts(chk) {
var chk;

	// Delete
	if(chk=='delete') {
		var posts=confirm("<?php echo $lang['admin_del_post_msg']; ?>");

		if(!posts) {
			return false;
		} else {
			document.list_posts.submit();
			return false;
		}
	}

}

//-->
</script>
<br />