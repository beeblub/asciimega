<?php
//remove animation by private secret
$secret = $_POST['secret'];

if($secret != "")
{
	include("ref/mysql_connect.php");
	$secret = mysqli_real_escape_string($link,$secret);

	//get id of animation

	$command = "Select id_animation from anonymousrights where authkey='".$secret."';";
	$result = mysqli_query($link,$command);
	
	$resultfound = false;	
	$animationid = -1;
	while ($row = mysqli_fetch_assoc($result)) {
	    $animationid = $row['id_animation'];
	    $resultfound = true;
	}
	//delete the animation when it exists
	if($resultfound == false)
		echo "Sorry. No result found.";
	else
	{
		//get the filename.
		$command = "Select url from animations where id=".$animationid.";";
		$result = mysqli_query($link,$command);
		$animationurl = -1;
		while ($row = mysqli_fetch_assoc($result)) {
		    $animationurl = $row['url'];
		}

		$command = "DELETE FROM anonymousrights where id_animation=".$animationid.";";
		//delete animation
		mysqli_query($link,$command);
		$command = "DELETE FROM animations where id=".$animationid.";";
		mysqli_query($link,$command);

		//delete all comments of this animation
		$command = "DELETE FROM comments where id_animation=".$animationid.";";
		mysqli_query($link,$command);

		//delete the file where the animation is stored
		unlink("files/".$animationurl);

		echo $animationurl."<br>";
		echo "Your animation has been deleted.";
	}
	

	mysqli_close($link);
}

?>
<form enctype="multipart/form-data" action="" method="POST">
    Secret: <input type="text" maxlength="30" name="secret" value=""><br>
    <input type="submit" value="Delete Animation" />
</form>
