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


$SESSION->save('backto',$_SERVER['QUERY_STRING']);
$voippanel['account'] = true;
$accountid = (int)$_GET['id'];

if ($accountid=='0' || empty($accountid) || is_null($accountid) || !isset($_GET['id'])) {
    unset($accountid);
    header("Location: ?m=hv_accountlist");
}

include(MODULES_DIR.'/hv_account.inc.php');

$layout['pagetitle'] = 'Informacje o koncie VoIP : '.$info['account']['name'];

$SMARTY->assign('listyear',$DB->GetAll('SELECT '.$DB->Year('start_time').' AS rok FROM hv_billing GROUP BY rok ORDER BY rok DESC'));
$SMARTY->display('hv_accountinfo.html');

?>