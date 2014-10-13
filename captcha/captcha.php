<?php

//Returns a captcha image --- experimental

   session_start();
   include("../ref/config.php");

   unset($_SESSION['captcha_spam']);

   function randomString($len) {
      function make_seed(){
         list($usec , $sec) = explode (' ', microtime());
         return (float) $sec + ((float) $usec * 100000);
      }
      srand(make_seed());  
                       
      //possible contains all chars
      $possible="ABCDEFGHJKLMNPRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789";
      $str="";
      while(strlen($str)<$len) {
        $str.=substr($possible,(rand()%(strlen($possible))),1);
      }
   return($str);
   }

	$text = randomString(5); 
	$_SESSION['captcha_spam'] = $text; 

	header('Content-Disposition: Attachment;filename=captcha.png');
   	header('Content-type: image/png');
 	$im = imagecreatefrompng('cap3.PNG');
	
	global $config_captcha;

	$color = ImageColorAllocate($im, 0, 0, 0);
	$ttf = $config_captcha['fontpath']; //font
	$ttfsize = 45; //font size
	$angle = rand(0,6);
	$t_x = rand(5,30);
	$t_y = 80;
	ImageTTFText($im, $ttfsize, $angle, $t_x, $t_y, $color, $ttf, $text); 

        imagepng($im,NULL); # works as expected
	imagedestroy($im);
?> 
