<?php
/*********************************************************************************
*                                                                                *
* (c) 2011 Copyright by Sylwester Kondracki                                      *
* e-mail : sylwek@pati.net.pl                                                    *
* gg     : 6164816                                                               *
*                                                                                *
* $Id: modifier.phone_format.php, v 1.0 2011/07/29 23:30:00 michu_06 Exp $       *
*                                                                                *
**********************************************************************************/
function smarty_modifier_phone_format($string)
{
    $stacjonarne = array(12,13,14,15,16,17,18,22,23,24,25,26,29,30,32,33,34,39,40,41,42,43,44,46,47,48,52,54,55,56,58,59,61,62,63,64,65,67,68,70,71,74,75,76,77,80,81,82,83,84,85,86,87,89,91,94,95);
    
    $format=0;
    $t=array();
    
    if(strlen($string)===7)
	$format=1;
    elseif (strlen($string)===9)
    {
	$t[0]=substr($string,0,2);
	if(in_array($t[0],$stacjonarne)) $format=2;
	else $format=3;
    }
    elseif (strlen($string)===12) $format = 4;
    elseif (strlen($string)===13) $format = 5;
    
    $string=str_replace(',','',$string);
    $string=str_replace(' ','',$string);
    $string=str_replace('(','',$string);
    $string=str_replace(')','',$string);
    $string=str_replace('-','',$string);
    $string=str_replace('/','',$string);
    $str='';
    $t=array();
    
    switch($format)
    {
	case '1' : 
		    $t[]=substr($string,0,3);
		    $t[]=substr($string,3,2);
		    $t[]=substr($string,5,2);
	break;
	
	case '2' :
		    $t[]='('.substr($string,0,2).')';
		    $t[]=substr($string,2,3);
		    $t[]=substr($string,5,2);
		    $t[]=substr($string,7,2);
	break;
	
	case '3' :
		    $t[]=substr($string,0,3);
		    $t[]=substr($string,3,3);
		    $t[]=substr($string,6,3);
	break;
	
	case '4' :
		    $t[]=substr($string,0,3);
		    $t[]=substr($string,3,3);
		    $t[]=substr($string,6,3);
		    $t[]=substr($string,9,3);
	break;
	
	case '5' :
		    $t[]=str_replace('00','+',substr($string,0,4));
		    $t[]=substr($string,4,3);
		    $t[]=substr($string,7,3);
		    $t[]=substr($string,10,3);
	break;
	
	
	default	:
		    $t[]=$string;
	break;
    }
    
    if(count($t)!==0) $str=implode(' ',$t);
    else $str=$string;
    
    return $str;
}
?>