<div id="profile_page">
<h1><?php echo $lang['user_profile']." :: ".$user_name; ?></h1>

<?php
	// Show the pic

	if($user_info['user_show_pic'] && !empty($user_info['user_pic'])) {

		// Verify the image size again :)
		$img=@getimagesize(CFG_PARENT."/files/".$user_info['user_pic']);

		if((isset($img[0]) && isset($img[1])) && ($img[0] < $bmc_vars['user_pic_width'] || $img[1] < $bmc_vars['user_pic_height'])) {
?>
	<img src="<?php echo $bmc_vars['site_url']."/files/".$user_info['user_pic']; ?>" alt="<?php echo $user_info['user_login']; ?>" /><br />
<?php
		}
	}
?>

<br />
<?php echo $lang['user_joined']; ?> :<br /><strong><?php echo bmc_Date($user_info['date']); ?></strong>

<br /><br />
<?php echo $lang['user_profile_last_login']; ?> :<br /><strong>
<?php
	// Parse and print the last login info
	$last_login=explode("|",$user_info['last_login']);
	$last_login=explode("(",$last_login[0]);
	unset($last_login[1]);
	$last_login=implode("", $last_login);
	echo $last_login;
?>
</strong>


<br /><br />
<?php echo $lang['user_profile_total_posts']; ?> : 
<?php
	// Get the total number of posts by this user
	$total_posts=$db->row_count("SELECT id FROM ".MY_PRF."posts WHERE author='{$user_info['id']}' AND status='1'");
	echo $total_posts;
?> , 
<?php echo $lang['user_profile_total_cmts']; ?> : 
<?php
	// Get the total number of posts by this user
	$total_comments=$db->row_count("SELECT id FROM ".MY_PRF."comments WHERE author='{$user_info['id']}'");
	echo $total_comments;
?>

<br /><br />
<?php echo $lang['user_name']; ?> :<br /><strong><?php echo $user_info['user_name']; ?></strong>

<br /><br />
<?php echo $lang['user_login']; ?> :<br /><strong><?php echo $user_info['user_login']; ?></strong>


<br /><br />
<?php echo $lang['user_nick']; ?> :<br /><strong><?php echo $user_info['user_nick']; ?></strong>

<br /><br />
<?php echo $lang['user_url']; ?> :<br /><a href="http://<?php echo str_replace("http://","",htmlentities($user_info['user_url'])); ?>"><strong><?php echo htmlentities($user_info['user_url']); ?></strong></a>
<?php
// Show the email if necessary

	if($user_info['user_show_email']) {
	?>

<br /><br />
<?php echo $lang['user_email']; ?> :<br />

	<script type="text/javascript">
	<!--
		document.write("<a href=\"mailto:<?php echo eregi_replace( "^([_\.0-9a-z-]+)@([0-9a-z][0-9a-z-]+)\.([a-z]{2,6})$", '\\1"+"&"+"#064;"+"\\2"+"."+"\\3',htmlentities($user_info['user_email'])); ?>\"><strong><?php echo htmlentities($user_info['user_email']); ?></strong></a>");
	//-->
	</script>
<?php
	}
?>

<br /><br />
<?php echo $lang['user_location']; ?> :<br /><strong><?php echo $user_info['user_location']; ?></strong>

<br /><br />
<?php echo $lang['user_birth']; ?> :<br /><strong>
<?php

	if(!empty($user_info['user_birth'])) {
		list($b_day, $b_month, $b_year)=explode("/",$user_info['user_birth']); // get the birth date

		if($year > 1970) {
			$stamp=strtotime("$b_month/$b_day/$b_year");

			$b_day=bmc_Date($stamp, "w");
			$b_month=bmc_Date($stamp, "M");

			echo $b_day." , ".date("d",$stamp)." ".$b_month.", ".date("Y", $stamp);
		} else {
			// PHP has problem dealing with dates before 1970 on Win systems
			// So display the date as it is.
			echo $user_info['user_birth'];
		}
	}
?></strong>

<br /><br />
<?php echo $lang['user_yim']; ?> : <a href="http://edit.yahoo.com/config/send_webmesg?.target=<?php echo $user_info['user_yim']; ?>&.src=pg"><strong><?php echo $user_info['user_yim']; ?></a></strong> , &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo $lang['user_icq']; ?> : <a href="http://wwp.icq.com/<?php echo $user_info['user_icq']; ?>#pager"><strong><?php echo $user_info['user_icq']; ?></a></strong> , &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo $lang['user_msn']; ?> : <a href="mailto:<?php echo str_replace("@","&#064;", $user_info['user_msn']); ?>"><strong><?php echo str_replace("@","&#064;", $user_info['user_msn']); ?></a></strong>

<br /><br />
<strong><?php echo $lang['user_profile']; ?></strong><br />
<?php echo nl2br(bmc_wordwrap(htmlentities($user_info['user_profile']), $bmc_vars['summary_wrap'],"\n")); ?>

</div>