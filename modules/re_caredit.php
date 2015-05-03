<?php

$idc = $_GET['idc'] ? $_GET['idc'] : 0;

if (!$carinfo = $RE->GetCar($idc)) {
    header("Location: ?m=re_carlist");
}



$layout['pagetitle'] = 'Edycja pojazdu : '.$carinfo['dr_d1'].' '.$carinfo['dr_d3'];


$LMS->InitXajax();
include(MODULES_DIR.'/re_car.inc.php');
$SMARTY->assign('xajax',$LMS->RunXajax());

$SMARTY->assign('userlist',$LMS->getusernames());
$SMARTY->assign('cartype',$RE->getdictionarycartypelist(true));
$SMARTY->assign('carinfo',$carinfo);
$SMARTY->display('re_caredit.html');

?>