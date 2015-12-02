#!/usr/bin/php
<?php

/*
 *  iNET LMS version
 *
 *  (C) Copyright 2001-2009 LMS Developers
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
 *  $Id: index.php,v 1.212 2009/10/16 11:01:11 alec Exp $
 
 *  Modyfikacja: Aplikacja IPTV versja 1.2
 *  2011 ITMSOFT
 *  1.2.1 23/08/2011 19:00:00
 
 *  Modyfikacja: Aplikacja IPTV versja 1.2
 *  2014 SGT
 *  1.2.1 23/08/2011 19:00:00 
 
 */

include('/etc/lms/init_lms.php');

if (get_conf('jambox.enabled')) {
    require_once(LIB_DIR.'/LMS.tv.class.php');
    $LMSTV = new LMSTV($DB,$AUTH,$CONFIG);
}

$to_insert = array();

$res = $DB->GetAll('select * from tv_billingevent where docid = 0 or docid is null order by account_id asc');
foreach ($res as $key => $r){
	if (!isset($to_insert[$r['cust_number']])){
		$to_insert[$r['cust_number']] = array();
	}
	//		if ((bool)$DB->GetOne('SELECT id from invoicecontents WHERE hash=?', array(md5($r['group_id'])))){
	//			unset($res[$key]);
	//		} else {
	$to_insert[$r['cust_number']][] = $r;
	//		}
}


foreach ($to_insert as $key => $i){
	$numberplanid = get_conf('jambox.numberplanid',0); # FIXMETVSGT NA PRAWIDŁOWY
	$type = 1;
	$cdate = time();
	//$customerid = $DB->GetOne('SELECT id FROM customers WHERE cust_number = ?', array($i[0]['customerid']));
	$customerid = $i[0]['customerid'];
	$customer = $DB->GetRow('SELECT lastname, name, address, city, zip, ssn, ten, countryid, divisionid, paytime,
				post_name, post_address, post_zip, post_city, invoice_name, invoice_address,
				invoice_zip, invoice_city, invoice_countryid, invoice_ten, invoice_lastname, invoice_ssn 
		FROM customers WHERE id = ? LIMIT 1;',array($customerid));
	
	$division = $DB->GetRow('SELECT name, shortname, address, city, zip, countryid, ten, regon,
				account, inv_header, inv_footer, inv_author, inv_cplace, urllogofile 
				FROM divisions WHERE id = ? LIMIT 1;',array($customer['divisionid']));
	
	$number = $LMS->GetNewDocumentNumber(DOC_INVOICE, $numberplanid, $cdate);
	
	if ($numberplanid)
		    $fullnumber = docnumber($number, $DB->GetOne('SELECT template FROM numberplans WHERE id = ?', array($numberplanid)), $cdate);
		else
		    $fullnumber = NULL;
	
	$invoice['customername'] = $customer['lastname'];
	if (!empty($customer['name']))
	    $invoice['customername'] .= "<br>".$customer['name'];
	    
	
	$invoice['version'] = get_conf('invoices.template_version','1');
	$invoice['templatetype'] = get_conf('invoices.type');
	$invoice['sdateview'] = get_conf('invoices.sdateview');
	
	if ($division['urllogofile'])
	    $invoice['urllogofile'] = $division['urllogofile'];
	else
	    $invoice['urllogofile'] = get_conf('invoices.urllogofile','');
	
	$invoice['templatefile'] = get_conf('invoices.template_file','FT-0100');
	
	
	if (isset($customer['invoice_name']) && isset($customer['invoice_address']) && isset($customer['invoice_zip']) && isset($customer['invoice_city']) && 
		    !empty($customer['invoice_name']) && !empty($customer['invoice_address']) && !empty($customer['invoice_zip']) && !empty($customer['invoice_city']))
		{
		    $invoice['customername'] = $customer['invoice_name'];
		    
		    if (!empty($customer['invoice_lastname']))
			$invoice['customername'] .= "<br>".$customer['invoice_lastname'];
		    
		    $customer['address'] = $customer['invoice_address'];
		    $customer['zip'] = $customer['invoice_zip'];
		    $customer['city'] = $customer['invoice_city'];
		    $customer['countryid'] = ($customer['invoice_countryid'] ? $customer['invoice_countryid'] : 0);
		    $customer['ten'] = ($customer['invoice_ten'] ? $customer['invoice_ten'] : '');
		    $customer['ssn'] = ($customer['invoice_ssn'] ? $customer['invoice_ssn'] : '');
		}
	
	
	$paytime = $customer['paytime'];
	if ($paytime == -1) $paytime = '14';

	$DB->Execute('INSERT INTO documents (number, numberplanid, type, countryid, divisionid, 
		userid, customerid, name, address, zip, city, ten, ssn, cdate, sdate, paytime, paytype,
		div_name, div_shortname, div_address, div_city, div_zip, div_countryid, div_ten, div_regon,
		div_account, div_inv_header, div_inv_footer, div_inv_author, div_inv_cplace, fullnumber,
		
		version, templatetype, templatefile, sdateview, urllogofile,
		post_name, post_address, post_zip, post_city, print_balance_info
		
		) 
		VALUES(?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
		array(
		$number, 
		$numberplanid, 
		$customer['countryid'], 
		$customer['divisionid'], 
		($AUTH->id ? $AUTH->id : 0),
		$customerid, 
		$customer['lastname']." ".$customer['name'], 
		$customer['address'], 
		$customer['zip'],
		$customer['city'], 
		$customer['ten'], 
		$customer['ssn'], 
		$cdate, 
		$cdate, 
		$paytime, 
		2,
		($division['name'] ? $division['name'] : ''),
		($division['shortname'] ? $division['shortname'] : ''),
		($division['address'] ? $division['address'] : ''), 
		($division['city'] ? $division['city'] : ''), 
		($division['zip'] ? $division['zip'] : ''),
		($division['countryid'] ? $division['countryid'] : 0),
		($division['ten'] ? $division['ten'] : ''), 
		($division['regon'] ? $division['regon'] : ''), 
		($division['account'] ? $division['account'] : ''),
		($division['inv_header'] ? $division['inv_header'] : ''), 
		($division['inv_footer'] ? $division['inv_footer'] : ''), 
		($division['inv_author'] ? $division['inv_author'] : ''), 
		($division['inv_cplace'] ? $division['inv_cplace'] : ''),
		($fullnumber ? $fullnumber : NULL),
		($invoice['version'] ? $invoice['version'] : '1'),
		($invoice['templatetype'] ? $invoice['templatetype'] : ''),
		($invoice['templatefile'] ? $invoice['templatefile'] : ''),
		($invoice['sdateview'] ? 1 : 0),
		($invoice['urllogofile'] ? $invoice['urllogofile'] : ''),
		($customer['post_name'] ? $customer['post_name'] : ''),
		($customer['post_address'] ? $customer['post_address'] : ''),
		($customer['post_zip'] ? $customer['post_zip'] : ''),
		($customer['post_city'] ? $customer['post_city'] : ''),
		get_conf('invoices.print_balance_info','1'),
		));

	$iid = $DB->GetLastInsertID('documents');

	$itemid=0;
	foreach($i as $idx => $item) {
	
		$tmptval =  $item['be_vat'] * 100;
		$taxtid = $DB->GetOne("SELECT id FROM taxes WHERE value = $tmptval 
			AND ((validfrom = 0 and validto = 0) 
			or ($cdate >= validfrom AND $cdate <= validto)
			or (validfrom = 0 AND $cdate <= validto)
			or ($cdate >= validfrom AND validto = 0))");	
	
		$itemid++;
		$be_gross 	= str_replace(',','.',$item['be_gross']);

		$DB->Execute("INSERT INTO invoicecontents (docid, value, taxid, prodid,
					content, count, description, tariffid, itemid)
					VALUES (?, ?, ?, '', 'usl.', 1, ?, 'FIXMETVSGT', ".$itemid.")",
					array($iid, $item['be_gross'], $taxtid, $item['be_desc']));

		$tmpval =  str_replace(",",".", $item['be_gross'] * -1);
		//print_r("INSERT INTO cash (time, value, taxid, customerid, comment, docid, itemid) VALUES (".$cdate.", ".$tmpval.", ".$taxtid.", ".$customerid.", '".$item['be_desc']."', ".$iid.", ".$itemid.")");
		
		$DB->Execute("INSERT INTO cash (time, value, taxid, customerid, comment, docid, itemid)
					VALUES (".$cdate.", ".$tmpval.", ?, ".$customerid.", ?, ".$iid.", ".$itemid.")",
					array($taxtid, $item['be_desc']));
					
		//$icd = $DB->GetLastInsertID('invoicecontents');
		$DB->Execute('update tv_billingevent set docid =? where id = ?', array($iid, $item['id']));
	}
}

exit;

?>
