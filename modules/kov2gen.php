<?php

/*
 *  iNET LMS
 *
 *  (C) Copyright 2012-2015 LMS Developers
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
*/

$msc[1] = 'Styczeń';
$msc[2] = 'Luty';
$msc[3] = 'Marzec';
$msc[4] = 'Kwiecień';
$msc[5] = 'Maj';
$msc[6] = 'Czerwiec';
$msc[7] = 'Lipiec';
$msc[8] = 'Sierpień';
$msc[9] = 'Wrzesień';
$msc[10] = 'Październik';
$msc[11] = 'Listopad';
$msc[12] = 'Grudzień';

function getValue($cid,$year,$month)
{
    global $DB;
    $start = mktime(0,0,0,$month,1,$year);
    $end = mktime(23,59,59,$month,date("t",mktime(0,0,0,$month,1,$year)),$year);
    
    
    $suspension_percentage = f_round(get_conf('finances.suspension_percentage',0));
    $sql =
    'SELECT a.customerid,
	SUM((CASE a.suspended
		WHEN 0 THEN (((100 - a.pdiscount) * (CASE WHEN t.value IS NULL THEN l.value ELSE t.value END) / 100) - a.vdiscount)
		ELSE ((((100 - a.pdiscount) * (CASE WHEN t.value IS NULL THEN l.value ELSE t.value END) / 100) - a.vdiscount) * ' . $suspension_percentage . ' / 100) END)
	* (CASE t.period
	WHEN ' . MONTHLY . ' THEN 1
	WHEN ' . YEARLY . ' THEN 1/12.0
	WHEN ' . HALFYEARLY . ' THEN 1/6.0
	WHEN ' . QUARTERLY . ' THEN 1/3.0
	ELSE (CASE a.period
	    WHEN ' . MONTHLY . ' THEN 1
	    WHEN ' . YEARLY . ' THEN 1/12.0
	    WHEN ' . HALFYEARLY . ' THEN 1/6.0
	    WHEN ' . QUARTERLY . ' THEN 1/3.0
	    ELSE 0 END)
		END)
	) AS value 
	FROM assignments a
	LEFT JOIN tariffs t ON (t.id = a.tariffid)
	LEFT JOIN liabilities l ON (l.id = a.liabilityid AND a.period != ' . DISPOSABLE . ')
	WHERE (a.datefrom <= '.$start.' OR a.datefrom = 0) AND (a.dateto >= '.$end.' OR a.dateto = 0) 
	AND a.customerid = '.$cid.';';


    $tmp = $DB->GetRow($sql);
    $val = str_replace(',','.',sprintf('%01.2f',$tmp['value']));
    return $val;

}

if (isset($_GET['cid']) && isset($_GET['year']) && isset($_GET['month']) &&
    intval($_GET['cid']) && intval($_GET['year']) && intval($_GET['month']) &&
    $LMS->CustomerExists($_GET['cid']) == TRUE)// && getValue(intval($_GET['cid']),intval($_GET['year']),intval($_GET['month'])) > 0)
{
    $customer = $LMS->getcustomer($_GET['cid']);
    $division = $DB->GetRow('SELECT * FROM divisions WHERE id = ? LIMIT 1;',array($customer['divisionid']));
    if (!$value = getValue(intval($_GET['cid']),intval($_GET['year']),intval($_GET['month'])))
	$value = 0;
    $name1 = $name2 = $address = $city = '';
    
    if (!empty($customer['invoice_name']) && !empty($customer['invoice_address']) && !empty($customer['invoice_zip']) && !empty($customer['invoice_city']))
    {
	$name1 = $customer['invoice_name'];
	if (!empty($customer['invoice_lastname']))
	    $name2 = $customer['invoice_lastname'];
	$address = $customer['invoice_address'];
	$city = $customer['invoice_zip'].' '.$customer['invoice_city'];
    } else {
	$name1 = $customer['lastname'];
	$name2 = $customer['name'];
	$address = $customer['address'];
	$city = $customer['zip'].' '.$customer['city'];
    }

    $img = imagecreate(733,320);
    $font = LIB_DIR.'/ezpdf/arial.ttf';
    $fontb = LIB_DIR.'/ezpdf/arialbd.ttf';
    $white = imagecolorallocate($img, 255, 255, 255);
    $black = imagecolorallocate($img, 0, 0, 0);

    imagefilledrectangle($img,0,0,733,320,$white);

    $FT0100 = imagecreatefromjpeg(SYS_DIR.'/img/fbook.jpg');
    imagecopy($img,$FT0100,0,0,0,0,imagesx($FT0100),imagesy($FT0100));
    
    //lewy formularz
    
    imagettftext($img,8,0,28,22,$black,$fontb,format_bankaccount($customer['bankaccount']));
    imagettftext($img,8,0,28,75,$black,$fontb,$division['shortname']);
    imagettftext($img,8,0,28,87,$black,$fontb,$division['address']);
    imagettftext($img,8,0,28,99,$black,$fontb,$division['zip'].' '.$division['city']);
    $width = 0;
    for ($i=0; $i<mb_strlen(moneyf($value)); $i++)
    {
	$tmp = imagettfbbox(9,0,$fontb,substr(moneyf($value),$i,1));
	$width += $tmp[2];
    }
    imageline($img,28,120,200-$width-10,120,$black);
    imagettftext($img,9,0,200-$width,123,$black,$fontb,moneyf($value));
    imagettftext($img,8,0,28,150,$black,$fontb,$name1);
    imagettftext($img,8,0,28,168,$black,$fontb,$name2);
    imagettftext($img,8,0,28,200,$black,$fontb,$address);
    imagettftext($img,8,0,28,218,$black,$fontb,$city);
    
    // prawy formularz
    
    imagettftext($img,9,0,243,23,$black,$fontb,$division['name']);
    imagettftext($img,9,0,243,50,$black,$fontb,$division['address'].', '.$division['zip'].' '.$division['city']);

    $con = substr($customer['bankaccount'],0,2);
    for ($i=0; $i < strlen($con); $i++)
    imagettftext($img,12,0,241+($i*17),76,$black,$fontb,format_bankaccount($con[$i]));
    
    $con = substr($customer['bankaccount'],2,8);
    for ($i=0; $i < strlen($con); $i++)
    imagettftext($img,12,0,277+($i*17),76,$black,$fontb,format_bankaccount($con[$i]));
    
    $con = substr($customer['bankaccount'],10,8);
    for ($i=0; $i < strlen($con); $i++)
    imagettftext($img,12,0,417+($i*17),76,$black,$fontb,format_bankaccount($con[$i]));
    
    $con = substr($customer['bankaccount'],18,8);
    for ($i=0; $i < strlen($con); $i++)
    imagettftext($img,12,0,557+($i*17),76,$black,$fontb,format_bankaccount($con[$i]));
    
    
    $width= mb_strlen($value) * 17;

    for ($i=0; $i<mb_strlen($value);  $i++)
	imagettftext($img,12,0,((710-$width)+($i*17)),101,$black,$fontb,$value[$i]);
    imageline($img,502,95,710-$width-12,95,$black);
    
    $value = trans('$a dollars $b cents', to_words(floor($value)), to_words(round(($value - floor($value)) * 100)));
    imagettftext($img,11,0,243,126,$black,$fontb,$value);
    
    imagettftext($img,9,0,243,152,$black,$fontb,$name1.' '.$name2);
    imagettftext($img,9,0,243,177,$black,$fontb,$address.', '.$city);
    
    imagettftext($img,9,0,243,202,$black,$fontb,'Abonament za '.$msc[intval($_GET['month'])].' '.$_GET['year'].', '.' ID:'.$customer['id']);
    
    header("Content-type: image/jpeg");
    imagejpeg($img);
    imagedestroy($img);
    
}

?>