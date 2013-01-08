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


if (!isset($_GET['cusid'])) $SESSION->redirect('?m=hv_accountlist'); 
    else $customerid = intval($_GET['cusid']);
    
if (!$HIPERUS->GetCustomerExists($customerid)) $SESSION->redirect('?m=hv_accountlist');

$terminallist = $HIPERUS->GetTerminalOneOrList(NULL,$customerid);

if (isset($_POST['pstnadd'])) {
    $dane = $_POST['pstnadd'];
    $dane['id_customer'] = $customerid;
    $HIPERUS->AddPSTNForTerminal($dane);
    $SESSION->redirect('?m=hv_accountinfo&id='.$customerid);
}

$layout['pagetitle'] = 'Konto VoIP : '.$DB->GetOne('SELECT name FROM hv_customers WHERE id=? LIMIT 1 ;',array($customerid));

$SMARTY->assign('terminallist',$terminallist);
$SMARTY->assign('customerid',$customerid);
$SMARTY->display('hv_pstnadd.html');

?>