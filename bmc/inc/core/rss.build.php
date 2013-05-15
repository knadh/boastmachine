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


// =======================
// General configuration

$rss_date="r"; 
	// RFC 822 compliant date formatting string

$rss_trunc=2000;
	// The maximum number of characters of the summary of the post to be published

$rss_total=10;
	// Total number of posts to be published

$rss_encoding="utf-8";
	// You might want to change this to the right encoding for your special characters



// =======================
// Get the feed type requested for
// 1 = RSS .90 , 2 = RSS 2.0 , 3 = Atom .03


// Check whether RSS feeds are enabled glboally, and for this blog

if(!$bmc_vars['rss_feed']) {
	return;
}

if(defined('BLOG') && BLOG) {
	$blog=BLOG;
}

	// ========== Do some basic preparations, common for all feeds

	$now=bmc_Date(0,$rss_date); // Present time
	$now_iso=bmc_iso8601_date(time());

	// Get the posts data from the DB

	if(!isset($i_blog)) {
		$this_blog=$db->query("SELECT * FROM ".MY_PRF."blogs WHERE id='{$blog}' AND frozen='0'",false);
	} else {
		$this_blog=$i_blog;
	}

	if(!$this_blog['rss_feed']) {
		return;	// Rss feed is disabled for this blog
	}

	$result=$db->query("SELECT id,title,summary,author,date,cat,format FROM ".MY_PRF."posts WHERE blog='{$this_blog['id']}' AND status='1' ORDER BY date DESC LIMIT 0,$rss_total");

	$blog_url=$bmc_vars['site_url']."/".$this_blog['blog_file'];
	$this_blog['blog_name']=bmc_htmlEntities($this_blog['blog_name']);
	$this_blog['blog_info']=bmc_htmlEntities($this_blog['blog_info']);

	$rss_1="";
	$rss_2="";
	$atom="";

	include_once CFG_ROOT."/inc/users/bbcode.php";

//============== CREATE THE RSS .92 AND RSS 2 FEED FOR ==============

$file_name=str_replace(".php","",$this_blog['blog_file']);

$fp_1=fopen(CFG_PARENT."/rss/".$file_name."_rss1.xml", "w+") or bmc_Template($lang['post_rss_no']);
$fp_2=fopen(CFG_PARENT."/rss/".$file_name."_rss2.xml", "w+") or bmc_Template($lang['post_rss_no']);
$fp_3=fopen(CFG_PARENT."/rss/".$file_name."_atom.xml", "w+") or bmc_Template($lang['post_rss_no']);

$ver=BMC_VERSION;	// bM's version

$rss_1=<<<EOF
<?xml version="1.0" encoding="{$rss_encoding}"?>
<?xml-stylesheet href="{$bmc_vars['site_url']}/rss/rss20.xsl" type="text/xsl"?>


<!-- generator="boastMachine $ver , http://boastology.com" -->
<rss version=".92">
 <channel>
	<title>{$this_blog['blog_name']}</title>
	<link>$blog_url</link>
	<description>{$this_blog['blog_info']}</description>
	<language>en</language>
	<docs>http://backend.userland.com/rss092</docs>

EOF;
fputs($fp_1, $rss_1);



$rss_2=<<<EOF
<?xml version="1.0" encoding="{$rss_encoding}"?>
<?xml-stylesheet href="{$bmc_vars['site_url']}/rss/rss20.xsl" type="text/xsl"?>

<!-- generator="boastMachine $ver" -->
<rss version="2.0">
 <channel>
	<title>{$this_blog['blog_name']}</title>
	<link>$blog_url</link>
	<description>{$this_blog['blog_info']}</description>
	<language>en</language>
	<docs>http://backend.userland.com/rss092</docs>
	<pubDate>$now</pubDate>
	<managingEditor>{$bmc_vars['site_email']}</managingEditor>
	<webMaster>{$bmc_vars['site_email']}</webMaster>

EOF;
fputs($fp_2, $rss_2);

// The unique ID for this blog
$atom_id_domain=explode("/",str_replace("http://","",$bmc_vars['site_url']));
$atom_id="tag:".$atom_id_domain[0].",".bmc_Date(0,"Y-m-d").":/archives/".$this_blog['id'];

$atom=<<<EOF
<?xml version="1.0" encoding="{$rss_encoding}"?>
<feed version="0.3" xmlns="http://purl.org/atom/ns#" xml:lang="en">
  <title>{$this_blog['blog_name']}</title>
  <tagline>{$this_blog['blog_info']}</tagline>
  <id>{$atom_id}</id>
  <link rel="alternate" type="text/html" href="{$bmc_vars['site_url']}" /> 
  <copyright>Copyright (c) {$bmc_vars['site_url']}</copyright>
  <modified>{$now_iso}</modified>

EOF;
fputs($fp_3, $atom);

	foreach($result as $post) {

		// ==== Prepare the variables
		// ==== We use bmc_htmlEntities() to make the text safe for xml pages

		$title="<![CDATA[".bmc_htmlEntities($post['title'])."]]>";

		$desc=$post['summary'];

		if(strlen($desc) > $rss_trunc) { $desc=substr($desc,0,$rss_trunc)." .."; } // if the description is too long, truncate it



		if($post['format'] == "html") {
			$desc=bmc_bbCode($desc);
			$desc=bmc_Smilify($desc);
			$desc="<![CDATA[{$desc}]]>";
		} else {
			$desc=bmc_htmlEntities($desc);
			$desc=bmc_bbCode($desc);
			$desc=bmc_Smilify($desc);
			$desc="<![CDATA[{$desc}]]>";
		}

		$id=bmc_htmlEntities($post['id']);
		$date=bmc_Date($post['date'],$rss_date);

		$url=bmc_htmlEntities("{$bmc_vars['site_url']}/".bmc_SE_friendly_url('post',$this_blog['blog_file'],$id,$post['title']));

		$catid=$post['cat'];


	// Get the category name
	$cat = $db->query( "SELECT cat_name FROM ".MY_PRF."cats WHERE id='{$catid}'", false );
	$cat = bmc_htmlEntities($cat['cat_name']);


// Print the RSS .92 tags
$rss_1=<<<EOF
    <item>
      <title>$title</title>
      <description>$desc</description>
      <link>$url</link>
    </item>

EOF;
fputs($fp_1, $rss_1);



// Print the RSS 2.0 tags
$rss_2=<<<EOF
    <item>
      <title>$title</title>
      <description>$desc</description>
      <link>$url</link>
      <pubDate>$date</pubDate>
      <category>$cat</category>
      <comments>{$url}#cmt</comments>
    </item>

EOF;
fputs($fp_2, $rss_2);


$auth_url=bmc_htmlEntities($bmc_vars['site_url']."/profile.php?id=".$post['author']); // The user's profile page

$user=bmc_dispUser($post['author']); // The user's display name

$atom_id_domain=explode("/",str_replace("http://","",$bmc_vars['site_url']));
$atom_id="tag:".$atom_id_domain[0].",".bmc_Date(0,"Y-m-d").":/archives/".$this_blog['id']."/".$post['id'];

$atom_rel_title=bmc_htmlEntities($post['title']);

$date_iso=bmc_iso8601_date($post['date']);

// Prints the Atom tags
$atom=<<<EOF
<entry>
  <title>$title</title>
  <link rel="alternate" type="text/html" href="$url"/>
  <issued>$date_iso</issued>
  <modified>$now_iso</modified>
  <id>$atom_id</id>
  <content type="text/html" mode="escaped" xml:lang="en" xml:base="{$bmc_vars['site_url']}">
  $desc
  </content>
  <link rel="related" type="text/html" href="$url" title="{$atom_rel_title}"/>
  <author>
    <name>$user</name>
    <url>$auth_url</url>
  </author>
</entry>

EOF;
fputs($fp_3, $atom);

	}



// Print the ending tags in .92 and 2.0 files
$xml="  </channel>\n</rss>";
fputs($fp_1, $xml);
fputs($fp_2, $xml);
fputs($fp_3, "\n\n</feed>");

fclose($fp_1);
fclose($fp_2);
fclose($fp_3);


?>