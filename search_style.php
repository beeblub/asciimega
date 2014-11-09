<?php
include("functions.php");

$options = "l";

if(isset($_GET['o']))
$options = $_GET['o'];

$options_text = $_GET['t'];

$limit = 10;
if(isset($_GET['limit']))
$limit = $_GET['limit'];
if($limit > 20)
$limit = 20;
if($limit < 2)
$limit = 2;

//when there are no options declared, do this:
$command = "";

include("ref/mysql_connect.php");


$animationcount_q = "select count(*) from animations";
$result = mysqli_query($link,$animationcount_q);
$animationcount = 0;
while ($row = mysqli_fetch_assoc($result)) {
	$animationcount = $row['count(*)'];
}

$searchstr = "Result ";

$from = 0;
if(isset($_GET['from']))
	$from = intval($_GET['from']);
if($from > $animationcount)
	$from = 0;
if($from < 0)
	$from = 0;

$to = $from+$limit;
if($to > $animationcount)
	$to = $animationcount;

//otherwise, do something of these here:
	if($options == "mv")
	{
		//most views
		$command = "Select author_id,title,description,url,filesize,frames,views,thumbnail,comments from animations order by views DESC limit $from,$limit;";
		$searchstr .= " for the most viewed animations from place $from to $to of $animationcount.";
	}
	else if($options == "n" && $options_text != "")
	{
		//name
			//echo $options2;
			$command = "Select author_id,title,description,url,filesize,frames,views,thumbnail,comments from animations where title LIKE '%".mysqli_real_escape_string($link,$options_text)."%' order by views DESC limit $from,$limit;";
			//echo $command;
			$searchstr .= " of animations with the name '$options_text': ";
	}
	else
	{
		//latest
		$command = "Select author_id,title,description,url,filesize,frames,views,thumbnail,comments from animations order by date DESC limit $from,$limit;";
		$searchstr .= " for the latest animations from place $from to $to of $animationcount.";
	}

//get the animations and display them

$result = mysqli_query($link,$command);

$opt2string = "";
if($options == "n" && $options_text != "")
{
	$newanimationcount_q = "select count(*) from animations where title LIKE '%".mysqli_real_escape_string($link,$options_text)."%';";
	$result2 = mysqli_query($link,$newanimationcount_q);
	$newanimationcount = 0;

	while ($row = mysqli_fetch_assoc($result2)) {
		$newanimationcount = $row['count(*)'];
	}

	$newto = $to;
	if($newto > $newanimationcount)
		$newto = $newanimationcount;


	$opt2string = "&t=$options_text";
	$searchstr.= " found ".$newanimationcount." in ".$animationcount." animations.";
	$searchstr.= "<br>Showing from ".$from." to ".$newto.".";
	$animationcount = $newanimationcount;
}


if($to < $animationcount)
	$searchstr .= "<br><a href = 'index.php?o=$options$opt2string&from=$to&limit=$limit'>foreward</a>";
if($from > 0)
	$searchstr .= "<br><a href = 'index.php?o=$options$opt2string&from=".($from-$limit)."&limit=$limit'>backward</a>";


echo "<div style='background-color:rgba(255,255,255,0.5);padding:10px;'>$searchstr</div><br>";

$count = 0;

echo "<center>";

while ($row = mysqli_fetch_assoc($result)) {
	$count++;
    	$authorid = $row['author_id'];
	$title = strip_tags($row['title']);
	$description = strip_tags($row['description']);
	$url = $row['url'];
	$filesize = $row['filesize'];
	$frames = $row['frames'];
	$views = $row['views'];
	$thumbnail = $row['thumbnail'];   
	$comments = $row['comments'];
	if($thumbnail == NULL)
		$thumbnail = "";

	$authorname = "Anonymous";

	if($authorid != NULL)
	{
		//Authorname logic
	}
	
	$neededHeight = 360;
	$neededWidth = 200;
	//display the view
	create_view($neededHeight,$neededWidth,$url,$title,$description,$views,$authorname,$comments,$filesize,$frames,false);
}
mysqli_close($link);

//no animation
if($count == 0)
{
	echo "<div name='animationtable'>No Results found.</div>";
}
?>
</center>
