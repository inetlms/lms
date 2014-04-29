<?php

/*
 *  iNET LMS
 *
 *  (C) Copyright 2001-2012 LMS Developers
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
 */

$SESSION->save('backto',$_SERVER['QUERY_STRING']);

if (isset($_GET['closed']) && !empty($_GET['closed'])) {
    $RAD->ClosedRadacct($_GET['closed']);
}

$layout['pagetitle'] = 'Statystyki połączeń - ';

if (!isset($_GET['status'])) {
    $SESSION->restore('rad_list_status',$_GET['status']);
}

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status = $_GET['status'];
}


function kick_user($rid)
{
    global $DB,$RAD;
    $obj = new xajaxResponse();
    $result = $RAD->disconnect_user($rid);

    $obj->script("self.location.href='?m=rad_radacct&status=open';");
    return $obj;
}

if ($status == 'open') {
    $layout['pagetitle'] .= 'sesje otwarte';
    $_GET['enddatefrom'] = $_GET['enddateto'] = '';
    $lista = 'list';
} elseif ($status == 'completed') {
    $layout['pagetitle'] .= 'sesje zakończone';
    $lista = 'list1';
} elseif ($status == 'all') {
    $layout['pagetitle'] .= 'wszystkie sesje';
    $lista = 'list2';
} else {
    $layout['pagetitle'] .= 'czeski błąd';
    $_GET['startdatefrom'] = $_GET['startdateto'] = $_GET['enddatefrom'] = $_GET['enddateto'] = '';
    $lista = 'list';
}

$listdata['status'] = $status;
$SESSION->save('rad_list_status',$status);


if (!isset($_GET['zerowe']))
    $SESSION->restore('rad_'.$lista.'_zerowe',$zerowe);
else
    $zerowe = $_GET['zerowe'];
if (empty($zerowe))
    $zerowe = 'all';
$SESSION->save('rad_'.$lista.'_zerowe',$zerowe);

if (!isset($_GET['sessions']))
    $SESSION->restore('rad_'.$lista.'_sessions',$sessions);
else
    $sessions = $_GET['sessions'];
if (empty($sessions))
    $sessions = 'all';
$SESSION->save('rad_'.$lista.'_sessions',$sessions);

if (!isset($_GET['cause']))
    $SESSION->restore('rad_'.$lista.'_cause',$cause);
else
    $cause = $_GET['cause'];
if (empty($cause))
    $cause='all';
$SESSION->save('rad_'.$lista.'_cause',$cause);

if (!isset($_GET['startdatefrom']))
    $SESSION->restore('rad_'.$lista.'_startdatefrom',$startdatefrom);
else
    $startdatefrom = $_GET['startdatefrom'];
$SESSION->save('rad_'.$lista.'_startdatefrom',$startdatefrom);

if (!isset($_GET['startdateto']))
    $SESSION->restore('rad_'.$lista.'_startdateto',$startdateto);
else
    $startdateto = $_GET['startdateto'];
$SESSION->save('rad_'.$lista.'_startdateto',$startdateto);

if (!isset($_GET['enddatefrom']))
    $SESSION->restore('rad_'.$lista.'_enddatefrom',$enddatefrom);
else
    $enddatefrom = $_GET['enddatefrom'];
$SESSION->save('rad_'.$lista.'_enddatefrom',$enddatefrom);

if (!isset($_GET['enddateto']))
    $SESSION->restore('rad_'.$lista.'_enddateto',$enddateto);
else
    $enddateto = $_GET['enddateto'];
$SESSION->save('rad_'.$lista.'_enddateto',$enddateto);

if (!isset($_GET['cid']))
    $SESSION->restore('rad_'.$lista.'_cid',$cid);
else
    $cid = $_GET['cid'];
$SESSION->save('rad_'.$lista.'_cid',$cid);

if (!isset($_GET['nid']))
    $SESSION->restore('rad_'.$lista.'_nid',$nid);
else
    $nid = $_GET['nid'];
$SESSION->save('rad_'.$lista.'_nid',$nid);

if (!isset($_GET['page']))
    $SESSION->restore('rad_'.$lista.'_page',$_GET['page']);


$radlist = $RAD->getradacctlist($status,$zerowe,$sessions,$cause,$startdatefrom,$startdateto,$enddatefrom,$enddateto,$cid,$nid);

$listdata['total'] = sizeof($radlist);
$listdata['zerowe'] = $zerowe;
$listdata['sessions'] = $sessions;
$listdata['cause'] = $cause;
$listdata['startdatefrom'] = $startdatefrom;
$listdata['startdateto'] = $startdateto;
$listdata['enddatefrom'] = $enddatefrom;
$listdata['enddateto'] = $enddateto;
$listdata['cid'] = $cid;
$listdata['nid'] = $nid;
$listdata['searchcustomer'] = ($cid ? $LMS->getcustomername($cid) : '');
$listdata['searchnode'] = ($nid ? $LMS->getnodename($nid) : '');

$page = (! $_GET['page'] ? 1 : $_GET['page']);
$pagelimit = get_conf('radius.page_view',50);
$start = ($page -1) * $pagelimit;

$SESSION->save('rad_'.$lista.'_page',$page);

$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array('kick_user'));
$SMARTY->assign('xajax', $LMS->RunXajax());

$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('page',$page);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('start',$start);
$SMARTY->assign('radlist',$radlist);
$SMARTY->display('rad_radacct.html');
?>