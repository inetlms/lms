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
 *  $Id: v 1.00 2012/12/22 01:53:35 Sylwester Kondracki Exp $
 */

include(MODULES_DIR.'/infocenter.inc.php');
$layout['popup'] = true;
$layout['pagetitle'] = '<b>Call Center - Zgłoszenie Nr. '.$topic['id'].'/'.sprintf("%04d",$_GET['cid']).'/'.date('Y',$topic['cdate']).'</b>';

if (isset($_GET['cid'])) $layout['pagetitle'] .= '<br><b>klient:</b> '.$LMS->GetCustomerName(intval($_GET['cid']));

$type = $_GET['type'];
$SMARTY->assign('type',$type);

$print = (isset($_GET['print']) ? $_GET['print'] : 'topic');
$SMARTY->assign('print',$print);

$output = $SMARTY->fetch('infocenterprint.html');
html2pdf($output, trans('Reports'), $layout['pagetitle']);

$SMARTY->display('infocenterprint.html');
?>