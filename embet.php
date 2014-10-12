<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta charset="utf-8"/>
<script src="playanimation.js"></script>
<style>
html{margin: 0;width:100%;height:100%;}
body{margin: 0;width:100%;height:100%;overflow:hidden;}
#animation_win{
position: absolute;width: 100%;
/* Firefox */
height: -moz-calc(100% - 20px);
/* WebKit */
height: -webkit-calc(100% - 20px);
/* Opera */
height: -o-calc(100% - 20px);
/* Standard */
height: calc(100% - 20px);

background-color: black;

-webkit-user-select: none;
-khtml-user-select: none;
-moz-user-select: none;
-o-user-select: none;
user-select: none;

cursor: default;
}
#animation_win_t{
display: table;
position: absolute;
height: 100%;
width: 100%;
}
#animation_win_c{
display: table-cell;
vertical-align: middle;
}
#animation_win_container{
margin-left: auto;
margin-right: auto;
}
#animation_testsize{
position: absolute;
visibility: hidden;
height: auto;
width: auto;
}
#animation_wrapper{
text-align: left;
display: inline-block;
}
#footer1{
position: absolute;
bottom:0px;
width: 100%;
height: 20px;
background-color: gray;
}
#footer1_bar{
width:100%;
height:50px;
background-color:lightgray;
}
#display_preplay{
z-index:1;
opacity:0.5;
position: absolute;
top:0px;
left:0px;
width: 100%;
height:100%;
background-color:black;
}
#animation_wrapper_str
{
position:relative;
color:white;
}

section:-webkit-full-screen
{
	float: none;
	width: 100%;
	height: 100%;
	padding: 0;
	margin: 0;
	border: 0 none;
	background-color: #f00;
}

section:-moz-full-screen
{
	float: none;
	width: 100%;
	height: 100%;
	padding: 0;
	margin: 0;
	border: 0 none;
}

section:-ms-full-screen
{
	float: none;
	width: 100%;
	height: 100%;
	padding: 0;
	margin: 0;
	border: 0 none;
}

section:-o-full-screen
{
	float: none;
	width: 100%;
	height: 100%;
	padding: 0;
	margin: 0;
	border: 0 none;
}

section:full-screen
{
	float: none;
	width: 100%;
	height: 100%;
	padding: 0;
	margin: 0;
	border: 0 none;
}
</style>
<script>
function chfontsize_valid()
{
	var fsize = parseInt(document.getElementById("in_chfontsize").value) || 0;
	//alert(fsize);
	if(fsize > 0)
		return true;
	else
		return false;
}
function chfontsize_check()
{
	var fsize = parseInt(document.getElementById("in_chfontsize").value) || 0;
	//alert(fsize);
	if(fsize > 0)
		changefontsize(fsize+"px");
}

function dofullscreen() {

	if (RunPrefixMethod(document, "FullScreen") || RunPrefixMethod(document, "IsFullScreen")) {
		RunPrefixMethod(document, "CancelFullScreen");
	}
	else {
		RunPrefixMethod(document.getElementById("fullscreen"), "RequestFullScreen");
	}

}

var pfx = ["webkit", "moz", "ms", "o", ""];
function RunPrefixMethod(obj, method) {
	
	var p = 0, m, t;
	while (p < pfx.length && !obj[m]) {
		m = method;
		if (pfx[p] == "") {
			m = m.substr(0,1).toLowerCase() + m.substr(1);
		}
		m = pfx[p] + m;
		t = typeof obj[m];
		if (t != "undefined") {
			pfx = [pfx[p]];
			return (t == "function" ? obj[m]() : obj[m]);
		}
		p++;
	}

}
window.onscroll = function () { window.scrollTo(0, 0); };
</script>

</head>

<body>
<section id="fullscreen">
	<div id="animation_win">
			<div id="animation_win_t">
				<div id="animation_win_c">
					<center>
					<div id = "animation_wrapper_str">
						Downloading Animation..
						<noscript>
						You don't have javascript enabled.  You can not play animations.
						</noscript>
					</div>
					</center>
					<div id="animation_win_container">
						<code id="animation_testsize">
						</code>
						<code id="animation_wrapper">
						</code>
					</div>
				</div>
			</div>
	</div>
	<div id="footer1" onmouseover="this.style.bottom='30px';" onmouseout="this.style.bottom='0px';">
		<div id="progAnimation" style="height:20px"></div>
			<script>fill_with_feedback("progAnimation",0);</script>
			<div id="footer1_bar">
				<table border = "0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
				<td>
				<input  type="button" name="derButton" value="play" onclick="play();">
				</td>
				<td>
				<input  type="button" name="derButton" value="pause" onclick="stop();">
				</td>
				<td>
				<input  type="button" name="derButton" value="reset" onclick="reset();">
				</td>
				<td>
				<div id="animation_info" style="font-size:10px">
				</div>
				</td>
				<td width="100%" style="text-align:right">
				<input title="Change the Fontsize" type="text" name="textfontsize" id="in_chfontsize" maxlength="3" size="3" onkeyup="chfontsize_check()"/>
				<input type="button" name="derButton" value="fullscreen" onclick="dofullscreen();">
				</td>
				</tr>
				</table>
			</div>
		</div>
	</div>

	<div id="display_preplay" onclick="if(donotallowplay == false){clearstart();}">
	</div>

<?php
$animationname = $_GET['a'];

//when there is an error parsing the animationname, then dont allow playing the animation.

$eerror = "<script>donotallowplay = true; document.getElementById('animation_wrapper_str').innerHTML = 'Sorry. The animation does not exist or has been removed.';</script>";
//Name must not be empty
if($animationname == "")
	echo $eerror;
//if name is "%local", then we want to play the locally saved animation
else if($animationname == "%local")
{
	echo "<script>afls();clearstart();</script>";
}
else
{
	//we check whether there is a point in the name
	$pos = strpos(".",$animationname);
	if($pos == false)
	{
		//check whether file exists
		if(file_exists("files/".$animationname))
		{
			//FILE EXISTS

			//start animation
			echo "<script>afu2('".$animationname."');</script>";

			//count the view:
			include("ref/mysql_connect.php");
			$command = "UPDATE animations SET views = views + 1,last_view=CURRENT_TIMESTAMP() WHERE url='".mysqli_real_escape_string($link,$animationname)."';";
			mysqli_query($link,$command);
			mysqli_close($link);

			//ADDITIONAL OPTIONS
			$options = $_GET['o'];
			//autoplay
			if($options == "ap")
			{
				echo "<script>clearstart();</script>";
			}
		}
		else
			echo $eerror;
	}
	else
	echo $eerror;
}


?>
</section>

<script>
var AWx = 0;
var AWy = 0;
//changes the fontsize dynamically.
function onwindowresize()
{
	var Mscreen = document.getElementById("animation_win");
	var Mwx = Mscreen.clientWidth;
	var Mwy = Mscreen.clientHeight;

	if(Mwx != AWx || AWy != Mwy)
	{
		AWx = Mwx;
		AWy = Mwy;
		
		changefontsize((Math.round(getFullScaleFontSize())-1)+"px");
	}
}
var Mcx = null;
var Mcy = null;
var Mfac = null;
//Gets the Font Scale for your screensize
function getFullScaleFontSize()
{
	var Mscreen = document.getElementById("animation_win");
	var Mwx = Mscreen.clientWidth;
	var Mwy = Mscreen.clientHeight;
	var Mwfac = Mwx/Mwy;

	//get font size 1000 measurements
	if(Mcx == null)
	{
		var Mcontainer = document.getElementById("animation_testsize");
		Mcontainer.style.fontSize = "1000px";
		Mcontainer.innerHTML = "X";
		Mcx = Mcontainer.clientWidth;
		Mcy = Mcontainer.clientHeight;
		Mfac = (movx*Mcx)/(Mcy*movy);
	}
	var Mfontsize = 0;
	if(Mwfac > Mfac)
	{
		//frame width larger than format width
		//-> format heigth should fit frame heigth
		Mfontsize = (1000*Mwy)/(Mcy*(movy));
	}
	else
	{
		//frame width smaller (or equal) than format width
		//-> format width should fit frame width
		Mfontsize = (1000*Mwx)/(Mcx*(movx));
	}

	return Mfontsize;
}
window.onresize=  function(){if(chfontsize_valid() == false)onwindowresize();};
//we need to initialize the fontsize first
onwindowresize();
</script>

</body>
</html>
