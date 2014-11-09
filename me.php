<?php
$_GET['n'] = "Anonymous";
include("ref/functions.php");
$res = getuserid();
if($res != -1)
	$_GET['n'] = getusername();
include("profile.php");
?>
