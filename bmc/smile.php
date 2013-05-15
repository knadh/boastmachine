<?php include_once dirname(__FILE__)."/main.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Smilies</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<style type="text/css">
	<!--
	body, br, p, html {
		font-family: verdana;
		font-size: 10px;
		background: #F7F7F7;
	}
	//-->
	</style>

</head>

<body>

<?php


// Print out all the smilies in the directory
$ar=bmc_getSmileFiles();

	for($n=0;$n<=count($ar)-1;$n++) {
		$name=explode(".",$ar[$n]);
		$name=$name[0]; $name=":".strtolower($name).":";

echo <<<EOF
<img alt="$name" src="{$bmc_vars['site_url']}/smilies/$ar[$n]" />&nbsp;&nbsp;&nbsp;$name <br />
EOF;

	}

echo <<<EOF
</table>
<hr width="200" align="left" size="1" color="black">
<table border="0" cellpadding="2" cellspacing="0" width="169">
EOF;

// Print out the symbols from .pak file
$sm=fread(fopen(CFG_PARENT."/smilies/smiles.pak", "r"), 100000);
$sm=explode("\n",$sm);

	for($i=0;$i<=count($sm);$i++) {

		if(isset($sm[$i]) && strpos(trim("-$sm[$i]"),"#") != 1) {
		list($file, $smil) = explode("=", $sm[$i]);

echo <<<EOF
<img alt="$name" src="{$bmc_vars['site_url']}/smilies/$file" />&nbsp;&nbsp;&nbsp;$smil <br />
EOF;

		}
	}


?>

</body>
</html>