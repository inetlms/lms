<?php
/*********************************************************************************
*                                                                                *
* (c) 2011 Copyright by Sylwester Kondracki                                      *
* www    : www.lms.pati.net.pl  www.lmsdodatki.pl                                *
* e-mail : sylwek@pati.net.pl                                                    *
* gg     : 6164816                                                               *
*                                                                                *
* $Id: modifier.bankaccount_format.php, v 1.0 2011/07/29 23:30:00 michu_06 Exp $ *
*                                                                                *
**********************************************************************************/
function smarty_modifier_bankaccount_format($string){$str='';$t=array();$string=str_replace(' ','',$string);$string=str_replace('-','',$string);$t[0]=substr($string,0,2);$t[1]=substr($string,2,4);$t[2]=substr($string,6,4);$t[3]=substr($string,10,4);$t[4]=substr($string,14,4);$t[5]=substr($string,18,4);$t[6]=substr($string,22,4);$str=implode(' ',$t);return $str;}
?>