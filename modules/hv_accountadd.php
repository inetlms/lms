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


$layout['pagetitle'] = 'Nowe konto VoIP';
$cusid = ($_GET['cusid'] ? intval($_GET['cusid']) : NULL);
$dane = NULL;

if ($cusid) {
    $tmp = $LMS->GetCustomer($cusid);
    $dane['name'] = $dane['b_name'] = str_replace('"','',$tmp['customername']);
    $address = $street_number = $flat_number = '';

    if ($tmp['address']!='') {
	$tmp1 = explode(' ',$tmp['address']);
	$i = count($tmp1)-1;
	$address = '';
	for ($j=0;$j<$i;$j++) $address .= $tmp1[$j].' ';
	
	if (isset($tmp1[$i])) {
	    $tmp2 = explode('/',$tmp1[$i]);
	    $street_number = $tmp2[0];
	    if (isset($tmp2[1])) $flat_number = $tmp2[1];
	}
    }

    $dane['address'] = $dane['b_address'] = $address;
    $dane['street_number'] = $dane['b_street_number'] = $street_number;
    $dane['flat_number'] = $dane['b_apartment_number'] = $flat_number;
    $dane['postcode'] = $dane['b_postcode'] = $tmp['zip'];
    $dane['city'] = $dane['b_city'] = $tmp['city'];
    $dane['country'] = $dane['b_country'] = 'Polska';
    $dane['email'] = $tmp['email'];

    if ($tmp['type']=='1') {
	$dane['b_nip'] = $tmp['ten'];
	$dane['b_regon'] = $tmp['regon'];
    } else { 
	if ($tmp['ten']=='') $dane['b_nip'] = $tmp['ssn']; 
	else $dane['b_nip'] = $tmp['ten']; 
	$dane['b_regon'] = '';
    }

}

if ( isset($_POST['accountadd']) ) {
    $dane = $_POST['accountadd'];
    if ($idnew=$HIPERUS->AddCustomer($dane)) $SESSION->redirect('?m=hv_accountinfo&id='.$idnew.'');
}

$SMARTY->assign('voip_to_lms',get_conf('hiperus_c5.force_relationship'));
$SMARTY->assign('dane',$dane);
$SMARTY->assign('wlr',get_conf('hiperus_c5.wlr'));
$SMARTY->assign('cusid',$cusid);
$SMARTY->assign('cuslms',$HIPERUS->GetCustomerLMSMinList());
$SMARTY->assign('price',$HIPERUS->GetPriceList());
$SMARTY->display('hv_accountadd.html');

?>