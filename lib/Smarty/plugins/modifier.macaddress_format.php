<?php

/*********************************************************************************
*                                                                                *
* LMS Magazyn version 2.0.0                                                      *
*                                                                                *
* (c) 2011 Copyright by Sylwester Kondracki                                      *
* www    : www.lms.pati.net.pl  www.lmsdodatki.pl                                *
* e-mail : sylwek@pati.net.pl                                                    *
* gg     : 6164816                                                               *
*                                                                                *
* $Id: modifier.macaddress_format.php, v 1.0 2011/07/29 23:30:00 michu_06 Exp $  *
*                                                                                *
**********************************************************************************/

function smarty_modifier_macaddress_format($string, $spacify_char = ':'){if(strlen($string)!=12)return false;else return implode($spacify_char,str_split(preg_replace('/[^0-9A-F]/','',strtoupper($string)),2));}

?>