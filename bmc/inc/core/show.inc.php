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


// ====================
// Show blog list/user list/post list/archive list/category list.. :)

function bmc_show_list($what, $start_tag="", $end_tag="", $blog=null, $num_posts=10) {
global $db, $lang, $bmc_vars;

	if(!isset($what)) {
		return false;
	}


	if($blog && $what != "blog") {
		// Get the blog details from the DB
		$i_blog=$db->query("SELECT * FROM ".MY_PRF."blogs WHERE id='$blog' AND frozen='0'", false);

		if(!$i_blog['blog_name']) {
			echo $lang['no_data']; return false;
		}
	}


	switch($what) {

		// Print the category list
		case 'cats':


			if(!$i_blog['id']) {
				echo $lang['no_data']; return false;
			}

			if(!file_exists(CFG_ROOT."/inc/vars/cache/cats.dat")) {
				clearstatcache();
				echo "N/A";
				return false;
			}

			$data=fread(fopen(CFG_ROOT."/inc/vars/cache/cats.dat", "r"), filesize(CFG_ROOT."/inc/vars/cache/cats.dat"));
			$data=unserialize($data); // Convert the serialized data to php code form

				foreach($data as $cat) {
					if($i_blog['id'] == $cat['blog']) {
						echo "$start_tag<a href=\"{$bmc_vars['site_url']}/".bmc_SE_friendly_url('cat',$i_blog['blog_file'],$cat['id'],$cat['cat_name'])."\" title=\"".bmc_htmlentities($cat['cat_name'])."\">".bmc_htmlentities($cat['cat_name'])."</a>$end_tag\n";
					}
				}
		break;



		// Print the blog list
		case 'blogs':

			if(!file_exists(CFG_ROOT."/inc/vars/cache/blogs.dat")) {
				clearstatcache();
				echo "N/A";
				return false;
			}

			$data=fread(fopen(CFG_ROOT."/inc/vars/cache/blogs.dat", "r"),  filesize(CFG_ROOT."/inc/vars/cache/blogs.dat"));
			$data=unserialize($data); // Convert the serialized data to php code form

				foreach($data as $blog_data) {
					echo "$start_tag<a href=\"{$bmc_vars['site_url']}/{$blog_data['blog_file']}\" title=\"".bmc_htmlentities($blog_data['blog_name'])."\">".bmc_htmlentities($blog_data['blog_name'])."</a>$end_tag\n";
				}
			return true;
		break;


		// Print the archive list
		case 'archive':

			if(!$i_blog['id']) {
				echo $lang['no_data']; return false;
			}

			if(!$bmc_vars['archive']) {
				return false;
			}

			if(!file_exists(CFG_ROOT."/inc/vars/cache/archive.dat")) {
				clearstatcache();
				echo "N/A";
				return false;
			}

			$data=fread(fopen(CFG_ROOT."/inc/vars/cache/archive.dat", "r"),  filesize(CFG_ROOT."/inc/vars/cache/archive.dat"));
			$data=unserialize($data); // Convert the serialized data to php code form


			if(isset($data[$blog]) && $data[$blog]) {

				foreach($data[$blog] as $arch) {
					$link="0,".bmc_Date($arch, "m,Y");
					$disp=bmc_Date($arch, "F Y");
					echo "$start_tag<a href=\"{$bmc_vars['site_url']}/".bmc_SE_friendly_url('archive',$i_blog['blog_file'],$link)."\" title=\"".bmc_htmlentities($disp)."\">".bmc_htmlentities($disp)."</a>$end_tag\n";
				}


			} else {
				return false;
			}
				return true;
		break;


		// Print the user list
		case 'users':

			$users=$db->query("SELECT user_login,user_name,id,date FROM ".MY_PRF."users ORDER BY date DESC");

			foreach($users as $user) {
				$date=bmc_Date($users['date']);
				include CFG_PARENT."/templates/".CFG_THEME."/user_list.php";
			}

		break;


		// Print the post list
		case 'posts':

			if(!$i_blog['id']) {
				echo $lang['no_data']; return false;
			}

			$posts=$db->query("SELECT id,title FROM ".MY_PRF."posts WHERE status='1' AND blog='{$i_blog['id']}' ORDER BY date DESC LIMIT 0,$num_posts");

			foreach($posts as $post) {
				echo "$start_tag<a href=\"{$bmc_vars['site_url']}/".bmc_SE_friendly_url('post',$i_blog['blog_file'],$post['id'],$post['title'])."\" title=\"".bmc_htmlentities($post['title'])."\">".nl2br(bmc_wordWrap(bmc_htmlentities($post['title']),25))."</a>$end_tag\n";
			}

		break;

		// Print the comments list
		case 'comments':

			if(!$i_blog['id']) {
				echo $lang['no_data']; return false;
			}

			$comments=$db->query("SELECT id,author,auth_name,date,post,data FROM ".MY_PRF."comments ORDER BY date DESC LIMIT 0,$num_posts");

			foreach($comments as $cmt) {

				// A registered user made the comment, get his info
				if(isset($cmt['author'])) {
					$auth_name=bmc_dispUser(bmc_htmlEntities($cmt['author']));
				} else {
					$auth_name=bmc_htmlEntities($cmt($cmt['auth_name']));
				}

				// Get the posts's info
				$post=$db->query("SELECT id,title FROM ".MY_PRF."posts WHERE id='{$cmt['post']}' AND blog='{$i_blog['id']}'", false);

				echo "$start_tag<a href=\"{$bmc_vars['site_url']}/".bmc_SE_friendly_url('post',$i_blog['blog_file'],$post['id'],$post['title'])."#cmt-{$cmt['id']}\" title=\"".bmc_htmlentities($post['title'])."\">".nl2br(bmc_htmlentities($post['title']))."</a><br />\n";
				echo nl2br(bmc_wordWrap(bmc_htmlentities($cmt['data']),50))."<br /><strong>{$lang['str_by']}</strong> {$auth_name} {$lang['str_on']} ".bmc_Date($cmt['date'])."\n$end_tag\n\n";
			}

		break;


	}

}



?>