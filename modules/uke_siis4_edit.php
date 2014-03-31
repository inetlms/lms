<?php

/*
 *  iNET LMS
 *
 *  (C) Copyright 2012 LMS iNET Developers
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
 *  Sylwester Kondracki Exp $
*/



include(LIB_DIR.'/UKE.class.php');

$idr = (isset($_GET['idr']) ? intval($_GET['idr']) : (isset($_POST['idr']) ? intval($_POST['idr']) : NULL));

$layout['action'] = 'edit';
$layout['pagetitle'] = 'Edycja raportu SIIS v4 za rok '.REPORT_YEAR;

$divinfo = $DB->GetAll('SELECT id,shortname,name FROM divisions ORDER BY name ASC;');
$SMARTY->assign('divinfo',$divinfo);

$rapdata = $DB->GetRow('SELECT * FROM uke WHERE id = ? LIMIT 1;',array($idr));
if ($rapdata['location_city'])
    $rapdata['teryt'] = true;
else
    $rapdata['teryt'] = false;

if ($rapdata['teryt']) {
    $location = '';
    $location .= ($rapdata['city'] ? $rapdata['city'].', ' : '');
    $location .= ($rapdata['street'] ? $rapdata['street'] : '');
    $rapdata['location'] = $location;
    unset($location);
} else 
    $rapdata['location'] = '';

$rapdata['tuck'] = (isset($_GET['tuck']) ? $_GET['tuck'] : (isset($_POST['tuck']) ? $_POST['tuck'] : 'DP'));

$SMARTY->assign('rapdata',$rapdata);

include(MODULES_DIR.'/uke_siis4_xajax.php');

$SMARTY->display('uke_siis4_edit.html');

?>