<?php

/*
 *  iNET LMS
 *
 *  (C) Copyright 2012-2015 iNET LMS Developers
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
 *
*/

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