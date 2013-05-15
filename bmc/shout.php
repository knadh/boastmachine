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


//======================== SHOUT BOX MODULE

	$shout=true; // Enable shout box?

	$shout_blog=false;	// Separate shoutboxes for different blogs?

	$max_posts=10; // Maximum number of posts

	$max_chars=100; // Mazimum number of characters to be allowed in the message
	
	$smile=true; // Allow smilies?

	$width=140; // Frame width

	$height=200; // Frame height

	$border=1; // Frame border

	$border_color="#AAAAAA";


//==================================

	include_once dirname(__FILE__)."/main.php";

	// Check whether shout box is enabled
	if(!$shout) {
		die($lang['no_shout']);
	}

	$shout_file="shout.dat";	// Default shoutbox data file


	if(defined('BLOG')) {
		$the_blog=BLOG;
	} else {
		if(isset($_REQUEST['blog']) && is_numeric($_REQUEST['blog'])) {
			$the_blog=$_REQUEST['blog'];
		}
	}


	if($shout_blog) {	// Different shout boxes for different blogs
		$shout_file="shout_{$the_blog}.dat";
	}


// Save a post
if(isset($_POST['action']) && $_POST['action'] == "save" && !empty($_POST['name']) && !empty($_POST['msg'])) {

	$data=@fread(fopen(CFG_ROOT."/inc/vars/$shout_file","r"), filesize(CFG_ROOT."/inc/vars/$shout_file"));
	$data=trim($data);
	$data=explode("\n",$data);

	$num=count($data);

	// Cut out || from the posted data
	$name=trim(str_replace("||","|",$_POST['name']));
	$url=trim(str_replace("||","|",$_POST['url']));
	$msg=trim(str_replace("||","|",$_POST['msg']));

	$date=time();
	$ip=$_SERVER['REMOTE_ADDR']; // The poster's ip

	$msg=str_replace("\n"," ",$msg); // Strip the line breaks

	// Formatted data
	$str="$name||$url||$date||$ip||$msg";

	if($num >= $max_posts) {
		// If the number of entries exceed the max allowed, remove the oldest entry
		unset($data[0]);
		$data[]=$str;
		$data=implode("\n",$data);
	} else {
		$data=implode("\n",$data);
		$data.="\n".$str;
	}


	// Save the data
	$f=fopen(CFG_ROOT."/inc/vars/$shout_file", "w+");
	fputs($f,$data);
	fclose($f);


	bmc_Go("shout.php?action=show&blog={$the_blog}"); // Redirect
}

// ========================

if(isset($_GET['action']) && $_GET['action']=="add") {
	// Show the 'ADD SHOUT' form
	include CFG_PARENT."/templates/".CFG_THEME."/shout_box.php";
	exit;
}


if(isset($_GET['action']) && $_GET['action'] == "show") {


	$fdata=@fread(fopen(CFG_ROOT."/inc/vars/$shout_file","r"), filesize(CFG_ROOT."/inc/vars/$shout_file"));

	if(!$fdata) { exit; } // no data

	$data=explode("\n",$fdata);
	$data=array_reverse($data); // Reverse the order of entries


	if(!count($data)) { exit; } // there are no posts


	// Collect the data and parse into an array
	for($n=0;$n<=count($data);$n++) {
		if(!empty($data[$n])) {

			list($name,$url,$date,$ip,$msg)=explode("||",$data[$n]);
			$msg=str_replace("\\n","\n",$msg);

			if(trim($name)) {
				$posts['name'][$n]=bmc_htmlentities($name);
				$posts['url'][$n]=bmc_htmlentities($url);
				$posts['msg'][$n]=bmc_htmlentities(noSlash($msg));
				$posts['date'][$n]=$date;
				$posts['ip'][$n]=$ip;
			}
		}
	}

	include CFG_PARENT."/templates/".CFG_THEME."/shout_box.php";
}

else {
	?>

	<script type="text/javascript">
	<!--
		document.writeln("<iframe src=\"<?php echo $bmc_vars['site_url']."/".BMC_DIR; ?>/shout.php?action=show&amp;blog=<?php echo $the_blog; ?>\" name=\"shout\" style=\"border-width: <?php echo $border; ?>px; border-style:solid;  border-color: <?php echo $border_color; ?>;\" frameborder=\"0\" height=\"<?php echo $height; ?>\" width=\"<?php echo $width; ?>\"></iframe>\n");
		document.writeln("<br /><a href=\"<?php echo $bmc_vars['site_url']."/".BMC_DIR; ?>/shout.php?action=add&amp;blog=<?php echo $the_blog; ?>\" target=\"shout\"><strong><?php echo $lang['post_post_but']; ?></strong></a><br /><br />\n");
	//-->
	</script>

	<?php
}
	?>