<meta charset="utf-8"/>


<?php
include("ref/mysql_connect.php");
$command = "Select author_id,title,description,url,filesize,frames,views from animations order by date desc limit 10;";
$result = mysqli_query($link,$command);

while ($row = mysqli_fetch_assoc($result)) {
    	$authorid = $row['author_id'];
	$title = $row['title'];
	$description = $row['description'];
	$url = $row['url'];
	$filesize = $row['filesize'];
	$frames = $row['frames'];
	$views = $row['views'];
    	
	$authorname = "Anonymous";	

	if($authorid != -1)
	{
		//Authorname logic
	}
	
	echo "Autor: ".$authorname."<br>Titel: ".$title."<br> Beschreibung:".$description."<br> Dateigröße: ".$filesize." Bytes.<br>Anzahl Frames: ".$frames."<br>Anzahl Views: ".$views."<br><iframe width='360' height='200' src='request?a=".$url."&o=ap' allowFullScreen>Guckst du</iframe><br><br>";
}
?>
