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


$text  = "# --------------------------------------------------------\n";
$text .= "# boastMachine MySQL DB dump\n"; 
$text .= "# Host: ".$my_host."\n"; 
$text .= "# Time: ".bmc_Date(0,"d. F Y")." um ".bmc_Date(0,"H:i")."\n"; 
$text .= "# Running on: ".php_uname()."\n"; 
$text .= "# MySQL-Version: ".mysql_get_server_info()."\n"; 
$text .= "# PHP-Version: ".phpversion()."\n"; 
$text .= "# Database: `$my_db`\n"; 
$text .= "# --------------------------------------------------------\n\n\n"; 


// Prepare the list of tables to be backed up
if(isset($_POST['bkp_posts'])) $tbl_array[] = MY_PRF."posts"; 
if(isset($_POST['bkp_comments'])) $tbl_array[] = MY_PRF."comments"; 
if(isset($_POST['bkp_cats'])) $tbl_array[] = MY_PRF."cats"; 
if(isset($_POST['bkp_votes'])) $tbl_array[] = MY_PRF."votes"; 
if(isset($_POST['bkp_blogs'])) $tbl_array[] = MY_PRF."blogs";
if(isset($_POST['bkp_settings'])) $tbl_array[] = MY_PRF."vars";
if(isset($_POST['bkp_users'])) $tbl_array[] = MY_PRF."users";
if(isset($_POST['bkp_trackbacks'])) $tbl_array[] = MY_PRF."trackbacks";
if(isset($_POST['bkp_links'])) $tbl_array[] = MY_PRF."links";


// GZipping
if(isset($_POST['bk_gzip'])) {
	$use_gzip=true;
} else {
	$use_gzip=false;
}


$newfile=$text;

for($i=0;$i<=count($tbl_array)-1;$i++) {
    $newfile .= get_def($tbl_array[$i]);
    $newfile .= "\n\n";
    $newfile .= get_content($tbl_array[$i]);
    $newfile .= "\n\n";
    $i++;
}

	$file_name = "bmc-".bmc_Date(0,"d_M_Y_h-j-s-a") . ".bak.sql"; // Unique filename
	$file_path = CFG_PARENT."/backup/".$file_name;

	if ($use_gzip) {
		$file_path .= ".gz";	// Make the file .gz
		$newfile=gzencode($newfile,9);	// gZip the data
	}

	$fp = fopen($file_path, "w+");
	fwrite($fp, $newfile);
	fclose($fp);


	bmc_Go($bmc_vars['site_url']."/".BMC_DIR."/admin.php?action=backup#prev");	// Redirect

// ======================================================

  function get_def($table) {
    $def = "";
    $def .= "DROP TABLE IF EXISTS `$table`;\n";
    $def .= "CREATE TABLE `$table` (\n";
    $result = mysql_query("SHOW FIELDS FROM $table") or die("Table $table not existing in database");
    while($row = mysql_fetch_array($result)) {
      $def .= "    {$row['Field']} {$row['Type']}";
      if ($row["Default"] != "") $def .= " DEFAULT '{$row['Default']}'";
      if ($row["Null"] != "YES") $def .= " NOT NULL";
      if ($row['Extra'] != "") $def .= " {$row['Extra']}";
      $def .= ",\n";
    }
    $def = ereg_replace(",\n$","", $def);
    $result = mysql_query("SHOW KEYS FROM $table");
    while($row = mysql_fetch_array($result)) {
      $kname=$row['Key_name'];
      if(($kname != "PRIMARY") && ($row['Non_unique'] == 0)) $kname="UNIQUE|$kname";
      if(!isset($index[$kname])) $index[$kname] = array();
      $index[$kname][] = $row['Column_name'];
    }
    while(list($x, $columns) = @each($index)) {
      $def .= ",\n";
      if($x == "PRIMARY") $def .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
      else if (substr($x,0,6) == "UNIQUE") $def .= "   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
      else $def .= "   KEY $x (" . implode($columns, ", ") . ")";
    }
    $def .= "\n);\n";
    return (stripslashes($def));
  }


  function get_content($table) {
    $content="";
	$fields="";
    $result = mysql_query("SELECT * FROM $table");

	$keys = mysql_query("SHOW FIELDS FROM $table");

	$fields="(";
	while($row = mysql_fetch_array($keys)) {
		$kname=$row['Field'];
		$fields.="$kname,";
	}


	$fields.=")";
	$fields=str_replace(",)",")", $fields);

    while($row = mysql_fetch_row($result)) {
      $insert = "INSERT INTO `$table` {$fields} VALUES (";
      for($j=0; $j<mysql_num_fields($result);$j++) {
        if(!isset($row[$j])) $insert .= "NULL,";
        else if($row[$j] != "") $insert .= "'".addslashes($row[$j])."',";
        else $insert .= "'',";
      }
      $insert = ereg_replace(",$","",$insert);
      $insert .= ");\n";
      $content .= $insert;
    }
    return $content;
  }

?>