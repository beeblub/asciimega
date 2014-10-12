<?php
//report an animation by url
$url = $_GET['url'];
//d states whether we are finished reporting / hides the form
$d = $_GET['d'];
$reason = $_POST['content'];
if($reason != "" && strlen($reason) > 49)
{
	$errmsg = "Wrong Captcha. Go back and try again.";
	session_start();
   	if(isset($_SESSION['captcha_spam']))
	{
		if($_SESSION['captcha_spam'] == $_POST['captcha'])
		{
			$errmsg = "";
			//when the file exists, we post the report.
			if(file_exists("files/" . $url))
			{
				include("ref/mysql_connect.php");
				$command = "INSERT INTO reports (id, url, content) VALUES (0, '".mysqli_real_escape_string($link,$url)."', '".mysqli_real_escape_string($link,$reason)."');";
				mysqli_query($link,$command);
				mysqli_close($link);

		
				echo "Your report has been sent.";
				echo "<br>Go back to the main page here: <a href='/ascii'>click</a>";
				//must reset captcha
				unset($_SESSION['captcha_spam']);
			}
			else
			$errmsg = "The animation you want to report does not exist. Maybe it has already been removed.";
		}
	}
	if($errmsg != "")
	echo $errmsg;
}
//the form
else if($url != "" && $d == "")
{
	echo "Sooo.. You want to report the animation at ".$url."?";
	echo "<br><br>Please tell us why you want this animation to be removed here:";
	echo "<form action=\"?".$_SERVER['QUERY_STRING']."&d=0\" method=\"POST\">";
	echo "<p>Reason (at least 50 characters):<br><textarea class='contenttext' name='content' type='text' size='30' maxlength='500'></textarea></p>
	  Captcha: (Do not re-open or posting will fail.)<br>
	  <img style='user-drag: none; -moz-user-select: none; -webkit-user-drag: none;' src='captcha/captcha.php' border='0' title='captcha'>
	  <br>Input here:
	  <input type='text' name='captcha' size='5' maxlength='5'>
	  </p>
	  <p><br><input name='submit' type='submit' value='submit'/></p>
	</form>";
}
else
	echo "Nothing here. Maybe your reason was smaller than 50 chars.";
?>
<style>
.contenttext{
background-color:white;
color:black;
width:50%;
height:50%;
}
</style>
