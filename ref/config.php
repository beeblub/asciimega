<?php
$config_countusers = array (
  //relative path from main directory
  'filename' => 'nonpublic/besucher_online.txt',
  'timelimit' => 300 // 5 minutes
);
$config_track = array (
   //relative path from main directory
  'logpath_pre' => "nonpublic/log-",
  'logpath_post' => ".txt"
);
$config_captcha = array(
   //THE ABSOLUTE PATH TO THE FONT!
  'fontpath' => "/var/www/html/asciimega/captcha/XFILES.TTF"
);
?>
