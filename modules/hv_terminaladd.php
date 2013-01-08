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


$cusid = intval($_GET['cusid']);
$account = $HIPERUS->GetCustomer($cusid);
$layout['pagetitle'] = 'Nowy Terminal dla : '.$account['name'];
$dane = array();
$blad = false;

if (isset($_POST['terminaladd'])) {

    $dane = $_POST['terminaladd'];

    if ($HIPERUS->CreateTerminal($dane['customer_id'],$dane['username'],$dane['password'],$dane['id_pricelist'],$dane['screen_numbers'],$dane['t38_fax'],$dane['id_subscription'],$dane['subscription_from'],$dane['subscription_to'],$dane['id_terminal_location'])) {
	$HIPERUS->ImportTerminalList($dane['customer_id']);
	$SESSION->redirect('?m=hv_accountinfo&id='.$dane['customer_id']);
    } else {
	$blad = true;
	$dane['username'] = '';
    }

}

$SMARTY->assign('dane',$dane);
$SMARTY->assign('blad',$blad);
$SMARTY->assign('account',$account);
$SMARTY->assign('price',$HIPERUS->GetPriceList());
$SMARTY->assign('subscription',$HIPERUS->GetSubscriptionList());
$SMARTY->display('hv_terminaladd.html');

?>