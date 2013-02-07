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


require_once(LIB_DIR.'/HiperusActions.class.php');

class LMSHiperus
{
    var $DB;


    function LMSHiperus(&$DB)
    {
	$this->DB = &$DB;
    }


    private function HP_ChangePSTNNumberData($e_data) {
        $hlib = new HiperusLib();
        $r = new stdClass();
	$r->id_extension = $e_data['id'];
	$r->extension = $e_data['extension'];
	$r->country_code = $e_data['country_code'];
	$r->number = $e_data['number'];
	$r->is_main = ($e_data['is_main'] == 't' ? true : false );
	$r->disa_enabled = ($e_data['disa_enabled'] == 't' ? true : false );
        $r->clir = ($e_data['clir'] == 't' ? true : false );
        $r->virtual_fax = ($e_data['virtual_fax'] == 't' ? true : false );
        $r->terminal_name = $e_data['terminal_name'];
        $r->voicemail_enabled = ($e_data['voicemail_enabled'] == 't' ? true : false );
        $response = $hlib->sendRequest("SaveExtensionData",$r);
        if($response->success===false) {
            throw new Exception("Nie można zapisać danych numeru PSTN. \n".$response->error_message);
        }
        return true;
    }


    function ImportEndUserList($cus=NULL) {
	if (is_null($cus)) $cus = $this->DB->GetAll('SELECT id FROM hv_customers ORDER BY id ASC '); else $cus[0]['id'] = $cus;
	
	$cus_count = count($cus);
	if ($cus_count!==0) {
	    for ($i=0;$i<$cus_count;$i++) {
		$lista = HiperusActions::GetEndUserList($cus[$i]['id']);
	        if (is_array($lista)) $count = count($lista); else $count=0;
		if ($count!==0)
		    for ($j=0;$j<$count;$j++)
			if (!is_null($lista[$j]['id'])) {
			    if (!$this->DB->GetOne('SELECT 1 FROM hv_enduserlist WHERE id=? '.$this->DB->LIMIT(1).' ;',array($lista[$j]['id']))) { 
				$this->DB->Execute('INSERT INTO hv_enduserlist (id,customerid,password,email,admin,vm_count,fax_count,exten_count,vexten_count) VALUES (?,?,?,?,?,?,?,?,?) ;',
			        array(
				    $lista[$j]['id'],
				    $cus[$i]['id'],
				    (!empty($lista[$j]['password']) ? $lista[$j]['password'] : NULL),
				    (!empty($lista[$j]['email']) ? $lista[$j]['email'] : NULL),
				    (!empty($lista[$j]['admin']) ? $lista[$j]['admin'] : 't'),
				    (!empty($lista[$j]['vm_count']) ? $lista[$j]['vm_count'] : NULL),
				    (!empty($lista[$j]['fax_count']) ? $lista[$j]['fax_count'] : NULL),
				    (!empty($lista[$j]['exten_count']) ? $lista[$j]['exten_count'] : NULL),
				    (!empty($lista[$j]['vexten_count']) ? $lista[$j]['vexten_count'] : NULL)
				    ));
			    } else {
				$this->DB->Execute('UPDATE hv_enduserlist SET customerid=?, password=?, email=?, admin=?, vm_count=?, fax_count=?, exten_count=?, vexten_count=? WHERE id=? ; ',
				array(
				    $cus[$i]['id'],
				    (!empty($lista[$j]['password']) ? $lista[$j]['password'] : NULL),
				    (!empty($lista[$j]['email']) ? $lista[$j]['email'] : NULL),
				    (!empty($lista[$j]['admin']) ? $lista[$j]['admin'] : 't'),
				    (!empty($lista[$j]['vm_count']) ? $lista[$j]['vm_count'] : NULL),
				    (!empty($lista[$j]['fax_count']) ? $lista[$j]['fax_count'] : NULL),
				    (!empty($lista[$j]['exten_count']) ? $lista[$j]['exten_count'] : NULL),
				    (!empty($lista[$j]['vexten_count']) ? $lista[$j]['vexten_count'] : NULL),
				    $lista[$j]['id']
				    ));
			    }
			}
	    }
	    unset($lista);
	    unset($count);
	}
	unset($cus);
	unset($cus_count);
    }


    function ImportPriceList() {
	$lista = HiperusActions::GetPriceListList();
	if (is_array($lista)) $count = count($lista); else $count=0;
	if ($count!==0)
	    for ($i=0;$i<$count;$i++)
		if (!is_null($lista[$i]['id']))
		{
		    if (!$this->DB->GetOne('SELECT 1 FROM hv_pricelist WHERE id=? '.$this->DB->Limit('1').' ;',array($lista[$i]['id']))) {
			$this->DB->Execute('INSERT INTO hv_pricelist (id,name,charge_internal_call) VALUES (?,?,?) ;',
				array(
					$lista[$i]['id'],
					(!empty($lista[$i]['name']) ? $lista[$i]['name'] : NULL),
					(!empty($lista[$i]['chare_internal_call']) ? $lista[$i]['charge_internal_call'] : 'f')
				    )
				);
		    } else {
			$this->DB->Execute('UPDATE hv_pricelist SET name=?, ,charge_internal_call=? WHERE id=? ;',
				array(
					(!empty($lista[$i]['name']) ? $lista[$i]['name'] : NULL),
					(!empty($lista[$i]['chare_internal_call']) ? $lista[$i]['charge_internal_call'] : 'f'),
					$lista[$i]['id']
				    )
				);
		    }
		}
	unset($lista);
	unset($count);
    }


    function ImportSubscriptionList() {
	
	$lista = HiperusActions::GetSubscriptionlist();
	
	if (is_array($lista)) $count = count($lista); else $count=0;
	
	if ($count!==0)
	    for ($i=0;$i<$count;$i++)
		if (!is_null($lista[$i]['id']))
		    if (!$this->DB->GetOne('SELECT 1 FROM hv_subscriptionlist WHERE id=? '.$this->DB->Limit('1').' ;',array($lista[$i]['id']))) { 
			$this->DB->Execute('INSERT INTO hv_subscriptionlist (id,name,value,f_dld,f_mobile,f_ild,id_reseller,invoice_value) VALUES (?,?,?,?,?,?,?,?) ;',
			    array(
				    $lista[$i]['id'],
				    (!empty($lista[$i]['name']) ? $lista[$i]['name'] : NULL),
				    (!empty($lista[$i]['value']) ? $lista[$i]['value'] : '0.00'),
				    (!empty($lista[$i]['f_dld']) ? $lista[$i]['f_dld'] : NULL),
				    (!empty($lista[$i]['f_mobile']) ? $lista[$i]['f_mobile'] : NULL),
				    (!empty($lista[$i]['f_ild']) ? $lista[$i]['f_ild'] : NULL),
				    (!empty($lista[$i]['id_reseller']) ? $lista[$i]['id_reseller'] : NULL),
				    (!empty($lista[$i]['invoice_value']) ? $lista[$i]['invoice_value'] : '0.00')
				  )
			);
		    } else {
			$this->DB->Execute('UPDATE hv_subscriptionlist name=?, value=?, f_dld=?, f_mobile=?, f_ild=?, id_reseller=?, invoice_value=? WHERE id=? ;',
			    array(
				    (!empty($lista[$i]['name']) ? $lista[$i]['name'] : NULL),
				    (!empty($lista[$i]['value']) ? $lista[$i]['value'] : '0.00'),
				    (!empty($lista[$i]['f_dld']) ? $lista[$i]['f_dld'] : NULL),
				    (!empty($lista[$i]['f_mobile']) ? $lista[$i]['f_mobile'] : NULL),
				    (!empty($lista[$i]['f_ild']) ? $lista[$i]['f_ild'] : NULL),
				    (!empty($lista[$i]['id_reseller']) ? $lista[$i]['id_reseller'] : NULL),
				    (!empty($lista[$i]['invoice_value']) ? $lista[$i]['invoice_value'] : '0.00'),
				    $lista[$i]['id']
				  )
			);
		    }
	unset($lista);
	unset($count);
    }


    private function insertbilling($lista,$cusid,$success)
    {
	if (is_array($lista)) $count = count($lista); else $count=0;
	if ($count!==0) for ($j=0;$j<$count;$j++)
	{
		if (!$this->DB->GetOne('SELECT 1 FROM hv_billing WHERE id=? '.$this->DB->Limit('1').';',array($lista[$j]['id']))) { //wstawiamy dane
		    $this->DB->Execute('INSERT INTO hv_billing (id,customerid,rel_cause,start_time,start_time_unix,customer_name,terminal_name,ext_billing_id,caller,bill_cpb,duration,calltype,
			    country,description,operator,type,cost,price,init_charge,reseller_price,reseller_cost,reseller_init_charge,margin,subscription_used,platform_type,success_call) 
			    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ;',
			    array(
				    $lista[$j]['id'],
				    $cusid,
				    $lista[$j]['rel_cause'],
				    $lista[$j]['start_time'],
				    strtotime($lista[$j]['start_time']),
				    $lista[$j]['customer_name'],
				    (!empty($lista[$j]['terminal_name'])		? $lista[$j]['terminal_name'] 		: NULL), 
				    (!empty($lista[$j]['ext_billing_id'])		? $lista[$j]['ext_billing_id'] 		: 0),
				    (!empty($lista[$j]['caller'])			? $lista[$j]['caller']			: NULL),
				    (!empty($lista[$j]['bill_cpb'])			? $lista[$j]['bill_cpb']		: NULL),
				    (!empty($lista[$j]['duration'])			? $lista[$j]['duration']		: 0),
				    (!empty($lista[$j]['calltype'])			? $lista[$j]['calltype']		: NULL),
				    (!empty($lista[$j]['country'])			? $lista[$j]['country']			: NULL),
				    (!empty($lista[$j]['description'])			? $lista[$j]['description']		: NULL),
				    (!empty($lista[$j]['operator'])			? $lista[$j]['operator']		: NULL),
				    (!empty($lista[$j]['type'])				? $lista[$j]['type']			: NULL),
				    (!empty($lista[$j]['cost'])				? $lista[$j]['cost']			: 0),
				    (!empty($lista[$j]['price'])			? $lista[$j]['price']			: 0),
				    (!empty($lista[$j]['init_charge'])			? $lista[$j]['init_charge']		: 0),
				    (!empty($lista[$j]['reseller_price'])		? $lista[$j]['reseller_price']		: 0),
				    (!empty($lista[$j]['reseller_cost'])		? $lista[$j]['reseller_cost']		: 0),
				    (!empty($lista[$j]['reseller_init_charge'])		? $lista[$j]['reseller_init_charge']	: 0),
				    (!empty($lista[$j]['margin'])			? $lista[$j]['margin']			: 0),
				    $lista[$j]['subscription_used'],
				    (!empty($lista[$j]['platform_type'])		? $lista[$j]['platform_type']		: NULL),
				    $success
				  )
			);
		} 
	}
    }


    function ImportBilling($from=NULL,$to=NULL,$success='yes',$type='outgoing',$cus=NULL,$quiet=true) {
	
	if (is_null($from)||is_null($to)) { 
	    $from = date('Y-m-d',time()); 
	    $to = $from;
	}

	$success = strtolower($success);if (!in_array($success,array('all','yes','no'))) $success='yes';
	$type = strtolower($type);

	if (!in_array($type,array('all','incoming','outgoing','disa','forwarded','internal','vpbx'))) $type='outgoing';

	if (is_null($cus)){
	    $cus = $this->DB->GetAll('SELECT id, create_date, name FROM hv_customers ORDER BY id ASC');
	    $cus_count = count($cus);
	    if ($cus_count!==0) for ( $i=0; $i<$cus_count; $i++) $cus[$i]['create_date'] = substr($cus[$i]['create_date'],0,10);
	}
	else { 
	    $cus[0]['id'] = $cus;
	    $cus_count = count($cus); 
	}

	for ($i=0;$i<$cus_count;$i++) {

	    if (!$quiet) fwrite(STDOUT,"Pobieram dla : ".$cus[$i]['name']." ");

	    if ($success==='yes' || $success==='all') {
		    
		    if ($type==='incoming' || $success==='all') {
			if(!$quiet) fwrite(STDOUT,".");
			$this->insertbilling(
			    HiperusActions::Getbilling($from,$to,null,null,true,$cus[$i]['id'],'incoming'),
			    $cus[$i]['id'],
			    't');
		    }
		    
		    if ($type==='outgoing' || $success==='all') {
			if(!$quiet) fwrite(STDOUT,".");
			$this->insertbilling(HiperusActions::Getbilling($from,$to,null,null,true,$cus[$i]['id'],'outgoing'),$cus[$i]['id'],'t');
		    }
		    
		    if ($type==='disa' || $success==='all') {
			if(!$quiet) fwrite(STDOUT,".");
			$this->insertbilling(HiperusActions::Getbilling($from,$to,null,null,true,$cus[$i]['id'],'disa'),$cus[$i]['id'],'t');
		    }
		    
		    if ($type==='forwarded' || $success==='all') {
			if(!$quiet) fwrite(STDOUT,".");
			$this->insertbilling(HiperusActions::Getbilling($from,$to,null,null,true,$cus[$i]['id'],'forwarded'),$cus[$i]['id'],'t');
		    }
		    
		    if ($type==='internal' || $success==='all') {
			if(!$quiet) fwrite(STDOUT,".");
			$this->insertbilling(HiperusActions::Getbilling($from,$to,null,null,true,$cus[$i]['id'],'internal'),$cus[$i]['id'],'t');
		    }
		    
		    if ($type==='vpbx' || $success==='all') {
			if(!$quiet) fwrite(STDOUT,".");
			$this->insertbilling(HiperusActions::Getbilling($from,$to,null,null,true,$cus[$i]['id'],'vpbx'),$cus[$i]['id'],'t');
		    }
	    
	    }


	    if ($success==='no' || $success==='all') {
	    
		    if ($type==='incoming' || $success==='all') {
			if(!$quiet) fwrite(STDOUT,".");
			$this->insertbilling(HiperusActions::Getbilling($from,$to,null,null,false,$cus[$i]['id'],'incoming'),$cus[$i]['id'],'f');
		    }
		    
		    if ($type==='outgoing' || $success==='all') {
			if(!$quiet) fwrite(STDOUT,".");
			$this->insertbilling(HiperusActions::Getbilling($from,$to,null,null,false,$cus[$i]['id'],'outgoing'),$cus[$i]['id'],'f');
		    }
		    
		    if ($type==='disa' || $success==='all') {
			if(!$quiet) fwrite(STDOUT,".");
			$this->insertbilling(HiperusActions::Getbilling($from,$to,null,null,false,$cus[$i]['id'],'disa'),$cus[$i]['id'],'f');
		    }
		    
		    if ($type==='forwarded' || $success==='all') {
			if(!$quiet) fwrite(STDOUT,".");
			$this->insertbilling(HiperusActions::Getbilling($from,$to,null,null,false,$cus[$i]['id'],'forwarded'),$cus[$i]['id'],'f');
		    }
		    
		    if ($type==='internal' || $success==='all') {
			if(!$quiet) fwrite(STDOUT,".");
			$this->insertbilling(HiperusActions::Getbilling($from,$to,null,null,false,$cus[$i]['id'],'internal'),$cus[$i]['id'],'f');
		    }
		    
		    if ($type==='vpbx' || $success==='all') {
			if(!$quiet) fwrite(STDOUT,".");
			$this->insertbilling(HiperusActions::Getbilling($from,$to,null,null,false,$cus[$i]['id'],'vpbx'),$cus[$i]['id'],'f');
		    }
	    }
	
	    if (!$quiet) fwrite(STDOUT,"\n");
	}
    }


    function ImportBillingToFile($from,$to)  {
	
	$lista = HiperusActions::GetbillingFile($from,$to);
	
    }


    function AllImportBilling($from=NULL,$to=NULL) {

	$lista = HiperusActions::Getbilling($from,$to);

    }
    
    
    function GetCustomerList() {
    
	return $this->DB->GetAll('SELECT * FROM hv_customers');
	
    }
    
    
    function GetCustomerListList($sort=NULL,$filtr=NULL)
    {
	
	if (is_null($sort)) $sort = ' ORDER BY hv.name ASC';
	else
	{
	    switch ($sort)
	    {
		case 'name,asc'		: $sort = ' ORDER BY hv.name ASC';	break;
		case 'name,desc'	: $sort = ' ORDER BY hv.name DESC';	break;
		default 		: $sort = ' ORDER BY hv.name ASC';	break;
	    }
	}
	$hvext = $hvvat = $hvpayment = $hvprice = $extid = '';
	if (!is_null($filtr) && is_array($filtr))
	{
	    if (isset($filtr['hvext'])) { 
		switch ($filtr['hvext'])
		{
		    case '1'		: $hvext = ' AND hv.ext_billing_id!=0'; break;
		    case '2'		: $hvext = ' AND ( hv.ext_billing_id=0 OR hv.ext_billing_id IS NULL) '; break;
		    default		: $hvext = ''; break;
		}
	    } else $hvext = '';
	    
	    if (isset($filtr['hvvat'])) {
		switch ($filtr['hvvat'])
		{
		    case 'none'		: $hvvat = ' AND ha.keyvalue=\'0\' '; break;
		    case 'hiperus'	: $hvvat = ' AND ha.keyvalue=\'1\' '; break;
		    case 'lms'		: $hvvat = ' AND ha.keyvalue=\'2\' '; break;
		    default		: $hvvat = ''; break;
		}
	    } else $hvvat = '';
	    
	    if (isset($filtr['hvpayment'])) {
		switch ($filtr['hvpayment'])
		{
		    case 'postpaid'	: $hvpayment = ' AND hv.payment_type=\'postpaid\' '; break;
		    case 'prepaid'	: $hvpayment = ' AND hv.payment_type=\'prepaid\' '; break;
		    default		: $hvpayment = ''; break;
		}
	    } else $hvpayment = '';
	    
	    if (isset($filtr['hvprice'])) {
		{
		    if ($filtr['hvprice'] == 'noprice') $hvprice = ' AND id_default_pricelist IS NULL ';
		    elseif ($filtr['hvprice'] == '') $hvprice = ' ';
		    else $hvprice = ' AND hv.id_default_pricelist = '.$filtr['hvprice'].' ';
		}
	    } else $hvprice = '';
	    
	    if (isset($filtr['extid']))
	    {
		$extid = ' AND hv.ext_billing_id = '.$filtr['extid'].' ';
	    }
	    else $extid = '';
	}
	$sql = 'SELECT 
		hv.id, hv.name, hv.address, hv.street_number, hv.apartment_number, hv.postcode, hv.city, hv.ext_billing_id ,hv.id_default_pricelist ,
		hv.payment_type, hv.active, 
		c.id AS cid, c.lastname AS clastname, c.name AS cname, c.address AS caddress, c.zip AS czip, c.city AS ccity,'
		.' COALESCE( (SELECT hv_pricelist.name FROM hv_pricelist WHERE hv_pricelist.id = hv.id_default_pricelist '.$this->DB->Limit('1').'),NULL) AS price_name, '
		.' COALESCE((SELECT COUNT(*) FROM hv_pstn WHERE hv_pstn.customerid=hv.id),0) AS pstncount, '
		.' COALESCE((SELECT COUNT(*) FROM hv_terminal WHERE hv_terminal.customerid=hv.id),0) AS terminalcount, '
		.' ha.keyvalue AS invoice '
		.' FROM hv_customers AS hv '
		.' LEFT JOIN hv_assign AS ha ON (ha.customerid = hv.id) '
		.' LEFT JOIN customers AS c ON (c.id = hv.ext_billing_id) '
		.' WHERE 1=1 '
		.$hvext
		.$hvvat
		.$hvpayment
		.$hvprice
		.$extid
		.($sort ? $sort : '')
		.' ;';
	return $this->DB->GetAll($sql);
	
    }
    
    
    
    function GetCustomerLMSMinList($id=NULL)
    {
	if (is_null($id))
	return $this->DB->GetAll('SELECT id, lastname, name, email, address,zip,city,ten,ssn,regon,post_address,post_zip,post_city FROM customers WHERE deleted=0 AND status=3  ORDER BY lastname,name ASC  ;');
	else
	return $this->DB->GetRow('SELECT id, lastname, name, email, address,zip,city,ten,ssn,regon,post_address,post_zip,post_city FROM customers WHERE  id='.$id.' ;');
    }
    
    function GetPriceList()
    {
	return $this->DB->GetAll('SELECT * FROM hv_pricelist ;');
    }
    
    function GetSubscriptionList()
    {
	return $this->DB->GetAll('SELECT * FROM hv_subscriptionlist ;');
    }


    function GetListBillingByCustomer($customerid,$rok=NULL,$msc=NULL,$calltype=NULL,$callsuccess=NULL,$terminal=NULL)
    {
	$call_success = NULL;
	if (!is_null($terminal) && empty($terminal)) $terminal=NULL;
	if (!is_null($rok) && empty($rok)) $rok=NULL;
	if (!is_null($msc) && empty($msc)) $msc=NULL;
	if (!is_null($calltype) && empty($calltype)) $calltype=NULL;
	if (!is_null($callsuccess) && empty($callsuccess)) $callsuccess=NULL;
	if (!is_null($callsuccess))
	{
	    if (is_bool($callsuccess)===true)
	    {
		if ($callsuccess===true) $call_success='t';
		elseif ($callsuccess===false) $call_success='f';
		else $call_success = NULL;
	    }
	    elseif (is_string($callsuccess)===true)
	    {
		if (strtolower($callsuccess)=='t') $call_success='t';
		elseif (strtolower($callsuccess)=='f') $call_success='f';
		else $call_success=NULL;
	    }
	    else $call_success = NULL;
	}
	    else $call_success = NULL;
	    
	$zap = 'SELECT 
		SUM(cost) AS cost, SUM(init_charge) AS init_charge, SUM(reseller_cost) AS reseller_cost, SUM(reseller_init_charge) AS reseller_init_charge, '.$this->DB->month('start_time').' AS msc, '.$this->DB->year('start_time').' AS rok 
		FROM hv_billing 
		WHERE customerid='.$customerid.' '
		.(!is_null($rok) ? ' AND '.$this->DB->year('start_time').' = \''.$rok.'\' ' : '')
		.(!is_null($msc) ? ' AND '.$this->DB->month('start_time').' = \''.$msc.'\' ' : '')
		.(!is_null($calltype) ? ' AND calltype=\''.$calltype.'\' ' : '') 
		.(!is_null($call_success) ? ' AND success_call = \''.$call_success.'\' ' : '')
		.(!is_null($terminal) ? ' AND terminal_name = \''.$terminal.'\' ': '')
		.' GROUP BY  msc, rok ORDER BY rok DESC, msc DESC ;';
	return $this->DB->GetAll($zap);
    }
    

    
    
    function GetListBillingByCustomer2($customerid,$rok=NULL,$msc=NULL,$terminal=NULL)
    {
	$call_success = NULL;
	if (!is_null($terminal) && empty($terminal)) $terminal=NULL;
	if (!is_null($rok) && empty($rok)) $rok=NULL;
	if (!is_null($msc) && empty($msc)) $msc=NULL;
	$abonament = $this->GetSubscriptionByTerminalName($terminal);
	$zap = 'SELECT 
		SUM(b.cost) AS cost, 
		SUM(b.init_charge) AS init_charge, 
		SUM(b.reseller_cost) AS reseller_cost, 
		SUM(b.reseller_init_charge) AS reseller_init_charge, '
		.' '.$this->DB->month('b.start_time').' AS msc,'
		.''.$this->DB->year('b.start_time').' as rok, '
		.'\''.$abonament.'\' AS subscription 
		FROM hv_billing b 
		WHERE customerid='.$customerid.' '
		.(!is_null($terminal) ? ' AND terminal_name = \''.$terminal.'\' ': '')
		.(!is_null($rok) ? ' AND '.$this->DB->YEAR('start_time').' = \''.$rok.'\' ' : '')
		.(!is_null($msc) ? ' AND '.$this->DB->MONTH('start_time').' = \''.$msc.'\' ' : '')
		.' GROUP BY  msc, rok ORDER BY rok DESC, msc DESC ;';
	return $this->DB->GetAll($zap);
    }
    
    function GetBillingByCustomer($customerid,$rok=NULL,$msc=NULL,$calltype=NULL,$callsuccess=NULL,$terminal=NULL)
    {
	$call_success = NULL;
	if (!is_null($rok) && empty($rok)) $rok=NULL;
	if (!is_null($msc) && empty($msc)) $msc=NULL;
	if (!is_null($calltype) && empty($calltype)) $calltype=NULL;
	if (!is_null($callsuccess) && empty($callsuccess)) $callsuccess=NULL;
	if (!is_null($terminal) && empty($terminal)) $terminal=NULL;
	if (!is_null($callsuccess))
	{
	    if (is_bool($callsuccess)===true)
	    {
		if ($callsuccess===true) $call_success='t';
		elseif ($callsuccess===false) $call_success='f';
		else $call_success = NULL;
	    }
	    elseif (is_string($callsuccess)===true)
	    {
		if (strtolower($callsuccess)=='t') $call_success='t';
		elseif (strtolower($callsuccess)=='f') $call_success='f';
		else $call_success=NULL;
	    }
	    else $call_success = NULL;
	}
	    else $call_success = NULL;
	    
	
	$zap = 'SELECT * FROM hv_billing 
		WHERE customerid='.$customerid.' '
		.(!is_null($terminal) ? ' AND terminal_name = \''.$terminal.'\' ': '')
		.(!is_null($rok) ? ' AND '.$this->DB->YEAR('start_time').' = \''.$rok.'\' ' : '')
		.(!is_null($msc) ? ' AND '.$this->DB->MONTH('start_time').' = \''.$msc.'\' ' : '')
		.(!is_null($calltype) ? ' AND calltype=\''.$calltype.'\' ' : '') 
		.(!is_null($call_success) ? ' AND success_call = \''.$call_success.'\' ' : '')
		.' ORDER BY start_time DESC ;';
	return $this->DB->GetAll($zap);
    }
    
    function GetSubscriptionByTerminalName($terminal=NULL)
    {
	if (is_null($terminal) || !is_string($terminal)) return false;
	return $this->DB->GetOne('SELECT COALESCE(s.invoice_value,0) AS value 
				FROM hv_terminal AS t 
				LEFT JOIN hv_subscriptionlist AS s ON (s.id = t.id_subscription) 
				WHERE t.username = ? '.$this->DB->Limit('1').' ;',array($terminal));
    }


    function GetListProvince($active=NULL)
    {
	return $this->DB->GetAll('SELECT id, name FROM hv_province ;');
    }
    
    function GetListCountyByProvince($id)
    {
	return $this->DB->GetAll('SELECT hv_county.id, hv_county.name FROM hv_county JOIN hv_pcb ON (hv_county.id = hv_pcb.county) WHERE hv_pcb.province=? GROUP BY hv_county.name, hv_county.id ORDER BY hv_county.name ASC ;',array($id));
    }
    
    function GetListBoroughByCounty($id)
    {
	return $this->DB->GetAll('SELECT hv_borough.id, hv_borough.name FROM hv_borough JOIN hv_pcb ON (hv_borough.id = hv_pcb.borough) WHERE hv_pcb.county=? GROUP BY hv_borough.name, hv_borough.id ORDER BY hv_borough.name ASC ;',array($id));
	
    }
    
    
    
    function GetNameProvince($id)
    {
	return $this->DB->GetOne('SELECT name FROM hv_province WHERE id=? '.$this->DB->Limit('1').' ;',array($id));
    }
    
    function GetNameCounty($id)
    {
	return $this->DB->GetOne('SELECT name FROM hv_county WHERE id=? '.$this->DB->Limit('1').' ;',array($id));
    }
    
    function GetNameBorough($id)
    {
	return $this->DB->GetOne('SELECT name FROM hv_borough WHERE id=? '.$this->DB->Limit('1').' ;',array($id));
    }


    function GetPSTNOneOrList($pstnid=NULL,$customerid=NULL)
    {
	if (!is_null($pstnid)) return $this->DB->GetRow('SELECT t.* FROM hv_pstn AS t WHERE t.id=? ;',array($pstnid));
	elseif (!is_null($customerid)) return $this->DB->GetAll('SELECT t.* FROM hv_pstn AS t WHERE t.customerid=? ;',array($customerid));
	else return $this->DB->GetAll('SELECT t.* FROM hv_pstn AS t ;');
    }
    
    function GetPstnRangeList($active=false)
    {
	if (!is_bool($active)) $active = false;
	if ($active === true) $tmp = ' WHERE ussage=\'t\' ';
	else $tmp = '';
	$sql = 'SELECT pr.*, 
	    (SELECT COUNT(*) FROM hv_pstnusage WHERE hv_pstnusage.customerid!=0 AND hv_pstnusage.idrange = pr.id) AS uzyte,
	    (SELECT COUNT(*) FROM hv_pstnusage WHERE hv_pstnusage.idrange = pr.id) AS ilosc 
	     FROM hv_pstnrange AS pr '.$tmp.' ORDER BY range_start ASC ;';
	return $this->DB->GetAll($sql);
    }
    

    function GetPSTNInfoList($id,$empty=false)
    {
	$id = intval($id);
	$zap = 'SELECT * FROM hv_pstnusage WHERE idrange='.$id.' '.($empty ? 'AND customerid=0 ' : '').' ORDER BY number ASC ;';
	return $this->DB->GetAll($zap);
    }
    
    
    function UpdatePSTN($dane=NULL)
    {
	if (is_null($dane) || !is_array($dane)) return false;
	$old_extenstion = $dane['oldpstn'];
	$customer_id = $dane['customerid'];
	unset($dane['oldpstn']);
	unset($dane['customerid']);
	if ($this->HP_ChangePSTNNumberData($dane))
	{
	    $this->ImportTerminalList($customer_id);
	    $this->ImportPSTNList($customer_id);
	    if ($lista = $this->GetPSTNOneOrList(NULL,$customer_id))
	    {
		$cusname = $this->DB->GetOne('SELECT name FROM hv_customers WHERE id='.$customer_id.' LIMIT 1 ;');
		$this->DB->Execute('UPDATE hv_pstnusage SET customerid=0, customer_name=NULL WHERE customerid='.$customer_id.' ;');
		for ($i=0;$i<count($lista);$i++) $this->DB->Execute('UPDATE hv_pstnusage SET customerid='.$customer_id.' , customer_name=\''.$cusname.'\' WHERE extension=\''.$lista[$i]['extension'].'\' ;');
	    }
	    return true;
	}
    }
    

    function DeletePSTNCustomer($dane=NULL)
    {
	if (is_null($dane) ) return false;
	$customer_id = $this->DB->GetOne('SELECT customerid FROM hv_pstn WHERE id=? ;',array($dane));
	if (HiperusActions::DelPSTNNumber($dane))
	{
	    $cusname = $this->DB->GetOne('SELECT name FROM hv_customers WHERE id=? LIMIT 1 ;',array($customer_id));
	    $this->DB->Execute('DELETE FROM hv_pstn WHERE id=? ;',array($dane));
	    $this->ImportTerminalList($customer_id);
	    $this->ImportPSTNList($customer_id);
	    $this->DB->Execute('UPDATE hv_pstnusage SET customerid=0, customer_name=NULL WHERE customerid='.$customer_id.' ;');
	    if ($lista = $this->GetPSTNOneOrList(NULL,$customer_id))
	    {
		for ($i=0;$i<count($lista);$i++) $this->DB->Execute('UPDATE hv_pstnusage SET customerid='.$customer_id.' , customer_name=\''.$cusname.'\' WHERE extension=\''.$lista[$i]['extension'].'\' ;');
	    }
	    return true;
	}
    }

    function ImportPSTNList($cusid=NULL)
    {
	if (is_null($cusid)) 
	    $cus = $this->DB->GetAll('SELECT id FROM hv_customers ORDER BY id ASC '); 
	else
	{ 
	    $tmp = $cusid;
	    $cus = array();
	    $cus[0]['id'] = $tmp;
	    unset($tmp);
	}
	$cus_count = count($cus);
	if ($cus_count!==0)
	{
	    for ($i=0;$i<$cus_count;$i++)
	    {
		$lista = HiperusActions::GetPSTNNumberList($cus[$i]['id']);
		if (is_array($lista)) $count = count($lista); else $count=0;
		if ($count!==0)
		    for ($j=0;$j<$count;$j++)
			if (!is_null($lista[$j]['id']))
			if (!$this->DB->GetOne('SELECT 1 FROM hv_pstn WHERE id=? LIMIT 1 ;',array($lista[$j]['id']))) { 
			    $this->DB->Execute('INSERT INTO hv_pstn (id,customerid,extension,country_code,number,is_main,disa_enabled,clir,virtual_fax,terminal_name,id_auth,create_date,voicemail_enabled
				) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?) ;',
				array(
				    $lista[$j]['id'],$cus[$i]['id'],
					(!empty($lista[$j]['extension']) ? $lista[$j]['extension'] : NULL),
					(!empty($lista[$j]['country_code']) ? $lista[$j]['country_code'] : '48'),
					(!empty($lista[$j]['number']) ? $lista[$j]['number'] : NULL),
					$lista[$j]['is_main'],
					$lista[$j]['disa_enabled'],
					$lista[$j]['clir'],
					$lista[$j]['virtual_fax'],
					(!empty($lista[$j]['terminal_name']) ? $lista[$j]['terminal_name'] : NULL),
					(!empty($lista[$j]['id_auth']) ? $lista[$j]['id_auth'] : NULL),
					(!empty($lista[$j]['create_date']) ? $lista[$j]['create_date'] : NULL),
					$lista[$j]['voicemail_enabled']
				    ));
			} else { 
			    $this->DB->Execute('UPDATE hv_pstn SET id=?, customerid=?, extension=?, country_code=?, number=?, is_main=?, disa_enabled=?, clir=?, virtual_fax=?, 
						terminal_name=?, id_auth=?, create_date=?, voicemail_enabled=? WHERE id=? ;',
				array(
					(!empty($lista[$j]['extension']) ? $lista[$j]['extension'] : NULL),
					(!empty($lista[$j]['country_code']) ? $lista[$j]['country_code'] : '48'),
					(!empty($lista[$j]['number']) ? $lista[$j]['number'] : NULL),
					$lista[$j]['is_main'],
					$lista[$j]['disa_enabled'],
					$lista[$j]['clir'],
					$lista[$j]['virtual_fax'],
					(!empty($lista[$j]['terminal_name']) ? $lista[$j]['terminal_name'] : NULL),
					(!empty($lista[$j]['id_auth']) ? $lista[$j]['id_auth'] : NULL),
					(!empty($lista[$j]['create_date']) ? $lista[$j]['create_date'] : NULL),
					$lista[$j]['voicemail_enabled'],
					$lista[$j]['id'],$cus[$i]['id']
				    ));
			}
	    }
	    unset($lista);
	    unset($count);
	}
	unset($cus);
	unset($cus_count);
    }
    
    function ImportPSTNRangeList()
    {
	$lista = NULL;
	
	$hlib = new HiperusLib();
        $r = new stdClass();
        $response = $hlib->sendRequest("GetPlatformNumberingRange",$r);

	if  (!$response || !$response->success) $lista = array();
        else $lista = $response->result_set;
	
	$count = count($lista);
	if (!is_null($lista) && is_array($lista) && $count!==0)
	{
	    $this->DB->Execute('DELETE FROM hv_pstnrange ;');
	    for ($i=0;$i<$count;$i++)
		$this->DB->Execute('INSERT INTO hv_pstnrange (id,range_start,range_end,description,id_reseller,country_code,open_registration) VALUES (?,?,?,?,?,?,?) ;',
			array(
			    $lista[$i]['id'],
			    $lista[$i]['range_start'],
			    $lista[$i]['range_end'],
			    $lista[$i]['description'],
			    $lista[$i]['id_reseller'],
			    $lista[$i]['country_code'],
			    $lista[$i]['open_registration']
			));
	}
    }
    
    function ImportPSTNUsageList()
    {
	$this->DB->Execute('DELETE FROM hv_pstnusage ;');
	$pule = $this->DB->GetAll('SELECT id FROM hv_pstnrange ');
	if (is_array($pule)) $count = count($pule); else $count = 0;
	if ( $count!==0)
	    for ($i=0;$i<$count;$i++)
		{
		    $hlib = new HiperusLib();
		    $r = new stdClass();
		    $r -> id_platform_numbering = $pule[$i]['id'];
		    $response = $hlib->sendRequest("GetPlatformNumberingUsage",$r);
		    if (!$response || !$response->success) $lista = array();
		    else $lista = $response->result_set;
		    
		    if (is_array($lista)) $countl = count($lista); else $countl = 0;
		    if ($countl !== 0)
		    for ($j=0;$j<$countl;$j++)
		    {
			if (!isset($lista[$j]['id_customer']) || is_null($lista[$j]['id_customer']) || empty($lista[$j]['id_customer'])) $customerid = 0; else $customerid = $lista[$j]['id_customer'];
			if (!isset($lista[$j]['customer_name']) || is_null($lista[$j]['customer_name']) || empty($lista[$j]['customer_name'])) $customername = NULL; else $customername = $lista[$j]['customer_name'];
			$this->DB->Execute('INSERT INTO hv_pstnusage (extension,number,customerid,country_code,customer_name,idrange) VALUES (?,?,?,?,?,?) ;',
			    array($lista[$j]['extension'],$lista[$j]['number'],$customerid,$lista[$j]['country_code'],$customername,$pule[$i]['id']));
		    }
		}
    }
    
    
    function AddPstnForTerminal($dane) 
    {
	$id_customer = $dane['id_customer'];
        
        $number_data = array();
        $number_data['number'] = $dane['number'];
        $number_data['country_code'] = $dane['country_code'];
    
        if ($dane['is_main']=='t') $number_data['is_main'] = true; else $number_data['is_main'] = false;
        if ($dane['disa_enabled']=='t') $number_data['disa_enabled'] = true; else $number_data['disa_enabled'] = false;
        if ($dane['clir']=='t') $numer_data['clir'] = true; else $number_data['clir'] = false;
        if ($dane['virtual_fax']=='t') $number_data['virtual_fax'] = true; else $number_data['virtual_fax'] = false;
        if ($dane['voicemail_enabled']=='t') $number_data['voicemail_enabled'] = true; else $number_data['voicemail_enabled'] = false;
    
        $terminal_data = array();
        $terminal_data['id_terminal'] = $dane['id_terminal'];
    
        if (HiperusActions::CreatePSTNNumber($id_customer,$number_data,$terminal_data))
        {
		$this->ImportTerminalList($id_customer);
		$this->ImportPSTNList($id_customer);
		$cusname = $this->DB->GetRow('SELECT id, name FROM hv_customers WHERE id='.$id_customer.' LIMIT 1 ;');
		$this->DB->Execute('UPDATE hv_pstnusage SET customerid=?, customer_name=? WHERE number=? ;',array($cusname['id'],$cusname['name'],$number_data['number']));
		return true;
        } else return false;
        
    }



    function getcustomerexists($id)
    {
	return ($this->DB->GetOne('SELECT id FROM hv_customers WHERE id=? LIMIT 1;',array($id)) ? TRUE : FALSE);
    }
    
    function DelCustomer($id)
    {
	if ($return=HiperusActions::DelCustomer($id))
	{
	    $this->DB->Execute('DELETE FROM hv_billing WHERE customerid=? AND (ext_billing_id=0 OR ISNULL(ext_billing_id)) ;',array($id));
	    $this->DB->Execute('UPDATE hv_pstnusage SET customerid=0, customer_name=? WHERE customerid=? ;',array('',$id));
	    $this->DB->Execute('DELETE FROM hv_customers WHERE id=? ;',array($id));
	    $this->DB->Execute('DELETE FROM hv_assign WHERE customerid=? ;',array($id));
	    $this->DB->Execute('DELETE FROM hv_enduserlist WHERE customerid=? ;',array($id));
	    $this->DB->Execute('DELETE FROM hv_terminal WHERE customerid=? ;',array($id));
	    $this->DB->Execute('DELETE FROM hv_pstn WHERE customerid=? ;',array($id));
	    
	    return true;
	} else return false;
    }

    function ImportCustomersList()
    {
	$lista = HiperusActions::GetCustomerList();
	if (is_array($lista))$count = count($lista);else $count=0;
	$tmp='0';
	if ($count!==0)
	    for($i=0;$i<$count;$i++)
		if (!is_null($lista[$i]['id']))
		{
		    if (!$this->DB->GetOne('SELECT 1 FROM hv_customers WHERE id=? LIMIT 1 ;',array($lista[$i]['id']))) 
		    {
			$this->DB->Execute('INSERT INTO hv_customers (id,name,id_reseller,email,address,street_number,apartment_number,postcode,city,country,b_name,b_address,b_street_number,b_apartment_number,
				b_postcode,b_city,b_country,b_nip,b_regon,ext_billing_id,issue_invoice,id_default_pricelist,id_default_balance,payment_type,is_wlr,active,create_date,
				consent_data_processing,platform_user_add_stamp,open_registration,is_removed) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ;',
				array(
					$lista[$i]['id'],
					(!empty($lista[$i]['name']) ? $lista[$i]['name'] : NULL ),
					(!empty($lista[$i]['id_reseller']) ? $lista[$i]['id_reseller'] :NULL ),
					(!empty($lista[$i]['email']) ? $lista[$i]['email'] :NULL ),
					(!empty($lista[$i]['address']) ? $lista[$i]['address'] : NULL ),
					(!empty($lista[$i]['street_number'])?$lista[$i]['street_number']:NULL),
					(!empty($lista[$i]['apartment_number'])?$lista[$i]['apartment_number']:NULL),
					(!empty($lista[$i]['postcode']) ? $lista[$i]['postcode' ]:NULL),
					(!empty($lista[$i]['city'])?$lista[$i]['city']:NULL),
					(!empty($lista[$i]['country'])?$lista[$i]['country']:NULL),
					(!empty($lista[$i]['b_name'])?$lista[$i]['b_name']:NULL),
					(!empty($lista[$i]['b_address'])?$lista[$i]['b_address']:NULL),
					(!empty($lista[$i]['b_street_number'])?$lista[$i]['b_street_number']:NULL),
					(!empty($lista[$i]['b_apartment_number'])?$lista[$i]['b_apartment_number']:NULL),
					(!empty($lista[$i]['b_postcode'])?$lista[$i]['b_postcode']:NULL),
					(!empty($lista[$i]['b_city'])?$lista[$i]['b_city']:NULL),
					(!empty($lista[$i]['b_country'])?$lista[$i]['b_country']:NULL),
					(!empty($lista[$i]['b_nip'])?$lista[$i]['b_nip']:NULL),
					(!empty($lista[$i]['b_regon'])?$lista[$i]['b_regon']:NULL),
					(!empty($lista[$i]['ext_billing_id']) ? $lista[$i]['ext_billing_id'] : NULL),
					(!empty($lista[$i]['issue_invoice'])?$lista[$i]['issue_invoice']:'f'),
					(!empty($lista[$i]['id_default_pricelist'])?$lista[$i]['id_default_pricelist']:NULL),
					(!empty($lista[$i]['id_default_balance'])?$lista[$i]['id_default_balance']:NULL),
					(!empty($lista[$i]['payment_type'])?$lista[$i]['payment_type']:'postpaid'),
					(!empty($lista[$i]['is_wlr'])?$lista[$i]['is_wlr']:'f'),
					(!empty($lista[$i]['active'])?$lista[$i]['active']:'t'),
					(!empty($lista[$i]['create_date'])?$lista[$i]['create_date']:NULL),
					(!empty($lista[$i]['consent_data_processing'])?$lista[$i]['consent_data_processing']:'f'),
					(!empty($lista[$i]['platform_user_add_stamp'])?$lista[$i]['platform_user_add_stamp']:NULL),
					(!empty($lista[$i]['open_registration'])?$lista[$i]['open_registration']:'f'),
					(!empty($lista[$i]['is_removed'])?$lista[$i]['is_removed']:'f')
				    )
			    );
			if ($lista[$i]['issue_invoice']=='f') $tmp = '0'; else $tmp = '1';
			if (!$this->DB->GetOne('SELECT 1 FROM hv_assign WHERE customerid=? AND keytype=? LIMIT 1 ;',array($lista[$i]['id'],'issue_invoice')))
			    $this->DB->Execute('INSERT INTO hv_assign (customerid,keytype,keyvalue) VALUES (?,?,?) ;',array($lista[$i]['id'],'issue_invoice',$tmp));
			else
			    if ($tmp=='1') $this->DB->Execute('UPDATE hv_assign SET keyvalue=? WHERE customerid=? AND keytype=? ;',array(1,$lista[$i]['id'],'issue_invoice'));
		    } else {
			$this->DB->Execute('UPDATE hv_customers SET id=?, name=?, id_reseller=?, email=?, address=?, street_number=?, apartment_number=?, postcode=?, city,country=?, b_name=?, b_address=?, 
					    b_street_number=?, b_apartment_number=?, b_postcode=?, b_city=?, b_country=?, b_nip=?, b_regon=?, ext_billing_id=?, issue_invoice=?, id_default_pricelist=?, 
					    id_default_balance=?, payment_type=?, is_wlr=?, active=?, create_date=?, consent_data_processing=?, platform_user_add_stamp=?, 
					    open_registration=?, is_removed=? WHERE id=? ;',
				array(
					(!empty($lista[$i]['name']) ? $lista[$i]['name'] : NULL ),
					(!empty($lista[$i]['id_reseller']) ? $lista[$i]['id_reseller'] :NULL ),
					(!empty($lista[$i]['email']) ? $lista[$i]['email'] :NULL ),
					(!empty($lista[$i]['address']) ? $lista[$i]['address'] : NULL ),
					(!empty($lista[$i]['street_number'])?$lista[$i]['street_number']:NULL),
					(!empty($lista[$i]['apartment_number'])?$lista[$i]['apartment_number']:NULL),
					(!empty($lista[$i]['postcode']) ? $lista[$i]['postcode' ]:NULL),
					(!empty($lista[$i]['city'])?$lista[$i]['city']:NULL),
					(!empty($lista[$i]['country'])?$lista[$i]['country']:NULL),
					(!empty($lista[$i]['b_name'])?$lista[$i]['b_name']:NULL),
					(!empty($lista[$i]['b_address'])?$lista[$i]['b_address']:NULL),
					(!empty($lista[$i]['b_street_number'])?$lista[$i]['b_street_number']:NULL),
					(!empty($lista[$i]['b_apartment_number'])?$lista[$i]['b_apartment_number']:NULL),
					(!empty($lista[$i]['b_postcode'])?$lista[$i]['b_postcode']:NULL),
					(!empty($lista[$i]['b_city'])?$lista[$i]['b_city']:NULL),
					(!empty($lista[$i]['b_country'])?$lista[$i]['b_country']:NULL),
					(!empty($lista[$i]['b_nip'])?$lista[$i]['b_nip']:NULL),
					(!empty($lista[$i]['b_regon'])?$lista[$i]['b_regon']:NULL),
					(!empty($lista[$i]['ext_billing_id']) ? $lista[$i]['ext_billing_id'] : NULL),
					(!empty($lista[$i]['issue_invoice'])?$lista[$i]['issue_invoice']:'f'),
					(!empty($lista[$i]['id_default_pricelist'])?$lista[$i]['id_default_pricelist']:NULL),
					(!empty($lista[$i]['id_default_balance'])?$lista[$i]['id_default_balance']:NULL),
					(!empty($lista[$i]['payment_type'])?$lista[$i]['payment_type']:'postpaid'),
					(!empty($lista[$i]['is_wlr'])?$lista[$i]['is_wlr']:'f'),
					(!empty($lista[$i]['active'])?$lista[$i]['active']:'t'),
					(!empty($lista[$i]['create_date'])?$lista[$i]['create_date']:NULL),
					(!empty($lista[$i]['consent_data_processing'])?$lista[$i]['consent_data_processing']:'f'),
					(!empty($lista[$i]['platform_user_add_stamp'])?$lista[$i]['platform_user_add_stamp']:NULL),
					(!empty($lista[$i]['open_registration'])?$lista[$i]['open_registration']:'f'),
					(!empty($lista[$i]['is_removed'])?$lista[$i]['is_removed']:'f'),
					$lista[$i]['id']
				    )
			    );
			if ($lista[$i]['issue_invoice']=='f') $tmp = '0'; else $tmp = '1';
			if (!$this->DB->GetOne('SELECT 1 FROM hv_assign WHERE customerid=? AND keytype=? LIMIT 1 ;',array($lista[$i]['id'],'issue_invoice')))
			    $this->DB->Execute('INSERT INTO hv_assign (customerid,keytype,keyvalue) VALUES (?,?,?) ;',array($lista[$i]['id'],'issue_invoice',$tmp));
			else
			    if ($tmp=='1') $this->DB->Execute('UPDATE hv_assign SET keyvalue=? WHERE customerid=? AND keytype=? ;',array(1,$lista[$i]['id'],'issue_invoice'));
		    }
		}
	unset($tmp);
	unset($lista);
	unset($count);
    }

    
    function AddCustomer($dane)
    {
	if (!is_array($dane)) return false;

	if ($dane['invoice']=='1') $dane['issue_invoice'] = 't'; else $dane['issue_invoice'] = 'f';
	
	$tmp_invoice = $dane['invoice'];
	unset($dane['invoice']);
	    
	if ($return = HiperusActions::CreateCustomer($dane))
	{
	    $result = HiperusActions::GetCustomerData($return);
	    $this->DB->Execute('INSERT INTO hv_customers (id,name,id_reseller,email,address,street_number,apartment_number,postcode,city,country,b_name,b_address,b_street_number,b_apartment_number,
			    b_postcode,b_city,b_country,b_nip,b_regon,ext_billing_id,issue_invoice,id_default_pricelist,id_default_balance,payment_type,is_wlr,active,create_date,
			    consent_data_processing,platform_user_add_stamp,open_registration,is_removed) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ;',
			    array(
				$result['id'],
				( !empty($result['name']) ? $result['name']:NULL),
				( !empty($result['id_reseller']) ? $result['id_reseller']:NULL),
				( !empty($result['email']) ? $result['email']:NULL),
				( !empty($result['address']) ? $result['address']:NULL),
				( !empty($result['street_number']) ? $result['street_number']:NULL),
				( !empty($result['apartment_number']) ? $result['apartment_number']:NULL),
				( !empty($result['postcode']) ? $result['postcode']:NULL),
				( !empty($result['city']) ? $result['city']:NULL),
				( !empty($result['country']) ? $result['country']:NULL),
				( !empty($result['b_name']) ? $result['b_name']:NULL),
				( !empty($result['b_address']) ? $result['b_address']:NULL),
				( !empty($result['b_street_number']) ? $result['b_street_number']:NULL),
				( !empty($result['b_apartment_number']) ? $result['b_apartment_number']:NULL),
				( !empty($result['b_postcode']) ? $result['b_postcode']:NULL),
				( !empty($result['b_city']) ? $result['b_city']:NULL),
				( !empty($result['b_country']) ? $result['b_country']:NULL),
				( !empty($result['b_nip']) ? $result['b_nip']:NULL),
				( !empty($result['b_regon']) ? $result['b_regon']:NULL),
				( !empty($result['ext_billing_id']) ? $result['ext_billing_id'] : NULL),
				( !empty($result['issue_invoice']) ? $result['issue_invoice'] : 'f'),
				( !empty($result['id_default_pricelist']) ? $result['id_default_pricelist'] : NULL),
				( !empty($result['id_default_balance']) ? $result['id_default_balance'] : NULL),
				( !empty($result['payment_type']) ? $result['payment_type'] : 'postpaid'),
				( !empty($result['is_wlr']) ? $result['is_wlr'] : 'f'),
				( !empty($result['active']) ? $result['active'] : 't'),
				( !empty($result['create_date']) ? $result['create_date'] : NULL),
				( !empty($result['consent_data_processing']) ? $result['consent_data_processing'] : 'f'),
				( !empty($result['platform_user_add_stamp']) ? $result['platform_user_add_stamp'] : NULL),
				( !empty($result['open_registration']) ? $result['open_registration'] : 'f'),
				( !empty($result['is_removed']) ? $result['is_removed'] : 'f')
			    ));
			    
	    $this->DB->Execute('INSERT INTO hv_assign (customerid,keytype,keyvalue) VALUES (?,?,?) ;',array($result['id'],'issue_invoice',$tmp_invoice));
	    return $return;
	}

    }
    
    function GetCustomer($id)
    {
	return $this->DB->GetRow('SELECT h.*, (SELECT keyvalue FROM hv_assign WHERE customerid=h.id AND keytype=?) AS invoice FROM hv_customers AS h WHERE id=? LIMIT 1 ;',array('issue_invoice',$id));
    }
    
    function UpdateCustomer($dane)
    {
	
	if ($dane['invoice']=='1') $dane['issue_invoice'] = 't'; else $dane['issue_invoice'] = 'f';
	
	$invoice = $dane['invoice'];
	unset($dane['invoice']);
	
    	if (HiperusActions::ChangeCustomerData($dane))
	{
		$oldname = $this->DB->GetOne('SELECT name FROM hv_customers WHERE id='.$dane['id'].' LIMIT 1 ;');
		$dane = HiperusActions::GetCustomerData($dane['id']);
		$dane['invoice'] = $invoice;
		$this->DB->Execute('UPDATE hv_customers SET name=?, id_reseller=?, email=?, address=?, street_number=?, apartment_number=?, postcode=?, city=?, country=?, b_name=?, 
			    b_address=?, b_street_number=?, b_apartment_number=?, b_postcode=?,b_city=?,b_country=?,b_nip=?,b_regon=?,ext_billing_id=?,issue_invoice=?,id_default_pricelist=?,
			    id_default_balance=?,payment_type=?,is_wlr=?,active=?,create_date=?, consent_data_processing=?,platform_user_add_stamp=?,open_registration=?,is_removed=? WHERE id=? ;',
			    array(
				(!empty($dane['name']) ? $dane['name'] : NULL),
				(!empty($dane['id_reseller']) ? $dane['id_reseller'] : NULL),
				(!empty($dane['email']) ? $dane['email'] : NULL),
				(!empty($dane['address']) ? $dane['address'] : NULL),
				(!empty($dane['street_number']) ? $dane['street_number'] : NULL),
				(!empty($dane['apartment_number']) ? $dane['apartment_number'] : NULL),
				(!empty($dane['postcode']) ? $dane['postcode'] : NULL),
				(!empty($dane['city']) ? $dane['city'] : NULL),
				(!empty($dane['country']) ? $dane['country'] : NULL),
				(!empty($dane['b_name']) ? $dane['b_name'] : NULL),
				(!empty($dane['b_address']) ? $dane['b_address'] : NULL),
				(!empty($dane['b_street_number']) ? $dane['b_street_number'] : NULL),
				(!empty($dane['b_apartment_number']) ? $dane['b_apartment_number'] : NULL),
				(!empty($dane['b_postcode']) ? $dane['b_postcode'] : NULL),
				(!empty($dane['b_city']) ? $dane['b_city'] : NULL),
				(!empty($dane['b_country']) ? $dane['b_country'] : NULL),
				(!empty($dane['b_nip']) ? $dane['b_nip'] : NULL),
				(!empty($dane['b_regon']) ? $dane['b_regon'] : NULL),
				(!empty($dane['ext_billing_id']) ? $dane['ext_billing_id'] : NULL),
				(!empty($dane['issue_invoice']) ? $dane['issue_invoice'] : 'f'),
				(!empty($dane['id_default_pricelist']) ? $dane['id_default_pricelist'] : NULL),
				(!empty($dane['id_default_balance']) ? $dane['id_default_balance'] : NULL),
				(!empty($dane['payment_type']) ? $dane['payment_type'] : 'postpaid'),
				(!empty($dane['is_wlr']) ? $dane['is_wlr'] : 'f'),
				(!empty($dane['active']) ? $dane['active'] : 't'),
				(!empty($dane['create_date']) ? $dane['create_date'] : NULL),
				(!empty($dane['consent_data_processing']) ? $dane['consent_data_processing'] : 'f'),
				(!empty($dane['platform_user_add_stamp']) ? $dane['platform_user_add_stamp'] : NULL),
				(!empty($dane['open_registration']) ? $dane['open_registration'] : 'f'),
				(!empty($dane['is_removed']) ? $dane['is_removed'] : 'f'),
				$dane['id']
			    ));
		$this->DB->Execute('UPDATE hv_assign SET keytype=?, keyvalue=? WHERE customerid=? ;', array('issue_invoice',$invoice,$dane['id']));
		if ($dane['name']!==$oldname)
		{
		    $this->DB->Execute('UPDATE hv_billing SET customer_name=? WHERE customer_name=? ;',array($dane['name'],$oldname));
		    $this->DB->Execute('UPDATE hv_terminal SET customer_name=? WHERE customer_name=? ;',array($dane['name'],$oldname));
		    $this->DB->Execute('UPDATE hv_pstnusage SET customer_name=? WHERE customer_name=? ;',array($dane['name'],$oldname));
		}
		unset($oldname);
		unset($dane);
		return true;
	}
	else return false;
	
    }
    
    function GetLMSCustomerByVoIPID($id)
    {
	return $this->DB->GetRow('SELECT c.* FROM customers c JOIN hv_customers h ON (c.id = h.ext_billing_id) WHERE h.id = '.$id.' LIMIT 1');
    }
    

    function getterminalexists($id)
    {
	return ($this->DB->GetOne('SELECT id FROM hv_terminal WHERE id=? LIMIT 1;',array($id)) ? TRUE : FALSE);
    }
    function ImportTerminalList($cusid=NULL)
    {
	$cus = array();
	if (is_null($cusid)) $cus = $this->DB->GetAll('SELECT id FROM hv_customers ORDER BY id ASC '); 
	else $cus[0]['id'] = $cusid;
	$cus_count = count($cus);
	if ($cus_count!==0)
	{
	    for ($i=0;$i<$cus_count;$i++)
	    {
		$lista = HiperusActions::GetTerminalList($cus[$i]['id']);
		
		if (is_array($lista)) $count = count($lista); else $count=0;
		if ($count!==0)
		    for ($j=0;$j<$count;$j++)
		    {
			if (!is_null($lista[$j]['id']))
			    if (!$this->DB->GetOne('SELECT 1 FROM hv_terminal WHERE id=? LIMIT 1 ;',array($lista[$j]['id'])))
			    {
				$this->DB->Execute('INSERT INTO hv_terminal (id,customerid,username,password,screen_numbers,t38_fax,customer_name,id_pricelist,pricelist_name,balance_value,id_auth,id_subscription,
				    subscription_from,subscription_to,value_left,id_terminal_location,area_code,borough,county,province,sip_proxy,subscriptions,extensions) 
				    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ;',
				    array(
					$lista[$j]['id'],
					$cus[$i]['id'],
					(!empty($lista[$j]['username']) ? $lista[$j]['username'] : NULL),
					(!empty($lista[$j]['password']) ? $lista[$j]['password'] : NULL),
					(!empty($lista[$j]['screen_numbers']) ? $lista[$j]['screen_numbers'] : 't'),
					(!empty($lista[$j]['t38_fax']) ? $lista[$j]['t38_fax'] : 'f'),
					(!empty($lista[$j]['customer_name']) ? $lista[$j]['customer_name'] : NULL),
					(!empty($lista[$j]['id_pricelist']) ? $lista[$j]['id_pricelist'] : NULL),
					(!empty($lista[$j]['pricelist_name']) ? $lista[$j]['pricelist_name'] : NULL),
					(!empty($lista[$j]['balance_value']) ? $lista[$j]['balance_value'] : '0.00'),
					(!empty($lista[$j]['id_auth']) ? $lista[$j]['id_auth'] : NULL),
					(!empty($lista[$j]['id_subscription']) ? $lista[$j]['id_subscription'] : NULL),
					(!empty($lista[$j]['subscription_from']) ? $lista[$j]['subscription_from'] : NULL),
					(!empty($lista[$j]['subscription_to']) ? $lista[$j]['subscription_to'] : NULL),
					(!empty($lista[$j]['value_left']) ? $lista[$j]['value_left'] : '0.00'), 
					(!empty($lista[$j]['id_terminal_location']) ? $lista[$j]['id_terminal_location'] : NULL),
					(!empty($lista[$j]['area_code']) ? $lista[$j]['area_code'] : NULL),
					(!empty($lista[$j]['borough']) ? $lista[$j]['borough'] : NULL),
					(!empty($lista[$j]['county']) ? $lista[$j]['county'] : NULL),
					(!empty($lista[$j]['province']) ? $lista[$j]['province'] : NULL),
					(!empty($lista[$j]['sip_proxy']) ? $lista[$j]['sip_proxy'] : NULL),
					(!empty($lista[$j]['subscriptions']) ? $lista[$j]['subscriptions'] : NULL),
					(!empty($lista[$j]['extensions']) ? $lista[$j]['extensions'] : NULL)
				    )
				);
				
			} else {
			
				$this->DB->Execute('UPDATE hv_terminal SET customerid=?, username=?, password=?, screen_numbers=?, t38_fax=?, customer_name=?, id_pricelist=?, pricelist_name=?, 
						    balance_value=?, id_auth=?, id_subscription=?, subscription_from=?, subscription_to=?, value_left=?, id_terminal_location=?, area_code=?, borough=?, 
						    county=?, province=?, sip_proxy=?, subscriptions=?, extensions=? WHERE id=? ',
				    array(
					$cus[$i]['id'],
					(!empty($lista[$j]['username']) ? $lista[$j]['username'] : NULL),
					(!empty($lista[$j]['password']) ? $lista[$j]['password'] : NULL),
					(!empty($lista[$j]['screen_numbers']) ? $lista[$j]['screen_numbers'] : 't'),
					(!empty($lista[$j]['t38_fax']) ? $lista[$j]['t38_fax'] : 'f'),
					(!empty($lista[$j]['customer_name']) ? $lista[$j]['customer_name'] : NULL),
					(!empty($lista[$j]['id_pricelist']) ? $lista[$j]['id_pricelist'] : NULL),
					(!empty($lista[$j]['pricelist_name']) ? $lista[$j]['pricelist_name'] : NULL),
					(!empty($lista[$j]['balance_value']) ? $lista[$j]['balance_value'] : '0.00'),
					(!empty($lista[$j]['id_auth']) ? $lista[$j]['id_auth'] : NULL),
					(!empty($lista[$j]['id_subscription']) ? $lista[$j]['id_subscription'] : NULL),
					(!empty($lista[$j]['subscription_from']) ? $lista[$j]['subscription_from'] : NULL),
					(!empty($lista[$j]['subscription_to']) ? $lista[$j]['subscription_to'] : NULL),
					(!empty($lista[$j]['value_left']) ? $lista[$j]['value_left'] : '0.00'), 
					(!empty($lista[$j]['id_terminal_location']) ? $lista[$j]['id_terminal_location'] : NULL),
					(!empty($lista[$j]['area_code']) ? $lista[$j]['area_code'] : NULL),
					(!empty($lista[$j]['borough']) ? $lista[$j]['borough'] : NULL),
					(!empty($lista[$j]['county']) ? $lista[$j]['county'] : NULL),
					(!empty($lista[$j]['province']) ? $lista[$j]['province'] : NULL),
					(!empty($lista[$j]['sip_proxy']) ? $lista[$j]['sip_proxy'] : NULL),
					(!empty($lista[$j]['subscriptions']) ? $lista[$j]['subscriptions'] : NULL),
					(!empty($lista[$j]['extensions']) ? $lista[$j]['extensions'] : NULL),
					$lista[$j]['id']
				    )
				);
			}
		    }
	    }
	    unset($count);
	    unset($lista);
	}
	unset($cus);
	unset($cus_count);
    }
    
    function GetTerminalOneOrList($terminalid=NULL,$customerid=NULL, $sort=NULL, $filtr=NULL)
    {
	if (!is_null($terminalid)) return $this->DB->GetRow('SELECT t.* FROM hv_terminal AS t WHERE t.id='.intval($terminalid).' LIMIT 1;');
	elseif (!is_null($customerid)) return $this->DB->GetAll('SELECT t.* FROM hv_terminal AS t WHERE t.customerid='.intval($customerid).' ;');
	else
	{
	    if (is_null($sort) || !is_string($sort)) $sort = 'id,asc';
	    switch ($sort)
	    {
		case 'id,asc'		: $sort = ' ORDER BY t.id ASC '; break;
		case 'id,desc'		: $sort = ' ORDER BY t.id DESC '; break;
		case 'numbers,asc'	: $sort = ' ORDER BY t.extensions ASC '; break;
		case 'numbers,desc'	: $sort = ' ORDER BY t.extensions DESC '; break;
		case 'username,asc'	: $sort = ' ORDER BY t.username ASC '; break;
		case 'username,desc'	: $sort = ' ORDER BY t.username DESC '; break;
		case 'customername,asc'	: $sort = ' ORDER BY t.customer_name ASC '; break;
		case 'customername,desc': $sort = ' ORDER BY t.customer_name DESC '; break;
		default			: $sort = ' ORDER BY t.id ASC '; break;
	    }
	    
	    $price = '';
	    $subscription = '';
	    
	    if (!is_null($filtr) && is_array($filtr))
	    {
		if (isset($filtr['price']))
		{
		    if ($filtr['price'] == 'noprice') $price = ' AND t.id_pricelist IS NULL '; 
		    elseif ($filtr['price'] == '') $price = ' ';
		    else $price = ' AND t.id_pricelist = '.$filtr['price'].' ';
		}
		
		if (isset($filtr['subscription']))
		{
		    if ($filtr['subscription'] == 'nosubscription') $subscription = ' AND t.id_subscription IS NULL '; 
		    elseif ($filtr['subscription'] == '') $subscription = ' ';
		    else $subscription = ' AND t.id_subscription = '.$filtr['subscription'].' ';
		}
		
	    }
	    
	    $sql = 'SELECT t.* FROM hv_terminal AS t WHERE 1=1 '
	    .$price
	    .$subscription
	    .$sort 
	    .' ;';
	    return $this->DB->GetAll($sql);
	}
    }
    
    
    function GetIDLocationTerminal($p,$c,$b)
    {
	return $this->DB->GetOne('SELECT id FROM hv_pcb WHERE province='.intval($p).' AND county='.intval($c).' AND borough='.intval($b).' LIMIT 1; ');
    }
    
    function CreateTerminal($customer_id,$username,$password,$pricelist_id,$screen=NULL,$t38=NULL,$subscription_id=NULL,$subscription_from=NULL,$subscription_to=NULL,$id_terminal_location=NULL)
    {
            
        $hlib = new HiperusLib();
        $req = new stdClass();
	$req->id_customer = $customer_id;
	$req->username = $username;
        $req->password = $password;
        $req->id_pricelist = $pricelist_id;
        if (is_null($screen)) $req->screen_numbers = true;
        if (!is_bool($screen))
        {
	    $screen = strtolower($screen);
	    if ($screen == 't') $req->screen_numbers = true;
	    elseif ($screen=='f') $req->screen_numbers = false;
	    else $req->screen_numbers = true;
        }
        if (is_null($t38)) $req->t38_fax = false;
        if (!is_bool($t38))
        {
	    $t38 = strtolower($t38);
	    if ($t38 == 't') $req->t38_fax = true;
	    elseif ($t38 == 'f') $req->t38_fax = false;
	    else $req->t38_fax = false;
        }
        if (!is_null($subscription_id)) $req->id_subscription = $subscription_id;
        if (!is_null($subscription_from)) $req->subscription_from = str_replace('/','-',$subscription_from);
        if (!is_null($subscription_to)) $req->subscription_to = str_replace('/','-',$subscription_to);
        if (!is_null($id_terminal_location)) $req->id_terminal_location = $id_terminal_location;
        $ret = $hlib->sendRequest("AddTerminal",$req);
	if (!$ret || !$ret->success) return false; else return true;
    }
    
    function UpdateTerminal($dane=NULL)
    {
	if (is_null($dane) || !is_array($dane)) return false;
	
	if (!isset($dane['screen_numbers'])) $dane['screen_numbers'] = true;
	if (!isset($dane['t38_fax'])) $dane['t38_fax'] = false;
	if (!is_bool($dane['screen_numbers']))
	{
	    $dane['screen_numbers'] = strtolower($dane['screen_numbers']);
	    if ($dane['screen_numbers']=='t') $dane['screen_numbers'] = true;
	    elseif($dane['screen_numbers']=='f') $dane['screen_numbers'] = false;
	    else $dane['screen_numbers'] = true;
	}
	if (!is_bool($dane['t38_fax']))
	{
	    $dane['t38_fax'] = strtolower($dane['t38_fax']);
	    if ($dane['t38_fax']=='t') $dane['t38_fax'] = true;
	    elseif($dane['t38_fax']=='f') $dane['t38_fax'] = false;
	    else $dane['t38_fax'] = true;
	}
	
	if (HiperusActions::ChangeTerminalData($dane)) 
	{
	    $oldname = $this->DB->GetOne('SELECT username FROM hv_terminal WHERE id='.$dane['id_terminal'].' LIMIT 1 ;');
	    if ($dane['username']!==$oldname)
	    {
		$this->DB->Execute('UPDATE hv_billing SET terminal_name=? WHERE terminal_name=? ;',array($dane['username'],$oldname));
		$this->DB->Execute('UPDATE hv_pstn SET terminal_name=? WHERE terminal_name=? ;',array($dane['username'],$oldname));
		$this->DB->Execute('UPDATE hv_terminal SET username=? WHERE username=? ;',array($dane['username'],$oldname));
	    }
	    return true; 
	} else return false;
    }
    
    function DeleteTerminal($id=NULL)
    {
	if (is_null($id)) return false;
	$id = intval($id);
	$pstn = $this->DB->GetOne('SELECT extensions FROM hv_terminal WHERE id=? LIMIT 1;',array($id));
	$numery = array();
	$numery = explode("\n",$pstn);
	unset($numery[sizeof($numery)-1]);
	if (HiperusActions::DelTerminal($id))
	{
	    for ($i=0;$i<sizeof($numery);$i++) 
	    {
		$this->DB->Execute('UPDATE hv_pstnusage SET customerid=0, customer_name=NULL WHERE extension=? ;',array($numery[$i]));
		$this->DB->Execute('DELETE FROM hv_pstn WHERE extension=? ;',array($numery[$i]));
	    }
	    $this->DB->Execute('DELETE FROM hv_terminal WHERE id=? ;',array($id));
	}
    }
    
    
    function GetInvoiceList()
    {
	$hlib = new HiperusLib();
        $r = new stdClass();
        $response = $hlib->sendRequest("GetInvoiceList",$r);
        
        if(!$response || !$response->success) return false;
	    else return $response->result_set;
    }
    
    function GetConfigHiperus()
    {
	$hlib = new HiperusLib();
        $r = new stdClass();
        $response = $hlib->sendRequest("CheckLogin",$r);
        if(!$response || !$response->success) return false;
        else 
        {
	    $result = $response->result_set[0];

	    if ($this->DB->GetOne('SELECT 1 FROM uiconfig WHERE section=? AND var=?',array('hiperus_c5','voip_services')))
		$this->DB->Execute('UPDATE uiconfig SET value=? WHERE section=? AND var=?',array(($result['voip_services'] ? 1 : 0),'hiperus_c5','voip_services'));
	    else
		$this->DB->addconfig('hiperus_c5','voip_services',($result['voip_services'] ? 1 : 0));

	    if ($this->DB->GetOne('SELECT 1 FROM uiconfig WHERE section=? AND var=?',array('hiperus_c5','wlr')))
		$this->DB->Execute('UPDATE uiconfig SET value=? WHERE section=? AND var=?',array(($result['wlr_services'] ? 1 : 0),'hiperus_c5','wlr'));
	    else
		$this->DB->addconfig('hiperus_c5','wlr',($result['wlr_services'] ? 1 : 0));
	}
    }
    
    
    
    function userpanel_getterminalinfo($cusid)
    {
	$result = array();
	$cusid = intval($cusid);
	$hvid = $this->DB->GetOne('SELECT id FROM hv_customers WHERE ext_billing_id = '.$cusid.' LIMIT 1 ;');
	if ($lista = $this->DB->GetAll('SELECT * FROM hv_terminal WHERE customerid=? ',array($hvid))) return $lista;
	else return $result;
    }
    
    

}

$HIPERUS = new LMSHiperus($DB);

if (isset($layout) && is_array($layout))
{
    if (isset($_GET['m']) && in_array($_GET['m'],array('customerinfo','nodeadd','nodeinfo','voipaccountedit','voipaccountinfo','voipaccountadd','nodeedit','customeredit')))
    {
	if (in_array($_GET['m'],array('customerinfo','customeredit'))) $cusid = intval($_GET['id']);
	if (in_array($_GET['m'],array('nodeinfo','nodeedit'))) $cusid = $DB->GetOne('SELECT ownerid FROM nodes WHERE id='.intval($_GET['id']).' ;');
	if (in_array($_GET['m'],array('voipaccountedit','voipaccountinfo'))) $cusid = $DB->GetOne('SELECT ownerid FROM voipaccounts WHERE id='.intval($_GET['id']).' ;');
	if (in_array($_GET['m'],array('nodeadd','voipaccountadd'))) $cusid = intval($_GET['ownerid']);
	if ($accountid = $DB->GetOne('SELECT id FROM hv_customers WHERE ext_billing_id=? LIMIT 1;',array($cusid))) 
	    $SMARTY->assign('hiperusaccountcustomerlist',$HIPERUS->GetCustomerListList('name,asc',array('extid' => $cusid)));
    }
}
?>