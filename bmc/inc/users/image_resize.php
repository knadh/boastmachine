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


	// ==================================

	$width   = 203;	// Thumbnail width
	$height  = 152;	// Thumbnail height
	$quality = 75; // JPG thumbnail quality

	// ==================================

	$file_specs=explode("_", $filename);	// Split the file name
	$target=$file_specs[0]."_thumb_"; // Add _thumb_ after the username
	unset($file_specs[0]);
	$file_specs=implode("_", $file_specs);
	$target.=$file_specs;	// Final thumbnail filename
	

     $size = getimagesize(CFG_PARENT."/files/".$filename);

     // scale evenly
     $ratio = $size[0] / $size[1];
     if ($ratio >= 1){
          $scale = $width / $size[0];
     } else {
          $scale = $height / $size[1];
     }

	// make sure its not smaller to begin with!
    if ($width >= $size[0] && $height >= $size[1]){
    	$scale = 1;
    }

	$img_in = imagecreatefromjpeg(CFG_PARENT."/files/".$filename);
	$img_out = imagecreatetruecolor($size[0] * $scale, $size[1] * $scale);

	// Scale it down
	imagecopyresampled($img_out, $img_in, 0, 0, 0, 0, $size[0] * $scale, $size[1] * $scale, $size[0], $size[1]);
	imagejpeg($img_out, CFG_PARENT."/files/".$target, $quality);	// Produce the image

	imagedestroy($img_out);
	imagedestroy($img_in);

?>