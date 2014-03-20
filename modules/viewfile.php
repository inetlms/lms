<?php

$file = $LMS->GetFileInfo(intval($_GET['id']));


// pliki graficzne
if (in_array($file['filetype'],array('image/jpeg','image/jpg','image/pjpeg','image/gif','image/png')))
{
    header("Content-type: image/gif");
    switch ($file['filetype'])
    {
	case 'image/jpeg'	: header("Content-type: image/jpeg"); $img = imagecreatefromjpeg(UPLOADFILES_DIR."/".$file['filenamesave']);break;
	case 'image/jpg'	: header("Content-type: image/jpg"); $img = imagecreatefromjpeg(UPLOADFILES_DIR."/".$file['filenamesave']);break;
	case 'image/pjpeg'	: header("Content-type: image/pjpeg"); $img = imagecreatefromjpeg(UPLOADFILES_DIR."/".$file['filenamesave']);break;
	case 'image/gif'	: header("Content-type: image/gif"); $img = imagecreatefromgif(UPLOADFILES_DIR."/".$file['filenamesave']); break;
	case 'image/png'	: header("Content-type: image/png"); $img = imagecreatefrompng(UPLOADFILES_DIR."/".$file['filenamesave']); break;
    }
    
    list($width,$height) = getimagesize(UPLOADFILES_DIR."/".$file['filenamesave']);

    $new_width = $new_height = NULL;

    if (isset($_GET['width']) && !isset($_GET['height']))
    {
	$new_width = $_GET['width'];
	$new_height = $height * $new_width / $width;
    }
    elseif (!isset($_GET['width']) && isset($_GET['height']))
    {
	$new_height = $_GET['height'];
	$new_width = $width * $new_height / $height;
    }
    elseif (isset($_GET['width']) && isset($_GET['height']))
    {
	$x = $width / $_GET['width'];
	$y = $height / $_GET['height'];
	if ($y > $x)
	{
	    $new_width = round($width * (1/$y));
	    $new_height = round($height * (1/$y));
	} else {
	
	    $new_width = round($width * (1/$x));
	    $new_height = round($height * (1/$x));
	}
    }
    
    if ($new_width)
    {
	$image_new = imagecreatetruecolor($new_width,$new_height);
	imagecopyresized($image_new, $img, 0,0,0,0, $new_width,$new_height,$width,$height);
	$img = $image_new;
    }
    
    switch ($file['filetype'])
    {
	case 'image/jpeg'	: 
	case 'image/jpg'	: 
	case 'image/pjpeg'	: 
				imagejpeg($img);
	break;
	case 'image/gif'	: imagegif($img); break;
	case 'image/png'	: imagepng($img); break;
    }
}

?>