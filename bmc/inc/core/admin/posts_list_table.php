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
<input type="hidden" name="blog" value="{$blog}" />
<div id="posts_list_table">
<table width="100%" border="0" style="float:right" cellpadding="3" cellspacing="0" summary="User list">
	<thead>
		<tr>
			<th id="th0388AFB80000" valign="top" align="left" width="50%">
			<a href="?action=list_posts&amp;blog=$blog&amp;sort=title&amp;page=$pg">{$lang['admin_post_title']}</a>
			</th>
			<th id="th0388AFB80002" valign="top" align="left" width="14%">
			<a href="?action=list_posts&amp;blog=$blog&amp;sort=author&amp;page=$pg">{$lang['admin_post_author']}</a>
			</th>
			<th id="th0388AFB80001" valign="top" align="left" width="14%">
			<a href="?action=list_posts&amp;blog=$blog&amp;sort=date&amp;page=$pg">{$lang['admin_post_date']}</a>
			</th>
			<th id="th0388AFB80003" valign="top" align="left">
			<a href="?action=list_posts&amp;blog=$blog&amp;sort=date&amp;page=$pg">{$lang['admin_post_act']}</a>
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

			$author=$db->query("SELECT user_login FROM ".MY_PRF."users WHERE id='{$result['author']}'", false);
			$author=$author['user_login'];

		$result['title']=wordwrap($result['title'], 25, "\n", 1);

		// Get the number of comments
		$cmt_count=$db->row_count("SELECT id FROM ".MY_PRF."comments WHERE post='{$result['id']}'");

		// Is this a hidden post or a draft? If yes, assign 'hidden' or greyed colors
		if($result['status']!="1") {
			$hidden="bgcolor=\"#FFF5F5\" ";
			$hidden_chk="checked";
		} else {
			$hidden="";
			$hidden_chk="";
		}

		$blog_file=BLOG_FILE;

echo <<<EOF
		<tr>
			<td {$hidden}headers="th0388AFB80000" valign="top" align="left" width="50%">
			<a href="{$bmc_vars['site_url']}/$blog_file?id={$result['id']}" title="{$lang['admin_post_view']}">{$result['title']}</a>
			</td>
			<td {$hidden}headers="th0388AFB80002" valign="top" align="left" width="14%">
			<a href="?action=edit_user&amp;user={$result['author']}" title="{$lang['admin_user_edit']}">$author</a>
			</td>
			<td {$hidden}headers="th0388AFB80001" valign="top" align="left" width="14%">
			$date
			</td>
			<td {$hidden}headers="th0388AFB80003" valign="top" align="left">
			<a href="?action=edit_post&amp;blog=$blog&amp;id={$result['id']}" title="{$lang['admin_edit_post']}">{$lang['admin_post_edit']}</a> / <a href="?action=edit_comments&amp;blog=$blog&amp;id={$result['id']}">{$lang['admin_post_cmt']}&nbsp;($cmt_count)</a>
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

<div>
<script>
<!--
function chkAll(id) {
		for (var i=0;i<document.list_posts.elements.length;i++)
		{
			var e=document.list_posts.elements[i];
			if ((e.name == id+"[]") && (e.type=='checkbox')) { 
				if(e.checked == true) { e.checked=false; } else { e.checked=true; }
			}
		}
}

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
</div>