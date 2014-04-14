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
function smarty_modifier_sec_to_time($seconds)
{
    if ($seconds<=0) return '00:00:00';
    
    $days = floor($seconds / 86400);
    $seconds -= ($days * 86400);
    
    
    $hours = floor($seconds / 3600);
    $seconds -= ($hours * 3600);
    
    $minutes = floor($seconds / 60);
    $seconds -= ($minutes * 60);
    
    $second = floor($seconds);
    $seconds -= $second;
    
    if ($days)
	return sprintf("%01dd %02d:%02d:%02d",$days,$hours,$minutes,$second);
    else
	return sprintf("%02d:%02d:%02d",$hours,$minutes,$second);
}
?>