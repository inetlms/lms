<?php

/***********************************************************\
*                                                           *
*    LMS Hiperus C5 v.1.0.0                                 *
*    (c)2012 by Sylwester Kondracki                         *
*    Gadu-Gadu : 6164816                                    *
*    E-mail : sylwek@sylwester-kondracki.eu                 *
*                                                           *
*    $Id: billingprint.php 2012/09/01                       *
*                                                           *
\***********************************************************/

$layout['popup'] = true;

global $invoice,$pdf,$CONFIG,$LMS, $SMARTY;

$text = '';
require_once LIB_DIR.'/mpdf/mpdf.php';
$mpdf=new mPDF('iso-8859-2','A4',0, '', 8, 8, 8, 8, 0, 0);
$cusid = (int)$_GET['voipid'];

if (isset($_GET['extends'])) $extends = true; 
    else $extends = false;
    
if (isset($_GET['rok']) && !empty($_GET['rok'])) $rok = (int)$_GET['rok']; 
    else $rok = date('Y',time());
    
if (isset($_GET['msc']) && !empty($_GET['msc'])) $msc = (int)$_GET['msc']; 
    else $msc = date('m',time());
    
if (isset($_GET['terminal']) && !empty($_GET['terminal'])) $terminal = $_GET['terminal']; 
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
$SMARTY->assign('billing',$HIPERUS->GetBillingByCustomer($cusid,$rok,$msc,NULL,NULL,$terminal));
$SMARTY->assign('listyear',$DB->GetAll('SELECT '.$DB->YEAR('start_time').' AS rok FROM hv_billing GROUP BY rok'));
$SMARTY->assign('cusname',$DB->GetOne('SELECT name FROM hv_customers WHERE id=? LIMIT 1 ;',array($cusid)));
$SMARTY->assign('ctypename',array('incoming'=>'przy','outgoing'=>'wych','internal'=>'wew','disa'=>'disa','vpbx'=>'vpbx','forwarded'=>'prze'));

$text = $SMARTY->fetch($CONFIG['directories']['smarty_templates_dir'].'/hv_billinginfoext_print.html');

$SMARTY->assign('vprint',false);

$mpdf->autoMarginPadding = 0;
$mpdf->WriteHTML($text);
$mpdf->SetAuthor('iNET LMS Hiperus C5 v.1.0.0');
$mpdf->Output();

?>