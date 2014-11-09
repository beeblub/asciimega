<?php
//show single animation
include_once("functions.php");
$animationurl = $_GET['a'];
if($animationurl != "")
{
	include("ref/mysql_connect.php");
	//get the animation
	$command = "Select animations.id,url,title,animations.description,filesize,frames,views,thumbnail,comments,name,pic from animations,users where url='".mysqli_real_escape_string($link,$animationurl)."' and users.id = animations.author_id;";

	if($animationurl == "%random")
	{
		$offset_result = mysqli_query($link,"SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM animations;");
		$offset_row = mysqli_fetch_object( $offset_result );
		$offset = $offset_row->offset;
		$command = "SELECT id,url,author_id,title,description,filesize,frames,views,thumbnail,comments,pic FROM animations LIMIT $offset, 1 "; 
	}

	$result = mysqli_query($link,$command);

	$count = 0;

	$animationid = NULL;

	$numcomments = 0;

	while ($row = mysqli_fetch_assoc($result)) {
		$count++;
		$animationid = $row['id'];
	    	$authorname = $row['name'];
		$title = strip_tags($row['title']);
		$description = strip_tags($row['description']);
		$filesize = $row['filesize'];
		$frames = $row['frames'];
		
		$views = $row['views'];
		$thumbnail = $row['thumbnail'];   
		$comments = $row['comments']; 

		$userpicurl = $row['pic']; 

		$realurl = $row['url'];
		if($animationurl == "%random")
			$animationurl = $realurl;

		$numcomments = $comments;
		if($thumbnail == NULL)
			$thumbnail = "";

		//display the animation + set the title
		echo "<title>".$title." by ".$authorname."</title>";

		echo "<center>";
		create_view(600,400,$animationurl,$title,$description,$views,$authorname,$comments,$filesize,$frames,$userpicurl,true);
		echo "<div id='reportdiv'><a href='report.php?url=".$animationurl."'>report</a></div>";
		echo "</center>";
		

		break;
	}
	
	if($count == 0)
	{
		//there is no animation.
		echo "<title>No result</title>";
		echo "<center>";
		create_message("No result found. The animation '".$animationurl."' does not exist or has been deleted.",true);
		echo "<br><font style=\"font-size:100px;\">(╯°□°）╯︵ ┻━┻ </h1>";
		echo "</center>";
	}
	else
	{
		//when the animation exists, we add the comments
		//Select content,content_url_animation,id_author,name from comments,users where comments.id_animation=7 and users.id = comments.id_author order by timestamp desc
		create_commentblock($link,"Select content,content_url_animation,name,pic from comments,users where id_animation=".mysqli_real_escape_string($link,$animationid)." and users.id = comments.id_author order by timestamp desc;",$numcomments,300,160,$animationid);
	}
	mysqli_close($link);
}
?>
