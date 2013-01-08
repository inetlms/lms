<?php
/*****************************************
* LMS Hiperus 1.0.0                      *
* (c) 2012 by Sylwester Kondracki        *
*                                        *
* gg : 6164816                           *
* www.sylwester-kondracki.eu             *
* www.lmsdodatki.pl                      *
* email : sylwester.kondracki@gmail.com  *
*                                        *
*****************************************/
function smarty_modifier_seconds_to_hours($seconds)
{
    if ($seconds<=0) return '00:00:00';
    $h = intval($seconds/pow(60,2));
    $m = intval($seconds/60)%60;
    $s = $seconds%60;
    return sprintf("%02d:%02d:%02d",$h,$m,$s);
}
?>