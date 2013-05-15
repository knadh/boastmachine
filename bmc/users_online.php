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


$enable_users_online=true; // Enable counting?
$user_online_timeout=5; // Time in minutes when the 'online users' list is to be reset


//=========================== End user config =======================


if(isset($show_users_online)) {

	$db->query("DELETE FROM ".MY_PRF."users_online WHERE time_stamp < ".(time()-(60*$user_online_timeout)));
	$users_online=$db->query("SELECT * FROM ".MY_PRF."users_online ");

	for($n=0;$n<count($users_online);$n++) {
		echo "<a href=\"http://network-tools.com/default.asp?host={$users_online[$n]['ip']}\">{$users_online[$n]['ip']}</a>\n";

		if(!empty($users_online[$n]['user'])) {
			$user_online_id=$db->query("SELECT id FROM ".MY_PRF."users WHERE user_login='{$users_online[$n]['user']}'", false);
			echo " ( <a href=\"{$bmc_vars['site_url']}/bmc/admin.php?action=edit_user&amp;user={$user_online_id['id']}\">{$users_online[$n]['user']}</a> )\n";
		}

	echo bmc_Date($users_online[$n]['time_stamp'],'',true);
	echo "<br />";

	}

}

else {

	if(!$enable_users_online) { return; }

	$online_ip=$_SERVER['REMOTE_ADDR'];	// The IP

	// No proper ip
	if(!$online_ip) { return; }

	// The user's name if logged in
	$online_user=bmc_isLogged();

	if(!$db->row_count("SELECT ip FROM ".MY_PRF."users_online WHERE ip='{$online_ip}'", false)) {
		// Added the user's info to the DB
		$db->query("INSERT INTO ".MY_PRF."users_online (time_stamp,ip,user) VALUES('".time()."','{$online_ip}','{$online_user}')");
	}

}

?>