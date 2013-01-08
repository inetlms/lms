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


$layout['popup'] = true;
$lista['range'] = $HIPERUS->GetPSTNRangeList(true);
$lista['pstn'] = '';

if (count($lista['range'])!==0) {
    if (!isset($_GET['id'])) {
	$lista['pstn'] = $HIPERUS->GetPSTNInfoList($lista['range'][0]['id']);
	$lista['rangeid'] = $lista['range'][0]['id'];
    } else {
	$lista['pstn'] = $HIPERUS->GetPSTNInfoList($_GET['id'],true);
	$lista['rangeid'] = $_GET['id'];
    }
}

if (isset($_GET['oldpstn'])) $lista['oldpstn'] = $_GET['oldpstn']; else $lista['oldpstn'] = '';
$SMARTY->assign('lista',$lista);
$SMARTY->display('hv_searchemptypstn.html');
?>