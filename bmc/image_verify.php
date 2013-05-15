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


// ====================

	$num_chars=6;
		// number of characters in the string generated

	$nums=true;
		// Include numbers ?

// ====================

session_start();

if(!isset($_SESSION['img_verification'])) {
	// No session registered. Create a random verification code and register it

	for($n=65;$n<(65+26);$n++) {
		$alpha[]=chr($n);	// characters from A to Z
	}

	$string="";

	for($n=0;$n<$num_chars;$n++) {
		$string.=$alpha[rand(0,25)];
	}


	// Include numbers
	if($nums) {
		for($n=0;$n<($num_chars/2);$n++) {
			$string[rand(0,($num_chars-1))]=rand(0,9);
		}
	}

	$_SESSION['img_verification']=$string;

} else {

	// Session was already registered, just get the pre-generated verification code
	$string = $_SESSION['img_verification'];
}


header("Content-type: image/png\n\n");	// Image header

$im = imagecreate(84,24);
$bg = imagecolorallocate($im, 255, 255, 255);

$line = imagecolorallocate($im, 216,216,216);

	// Draw random lines
	for($i=0;$i<=100;$i++) {
		imageline($im, rand(1,170), rand(1,170), rand(1,100), rand(1,100),$line);
	}

	// Text color array
	$text_color_array=array("255,51,0","0,0,0","51,0,204","204,51,102","102,102,102","0,153,0");

	for($n=0;$n<$num_chars;$n++) {

		// Break the color into R, G and B
		$text_color=$text_color_array[rand(0,count($text_color_array)-1)];
		$text_color=explode(",", $text_color);

		$text = imagecolorallocate($im, $text_color[0], $text_color[1], $text_color[2]);	// Text color
		imagestring ($im, rand(3,7), 15+($n*10), rand(2,10), substr($string, $n, 1), $text);	// Write the text
	}

imagepng($im);
imagedestroy($im);


?>