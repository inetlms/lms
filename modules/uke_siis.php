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

$layout['pagetitle'] = 'Raporty SIIS';

if (isset($_GET['closed_raport']) && !empty($_GET['closed_raport'])) {
    $DB->Execute('UPDATE uke SET closed=1 WHERE id = ? ;',array(intval($_GET['closed_raport'])));
}

if (isset($_GET['open_raport']) && !empty($_GET['open_raport'])) {
    $DB->Execute('UPDATE uke SET closed=0 WHERE id = ? ;',array(intval($_GET['open_raport'])));
}

$SMARTY->assign('reportlist',$UKE->getSIISlist());
$SMARTY->display('uke_siis.html');
?>