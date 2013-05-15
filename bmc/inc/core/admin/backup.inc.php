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

// =============

	$bytes=256;	// No of bytes to be read from the buffer at an instance while restoring

// =============


// Do the backup
if(isset($_POST['action']) && $_POST['action'] == "do_backup") {
	include CFG_ROOT."/inc/core/admin/mysql_bkp.php";
	exit;
}

// Delete a backup
if(isset($_GET['delete']) && !strpos("-".$_GET['delete'], "/") && !strpos("-".$_GET['delete'], "\\")) {

		$file=str_replace("\\","",$_GET['delete']);
		$file=str_replace("/","",$file);
		$file=str_replace("..","",$file); // Remove .. and / for security

	@unlink(CFG_PARENT."/backup/".$file);
	bmc_Go($bmc_vars['site_url']."/".BMC_DIR."/admin.php?action=backup#prev");
}


// Restore data
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "restore") {

	// Restore an uploaded file
	if(isset($_FILES['file']['name'])) {

		$userfile_name = $_FILES['file']['name'];
		$userfile_tmp = $_FILES['file']['tmp_name'];

		// Is gZipped?
		if(eregi("\.gz$", $userfile_name)) {
			$gzip=true;
		} else {
			$gzip=false;
		}

		// Create temporary file
		$file_temp=md5($userfile_name);
		$restore_file=$file_temp;
		move_uploaded_file($userfile_tmp, CFG_PARENT."/backup/$file_temp") or bmc_template('error_admin', $lang['admin_restore_fail']);

	} else {

		if(isset($_GET['file'])) {
			$file=str_replace("\\","",$_GET['file']);
			$file=str_replace("/","",$file);
			$file=str_replace("..","",$file); // Remove .. and / for security

			// Is gZipped?
			if(eregi("\.gz$", $file)) {
				$gzip=true;
			} else {
				$gzip=false;
			}

			$restore_file=$file;

		} else {
			bmc_Template('error_admin',$lang['admin_restore_fail']);
		}
	}


	// Open the file, read the data
	if($gzip) {
		$fp = @gzopen(CFG_PARENT."/backup/$restore_file", "r");
	} else {
		$fp=@fopen(CFG_PARENT."/backup/$restore_file", "r");
	}

	// Some problem with the file
	if(!$fp) {
		bmc_Template('error_admin',$lang['admin_restore_fail']);;
	}

	$data="";

	while(!feof($fp)) {
		if($gzip) {
			$data.= gzgets($fp, $bytes);
		} else {
			$data.= fgets($fp, $bytes);
		}
	}

	$data=explode("\n", $data);

// step through each line of the array and perform sql queries
$sql = "";
$gen = array();

	for($l=0; $l<sizeof($data); $l++) {
		$num_sp = 0;
		$num_ap = 0;
		$escapenext = 0;
	
		// are we currently "in" a query?
		if($sql == "") {
			// no, we're not, let's start a new
			$data[$l] = ltrim($data[$l]);
	
			// is this either a blank line or a commented line?
			if(rtrim($data[$l]) == "" || substr($data[$l], 0, 1) == "#" || substr($data[$l], 0, 2) == "--") {
				// it certainly is! let's just ignore it
				continue;
			}
		}
	
		$sql .= $data[$l];
	
		// no, this isn't just a comment, let's get started
		for($lc=0; $lc<strlen($sql); $lc++) {
			if($escapenext == 1) {
				$escapenext = 0;
			} else {
				$char = substr($sql, $lc, 1);
	
				// is this a speech mark?
				if($char == "\"") {
					// are we "in" a speech mark quote?
					if($num_sp%2) {
						// yup, so let's see whether or not this is escaped
						$num_sp++;
						// yea, it is escaped (unless above done), so ignore it
					} else {
						// no need to check escaping
						if(!($num_ap%2)) {
							// we're not inside an apostrophe quote either, so we can increment safely
							$num_sp++;
						}
					}
				} else if($char == "'") {
					// or an apostrophe?
					if($num_ap%2) {
						// yup, so let's see whether or not this is escaped
						$num_ap++;
		
						// yea, it is escaped (unless above done), so ignore it
					} else {
						// no need to check escaping
						if(!($num_sp%2)) {
							// we're not inside an apostrophe quote either, so we can increment safely
							$num_ap++;
						}
					}
				} else if($char == "\\") {
					$escapenext = 1;
				} else if($char == "#" || $char == ";") {
					// starting a comment or is this a semi-colon? are we inside a quote?
					if(!($num_sp%2) && !($num_ap%2)) {
						// we're not inside a quote, most likely this is a comment or end of line so let's cut the rest of the line off
						$gen[] = substr($sql, 0, $lc);
						$sql = "";
					}
				}
			}
		}
	}

	$gen[] = $sql;


for($l=0; $l<sizeof($gen); $l++){
	$sql = trim($gen[$l]);
	if($sql != "") {
		if(!@mysql_query($sql)) {
			bmc_Template("error_admin",$sql);
		}

	}
}


	// Close the file handles
	if($gzip) {
		gzclose($fp);
	} else {
		fclose($fp);
	}

	// If it was a temp file, Delete it
	if(isset($file_temp)) {
		@unlink(CFG_PARENT."/backup/$file_temp");
	}


	// =========== Now its time to sync the blog with the updated database records

	// Update the XML feeds
	$result=null; $i_blog=null;
	$result=$db->query("SELECT * FROM ".MY_PRF."blogs WHERE frozen='0' and rss_feed='1'");

	$bmc_path=BMC_DIR;

	foreach($result as $i_blog) {
		include CFG_ROOT."/inc/core/rss.build.php";

		// Also create the static files

		if(!empty($i_blog['blog_file'])) {
			clearstatcache();

$blog_data=<<<EOF
<?php
	// Static loader for blog '{$i_blog['blog_name']}' on {$date}
	\$blog_id={$i_blog['id']};
	include dirname(__FILE__)."/{$bmc_path}/start.php";
?>
EOF;
	
			$fp=@fopen(CFG_PARENT."/".$i_blog['blog_file'], "w+");
			if($fp) {
				fputs($fp, $blog_data);
				fclose($fp);
			}
		}

	}

	// Update the blog list/category list/ and the archive list
	bmc_updateCache('blogs');
	bmc_updateCache('cats');
	bmc_updateCache('archive');


	bmc_Template('admin_header',$lang['admin_restore_ok']);
	echo "<strong>".$lang['admin_restore_ok']."</strong>";
	bmc_Template('admin_footer');
	exit;
}


// ====================== The backup page

bmc_Template('admin_header', $lang['admin_backup_title']);
?>
<p><strong><?php echo $lang['admin_backup_title']; ?></strong></p>
<p><?php echo $lang['admin_backup_note']; ?></p>
<form name="backup" method="POST" action="admin.php">
<input type="hidden" name="action" value="do_backup" />
<input type="submit" value="<?php echo $lang['admin_backup_but']; ?>" /><br />
<input type="checkbox" name="bk_gzip" value="true" /> <?php echo $lang['admin_backup_gzip']; ?><br /><br />

<input type="checkbox" name="bkp_posts" value="true" checked /> <?php echo $lang['total_articles']; ?><br />
<input type="checkbox" name="bkp_comments" value="true" checked /> <?php echo $lang['comments']; ?><br />
<input type="checkbox" name="bkp_blogs" value="true" checked /> <?php echo $lang['blogs']; ?><br />
<input type="checkbox" name="bkp_cats" value="true" checked /> <?php echo $lang['cats']; ?><br />
<input type="checkbox" name="bkp_settings" value="true" checked /> <?php echo $lang['admin_set']; ?><br />
<input type="checkbox" name="bkp_users" value="true" checked /> <?php echo $lang['users']; ?><br />
<input type="checkbox" name="bkp_votes" value="true" checked /> <?php echo $lang['votes']; ?><br />
<input type="checkbox" name="bkp_trackbacks" value="true" checked /> <?php echo $lang['trackbacks']; ?><br />
<input type="checkbox" name="bkp_links" value="true" checked /> <?php echo $lang['links']; ?><br />
</form>

<br />
<hr width="100%" size="1" color="#CCCCCC">
<p><strong><?php echo $lang['admin_backup_restore']; ?></strong></p>

<script type="text/javascript">
<!--

function warnRestore(file) {
var msg=confirm('<?php echo $lang['admin_restore_warn']; ?>');

	if(!msg) {
		return;
	} else {
		document.location="?action=restore&file="+file;
	}

}

//-->
</script>

<form name="restore" method="POST" action="admin.php" ENCTYPE="multipart/form-data">
<input type="hidden" name="action" value="restore" />
<?php echo $lang['admin_restore_title']; ?><br />
<?php echo $lang['admin_restore_warn']; ?>
<br /><input type="file" name="file" maxlength="60" size="46"> 
<br />
<input type="submit" value="<?php echo $lang['admin_restore_but']; ?>"></form>
<br />
<hr width="100%" size="1" color="#CCCCCC">
<span id="prev"></span>
<strong><?php echo $lang['admin_backup_previous']; ?></strong><br /><br />
<table border="0" cellpadding="5" cellspacing="0" width="100%">

<?php

// List the existing backup files

	$handle = opendir(CFG_PARENT."/backup");
	while($file = readdir($handle)) {
	$ext=explode(".", $file);
	$ext=$ext[count($ext)-1];
		if( $ext == "gz" || $ext == "sql" ) {
?>

<tr>
<td width="50%">
<p><a href="<?php echo $bmc_vars['site_url']; ?>/backup/<?php echo $file; ?>"><?php echo $file; ?></a></p>
</td>

<td width="25%">
<p>( <a href="javascript:warnRestore('<?php echo $file; ?>');" title="<?php echo $lang['admin_backup_restore']; ?>"><?php echo $lang['admin_backup_restore']; ?></a> )</p>
</td>

<td width="25%">
<p><a href="?action=backup&delete=<?php echo $file; ?>"><?php echo $lang['admin_backup_delete']; ?></a></p>
</td>
</tr>
<?php
		}
	}

echo "</table><br /><br />";

bmc_Template('admin_footer');
exit;

?>