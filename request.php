<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta charset="utf-8"/>
<style>
html{margin: 0;width:100%;height:100%;}
body{margin: 0;width:100%;height:100%;overflow:hidden;
cursor: pointer;
-webkit-user-select: none;
-khtml-user-select: none;
-moz-user-select: none;
-o-user-select: none;
user-select: none;
}
#display_preplay{
position: relative;
z-index:1;
opacity:0.5;
top:0px;
left:0px;
width: 100%;
height:100%;
background-color:black;
color:white;
}
div.container4 {
    height: 100%;
    position: relative }
div.container4 div {
    margin: 0;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-right: -50%;
    transform: translate(-50%, -50%) }
</style>
</head>
<body>

<div id="display_preplay">
<div class=container4>
  <div><h1>></h1></div>
</div>
</div>

<?php
echo "<script>";
//echo "document.getElementById('display_preplay').innerHTML = 'hello you';";
echo "document.getElementById('display_preplay').onclick = function(){window.location = 'embet?".$_SERVER['QUERY_STRING']."';}</script>";
?>

</body>
<html>
