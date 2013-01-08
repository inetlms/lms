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


$layout['pagetitle'] = 'Lista kont VoIP';

$SESSION->save('backto',$_SERVER['QUERY_STRING']);

if ( !isset($_GET['hvext']) ) $SESSION->restore('hvext',$hvext); 
    else $hvext = $_GET['hvext']; 
    $SESSION->save('hvext',$hvext);
    
if ( !isset($_GET['hvvat']) ) $SESSION->restore('hvvat',$hvvat); 
    else $hvvat = $_GET['hvvat']; 
    $SESSION->save('hvvat',$hvvat);
    
if ( !isset($_GET['hvpayment']) ) $SESSION->restore('hvpayment',$hvpayment); 
    else $hvpayment = $_GET['hvpayment']; 
    $SESSION->save('hvpayment',$hvpayment);
    
if ( !isset($_GET['hvprice']) ) $SESSION->restore('hvprice',$hvprice); 
    else $hvprice = $_GET['hvprice']; 
    $SESSION->save('hvprice',$hvprice);
    
if ( !isset($_GET['hvlistsort']) ) $SESSION->restore('hvlistsort',$hvlistsort); 
    else $hvlistsort = $_GET['hvlistsort']; 
    $SESSION->save('hvlistsort',$hvlistsort);
    
if ( !isset($_GET['page']) ) $SESSION->restore('hvlp',$_GET['page']);

$filtr = array();
$filtr['hvext'] = $hvext; 
$filtr['hvvat'] = $hvvat; 
$filtr['hvpayment'] = $hvpayment; 
$filtr['hvprice'] = $hvprice; 
$filtr['hvlistsort'] = $hvlistsort;

$lista = $HIPERUS->GetCustomerListList($filtr['hvlistsort'],$filtr);

$listdata['total'] = sizeof($lista);

if ( $total > $listdata['total'] ) $total = $listdata['total'];
$page = (!$_GET['page'] ? 1 : $_GET['page']);
$pagelimit = get_conf('hiperus_c5.accountlist_pagelimit',50);
$start = ($page - 1) * $pagelimit;

$SESSION->save('hvlp',$page);
$SMARTY->assign('pricelist',$HIPERUS->GetPriceList());
$SMARTY->assign('filtr',$filtr);
$SMARTY->assign('lista',$lista);
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('page',$page);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('start',$start);
$SMARTY->display('hv_accountlist.html');

?>