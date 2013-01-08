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


if (!isset($_GET['id']) ) {
    header("Location: ?m=hv_pstnrangelist");
    die();
}

$id = (int)$_GET['id'];
$inf = $DB->GetRow('SELECT range_start, range_end, description FROM hv_pstnrange WHERE id=? LIMIT 1 ;',array($id));
$layout['pagetitle'] = 'Pula numerów : '.$inf['range_start'].' - '.$inf['range_end'].' ( '.$inf['description'].' )';

unset($inf);

$lista = $HIPERUS->GetPSTNInfoList($id);
$count = count($lista);

$SMARTY->assign('lista',$lista);
$SMARTY->assign('count',$count);
$SMARTY->display('hv_pstnusagelist.html');

?>