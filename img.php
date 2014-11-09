<?php
//f for filename
$filename = "ppic/".$_GET['f'];

//We want to display an image
header('Content-type: image/png');

$image = null;

//Get the image file
if(file_exists($filename) == false)
{
	$filename = 'ppic/error';
}
$image = imagecreatefrompng($filename);
list($width, $height) = getimagesize($filename);

$imagesize = $_GET['s'];

$x = 500;
$y = 500;

if($imagesize != '')
{
if($imagesize == '100x100')
{
	$x = 100;
	$y = 100;
}
else if($imagesize == '300x300')
{
	$x = 300;
	$y = 300;
}
else if($imagesize == '500x500')
{
	$x = 500;
	$y = 500;
}
else if($imagesize == '700x700')
{
	$x = 700;
	$y = 700;
}
else if($imagesize == '1000x1000')
{
	$x = 1000;
	$y = 1000;
}
}

//scale the image
//doesnt work
//$image_scaled = imagescale($image, $x, $y, IMG_NEAREST_NEIGHBOUR);
//works
$image_scaled = imagecreatetruecolor($x, $y);
imagecopyresampled($image_scaled, $image, 0, 0, 0, 0, $x, $y, $width, $height);
imagepng($image_scaled,NULL);

//display the new image
imagedestroy($image_scaled);
imagedestroy($image);
?>
