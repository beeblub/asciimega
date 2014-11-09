<head>
<meta charset="utf-8"/>
</head>
<?php
ini_set('display_errors',1);
error_reporting(E_ERROR);
$id = intval($_GET['id']);
$d = $_GET['d'];
$content = $_POST['content'];
$content_url_animation = $_POST['content_url_animation'];

define ('currtime', time());

$err = "";

if($id != 0)
{
//some time checking
if (!isset($_POST['date'])) { /* ->Spam */ }
elseif (!is_numeric($_POST['date'])) { /* ->Spam */ }
elseif (intval($_POST['date']) > currtime-5) { /* too fast ->Spam */ echo "Were you really that fast? Please slow down."; }
elseif (intval($_POST['date']) < currtime-10*3600) { /* too slow ->Spam */ echo "This document is too old.";}
else 
{ 
	session_start();
	//captcha test
   	if(isset($_SESSION['captcha_spam']))
	{
		if($_SESSION['captcha_spam'] == $_POST['captcha'])
		{
			$content = $_POST['content'];
			if($content != "" && strlen($content) > 4)
			{
				//insert comment
				include("ref/mysql_connect.php");
				include("ref/functions.php");				

				$id_author = 0;
				$res = getuserid();
				if($res != -1)
					$id_author = $res;

				if(file_exists("files/$content_url_animation") == true)
				$command = "INSERT INTO comments (id, id_animation, content,content_url_animation,id_author) VALUES (0, ".mysqli_real_escape_string($link,$id).", '".mysqli_real_escape_string($link,$content)."','".mysqli_real_escape_string($link,$content_url_animation)."',$id_author);";
				else
				$command = "INSERT INTO comments (id, id_animation, content,content_url_animation,id_author) VALUES (0, ".mysqli_real_escape_string($link,$id).", '".mysqli_real_escape_string($link,$content)."',NULL,$id_author);";

				//and add comment to counter
				$command2 = "UPDATE animations SET comments = comments + 1 WHERE id=".mysqli_real_escape_string($link,$id).";";
				$result = mysqli_query($link,$command2);

				if($result && mysqli_affected_rows($link) == 1){
				    mysqli_query($link,$command);
				    $err = "Your comment has been posted.";
				}
				else
				{
				    $err = "Something went wrong. Maybe the animation does not exist.";
				}

				mysqli_close($link);

				//must reset captcha
				unset($_SESSION['captcha_spam']);
			}
			else
			{
				$err = "Your comment was too short. It should be at least 5 characters long.";
				
			}

			

		}
		else
		{
			$err = "Wrong Captcha. Go back and try again.";
		}
	}
	//check captcha
}
}
else 
$err = "Not a valid animation id.";

echo $err."<br>";
//the form
if($id != 0 && $d == "")
{
echo "<br>Id to post to: ".$id;
echo "<form action=\"?".$_SERVER['QUERY_STRING']."&d=0\" method=\"POST\">";
echo '<input name="date" type="hidden" value="', time(), '" />';
echo "<p>Content (at least 5 characters):<br><textarea class='contenttext' name='content' type='text' size='30' maxlength='500'></textarea></p>Animation URL (Optional) <br><input type='text' name='content_url_animation' size='10' maxlength='10'><br>
  Captcha: (Do not re-open or posting will fail.)<br>
  <img style='user-drag: none; -moz-user-select: none; -webkit-user-drag: none;' src='captcha/captcha.php' border='0' title='captcha'>
  <br>Input here:
  <input type='text' name='captcha' size='5' maxlength='5'>
  </p>
  <p><br><input name='submit' type='submit' value='submit'/></p>
</form>";

}
?>
