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

global $invoice,$pdf,$CONFIG,$LMS, $SMARTY;

require_once LIB_DIR.'/mpdf/mpdf.php';
$mpdf=new mPDF('iso-8859-2','A4',0, '', 8, 8, 8, 8, 0, 0);

$text = '';
$cusid = (int)$_GET['cusid'];

if ( isset($_GET['extends']) ) $extends = true; 
    else $extends = false;
    
if ( isset($_GET['rok']) && !empty($_GET['rok']) ) $rok = (int)$_GET['rok']; 
    else $rok = date('Y',time());
    
if ( isset($_GET['msc']) && !empty($_GET['msc']) ) $msc = (int)$_GET['msc']; 
    else $msc = date('m',time());
    
if ( isset($_GET['terminal']) && !empty($_GET['terminal']) ) $terminal = $_GET['terminal']; 
    else $DB->GetOne('SELECT username FROM hv_terminal WHERE customerid=? LIMIT 1 ;',array($cusid));
    
$ctype = (isset($_GET['ctype']) ? $_GET['ctype'] : NULL);
$csuccess = (isset($_GET['csuccess']) ? $_GET['csuccess'] : NULL);
$lmscustomer = $HIPERUS->GetLMSCustomerByVoIPID($cusid);

$SMARTY->assign('stvat',((intval(get_conf('hiperus_c5.taxrate',get_conf('phpui.default_taxrate','23')))/100)+1));
$SMARTY->assign('lmscustomer',$lmscustomer);
$SMARTY->assign('divisions',$DB->GetRow('SELECT name, address, city, zip FROM divisions WHERE id=? LIMIT 1 ;',array($lmscustomer['divisionid'])));
$SMARTY->assign('terminal',$terminal);
$SMARTY->assign('subscription',$HIPERUS->GetSubscriptionByTerminalName($terminal));
$SMARTY->assign('extends',$extends);
$SMARTY->assign('rok',$rok);
$SMARTY->assign('msc',$msc);
$SMARTY->assign('ctype',$ctype);
$SMARTY->assign('csuccess',$csuccess);
$SMARTY->assign('cusid',$cusid);
$SMARTY->assign('miesiace',array('1'=>'styczeń','2'=>'luty','3'=>'marzec','4'=>'kwiecień','5'=>'maj','6'=>'czerwiec','7'=>'lipiec','8'=>'sierpień','9'=>'wrzesień','10'=>'październik','11'=>'listopad','12'=>'grudzień'));
$SMARTY->assign('billing',$HIPERUS->GetBillingByCustomer($cusid,$rok,$msc,$ctype,$csuccess,$terminal));
$SMARTY->assign('listyear',$DB->GetAll('SELECT '.$DB->Year('start_time').' AS rok FROM hv_billing GROUP BY rok'));
$SMARTY->assign('cusname',$DB->GetOne('SELECT name FROM hv_customers WHERE id=? LIMIT 1 ;',array($cusid)));
$SMARTY->assign('ctypename',array('incoming'=>'przy','outgoing'=>'wych','internal'=>'wew','disa'=>'disa','vpbx'=>'vpbx','forwarded'=>'prze'));

$text = $SMARTY->fetch($CONFIG['directories']['smarty_templates_dir'].'/hv_billinginfoext_print.html');
$SMARTY->assign('vprint',false);

$mpdf->autoMarginPadding = 0;
$mpdf->WriteHTML($text);
$mpdf->SetAuthor('iNET LMS Hiperus C5');
$mpdf->Output();

?>