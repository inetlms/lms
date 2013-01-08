<?php

$customer = $LMS->GetCustomer($_GET['cid']);
$division = $DB->GetRow('SELECT * FROM divisions WHERE id = ?', array($customer['divisionid']));
if (!isset($_GET['v']) || empty($_GET['v'])) $_GET['v'] = $LMS->Getcustomertariffsvalue($_GET['cid']);
$img = imagecreatetruecolor(1200,630);
$font = LIB_DIR.'/ezpdf/arial.ttf';
$fontb = LIB_DIR.'/ezpdf/arialbd.ttf';
// def kolory
$white	= imagecolorallocate($img, 255, 255, 255);
$black	= imagecolorallocate($img, 0, 0, 0);

imagefilledrectangle($img,0,0,1200,630,$white);

$FT0100A = imagecreatefrompng('img/FT0100A.png');
$FT0100B = imagecreatefrompng('img/FT0100B.png');

imagecopy($img,$FT0100A,0,10,0,0,imagesx($FT0100A),imagesy($FT0100A));
imagecopy($img,$FT0100B,310,10,0,0,imagesx($FT0100B),imagesy($FT0100B));

//odbiorca
imagettftext($img,8,0,47,135,$black,$font,$division['name']);
imagettftext($img,8,0,47,155,$black,$font,$division['address']);
imagettftext($img,8,0,47,175,$black,$font,$division['zip'].' '.$division['city']);
imagettftext($img,14,0,353,48,$black,$font,$division['name']);
imagettftext($img,14,0,353,100,$black,$font,$division['address'].' '.$division['zip'].' '.$division['city']);

//numerkonta
imagettftext($img,11,0,48,44,$black,$fontb,format_bankaccount($customer['bankaccount']));
$count = strlen($customer['bankaccount']);
for ($i=0; $i < $count; $i++)
imagettftext($img,18,0,355+($i*30),155,$black,$fontb,format_bankaccount($customer['bankaccount'][$i]));

$_GET['v'] = str_replace(',','.',$_GET['v']);
$value = moneyf($_GET['v']);
$width = 0;
for ($i=0; $i<strlen($value); $i++)
{
    $tmp = imagettfbbox(20,0,$fontb,substr($value,$i,1));
    $width += $tmp[2];
}
imageline($img,810,200,1180-$width-10,200,$black);
imagettftext($img,20,0,1180-$width,207,$black,$fontb,$value);


$width = 0;
for ($i=0; $i<strlen($value); $i++)
{
    $tmp = imagettfbbox(12,0,$fontb,substr($value,$i,1));
    $width += $tmp[2];
}
imageline($img,50,224,300-$width-10,224,$black);
imagettftext($img,12,0,300-$width,230,$black,$fontb,$value);


$value = $_GET['v'];
$value = to_words(floor($value)).' '.to_words(round(($value - floor($value)) * 100));
imagettftext($img,16,0,353,255,$black,$fontb,$value.' gr');

imagettftext($img,11,0,50,305,$black,$font,$customer['customername']);
imagettftext($img,11,0,50,322,$black,$font,$customer['address']);
imagettftext($img,11,0,50,340,$black,$font,$customer['zip'].' '.$customer['city']);
imagettftext($img,14,0,353,305,$black,$font,$customer['customername']);
imagettftext($img,14,0,353,358,$black,$font,$customer['address'].' '.$customer['zip'].' '.$customer['city']);

imagettftext($img,14,0,353,410,$black,$font,$_GET['title'].' ID:'.$_GET['cid']);
imagettftext($img,10,0,50,375,$black,$font,$_GET['title']);
header("Content-type: image/png");
imagepng($img);
imagedestroy($img);

?>