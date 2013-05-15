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


$enable_log=true; // Enable logging?
$num_ref=50; // number of referers to be logged


//=========================== End user config =======================

if(!$enable_log) { return; }


	// No proper data or the referer is from the same site
	if(empty($_SERVER['HTTP_REFERER']) || empty($_SERVER['REMOTE_ADDR']) || strpos("-".$_SERVER['HTTP_REFERER'], $bmc_vars['site_url'])) { return; }


	$ref_ip=$_SERVER['REMOTE_ADDR'];	// The IP
	$referer=$_SERVER['HTTP_REFERER'];	// The referer

	$ref_time=time();

	$ref_data=@fread(@fopen(dirname(__FILE__)."/inc/vars/ref.log","r"), @filesize(dirname(__FILE__)."/inc/vars/ref.log"));

	if(!empty($ref_data)) {
		$ref_data=@unserialize($ref_data);
	} else {
		$ref_data['url']=array();
		$ref_data['ip']=array();
		$ref_data['time']=array();
	}


	if(count($ref_data['url']) > $num_ref) {
		$count=$num_ref-1;
	} else {
		$count=count($ref_data['url'])-1;
	}
		// If the number of entries > the the wanted number, remove the top most one

			$ref_data_new['url']=array();
			$ref_data_new['ip']=array();
			$ref_data_new['time']=array();

			$ref_data_new['url'][]=$referer;
			$ref_data_new['ip'][]=$ref_ip;
			$ref_data_new['time'][]=$ref_time;

	if($count) {
		for($n=0;$n < $count;$n++) {
			$ref_data_new['url'][]=$ref_data['url'][$n];
			$ref_data_new['ip'][]=$ref_data['ip'][$n];
			$ref_data_new['time'][]=$ref_data['time'][$n];
		}
	}



	$fp=fopen(dirname(__FILE__)."/inc/vars/ref.log", "w+");
	fputs($fp, serialize($ref_data_new));
	fclose($fp);

?>