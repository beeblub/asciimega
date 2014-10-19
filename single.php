<?php
//show single animation
include("functions.php");
$animationurl = $_GET['a'];
if($animationurl != "")
{
	include("ref/mysql_connect.php");
	//get the animation
	$command = "Select id,url,author_id,title,description,filesize,frames,views,thumbnail,comments from animations where url='".mysqli_real_escape_string($link,$animationurl)."';";

	if($animationurl == "%random")
	{
		$offset_result = mysqli_query($link,"SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM animations;");
		$offset_row = mysqli_fetch_object( $offset_result );
		$offset = $offset_row->offset;
		$command = "SELECT id,url,author_id,title,description,filesize,frames,views,thumbnail,comments FROM animations LIMIT $offset, 1 "; 
	}

	$result = mysqli_query($link,$command);

	$count = 0;

	$animationid = NULL;

	$numcomments = 0;

	while ($row = mysqli_fetch_assoc($result)) {
		$count++;
		$animationid = $row['id'];
	    	$authorid = $row['author_id'];
		$title = strip_tags($row['title']);
		$description = strip_tags($row['description']);
		$filesize = $row['filesize'];
		$frames = $row['frames'];
		
		$views = $row['views'];
		$thumbnail = $row['thumbnail'];   
		$comments = $row['comments']; 

		$realurl = $row['url'];
		if($animationurl == "%random")
			$animationurl = $realurl;

		$numcomments = $comments;
		if($thumbnail == NULL)
			$thumbnail = "";

		$authorname = "Anonymous";	
		if($authorid != NULL)
		{
			//Authorname logic
		}

		//display the animation + set the title
		echo "<title>".$title." by ".$authorname."</title>";

		echo "<center>";
		create_view(600,400,$animationurl,$title,$description,$views,$authorname,$comments,$filesize,$frames,true);
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
		$command = "Select id,content,content_url_animation,id_author from comments where id_animation=".mysqli_real_escape_string($link,$animationid)." order by timestamp desc;";

		$result = mysqli_query($link,$command);

		$ccount = 0;

		//size of the preview (video in a comment)
		$width = 300;
		$height = 160;
		
		//comment header (possebility to post comments)
		echo "<center><div style='padding:30px;background-color:rgba(255,255,255,0.3);'>";
		echo "<font style='font-size:30px;'><i>Comments:</i></font><br>";
		echo "<a style='color:blue;' href='postcomment.php?id=".$animationid."'>Write a comment</a><br><br>";
		//display each comment
		while ($row = mysqli_fetch_assoc($result)) {
			$ccount++;
			$animationid = strip_tags($row['id']);
		    	$content = strip_tags($row['content']);
			$content_url_animation = strip_tags($row['content_url_animation']);
			$id_author = strip_tags($row['id_author']);

			$author_name = "";
			if($id_author == NULL)
				$author_name = "Anonymous";

			echo "<div id='comment'>".($numcomments-($ccount-1))." - ".$author_name.":<br>".$content."<br>";
			if($content_url_animation != NULL)
			{
				//check if exists. else: dont add an animation. Easy as that
				echo "<iframe src='request?a=".$content_url_animation."&o=ap' name='animationframe' id='".$content_url_animation."' width='".$width."' height='".$height."' frameborder='0' allowFullScreen>Guckst du</iframe><br><a style='color:black;' href='watch?a=".$content_url_animation."'>$content_url_animation</a>";
			}
			echo "</div><br>";
			
			
		}
		//no comments there
		if($ccount == 0)
		{
			echo "<font style='font-size:20px;'>There are none yet.</font><br>";
		}
		echo "</div></center>";
	}
}
?>
