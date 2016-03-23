<?php

/*
 *  iLMS
 *
 *  (C) Copyright 2015 iLMS Developers
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

$layout['pagetitle'] = 'Raporty SIIS ver. '.SIIS_VERSION.' ('.SIIS_REVISION.') rev. '.SIIS_VERCSV;

if (isset($_GET['closed_raport']) && !empty($_GET['closed_raport'])) {
    $DB->Execute('UPDATE uke SET closed=1 WHERE id = ? ;',array(intval($_GET['closed_raport'])));
}

if (isset($_GET['open_raport']) && !empty($_GET['open_raport'])) {
    $DB->Execute('UPDATE uke SET closed=0 WHERE id = ? ;',array(intval($_GET['open_raport'])));
}

if (isset($_GET['del_report']) && isset($_GET['idr']) && isset($_GET['is_sure']) && intval($_GET['idr']) && $_GET['is_sure'] == '1')
{
    $DB->Execute('DELETE FROM uke_data WHERE rapid = ?;',array(intval($_GET['idr'])));
    $DB->Execute('DELETE FROM uke WHERE id = ?;',array(intval($_GET['idr'])));
}

$SMARTY->assign('reportlist',$UKE->getSIISlist());
$SMARTY->display('uke_siis.html');
?>