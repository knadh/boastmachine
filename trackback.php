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


	include_once dirname(__FILE__)."/config.php";
	include_once dirname(__FILE__)."/$bmc_dir/main.php";


	//================== SEND TRACKBACKS =======================

	// This code works when the flag $send_track_back is set to true
	// Only used while posting a new article



	if(isset($send_track_back)) {
		$urls=$_POST['track_urls'];
		$urls=explode("\n", $urls); // The url list to be trackbacked

		$title=urlencode($_POST['title']);

		$excerpt=urlencode(substr($_POST['smr'],0,255)); // Cut the characters at 255

		$blog_name=urlencode($i_blog['blog_name']);
		$post_id=$db->query("SELECT last_insert_id() FROM ".MY_PRF."posts", false); // Id of the last post

		$url=$bmc_vars['site_url']."/".BLOG_FILE."?id=".$post_id['last_insert_id()']; // The perma link for this post
		$url=urlencode($url);
		// Send pings!
		for($n=0;$n<count($urls);$n++) {

		// Get the host url, parse it and take out the domain and the path
		$the_host=$urls[$n];
		$the_host=str_replace("http://","",$the_host);
		$the_host=str_replace("https://","",$the_host);
		$the_host=explode("/",$the_host);

		$host=trim($the_host[0]); // The host domain name
		unset($the_host[0]);
		$path="/".trim(implode("/",$the_host)); // The path

			if(!empty($path) && !empty($host)) {
				$query = "title=$title&url=$url&excerpt=$excerpt&blog_name=$blog_name";
				$fp = fsockopen("$host", 80, $errnum, $errstr, 30); // Send the ping
				if($fp) { 
					fputs($fp, "POST {$path} HTTP/1.1\r\n"); 
					fputs($fp, "Host: {$host}\r\n"); 
					fputs($fp, "Content-type: application/x-www-form-urlencoded; charset=\"{$lang['ENCODING']}\"\r\n"); 
					fputs($fp, "User-Agent: boastMachine ".BMC_VERSION."\r\n");
					fputs($fp, "Content-length: ".strlen($query)."\r\n"); 
					fputs($fp, "Connection: close\r\n\r\n"); 
					fputs($fp, $query."\r\n\r\n");
					fclose($fp);
				}
			}
		}

	return 1;
	}

	//================== ACCEPTING TRACKBACKS =======================


	$vars=explode("/", $_SERVER['REQUEST_URI']); // The URI (path)
	$post_id=$vars[count($vars)-1];
	$blog_id=$vars[count($vars)-2];

	// Get the blog/post ids from the trackback uri

	if(!$bmc_vars['trackbacks']) {
		bmc_trackback_respond(1,"Trackbacks disabled");
	}


	$track_blog=$db->query("SELECT id FROM ".MY_PRF."blogs WHERE frozen='0' AND id='{$blog_id}'", false);
	$track_post=$db->query("SELECT id FROM ".MY_PRF."posts WHERE accept_trackback='1' AND status ='1' AND blog='{$track_blog['id']}' AND id='{$post_id}'", false);

	if(!isset($track_post['id'])) {
		bmc_trackback_respond(1,"Invalid target post");
	}

	// Empty parameters
	if(!$post_id || !is_numeric($post_id) || !$blog_id || !is_numeric($blog_id)) {
		bmc_trackback_respond(1,"Missing post info");
	}

	// Get the posted variables
	if(!empty($_REQUEST)) {
		if(empty($_REQUEST['title']) || empty($_REQUEST['url'])) {
			bmc_trackback_respond(1,"Missing required fields");
		}
	} else {
		bmc_trackback_respond(1,"Missing required fields");
	}

	// Get the source charset
	preg_match_all ("/\"(.*?)\"/", $_SERVER['CONTENT_TYPE'], $charset);
	$charset=ereg_replace("[^[:space:]a-zA-Z0-9\-]", "", $charset[1][0]);

	$title=substr(mb_convert_encoding($_REQUEST['title'],$lang['ENCODING'],$charset),0,75); // Cut the characters at 75
	$url=mb_convert_encoding($_REQUEST['url'],$lang['ENCODING'],$charset);


// The excerpt ( summary )
if(!empty($_REQUEST['excerpt'])) {
	$excerpt=substr(mb_convert_encoding($_REQUEST['excerpt'],$lang['ENCODING'],$charset),0,400); // Cut the characters at 400
} else {
	$excerpt=$title;
}

	// Check for spam (3.1)
	bmc_filterSpam($title);
	bmc_filterSpam($excerpt);


// The blog name
if(!empty($_REQUEST['blog_name'])) {
	$blog_name=substr(mb_convert_encoding($_REQUEST['blog_name'],$lang['ENCODING'],$charset),0,75);
} else {
	$blog_name="";
}

	// Save the data
	$db->query("INSERT INTO ".MY_PRF."trackbacks (title,url,excerpt,blog_name,date,post) VALUES('{$title}','{$url}','{$excerpt}','{$blog_name}','".time()."','{$track_post['id']}')");

	// Send the response
	bmc_trackback_respond(1, "Ping accepted");

	// Trackback reception ends here!
	//====================================================




//============ Function to send response in XML format

function bmc_trackback_respond($error=0,$text="") {
global $lang;

$fp=fopen(CFG_ROOT."/inc/vars/track.log", "w+");
fputs($fp, serialize($_REQUEST));
fclose($fp);


	header("Content-type: text/xml\n\n"); // XML header
echo <<<EOF
	<?xml version="1.0" encoding="{$lang['ENCODING']}"?>
	<response>
	<error>$error</error>
	<message>$text</message>
	</response>
EOF;
exit;
}

?>