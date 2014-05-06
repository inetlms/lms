<?php
global $SMARTY;
for($i=4; $i<11; $i++) $weekdays[] = strftime('%a', $i*86400);
for($i=1; $i<13; $i++) $months[] = strftime('%B', mktime(0,0,0,$i,1,1970));

$SMARTY->assign('months', $months);
$SMARTY->assign('weekdays', $weekdays);
$SMARTY->display('module:calendar.html');
?>
