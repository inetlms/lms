<?php

/*
 * LMS version 1.11-git
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
 *  $Id$
 */

$customerid = intval($_GET['id']);

include(MODULES_DIR.'/infocenter.inc.php');
include(MODULES_DIR.'/customer.inc.php');
include(MODULES_DIR.'/customer_phone.inc.php');


if (check_modules(82) && get_conf('voip.enabled',0)) {// nettelekom
	include(MODULES_DIR.'/customer.voip.inc.php');
	$invoicelist = $voip->get_billing_details2($invoicelist);
}

$annex_info = array('section'=>'customer','ownerid'=>$customerid);
$SMARTY->assign('annex_info',$annex_info);

include(MODULES_DIR.'/customer_xajax.inc.php');

$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array('get_list_annex','delete_file_annex'));

$SMARTY->assign('xajax', $LMS->RunXajax());


if($customerinfo['cutoffstop'] > mktime(0,0,0))
        $customerinfo['cutoffstopnum'] = floor(($customerinfo['cutoffstop'] - mktime(23,59,59))/86400);

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$layout['pagetitle'] = trans('Customer Info: $a',$customerinfo['customername']);

foreach ($customerinfo['contacts'] as $a=>$b) {
    $p=SprawdzSiecTelefonu($b['phone']);
    $customerinfo['contacts'][$a]['operator_kod']=$p['kod'];
    $customerinfo['contacts'][$a]['operator_nazwa']=$p['nazwa'];
}

$SMARTY->display('customerinfo.html');


?>
