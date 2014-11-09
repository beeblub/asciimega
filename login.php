<style>
.error{
background-color:rgba(255,0,0,0.5);
}
</style>

<script src="md5.js"></script>
<script>
var md5hash;
function generatehash()
{
	var name = document.getElementById("in_name").value;
	var password = document.getElementById("in_password").value;
	md5hash = MD5(password+","+name);
	document.getElementById("in_password_hash").value = md5hash.substring(0,10)+"..";
	document.getElementById("password_hash").value = md5hash;
}
function reg_generatehash()
{
	var name = document.getElementById("reg_in_name").value;
	var password = document.getElementById("reg_in_password").value;
	md5hash = MD5(password+","+name);
	document.getElementById("reg_in_password_hash").value = md5hash.substring(0,10)+"..";
	document.getElementById("reg_password_hash").value = md5hash;
}
function submit_login()
{
	generatehash();
	document.getElementById("loginform").submit();
}
function submit_register()
{
	reg_generatehash();
	document.getElementById("registerform").submit();
}
function toogle_visibility(id) 
{
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
}
function toogle_form() {
       toogle_visibility("loginform");
       toogle_visibility("registerform");
   }
</script>

<?php
include_once("ref/functions.php");
//logout();
$action = $_GET['o'];
if($action == "logout")
{
	logout();
	echo "<script>window.location = 'login.php';</script>";
}
else if($action == "login" || $action == "register")
{
	//we want to log in
	//check for post data
	$name = $_POST['uname'];
	$pwhash = $_POST['pwhash'];
		
	$dataok = true;
	if(!isset($name) || !isset($pwhash))
	{
		$dataok = false;
	}
	else if(strlen($name) < 4 || strlen($pwhash) < 4)
	{
		$dataok = false;
		echo "<div class='error'>Both, password and username must be at least 4 characters long.</div>";
	}
	
	if($dataok)
	{
		session_start();
		$captchaisok = false;
		if(isset($_SESSION['captcha_spam']))
		{
			if($_SESSION['captcha_spam'] == $_POST['captcha'])
			{
				$captchaisok = true;
			}
			else{
			echo "<div class='error'>Wrong Captcha.</div>";}
		}
		else{
		echo "<div class='error'>There was a problem with the session storing the captcha.</div>";}
		
		if($captchaisok)
		{
			if($action == "login")
			{
				//get the id and return it. Then, set the userdata
				$result = check_exists($name,$pwhash);
				if($result == -1)
				{
					echo "The account does not exist!";
				}
				else
				{
					setuserdata($result,$name);
				}
			}
			else if ($action == "register")
			{
				//register an account.
				create_user($name,$pwhash);
			}
		}
	}
	
}
else if($action == "transferanimation")
{
	$userid = getuserid();

	if($userid != -1)
	{
		$secret = $_POST['secret'];
		if($secret != '')
		{
			include_once("ref/mysql_connect.php");
			$command = "UPDATE animations SET author_id = $userid where animations.id= (SELECT id_animation from anonymousrights where authkey = '".mysqli_real_escape_string($link,$secret)."');";
			$result = mysqli_query($link,$command);

			if(mysqli_affected_rows($link) == 1){
			    echo "Transfer complete.<br>";
			}
			else
			{
				echo "Incorrect secret.<br>";
			}
		}
		else
		echo "Not a valid secret.<br>";
	}
	else
	{
		echo "You need to log in first.<br>";
	}

}
else if($action == "accountupdate")
{
	$description = $_POST['description'];

	include_once("ref/mysql_connect.php");

	$userid = getuserid();

	if($userid != -1 && $description != "" && strlen($description) <= 500)
	{
		$command = "UPDATE users SET description = '".mysqli_real_escape_string($link,$description)."' where id=$userid;";

		$result = mysqli_query($link,$command);

		if(mysqli_affected_rows($link) == 1){
		    echo "Updated description.<br>";
		}
		else
		{
			echo "Somehow did not update description.<br>";
		}
	}
	else
	{
		echo "Did not update description.<br>";
	}

	$fileisok = false;
	$thefile = $_FILES['img'];
	if ($thefile != null)
	{
		if($thefile['error'] == 0)
		{
			if($thefile['size'] < 100000)
			{
				if($thefile["type"] == "image/png")
				{
					$image_info = getimagesize($thefile["tmp_name"]);
					$image_width = $image_info[0];
					$image_height = $image_info[1];
					if($image_width == 20 && $image_height == 20)
					{
						echo "file is ok :D<br>";
						$fileisok = true;
					}	
					else
					{
					echo "The uploaded file has the dimensions:". $image_width ."-".$image_height ."<br> Could not upload.<br>";				}	
				}
				else
				{
				echo "Could not upload file. Unsupported file format: ".$thefile['type']." <br>";
				}
			}
			else
			{
			echo "The uploaded file is too large.<br>";
			}
		}
		else
		{
			echo "Errors with the file upload.<br>";
		}
	}

	if($fileisok && $userid != -1)
	{
		//get actual picture url
		$command = "Select pic from users where id=$userid;";

		$result = mysqli_query($link,$command);
		$picurl = "";

		while ($row = mysqli_fetch_assoc($result)) {
			$picurl = $row['pic'];
		}
		//if basic, create new picture file:
		if($picurl == "a.png")
		{
			$randomfilename = generateRandomString(5);
			while (file_exists("files/" . $randomfilename)) {
			      $randomfilename = generateRandomString(strlen($randomfilename)+1);}
			$picurl = $randomfilename;
		}
		//if not, simply overwrite:
		if(move_uploaded_file($thefile["tmp_name"],"ppic/".$picurl))
		{
			//Update database
			$command = "UPDATE users set pic='$picurl' where id=$userid;";
			$result = mysqli_query($link,$command);
			echo "Updated picture!<br>";
		}
		else
		{
			echo "Error with moving file!<br>";
		}
		
	}

	

	mysqli_close($link);
	echo "<hr>";
}
$myid = getuserid();
if($myid == -1)
{
	echo "<h3>Log in | register</h3>";

	echo "<input type='button' value='switch' action='javascript:void(0)' onclick='toogle_form()'/>";

	echo "<form id='loginform' action=\"?o=login\" method=\"POST\" style=\"display:block;\">";
	echo "<p>Log in here:</p>";
	//echo '<input name="date" type="hidden" value="', time(), '" />';
	echo "Name:<br><input type='text' name='uname' size='15' maxlength='20' id='in_name' onkeyup='generatehash()'  onchange='generatehash()'><br>Password:<br><input type='password' size='10' id='in_password' onkeyup='generatehash()'><br><br>The entered password will never be sent to our servers.<br>Instead you will send this key:<input type='text' size='10' id='in_password_hash' disabled><br>";
	echo "<br>Captcha Input here:<input type='text' name='captcha' size='5' maxlength='5'><br>";
	echo "<input type='button' onclick='submit_login()' value='log in'/></p>";
	echo "<input name='pwhash' type='hidden' id='password_hash'/>";
	echo "</form>";

	echo "<form id='registerform' action=\"?o=register\" method=\"POST\" style=\"display:none;\">";
	echo "<p>Register here:</p>";
	echo "Name:<br><input type='text' name='uname' size='15' maxlength='20' id='reg_in_name' onkeyup='reg_generatehash()' onchange='reg_generatehash()'><br>Password:<br><input type='password' size='10' id='reg_in_password' onkeyup='reg_generatehash()'><br><br>The entered password will never be sent to our servers.<br>Instead you will send this key:<input type='text' size='10' id='reg_in_password_hash' disabled><br></p>";
	echo "<input name='pwhash' type='hidden' id='reg_password_hash'/>";
	echo "Captcha Input here:<input type='text' name='captcha' size='5' maxlength='5'><br>";
	echo "<input type='button' onclick='submit_register()' value='register'/>";
	echo "</form>";

	echo "Captcha: (Do not re-open or posting will fail.)<br>
  <img style='user-drag: none; -moz-user-select: none; -webkit-user-drag: none;' src='captcha/captcha.php' border='0' title='captcha'><br><br>";
}
else
{
	echo "You are logged in as \"".getusername()."\"";
	echo "<br>Log out <a href='login.php?o=logout'>here</a>";

	echo "<hr>Account Settings:<br><br>";
	echo "<form id='accountupdate' action=\"?o=accountupdate\" method=\"POST\" enctype=\"multipart/form-data\"";
	echo "<br>Description:<br><textarea name='description' maxlength='500'></textarea><br>Profile Picture (must be .png and 20x20px):<br>";
	echo "<input name='img' type='file' size='50' maxlength='100000' accept='image/png'><br><br>";
	echo "<input type='submit' value='update profile'/>";
	echo "</form>";
	
	echo "<hr>";
	echo "<form id='accountupdate' action=\"?o=transferanimation\" method=\"POST\" enctype=\"multipart/form-data\"";
	echo "<br>Animation Secret:<br><input type='text' name='secret' maxlength='20'/>";
	echo "<input type='submit' value='transfer ownership'/>";
	echo "</form>";
}
?>
