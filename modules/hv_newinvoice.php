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


$taxeslist = $LMS->GetTaxes();
$currtime=time();

$users_data = unserialize($_POST['users_data']);

$count_u = sizeof($users_data);
print_r($users_data);
for ( $i = 0; $i < $count_u; $i++)
{
	$invoice = array();
	$invoice['cdate'] = $currtime;
	$invoice['sdate'] = $currtime;
	$invoice['paytime_default'] = '1';
	$invoice['customerid'] = $users_data[$i]['id_ext'];
	$customer = $LMS->GetCustomer($invoice['customerid'],true);
	$invoice['numberplanid'] = $users_data[$i]['numberplanid'];

	if($customer['paytime'] != -1) $invoice['paytime'] = $customer['paytime'];
	elseif(($paytime = $DB->GetOne('SELECT inv_paytime FROM divisions WHERE id = ?',array($customer['divisionis']))) !== NULL) $invoice['paytime'] = $paytime;
	else $invoice['paytime'] = $CONFIG['invoices']['paytime'];

	if ($customer['paytype']) $invoice['paytype'] = $customer['paytype'];
	elseif($paytype = $DB->GetOne('SELECT inv_paytype FROM divisions WHERE id = ?',array($customer['divisionid']))) $invoive['paytype'] = $paytype;
	else if (($paytype = intval($CONFIG['invoices']['paytype'])) && isset($PAYTYPES[$paytype])) $invoice['paytype'] = $paytype;

	$invoice['number'] = $LMS->GetNewDocumentNumber(DOC_INVOICE,$invoice['numberplanid'],$invoice['cdate']);
	$invoice['type'] = DOC_INVOICE;
	$iddok = $LMS->AddInvoice(array('customer' => $customer, 'contents' => $users_data[$i]['content'], 'invoice' => $invoice));
} // end for users_data
?>