<?php

/*
 *  iNET LMS
 *
 *  (C) Copyright 2012-2015 iNET LMS Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  Sylwester Kondracki
 *  sylwester.kondracki@gmail.com
 *  gadu-gadu : 6164816
 *
*/

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