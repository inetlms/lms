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

$cusid = (int)$_GET['cusid'];

if ( isset($_GET['vprint'])) $vprint = true; 
    else $vprint = false;
    
if ( isset($_GET['extends'])) $extends = true; 
    else $extends = false;
    
if ( isset($_GET['rok']) && !empty($_GET['rok']) ) $rok = (int)$_GET['rok']; 
    else $rok = date('Y',time());
    
if ( isset($_GET['msc']) && !empty($_GET['msc']) ) $msc = (int)$_GET['msc']; 
    else $msc = date('m',time());
    
if ( isset($_GET['terminal']) && !empty($_GET['terminal']) ) $terminal = $_GET['terminal']; 
    else $terminal = $DB->GetOne('SELECT username FROM hv_terminal WHERE customerid=? LIMIT 1 ;',array($cusid));
    
$ctype = (isset($_GET['ctype']) ? $_GET['ctype'] : NULL);
$csuccess = (isset($_GET['csuccess']) ? $_GET['csuccess'] : NULL);

$SMARTY->assign('stvat',((intval(get_conf('hiperus_c5.taxrate',get_conf('phpui.default_taxrate','23')))/100)+1));
$SMARTY->assign('lmscustomer',$HIPERUS->GetLMSCustomerByVoIPID($cusid));
$SMARTY->assign('extends',$extends);
$SMARTY->assign('terminal',$terminal);
$SMARTY->assign('subscription',$HIPERUS->GetSubscriptionByTerminalName($terminal));
$SMARTY->assign('rok',$rok);
$SMARTY->assign('msc',$msc);
$SMARTY->assign('ctype',$ctype);
$SMARTY->assign('csuccess',$csuccess);
$SMARTY->assign('cusid',$cusid);
$billing = $HIPERUS->GetBillingByCustomer($cusid,$rok,$msc,$ctype,$csuccess,$terminal);
$SMARTY->assign('billing',$billing);
$SMARTY->assign('listyear',$DB->GetAll('SELECT '.$DB->Year('start_time').' AS rok FROM hv_billing GROUP BY rok'));
$SMARTY->assign('cusname',$DB->GetOne('SELECT name FROM hv_customers WHERE id=? LIMIT 1;',array($cusid)));
$SMARTY->assign('ctypename',array('incoming'=>'przy','outgoing'=>'wych','internal'=>'wew','disa'=>'disa','vpbx'=>'vpbx','forwarded'=>'prze'));

if ($vprint) {
    include('hv_billinginfoext_print.php');
    $SESSION->close();
    die;
}

$SMARTY->display('hv_billinginfoext.html');

?>