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


//=========================== DO NOT EDIT ANYTHING! ====================
// DONOT TOUCH!

define('IN_BMC', true); // Flag which tells that you are in the boastMachine system
define('BMC_VERSION', "v3.1");

// ===========================

	@include_once dirname(__FILE__)."/inc/vars/bmc_conf.php";	// The db/path config

	// Verify installation
	if((!isset($done) || !isset($root) || !isset($bmc_path)) && !isset($install_mode)) {
		@include dirname(__FILE__)."/../config.php";
		die("boastMachine is not properly installed! Please point your browser to <a href=\"".$bmc_dir."/install.php\">install.php</a> and complete the installation!");
	}
	if(isset($install_mode) && $install_mode==true) { return; }	// Install mode, dont proceed below this

	// Load the database config file
	include_once dirname(__FILE__)."/../config.php";

	// Define the necessary global constants
	define("CFG_PARENT", $root);
	define("CFG_ROOT", $root."/".$bmc_dir);
	define("BMC_DIR", $bmc_dir);

	define("MY_PRF", $my_prefix); // Define the table prefix as a constant

	// Load the DB processor
	include_once CFG_ROOT."/inc/core/db_mysql.php";

	// Load the common functions file
	include_once CFG_ROOT."/functions.php";

	$temp_vars=bmc_getSets(); // Get the settings

		foreach($temp_vars as $dat) {
			$bmc_vars[$dat['v_name']]=$dat['v_val'];
		}

	$bmc_vars['bmc_dir']=$bmc_dir;	// The 'bmc' directory


	// $bmc_vars holds all the system settings


	// ================== User pic max dimensions (profile) ====

	$bmc_vars['user_pic_width']=200; // Maximum allowed width
	$bmc_vars['user_pic_height']=200; // Maximum allowed height
	$bmc_vars['user_pic_size']=25; // Maximum allowed size in KB s

	$enable_se_friendly=true;	// Set this to false if SE friendly urls (mod_rewrite) dont work for you

	// ==================


	// The cookie path
	define("BMC_COOKIE", preg_replace("|http://[^/]+|is","", $bmc_vars['site_url']."/" ));

	// The cookie domain
	$ck_domain=parse_url($bmc_vars['site_url']);

		if(!strpos("-".$ck_domain['host'], ".")) {
			$cookie_host=""; //$ck_domain['host'];
		} else {
			$cookie_host=".".$ck_domain['host'];
		}

	define("BMC_COOKIE_DOMAIN", $cookie_host);


	// The Language pack
	$lang_file=$bmc_vars['lang'];
		if(!$lang_file) {
			include CFG_ROOT."/inc/lang/en.php";
		} else {
			include CFG_ROOT."/inc/lang/".$lang_file;
		}


if(defined('BLOG')) {

	$blog_id=BLOG;
	$i_blog=$db->query("SELECT * FROM ".MY_PRF."blogs WHERE id='{$blog_id}' AND frozen='0'", false);

	if(!isset($i_blog['blog_file']) || !isset($i_blog['blog_name'])) {
		bmc_template('error_page', $lang['no_blog']);
	}

	define("BLOG_FILE", $i_blog['blog_file']);
	define("BLOG_NAME", $i_blog['blog_name']);

} else {

	if(!empty($_REQUEST['blog']) && is_numeric($_REQUEST['blog'])) {
		$i_blog=$db->query("SELECT * FROM ".MY_PRF."blogs WHERE id='{$_REQUEST['blog']}' AND frozen='0'", false);
		define("BLOG", $i_blog['id']);
		define("BLOG_FILE", $i_blog['blog_file']);
		define("BLOG_NAME", $i_blog['blog_name']);
	}

}


//######### Include necessary config files ###############

// Enable the magic Quotes
if (!get_magic_quotes_gpc()) {
	$_GET    = add_magic_quotes($_GET);
	$_POST   = add_magic_quotes($_POST);
	$_COOKIE = add_magic_quotes($_COOKIE);
}

if(isset($install_mode) && $install_mode==true) {
	include CFG_ROOT."/inc/lang/en.php";
}

else {

	// The theme
	if(isset($blog_id)) {

		$blog_theme=$i_blog['theme'];

		// Check whether the blog has a separate theme
			if(!empty($blog_theme)) {
				$theme=$blog_theme;
			} else {
				$theme=$bmc_vars['theme'];	// Use the default theme
			}

	} else {
		$theme=$bmc_vars['theme'];
	}

		if(empty($theme)) { $theme="default"; }
		define("CFG_THEME",$theme); // Global theme name

}


$logged_in_user=bmc_isLogged();	// The currently logged in user if any

// Check wheter a user is already logged in
if($logged_in_user) {

	// If yes, add an array with some info to the $bmc_vars
	$bmc_vars['logged_in_user']['login']=$logged_in_user;	// The username

	$logged_in_user=null;
	$logged_in_user=$db->query("SELECT id,level,user_name,user_nick,user_showid,blogs FROM ".MY_PRF."users WHERE user_login='{$bmc_vars['logged_in_user']['login']}'", false);
	$bmc_vars['logged_in_user']['id']=$logged_in_user['id'];
	$bmc_vars['logged_in_user']['level']=$logged_in_user['level'];
	$bmc_vars['logged_in_user']['blogs']=$logged_in_user['blogs'];
	$bmc_vars['logged_in_user']['name']=$logged_in_user['user_name'];
	$bmc_vars['logged_in_user']['nick']=$logged_in_user['user_nick'];
	$bmc_vars['logged_in_user']['show_id']=$logged_in_user['user_showid'];


	if($logged_in_user['level'] == "4") {
		// The universal constant for an admin
		define("IS_ADMIN", 1);
	}
}

	bmc_chkIP(); // Check whether the user belogs to a banned IP or group

	global $lang;

	//include_once CFG_ROOT."/reflog.php";

?>