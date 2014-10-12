<center>
<div style="display: table-cell;">
<?php
include("functions.php");

$options = "nothing";
if(isset($_GET['o']))
$options = $_GET['o'];

$limit = "10";

//when there are no options declared, do this:
$command = "Select author_id,title,description,url,filesize,frames,views,thumbnail,comments from animations order by date DESC limit ".$limit.";";

include("ref/mysql_connect.php");

//otherwise, do something of these here:
if($options != "")
{
	if($options == "mv")
	{
		//most views
		$command = "Select author_id,title,description,url,filesize,frames,views,thumbnail,comments from animations order by views DESC limit ".$limit.";";
	}
	else if($options == "l")
	{
		//latest (is default)
	}
	else if($options == "n")
	{
		//name
		$options2 = $_GET['t'];
		if($options2 != "")
		{
			//echo $options2;
			$command = "Select author_id,title,description,url,filesize,frames,views,thumbnail,comments from animations where title LIKE '%".mysqli_real_escape_string($link,$options2)."%' order by views DESC limit ".$limit.";";
			//echo $command;
		}
		
	}
}

//get the animations and display them

$result = mysqli_query($link,$command);

$count = 0;

while ($row = mysqli_fetch_assoc($result)) {
	$count++;
    	$authorid = $row['author_id'];
	$title = $row['title'];
	$description = $row['description'];
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
	
	//display the view
	create_view(360,200,$url,$title,$description,$views,$authorname,$comments,$filesize,$frames,false);
}
mysqli_close($link);

//no animation
if($count == 0)
{
	echo "<div name='animationtable' style='display:inline-block;background-color:white;padding:10px;margin:10px;'>
<table cellpadding='0' cellspacing='0' border='0'  width='auto'>No Results found.</div>";
}
?>
</div>
</center>
