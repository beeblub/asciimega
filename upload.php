
<meta charset="utf-8"/>
<?php

//ini_set('display_errors',1);
//error_reporting(E_ALL);

//upload a file

//no user stuff whatsoever
function upload_animation_anonymous($a_title, $a_description, $a_url, $a_authkey,$a_filesize,$a_frames)
{
	include("ref/mysql_connect.php");
	$command = "INSERT INTO animations (id, author_id, title,description,url,filesize,frames,views,thumbnail,date) VALUES (0, NULL, '".mysqli_real_escape_string($link,$a_title)."','".mysqli_real_escape_string($link,$a_description)."','".$a_url."',".$a_filesize.",".$a_frames.",0,'',CURRENT_TIMESTAMP());";
	//echo $command;
	$worked = true;
	if (!mysqli_query($link,$command)) {
  		$worked = false;
	}
	$lastinsertid = mysqli_insert_id($link);
	//echo "<br>".$lastinsertid;
	$command = "INSERT INTO anonymousrights(id, id_animation, authkey) VALUES (0,".$lastinsertid.",'".$a_authkey."');";
	if($worked)
	{
		if (!mysqli_query($link,$command)) {
  			$worked = false;
		}
	}
	mysqli_close($link);
	return $worked;
}


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$uploaderror = "";


session_start();
if(isset($_SESSION['captcha_spam']))
if($_SESSION['captcha_spam'] == $_POST['captcha'])
{
if($_FILES['anim'] != null)
{
	//echo "File is not null<br>";
	$name = $_FILES['anim']['name'];
        $size = $_FILES['anim']['size'];
	$tmp = $_FILES['anim']['tmp_name'];
	$err = $_FILES['anim']['error'];
	//print_r($_FILES['anim']);
	if($err == 0)
	{
		if($size < 10000000)
		{
			//echo "<br>No error with file upload.<br>";
			$title = $_POST['title'];
			$description = $_POST['description'];
			$tlen = strlen($title);
			$dlen = strlen($description);
			if($tlen > 2 && $tlen <= 30 && $dlen > 2 && $dlen <= 300)
			{
				//we generate a secret
				$secret = generateRandomString(5);
				//modify it
				$secret = $secret.substr(hash("md5",$title.$description.$secret),0,5);

				//get the file content
				$content = file_get_contents($tmp);
				//split new lines
				$arr = preg_split("#\\\\n#",$content);
				//and we check the header
				if($arr[0] == "Header")
				{
					$wholesize = sizeof($arr);
					$heigth = 0;
					$headersize = 0;
					
					$mode = 0;
					//Reading header, if existent.
					for ($i = 1; $i < $wholesize; $i++) {
					    	if($arr[$i] == "Header End")
						{
							$headersize = $i;
							break;
						}
					  	if($mode == 0)
						{
							if($arr[$i] == "Height")
							{
								$mode = 1;
							}
						}
						else
						{
							if($mode == 1)
							{
								$heigth = $arr[$i];
							}
							$mode = 0;
						}
					}
					//some frame checking
					echo $heigth." ".$headersize." ".$wholesize."<br>";
					$tmpsum = $wholesize-($headersize)-2;
					$res = $tmpsum%($heigth);
					echo $res."<br>";
					//frame seems ok
					if($res == 0)
					{
						//we generate a name for the new animation
						$numframes = $tmpsum/$heigth;
						$randomfilename = generateRandomString(5);
						while (file_exists("files/" . $randomfilename)) {
						      $randomfilename = generateRandomString(strlen($randomfilename)+1);}
					
					      //now we upload the animation 

					      if(move_uploaded_file($_FILES["anim"]["tmp_name"],
					      "files/" . $randomfilename))
						{
					      		//echo "Stored in: " . "files/" . $randomfilename."<br>";
							$secret = $randomfilename."_".$secret;
							if(upload_animation_anonymous($title, $description, $randomfilename,$secret,$size,$numframes))
							{
								//yeaaay is uploaded!
								echo "<h1>Congratulations!</h1><br>";
								echo "Your animation is now stored in: ".$randomfilename."<br>";
								echo "<a href='watch?a=".$randomfilename."'>Watch it here.</a><br>";
								echo "You can always delete that animation using your secret key.<br>";
								echo "<br>Secret key: ".$secret."<br>";
								echo "This key also contains the name of your animation. So keep it.";
								//must reset captcha
								unset($_SESSION['captcha_spam']);
			
							}
						}
						else
						{
							$uploaderror = "There was a problem with the File Move.";
						}
						
					}
					else
					{
						$uploaderror = "There was a problem with the Header. ";
					}
				}
				else
				{
					$uploaderror = "File has no header.";
				}
						
			}
			else
			{
				$uploaderror = "Title/Description are not ok.";
			}
			
		}
		else
		{
			$uploaderror = "File is too large.";
		}
	}
}
else
{
	$uploaderror = "No file selected.";
}
}
else
{
	$uploaderror = "You entered the wrong captcha or did not upload anything yet.";
}

if($_FILES['anim']['error'] > 0)
	$uploaderror = "Error with the file.";

if($uploaderror != "")
echo "<br>".$uploaderror;

echo "<br><br>You can upload a file, if you want to:<br><br>"
?>

<script type="text/javascript" language="javascript">
function checkfile(sender) {
    var validExts = new Array(".txt");
    var fileExt = sender.value;
    fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
    if (validExts.indexOf(fileExt) < 0) {
      alert("Invalid file selected, valid files are of " +
               validExts.toString() + " types.");
      sender.value = "";
      return false;
    }
    else return true;
}
</script>

<form enctype="multipart/form-data" action="" method="POST">
    Title: <br><input type="text" size="20" maxlength="30" name="title" value=""><br>
    Description: <br><textarea type="text" size="20" maxlength="300" name="description" value=""></textarea><br>
    <!-- MAX_FILE_SIZE muss vor dem Dateiupload Input Feld stehen -->
    <input type="hidden" name="MAX_FILE_SIZE" value="30000"/><br>
    <!-- Der Name des Input Felds bestimmt den Namen im $_FILES Array -->
    Upload this Animation: <input name="anim" type="file" onchange="checkfile(this);"  /><br><br>
     <p>
  Captcha: (Do not re-open or posting will fail.)<br>
  <img style="user-drag: none; -moz-user-select: none; -webkit-user-drag: none;" src="captcha/captcha.php" border="0" title="captcha">
  <br>Input here:
  <input type="text" name="captcha" size="5" maxlength="5">
  </p>
    <input type="submit" value="Send File" />
</form>

