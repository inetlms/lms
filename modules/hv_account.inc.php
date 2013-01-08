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


$pbf = array();

if (!isset($_GET['pbfr']))	$SESSION->restore('pbfr',$pbf['r']);	else $pbf['r'] = $_GET['pbfr'];		$SESSION->save('pbfr',$pbf['r']);
if (!isset($_GET['pbfm']))	$SESSION->restore('pbfm',$pbf['m']);	else $pbf['m'] = $_GET['pbfm'];		$SESSION->save('pbfm',$pbf['m']);
if (!isset($_GET['pbfte']))	$SESSION->restore('pbfte',$pbf['te']);	else $pbf['te'] = $_GET['pbfte'];	$SESSION->save('pbfte',$pbf['te']);
if (empty($pbf['te'])) 
    $pbf['te'] = $DB->GetOne('SELECT username FROM hv_terminal WHERE customerid=? ;',array($accountid));
    
$info['account'] = $HIPERUS->GetCustomer($accountid);
$info['terminal'] = $HIPERUS->GetTerminalOneOrList(NULL,$info['account']['id']);
$info['pstn'] = $HIPERUS->GetPSTNOneOrList(NULL,$info['account']['id']);
$info['billing'] = $HIPERUS -> GetListBillingByCustomer2($info['account']['id'],$pbf['r'],$pbf['m'],$pbf['te']);


$SMARTY->assign('stvat',((intval(get_conf('hiperus_c5.taxrate',get_conf('phpui.default_taxrate','23')))/100)+1));
$SMARTY->assign('pbf',$pbf);
$SMARTY->assign('cuslms',$HIPERUS->GetCustomerLMSMinList($info['account']['ext_billing_id']));
$SMARTY->assign('price',$HIPERUS->GetPriceList());
$SMARTY->assign('subscription',$HIPERUS->GetSubscriptionList());
$SMARTY->assign('miesiace',array('1'=>'styczeń','2'=>'luty','3'=>'marzec','4'=>'kwiecień','5'=>'maj','6'=>'czerwiec','7'=>'lipiec','8'=>'sierpień','9'=>'wrzesień','10'=>'październik','11'=>'listopad','12'=>'grudzień'));

if (!isset($voippanel['account'])) $voippanel['account'] = false;

$SMARTY->assign('voippanel',$voippanel);
$SMARTY->assign('info',$info);

?>