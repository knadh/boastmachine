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

//******** DONOT TOUCH! *************

$install_mode=true;

//******** DONOT TOUCH! *************


$my_prefix="bmc_"; // Tables prefix

	@include dirname(__FILE__)."/main.php";
	@include dirname(__FILE__)."/inc/vars/bmc_conf.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>boastMachine installation</title>
	<style type="text/css">
	<!--
	@import url("../templates/default/bstyle.css");
		#align_center {
		text-align: center;
		width: 400px;
	}
	//-->
	</style>
</head>
<body>

<div><br /></div>

<div id="align_center">
<div class="form_fields">

<?php
if(!isset($_POST['install'])) {
?>

<h1>boastMachine installation</h1>

<?php
	// Check whether bm is already installed
	if(isset($my_db)) {
	mysql_connect($my_host, $my_user, $my_pass);
	$db=mysql_select_db($my_db);
		if($db) {
		echo "Note: ( boastMachine appears to be already installed )<br />\n";
		}
	mysql_close();
	}

?>

<form method="post" action="install.php" name="install">
<div>
<input type="hidden" name="c_url" value="" />
<input type="hidden" name="install" value="true" />
Autoset directory permissions? : <input type="checkbox" name="set_perm" value="true" /><br />
(Most likely to fail)
<br /><br />
MySQL server : <input type="text" name="db_host" value="localhost" /><br />
MySQL user : <input type="text" name="db_user" /><br />
MySQL password : <input type="password" name="db_pass" /><br />
MySQL database : <input type="text" name="db_name" /><br />
Overwrite existing tables? <input type="checkbox" name="ow" /><br /><br />

Create new database? <input type="checkbox" name="new_db" /><br />
(Only if you dont want to use an existing db)
<br /><br />

Admin username : <input type="text" name="admin_id" /><br />
Password : <input type="password" name="admin_pass" /><br />
Password #2 : <input type="password" name="admin_pass2" /><br /><br />

<input type="submit" value="Continue"><br /><br />
<div class="small_text">Warning! Overwriting the tables will destroy all existing data!</div>
</form>
</div>
<br /><br />
<a href="http://boastology.com">boastMachine <?php echo BMC_VERSION; ?></a>
</div>

<script type="text/javascript">
<!--
document.install.c_url.value=document.location;
//-->
</script>
</div>
<br /><br />
<?php
footer(); exit();
}


// Check the form

if(empty($_POST['db_name'])) {
	echo "<h1>Error!</h1>\nPlease enter your MySQL database name";
	footer(); exit;
}


if(empty($_POST['admin_id']) || strlen($_POST['admin_id']) < 3) {
	echo "<h1>Error!</h1>\nAdmin username empty or too short! (atleast 3 chars)";
	footer(); exit;
}

if(empty($_POST['admin_pass']) || strlen($_POST['admin_pass']) < 5) {
	echo "<h1>Error!</h1>\nAdmin password empty or too short! (atleast 5 chars)";
	footer(); exit;
}

if($_POST['admin_pass'] != $_POST['admin_pass2']) {
	echo "<h1>Error!</h1>\nAdmin passwords donot match!";
	footer(); exit;
}


	// Get the paths
	if(!empty($_SERVER['WINDIR'])) {
		$slash="\\";
	}
	else {
		$slash="/";
	}

		$path=explode($slash, dirname(__FILE__));
		$bmc_path=$path[count($path)-1];

		unset($path[count($path)-1]);

		$root=implode($slash, $path);
		$root=addslashes($root);

	if(empty($_POST['root'])) {
		if(!is_dir($root)) {
		?>
		<strong>Unable to determine the directory path!</strong>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		Please enter the absolute path to the boastMachine installation.<br />
		<input type="text" name="root" size="50" /><br />
		No trailing slash at the end!<br /><br />
		<input type="submit" value="Continue.." />
		<?php
			while(list($key,$value) = each($_POST)) {
				echo "<input type=\"hidden\" name=\"{$key}\" value=\"{$value}\">\n";
			}
		?>
		</form>
		<?php
		footer(); exit;
		}
	} else {
		$root=$_POST['root'];
	}



// Get the variables

$my_host=$_POST['db_host'];
$my_user=$_POST['db_user'];
$my_pass=$_POST['db_pass'];
$my_db=$_POST['db_name'];

// Tables
$tbl_posts=$my_prefix."posts";
$tbl_posts_dat=<<<EOF
CREATE TABLE $tbl_posts (
  id INT(10) NOT NULL AUTO_INCREMENT,
  title TINYTEXT NOT NULL default '',
  summary MEDIUMTEXT NOT NULL default '',
  data MEDIUMTEXT NOT NULL default '',
  author INT(10) NOT NULL default '0',
  keyws text NOT NULL default '',
  date TINYTEXT NOT NULL default '',
  draft_date TINYTEXT NOT NULL default '',
  password TINYTEXT NOT NULL default '',
  file TINYTEXT NOT NULL default '',
  format enum('text','html') NOT NULL default 'text',
  status enum('0','1', '2') NOT NULL default '1',
  cat INT(10) NOT NULL default '0',
  blog INT(10) NOT NULL default '0',
  user_ip TINYTEXT NOT NULL default '',
  accept_trackback enum('1','0') NOT NULL default '1',
  user_comment enum('1','0') NOT NULL default '1',
  user_comment_notify enum('1','0') NOT NULL default '1',
  user_vote enum('1','0') NOT NULL default '1',
  post_autobr enum('1','0') NOT NULL default '1',
  PRIMARY KEY  (id)
);
EOF;


$tbl_comments=$my_prefix."comments";
$tbl_comments_dat=<<<EOF
CREATE TABLE $tbl_comments (
  id INT(10) NOT NULL AUTO_INCREMENT,
  author INT(10) NOT NULL default '0',
  auth_name TINYTEXT NOT NULL default '',
  auth_email TINYTEXT NOT NULL default '',
  auth_url TINYTEXT NOT NULL default '',
  auth_ip TINYTEXT NOT NULL default '',
  date TINYTEXT NOT NULL default '',
  data text NOT NULL default '',
  post INT(10) NOT NULL default '0',
  parent_id INT(10) NOT NULL default '0',
  blog INT(10) NOT NULL default '0',
  PRIMARY KEY  (id)
);
EOF;


$tbl_cats=$my_prefix."cats";
$tbl_cats_dat=<<<EOF
CREATE TABLE $tbl_cats (
  id INT(10) NOT NULL AUTO_INCREMENT,
  cat_name TINYTEXT NOT NULL default '',
  cat_info TINYTEXT NOT NULL default '',
  blog int(10) NOT NULL default '0',
  PRIMARY KEY  (id)
);
EOF;

$tbl_links=$my_prefix."links";
$tbl_links_dat=<<<EOF
CREATE TABLE $tbl_links (
  id INT(10) NOT NULL AUTO_INCREMENT,
  title TINYTEXT NOT NULL default '',
  url TINYTEXT NOT NULL default '',
  description TINYTEXT NOT NULL default '',
  blog int(10) NOT NULL default '0',
  PRIMARY KEY  (id)
);
EOF;

$tbl_tracks=$my_prefix."trackbacks";
$tbl_tracks_dat=<<<EOF
CREATE TABLE $tbl_tracks (
  id INT(10) NOT NULL AUTO_INCREMENT,
  title TINYTEXT NOT NULL default '',
  url TINYTEXT NOT NULL default '',
  excerpt MEDIUMTEXT NOT NULL default '',
  blog_name TINYTEXT NOT NULL default '',
  date TINYTEXT NOT NULL default '',
  post INT(10) NOT NULL default '0',
  PRIMARY KEY  (id)
);
EOF;

$tbl_votes=$my_prefix."votes";
$tbl_votes_dat=<<<EOF
CREATE TABLE $tbl_votes (
  post INT(10) NOT NULL AUTO_INCREMENT,
  number  INT(10) NOT NULL default '0',
  total  INT(10) NOT NULL default '0',
  PRIMARY KEY  (post)
);
EOF;

$tbl_blogs=$my_prefix."blogs";
$tbl_blogs_dat=<<<EOF
CREATE TABLE $tbl_blogs (
  id INT(10) NOT NULL AUTO_INCREMENT,
  blog_name TINYTEXT NOT NULL default '',
  blog_date TINYTEXT NOT NULL default '',
  blog_info TINYTEXT NOT NULL default '',
  blog_file TINYTEXT NOT NULL default '',
  theme TINYTEXT NOT NULL default '',
  theme_name TINYTEXT NOT NULL default '',
  frozen enum('1','0') NOT NULL default '0',
  user_registrations enum('1','0') NOT NULL default '1',
  rss_feed enum('1','0') NOT NULL default '1',
  PRIMARY KEY  (id)
);
EOF;

$tbl_vars=$my_prefix."vars";
$tbl_vars_dat=<<<EOF
CREATE TABLE $tbl_vars (
  v_name VARCHAR(255) NOT NULL default '',
  v_val VARCHAR(255) NOT NULL default '',
  PRIMARY KEY  (v_name)
);
EOF;

$tbl_users=$my_prefix."users";
$tbl_users_dat=<<<EOF
CREATE TABLE $tbl_users (
  id INT(10) NOT NULL AUTO_INCREMENT,
  user_login TINYTEXT NOT NULL default '',
  user_pass TINYTEXT NOT NULL default '',
  user_name TINYTEXT NOT NULL default '',
  user_nick TINYTEXT NOT NULL default '',
  user_email TINYTEXT NOT NULL default '',
  user_url TINYTEXT NOT NULL default '',
  user_location TINYTEXT NOT NULL default '',
  user_birth TINYTEXT NOT NULL default '',
  user_yim TINYTEXT NOT NULL default '',
  user_msn TINYTEXT NOT NULL default '',
  user_icq TINYTEXT NOT NULL default '',
  user_profile MEDIUMTEXT NOT NULL default '',
  last_login TINYTEXT NOT NULL default '',
  user_pic TINYTEXT NOT NULL default '',
  user_showid enum('user_login','user_name', 'user_nick') NOT NULL default 'user_name',
  user_get_email enum('1','0') NOT NULL default '1',
  user_show_email enum('1','0') NOT NULL default '1',
  user_show_pic enum('1','0') NOT NULL default '1',
  public_profile enum('1','0') NOT NULL default '1',
  date TINYTEXT NOT NULL default '',
  level INT(10) NOT NULL default '2',
  blogs MEDIUMTEXT NOT NULL default '',
  PRIMARY KEY  (id)
);
EOF;

$tbl_users_online=$my_prefix."users_online";
$tbl_users_online_dat=<<<EOF
CREATE TABLE $tbl_users_online (
  time_stamp INT(10) NOT NULL DEFAULT '0',
  ip varchar(40) NOT NULL,
  user varchar(40) NOT NULL
);
EOF;

echo "Getting site url...  ";

// Get the site path
$c_url=explode("/",$_POST['c_url']);

unset($c_url[count($c_url)-1]); // Get lost of the name 'install.php'
unset($c_url[count($c_url)-1]); // Ditch the 'bmc' directory name
$c_url=implode("/",$c_url);
echo "Done <br /><br />";


	if(isset($_POST['set_perm'])) {
		echo "Trying to set directory/file permisions...  ";

		echo "{$root}  ".@chmod($root, 0777) or (" <strong>Failed..</strong><br />");
		echo "./backup ..  ".@chmod($root."/".$bmc_path."/backup", 0777) or (" <strong>Failed..</strong><br />");
		echo "./files .. ".@chmod($root."/".$bmc_path."/files", 0777) or (" <strong>Failed..</strong><br />");
		echo "./inc/vars .. ".@chmod($root."/".$bmc_path."/inc/vars", 0777) or (" <strong>Failed..</strong><br />");
		echo "./inc/vars/cache .. ".@chmod($root."/".$bmc_path."/inc/vars/cache", 0777) or (" <strong>Failed..</strong><br />");
		echo "./inc/lang .. ".@chmod($root."/".$bmc_path."/inc/lang", 0777) or (" <strong>Failed..</strong><br />");

		echo "Done <br /><br />";
	}


	// If its not a Win system, check for valid directory permissions
	if(empty($_SERVER['WINDIR'])) {

$perm=fileperms($root);
if($perm != '16895') {
	footer("Directory permission not 777 ! - {$root}");
}

$perm=fileperms($root."/backup");
if($perm != '16895') {
	footer("Directory permission not 777 ! - ./backup");
}

$perm=fileperms($root."/files");
if($perm != '16895') {
	footer("Directory permission not 777 ! - ./files");
}

$perm=fileperms($root."/rss");
if($perm != '16895') {
	footer("Directory permission not 777 ! - ./rss");
}

$perm=fileperms($root."/".$bmc_path."/inc/vars");
if($perm != '16895') {
	footer("Directory permission not 777 ! - ".dirname(__FILE__)."/inc/vars");
}

$perm=fileperms($root."/".$bmc_path."/inc/vars/cache");
if($perm != '16895') {
	footer("Directory permission not 777 ! - ".dirname(__FILE__)."/inc/vars/cache");
}

$perm=fileperms($root."/".$bmc_path."/inc/lang");
if($perm != '16895') {
	footer("Directory permission not 777 ! - ".dirname(__FILE__)."/inc/lang");
}

	}

	echo "Connecting to mysql...  ";
	@mysql_connect($my_host, $my_user, $my_pass) or footer(mysql_error());
	echo "Done <br />";


	// Create a new database if needed
	if(isset($_POST['new_db'])) {
		echo "Creating new database...";
		@mysql_query("CREATE DATABASE {$_POST['db_name']}") or footer(mysql_error());
		echo "Done <br />";
	}


	echo "Selecting database...  ";
	@mysql_select_db($_POST['db_name']) or footer(mysql_error()); 
	echo "Done <br /><br />";

	// Delete the tables if Overwriting is set
	if (isset($_POST['ow']))
	{
		echo "Dropping existing tables...  ";

		@mysql_query("DROP TABLE IF EXISTS `$tbl_posts`") or 	footer(mysql_error());
		@mysql_query("DROP TABLE IF EXISTS `$tbl_comments`") or footer(mysql_error());
		@mysql_query("DROP TABLE IF EXISTS `$tbl_cats`") or footer(mysql_error());
		@mysql_query("DROP TABLE IF EXISTS `$tbl_vars`") or footer(mysql_error());
		@mysql_query("DROP TABLE IF EXISTS `$tbl_users`") or footer(mysql_error());
		@mysql_query("DROP TABLE IF EXISTS `$tbl_blogs`") or footer(mysql_error());
		@mysql_query("DROP TABLE IF EXISTS `$tbl_votes`") or footer(mysql_error());
		@mysql_query("DROP TABLE IF EXISTS `$tbl_tracks`") or footer(mysql_error());
		@mysql_query("DROP TABLE IF EXISTS `$tbl_users_online`") or footer(mysql_error());
		@mysql_query("DROP TABLE IF EXISTS `$tbl_links`") or footer(mysql_error());

		echo "Done <br /><br />";
	}


// Create the tables

echo "Creating '{$tbl_posts}' ..  "; @mysql_query($tbl_posts_dat) or footer(mysql_error()); echo "Done <br />";
echo "Creating '{$tbl_comments}' ..  "; @mysql_query($tbl_comments_dat) or  footer(mysql_error()); echo "Done <br />";
echo "Creating '{$tbl_vars}' ..  "; @mysql_query($tbl_vars_dat) or  footer(mysql_error()); echo "Done <br />";
echo "Creating '{$tbl_cats}' ..  "; @mysql_query($tbl_cats_dat) or  footer(mysql_error()); echo "Done <br />";
echo "Creating '{$tbl_votes}' ..  "; @mysql_query($tbl_votes_dat) or  footer(mysql_error()); echo "Done <br />";
echo "Creating '{$tbl_users}' ..  "; @mysql_query($tbl_users_dat) or  footer(mysql_error()); echo "Done <br />";
echo "Creating '{$tbl_tracks}' ..  "; @mysql_query($tbl_tracks_dat) or  footer(mysql_error()); echo "Done <br />";
echo "Creating '{$tbl_blogs}' ..  "; @mysql_query($tbl_blogs_dat) or  footer(mysql_error()); echo "Done <br />";
echo "Creating '{$tbl_users_online}' ..  "; @mysql_query($tbl_users_online_dat) or  footer(mysql_error()); echo "Done <br />";
echo "Creating '{$tbl_links}' ..  "; @mysql_query($tbl_links_dat) or  footer(mysql_error()); echo "Done <br /><br />";

// Enter the initial data
$post_time=time();
echo "Inserting data into '{$tbl_posts}'...  "; @mysql_query("INSERT INTO $tbl_posts (title,cat,author,date,summary,format,blog,user_ip) VALUES('My first post!','1','1','{$post_time}','This is your first post on the world\'s best blogging platform ! From robust content management to advanced spam fighting features, boastMachine is loaded with all that, and maybe, more than that you\'ll ever need to run a blog. boastMachine stable core makes it work at lightning speeds and boastMachine uses the web\'s most powerful database system, MYSQL !\n\nboastMachine supports Smilie Packs :D and a powerful [b]bbCode[/b] engine makes text formatting easy! Thats about posting in text mode, and now , if you ever need to make html posts, boastMachine gives you a feature rich WYSIWYG editor !\nGood Luck and happy Blogging !','text','1','{$_SERVER['REMOTE_ADDR']}')") or footer(mysql_error()); echo "Done <br />";
echo "Inserting data into '{$tbl_users}'...  "; @mysql_query("INSERT INTO $tbl_users (level,user_login,user_pass,user_name,date,blogs) VALUES('4','{$_POST['admin_id']}','".md5($_POST['admin_pass'])."','Administrator','".time()."','a:1:{i:0;s:1:\"1\";}')") or footer(mysql_error()); echo "Done <br />";

$gmtime=gmmktime(0,0,0,date("m"),date("h"),date("Y")); // GMT time
$mytime=mktime(0,0,0,date("m"),date("h"),date("Y")); // Server's local time
$time_difference=($gmtime-$mytime)/3600; // The time difference between GMT and the server time


echo "Inserting data into '{$tbl_vars}'...  ";
@mysql_query("INSERT INTO $tbl_vars (v_name, v_val) 
VALUES ('words', ''),
('ips', ''),
('theme_name', 'Default'),
('theme', 'default'),
('lang','en.php'),
('site_email','admin@yoursite.com'),
('site_url','$c_url'),
('site_title','My weblog'),
('site_desc','Blog powered by boastMachine'),
('date_str','F j, Y, g:i a'),
('send_ping','0'),
('trackbacks','1'),
('ping_urls','http://boastology.com/ping/\nhttp://rpc.weblogs.com/RPC2'),
('p_page','25'),
('p_total','100'),
('archive','1'),
('title_wrap','30'),
('summary_wrap','75'),
('user_comment','1'),
('user_comment_threading','1'),
('user_comment_guests','1'),
('user_comment_session','0'),
('user_comment_notify','1'),
('image_verify','1'),
('user_vote','1'),
('user_send_post','1'),
('user_search','1'),
('rss_feed','1'),
('auto_convert_link','1'),
('user_registration','1'),
('user_new_welcome','1'),
('user_new_notify','0'),
('user_default_level','2'),
('post_html','1'),
('user_files','1'),
('post_send_subject','([NAME]) has asked you read this article!'),
('auto_purge','0'),
('spam_words',''),
('ban_spammer','0'),
('time_zone',''),
('gmt_diff','{$time_difference}')
") or footer(mysql_error()); echo "Done <br />";

echo "Inserting data into '{$tbl_links}'...  ";
@mysql_query("INSERT INTO $tbl_links (title,description,url,blog) 
VALUES ('boastMachine', 'boastMachine, powering the best blogs','http://boastology.com','0'),
 ('NewzPile', 'NewzPile :: Tech News 24/7 - News from hundreds of sources','http://newzpile.com','0'),
 ('Kailash Nadh', 'Kailash Nadh :: The creator of boastMachine','http://kailashnadh.name','0'),
 ('BN Soft', 'Quality web services','http://bnsoft.net','0')
") or footer(mysql_error()); echo "Done <br />";

echo "Inserting data into '{$tbl_cats}'...  "; @mysql_query("INSERT INTO $tbl_cats (cat_name,cat_info,blog) VALUES('General','This is where I post off the topic posts','1')") or footer(mysql_error()); echo "Done <br />";
echo "Inserting data into '{$tbl_blogs}'...  "; @mysql_query("INSERT INTO $tbl_blogs (blog_name,blog_date,blog_info,blog_file) VALUES('My first blog','".time()."', 'This is my weblog #1 and its powered by boastMachine!', 'index.php')") or footer(mysql_error()); echo "Done <br /><br />";

mysql_close();


// The config file
$conf_dat=<<<EOF
<?php

\$done=true;

\$root="$root";

\$bmc_path="$bmc_path";

\$my_host="{$_POST['db_host']}";
	// Your MYSQL server

\$my_user="{$_POST['db_user']}";
	// Your MySQL username

\$my_pass="{$_POST['db_pass']}";
	// Your MySQL password

\$my_db="{$_POST['db_name']}";
	// Your MySQL database name

\$my_prefix="$my_prefix";
	// MySQL tables prefix
?>
EOF;

 echo "Writing data to config file...  ";
// Write the conf file to save the installation info

$w=@fopen(dirname(__FILE__)."/inc/vars/bmc_conf.php","w+") or  footer("Cannot write to ./inc/vars/cachebmc_conf.php ! Please check the directory permission");
	if($w) {
		fputs($w,$conf_dat);
		fclose($w);
	}


echo "Creating cache files..";

	// Blog cache
	$blog_dat[0]['blog_name'] = "My first blog";
	$blog_dat[0]['blog_file'] = "index.php";
	$blog_dat[0]['id'] = "1";
	$w=@fopen(dirname(__FILE__)."/inc/vars/cache/blogs.dat","w+");
		if($w) {
			fputs($w,serialize($blog_dat));
			fclose($w);
		}

	// Category cache
	$cat_dat[0]['cat_name'] = "General";
	$cat_dat[0]['id'] = "1";
	$cat_dat[0]['blog'] = "1";
	$w=@fopen(dirname(__FILE__)."/inc/vars/cache/cats.dat","w+");
		if($w) {
			fputs($w,serialize($cat_dat));
			fclose($w);
		}


	// Archive cache
	$archive_dat[1]['0'] = "{$post_time}";
	$w=@fopen(dirname(__FILE__)."/inc/vars/cache/archive.dat","w+");
		if($w) {
			fputs($w,serialize($archive_dat));
			fclose($w);
		}


echo "Done <br />";

?>

<h1>Congratulations!</h1>
Congratulations! boastMachine was installed successfully on your webserver!
You can now login to your admin panel at <a href="<?php echo $c_url; ?>/bmc/admin.php"><?php echo $c_url; ?>/bmc/admin.php</a> and login with<br />
Username : <strong><?php echo $_POST['admin_id']; ?></strong> and Password : <strong><?php echo $_POST['admin_pass']; ?></strong><br /><br />
If you have any doubts or queries, you can visit the boastMachine website<br />
Good Luck!

<?php

	footer();

	function footer($fail=null) {
	if($fail) echo "<strong>Failed!</strong> <br /><br /><strong>Error message :</strong> $fail";
	?>
</div></div>
</body></html>
	<?php
		exit;
	}
?>