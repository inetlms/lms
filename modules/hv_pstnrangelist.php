<?php


/*
 * LMS iNET
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
 *  $Id: v 1.00 2012/12/20 22:01:35 Sylwester Kondracki Exp $
 */


$layout['pagetitle'] = 'Lista pól numeracyjnych PSTN';

if (isset($_GET['access'])) {

    $access=strtolower($_GET['access']);

    if (!in_array($access,array('f','t'))) $access='t';

    $id=(int)$_GET['id'];
    $DB->Execute('UPDATE hv_pstnrange SET ussage=? WHERE id=? ;',array($access,$id));

    if (SYSLOG) {
	$tmp = $DB->getone('select description from hv_pstnrange where id =? limit 1;',array($id));
	addlogs(($access=='t' ? 'Włączono' : 'Wyłączono').' Numerację PSTN '.$tmp,'e=up;m=voip');
    }

}

$SMARTY->assign('pstn',$HIPERUS->GetPstnRangeList());
$SMARTY->Display('hv_pstnrangelist.html');

?>