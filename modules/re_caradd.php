<?php


$layout['pagetitle'] = 'Dodaj nowy pojazd';
$carinfo = array();


$LMS->InitXajax();
include(MODULES_DIR.'/re_car.inc.php');
$SMARTY->assign('xajax',$LMS->RunXajax());

$SMARTY->assign('userlist',$LMS->getusernames());
$SMARTY->assign('cartype',$RE->getdictionarycartypelist(true));
$SMARTY->assign('carinfo',$carinfo);
$SMARTY->display('re_caredit.html');

?>