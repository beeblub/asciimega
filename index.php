<?php
//error debugging
//ini_set('display_errors',1);
error_reporting(E_ERROR);
?>

<html>
<head>
<meta charset="utf-8"/>
<link rel="icon" href="logo.png" type="image/x-icon" />
<link rel="shortcut icon" href="logo.png" type="image/x-icon" />
<link href='http://fonts.googleapis.com/css?family=Ubuntu&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<style>
html{margin: 0;width:100%;height:100%;}
body{margin: 0;width:100%;height:100%;overflow-x:hidden;overflow-y:scoll; font-family: 'Ubuntu', sans-serif;}

#reportdiv
{
 	background-color:#bb0011;
}

#comment
{
	background-color:rgba(255,255,255,0.6);
	padding:10px;
	display:inline-block;
}

.navigationItem{
	padding-top: 0.5%;
	padding-bottom: 0.5%;
	border: 0px solid;
	transition: background-color .5s ease-in-out;
	-webkit-transition:  background-color .5s ease-in-out;
	-moz-transition: background-color .5s ease-in-out;
	-o-transition: background-color .5s ease-in-out;
	-ms-transition: background-color .5s ease-in-out;
	cursor: default;
}
.navigationItem:hover{
	background-color: #757575;
}

a{
	cursor:default;
	text-decoration: none;
}

h1{
		font-size:12pt;
		color:rgba(255, 255, 255, .5);
		font-weight: lighter;
		margin:0;
	padding:0;
}

h2{
	font-size: 30pt;
	font-weight: bold;
	color:rgba(255, 255, 255, .5);
	margin:0;
	padding:0;
}

.naviItem:hover{
	opacity:.8;
	cursor:default;
}

#watchNaviSearch input{
	height: 30px;
	border:0;
	font-family: 'Ubuntu', sans-serif;
	padding:5px;
}

#inputGo{
	transition: background-color .5s ease-in-out;
	-webkit-transition:  background-color .5s ease-in-out;
	-moz-transition: background-color .5s ease-in-out;
	-o-transition: background-color .5s ease-in-out;
	-ms-transition: background-color .5s ease-in-out;

	height: 30px;
	border:0;
	font-family: 'Ubuntu', sans-serif;
	padding:5px;
}

#inputGo:hover{
	background-color:rgba(200,200,200,.5);
}

#watchNavigation{
	text-align:center;
	border: 3px rgba(0,0,0,.7) solid;
	cursor:default;
	color: rgba(255, 255, 255,.75);
	text-decoration: none;
}

.naviHover{
	background-color:rgba(0,0,0,.5);
	transition: background-color .5s ease-in-out;
	-webkit-transition:  background-color .5s ease-in-out;
	-moz-transition: background-color .5s ease-in-out;
	-o-transition: background-color .5s ease-in-out;
	-ms-transition: background-color .5s ease-in-out;
}

.naviHover:hover{
	background-color:rgba(0,0,0,.7);
}
.userslog{
background-color:rgba(255,255,255,0.9);
display:inline-block;
padding:50px;
color:black;
}
.logo{
z-index:5;position:absolute;left:0px;top:0px;width:150px;height:150px;
}

</style>
</head>
<body id="master" style="min-width: 750px;">

<noscript style="background-color: #171717; height: 100%; width: 100%; position: absolute; z-index: 1000; margin-left: -50%; margin-top: 0%;">
	<p>Javascript is not enabled. Please enable javascript before you use this site. </p><a style="color: #4aadd0;" class="hoverText" href="http://www.enable-javascript.com/" target="_blank">diesen</a><p></p>
</noscript>
<!-- head -->
<div style="left:0px;top:0px;width:100%;height:70px;background-color:black;opacity:0.7;">
<div id="navigationBar" height="70px">
<table border="0" style="color:white;width:100%;text-align:center;font-size:35px; height: 50px; padding-top: 20px;">
<tr>
<td width="30%">
</td>
<td>
<i><a style="text-decoration:none;color:white;" class='naviItem' href="index.php?p=w">Watch</a></i>
</td>
<td style="color:orange;">
<i><a style="text-decoration:none;color:orange;" class='naviItem' href="index.php?p=e">Create</a></i>
</td>
<td width="30%">
<div style="font-size:15px;" class='naviItem' onclick="window.location = 'index.php?p=log';" id="onlineusers">
<p title="view details" >Users Online: 0</p>
</div>
</td>
</tr>
</table>

</div>
</div>

<script>
function setonlineusers(numonline)
{
	document.getElementById("onlineusers").innerHTML = "Users Online: "+numonline;
}
function openNavi(event, divName){
	divCrnt = document.getElementById(divName);
		var crntDisplay = divCrnt.style.display;
		if(crntDisplay == "block"){
			if(divName=="watchNaviSearch"){document.getElementById('jsId').style.background = "rgba(0,0,0,.5)";}
			divCrnt.style.display = "none";
			if(divName=='watchNavigation'){
				var divOther = document.getElementById('watchNaviSearch');
				divOther.style.display='none';
			}
		}else{
			if(divName=="watchNaviSearch"){document.getElementById('jsId').style.background = "rgba(0,0,0,.7)";}
			divCrnt.style.display = "block";
			//setPosition(event, false);
		}
}
var arrayCategory = new Array("most popular", "latest");
var crntIndexCategory = 0;
lastvalue = 0;

function changeCategory(){
	if (crntIndexCategory == arrayCategory.length-1){crntIndexCategory=0;}
	else {crntIndexCategory +=1;}
	document.getElementById("txtCategory").innerHTML = arrayCategory[crntIndexCategory];

	lastvalue = crntIndexCategory;
}

//old
var lastvalue = 0;
function onselectchange(value)
{
	lastvalue = value;
	if(value == "name")
	{
		document.getElementById('searchforname').style.display = "inline-block";
	}
	else
	{
		document.getElementById('searchforname').style.display = "none";
	}
}
function dosearch_name()
{
	lastvalue = "name";
	dosearch();
}
function dosearch()
{
	if(lastvalue == 1)
	{
		window.location = "index?p=w&o=l";
	}
	if(lastvalue == 0)
	{
		window.location = "index?p=w&o=mv";
	}
	if(lastvalue == "name")
	{
		window.location = "index?p=w&o=n&t="+document.getElementById("searchforname_input").value;
	}
}
</script>
<center><div id="watchNavigation" style="display:none; position:relative; top:0px; left: -10%; height: 50px; width: 500px; z-index:2;"><!--left:150px;-->
	<table style="height:50px; width:500px;text-align: center;" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="25px" class="naviHover" ><h2>°</h2></td>
			<td width="150px" class="naviHover" title="change category" onclick="changeCategory();"><h1 id="txtCategory">most popular</h1></td>
			<!--<td width="50px" class="naviHover"><h1>first</h1></td>
			<td width="50px" class="naviHover" title="previous 10"><center><h2><</h2></center></td>
			<td width="75px" style="background-color:rgba(0,0,0,.5);"><center><h1>01-10</h1></center></td>
			<td width="50px" class="naviHover" title="next 10"><center><h2>></h2></center></td>
			<td  width="50px"class="naviHover"><h1>last</h1></td>-->
			<td  width="50px"class="naviHover"><input title="" type="button" onclick="dosearch()" id="inputGo" value="Go"></td>
			<td width="50px" class="naviHover" id="jsId" onclick="openNavi(event, 'watchNaviSearch');"><img src="searchIcon.png" style="margin-top: 5px; height: 30px; width: 30px; opacity:.5;"></img></td>
		</tr>
	</table>
</div></center>

<div id="watchNaviSearch" style="position:relative; left:386px;top:0px;display: none; height: 40px; padding:10px; width: 250px; z-index:3; background-color:rgba(0,0,0,.5); ">
	<table style="height:100%; width:100%;" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<form action="#" autocomplete="on" id="formSuche">
		<td width="90px">
			<input title="" type="text" id="searchforname_input" name="searchforname_input" placeholder="Search">
		</td>
		<td width="40px">
			<input title="" type="button" onclick="dosearch_name()" id="inputGo" value="Go">
		</td>
	</form>
	</tr>
	</table>
</div>

<div class="logo" onclick="this.style.position='absolute';">
<img width="100%"  src="logo.png"></img>
</div>
<div style="position:relative;height:15px;width:30px;">
</div>

<br><br>
<?php
$page = $_GET['p'];

if($GETp)
{
	$page = $GETp;
}

include 'tracker.php';

echo "<script>setonlineusers(".count_users().");</script>";

if($page == "" || $page == "w")
{	
	echo "<title>Watch</title>";
	echo "<script>var single = false;</script>";
	echo "<script>openNavi('-1','watchNavigation');</script>";
	include("search_style.php");
}
else if ($page == "e")
{
	echo "<title>Create</title>";
	include("editor.html");
}
else if ($page == "log")
{
	echo "<title>Users</title>";
	echo "<script>var single = false;</script>";
	echo "<center><div class='userslog'>";
	include("userslog.php");
	echo "</div></center>";
}
else if($page == "an")
{
	echo "<script>var single = true;</script>";
	//echo "Hello ".$_SERVER['QUERY_STRING'];
	include("single.php");
}
?>
</body>
<script>
//changes color
function getRandColor(brightness){
    var rgb = [Math.random() * 256, Math.random() * 256, Math.random() * 256];
    var mix = [brightness, brightness, brightness]; 
    var mixedrgb = [rgb[0] + mix[0], rgb[1] + mix[1], rgb[2] + mix[2]].map(function(x){ return Math.round(x/2.0)})
    return "rgb(" + mixedrgb.join(",") + ")";
  }

var lastrand;
//color the tables

var goodbcolors = ["rgb(46, 98, 116)","rgb(44, 149, 101)"];

if(single)
{
	var singletable=document.getElementsByName("animationtable_single")[0];
	singletable.style.display  = "inline-block";
	singletable.style.padding = "10px";
	singletable.style.margin="10px";
	//singletable.style.boxShadow = "7px 7px 7px black";
	//
	lastrand = goodbcolors[parseInt(Math.random() * 2)];
	singletable.style.backgroundColor = lastrand;
}

var x=document.getElementsByName("animationtable");

for(var i = 0;i<x.length;i++)
{
	var table = x[i];
	//table.style.display  = "inline-block;background-color:white;padding:10px;margin:10px;-webkit-box-shadow: 7px 7px 7px black;-moz-box-shadow: 7px 7px 7px black;box-shadow: 7px 7px 7px black;"
	table.style.display  = "inline-block";
	table.style.padding = "10px";
	table.style.margin="10px";
	table.style.boxShadow = "0px 8px 10px -1px black";
	lastrand = getRandColor(10+Math.random() * 246);
	table.style.backgroundColor = lastrand;
}
if(!single)
{
	var master = document.getElementById("master");
	var randcol1 = "rgb(93, 182, 188)";
	var randcol2 = "rgb(39, 48, 84)";
	//master.style.backgroundColor = randcol1;
	master.style.backgroundAttachment = "fixed";
	master.style.backgroundImage = "-webkit-linear-gradient("+randcol1+" 0%, "+randcol2+" 100%)";
	master.style.backgroundImage = "-moz-linear-gradient("+randcol1+" 0%, "+randcol2+" 100%)";
	master.style.backgroundImage = "-o-linear-gradient("+randcol1+" 0%, "+randcol2+" 100%)";
	master.style.backgroundImage = "linear-gradient("+randcol1+" 0%, "+randcol2+" 100%)";
	master.style.width = "100%";
	master.style.height = "100%";
}
else
{
	var master = document.getElementById("master");
	//master.style.backgroundColor = randcol1;
	master.style.backgroundColor = lastrand;
 	//master.style.backgroundColor = "rgb(46, 98, 116)";
	master.style.width = "100%";
	master.style.height = "100%";
}
//master.style = "background-attachment:fixed;background-image: -webkit-linear-gradient("+randcol1+" 0%, "+randcol2+" 100%); background-image: -moz-linear-gradient("+randcol1+" 0%, "+randcol2+" 100%); background-image: -o-linear-gradient("+randcol1+" 0%, "+randcol2+" 100%); background-image: linear-gradient("+randcol1+" 0%, "+randcol2+" 100%);width: 100%;height: 100%;background-color:"+randcol1+";";
</script>
</html>
