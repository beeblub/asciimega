<?php
//if not included yet
include_once("tracker.php");

$list = get_userlist();

echo "Users Online: ".count($list);

echo "<br><br>";

$visitors = array ();

foreach ($list as $ident => $item)
{
	if ( isset($visitors[$item[1]]) == false)
		$visitors[$item[1]] = 1;
	else
		$visitors[$item[1]] += 1;
}
echo "<table>";
foreach ($visitors as $page => $visitors)
{
	echo "<tr>";
	echo "<td>";
	echo $page;
	echo "</td>";
	echo "<td>";
	echo $visitors;
	echo "</td>";
	echo "</tr>";
}
echo "</table>";

echo "<br>";
$t = time();
$filename = "nonpublic/log-".date("Y-m-d",$t).".txt";
$zeilen = file ($filename);

$pages = array ();

if (is_array ($zeilen)) {
	echo "Clicks today: ".count($zeilen);
	foreach ($zeilen as $id => $zeile)
	{
		$zeile_ex = explode ('|', $zeile, 2)[0];
		if ( isset($pages[$zeile_ex]) == false)
			$pages[$zeile_ex] = 1;
		else
			$pages[$zeile_ex] += 1;
	}
	$variablestring1 = "";
	$variablestring2 = "";

	$count = 0;
	$maxcount = count($pages);
	foreach ($pages as $page => $num)
	{
		$count++;
		if($count < $maxcount)
		{
			$variablestring1 .= "'".$page."',";
			$variablestring2 .= "".$num.",";
		}
		else
		{
			$variablestring1 .= "'".$page."'";
			$variablestring2 .= "".$num."";
		}
	}
	echo "<div id='logdiv'></div>";
	echo "<script>var usrlog_page = new Array(".$variablestring1.");var usrlog_num = new Array(".$variablestring2.");</script>";
}
?>
<script>
//When data is available, display it!
if(usrlog_page != null)
{
	//sort table
	var maxnum = -1;
	var maxnum_id = -1;
	for(var i = 0;i<usrlog_num.length;i++)
	{
		maxnum = -1;
		maxnum_id = -1;
		for(var n = i;n<usrlog_num.length;n++)
		{
			var num = usrlog_num[n];
			if(num > maxnum)
			{
				maxnum_id = n;
				maxnum = num;
			}
		}
		//swap
		usrlog_num[maxnum_id] = usrlog_num[i];
		usrlog_num[i] = maxnum;

		var tmppage = usrlog_page[maxnum_id];
		usrlog_page[maxnum_id] = usrlog_page[i];
		usrlog_page[i] = tmppage;
		
	}
	//end sort
	
	var contentdiv = document.getElementById('logdiv');

	//create table string
	var contentstring = "<table>";

	for(var i = 0;i<usrlog_page.length;i++)
	{
		contentstring += "<tr>";
		contentstring += "<td>";
		contentstring += "<a href='"+usrlog_page[i]+"'>"+usrlog_page[i]+"</a>";
		contentstring += "</td>";
		contentstring += "<td>";
		contentstring += usrlog_num[i];
		contentstring += "</td>";
		contentstring += "</tr>";
	}
	//print table
	contentdiv.innerHTML = contentstring;
}



</script>
