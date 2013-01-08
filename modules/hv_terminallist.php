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


$layout['pagetitle'] = 'Lista terminali';
$filtr=array();

$SESSION->save('backto',$_SERVER['QUERY_STRING']);

if ( !isset($_GET['hvtls']) ) $SESSION->restore('hvtls',$hvtls); 
    else $hvtls = $_GET['hvtls']; 
    $SESSION->save('hvtls',$hvtls);

if ( !isset($_GET['hvtlprice']) ) $SESSION->restore('hvtlprice',$hvtlprice); 
    else $hvtlprice = $_GET['hvtlprice']; 
    $SESSION->save('hvtlprice',$hvtlprice);
    
if ( !isset($_GET['hvtlsubscription']) ) $SESSION->restore('hvtlsubscription',$hvtlsubscription); 
    else $hvtlsubscription = $_GET['hvtlsubscription']; 
    $SESSION->save('hvtlsubscription',$hvtlsubscription);
    
if ( !isset($_GET['page'])) $SESSION->restore('hvtlp',$_GET['page']);

$filtr['hvtls'] = $hvtls;
$filtr['hvtlprice'] = $hvtlprice;
$filtr['hvtlsubscription'] = $hvtlsubscription;

$tmpfiltr=array();
$tmpfiltr['price'] = $hvtlprice;
$tmpfiltr['subscription'] = $hvtlsubscription;

$terminallist = $HIPERUS->GetterminalOneorlist(NULL,NULL,$filtr['hvtls'],$tmpfiltr);
unset($tmpfilt);

$listdata['total'] = sizeof($terminallist);
$total = 100;
if ($total>$listdata['total']) $total = $listdata['total'];
$page = (!$_GET['page'] ? 1 : $_GET['page']);
$pagelimit = get_conf('hiperus_c5.terminallist_pagelimit',50);
$start = ($page - 1) * $pagelimit;
$SESSION->save('hvtlp',$page);
//echo $pagelimit;
$SMARTY->assign('filtr',$filtr);
$SMARTY->assign('terminallist',$terminallist);
$SMARTY->assign('pricelist',$HIPERUS->GetPriceList());
$SMARTY->assign('subscriptionlist',$HIPERUS->GetSubscriptionList());
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('page',$page);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('start',$start);

$SMARTY->display('hv_terminallist.html');

?>