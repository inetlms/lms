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

if (isset($_POST['contractordata']))
{
	$contractordata = $_POST['contractordata'];
	
	foreach($contractordata as $key=>$value)
		if($key != 'uid' && $key != 'contacts')
			$contractordata[$key] = trim($value);
			
	if($contractordata['lastname'] == '')
		$error['lastname'] = trans('Last/Company name cannot be empty!');

	if($contractordata['address']=='') $error['address'] = trans('Address required!');

	if($contractordata['ten'] !='' && !check_ten($contractordata['ten']) && !isset($contractordata['tenwarning']))
	{
		$error['ten'] = trans('Incorrect Tax Exempt Number! If you are sure you want to accept it, then click "Submit" again.');
		$tenwarning = 1;
	}
	

	if($contractordata['regon'] != '' && !check_regon($contractordata['regon']))
		$error['regon'] = trans('Incorrect Business Registration Number!');

	if($contractordata['zip'] !='' && !check_zip($contractordata['zip']) && !isset($contractordata['zipwarning']))
	{
		$error['zip'] = trans('Incorrect ZIP code! If you are sure you want to accept it, then click "Submit" again.');
		$zipwarning = 1;
	}

	if($contractordata['post_zip'] !='' && !check_zip($contractordata['post_zip']) && !isset($contractordata['post_zipwarning']))
	{
		$error['post_zip'] = trans('Incorrect ZIP code! If you are sure you want to accept it, then click "Submit" again.');
		$post_zipwarning = 1;
	}
	
	if($contractordata['email']!='' && !check_email($contractordata['email']))
		$error['email'] = trans('Incorrect email!');

	foreach($contractordata['uid'] as $idx => $val)
	{
		$val = trim($val);
		switch($idx)
		{
			case IM_GG:
				if($val!='' && !check_gg($val))
					$error['gg'] = trans('Incorrect IM uin!');
			break;
			case IM_YAHOO:
				if($val!='' && !check_yahoo($val))
					$error['yahoo'] = trans('Incorrect IM uin!');
			break;
			case IM_SKYPE:
				if($val!='' && !check_skype($val))
					$error['skype'] = trans('Incorrect IM uin!');
			break;
		}

		if($val) $im[$idx] = $val;
	}

	foreach($contractordata['contacts'] as $idx => $val)
    {
	        $phone = trim($val['phone']);
	        $name = trim($val['name']);
            $type = !empty($val['type']) ? array_sum($val['type']) : NULL;

            $contractordata['contacts'][$idx]['type'] = $type;

	        if($name && !$phone)
	                $error['contact'.$idx] = trans('Phone number is required!');
	        elseif($phone)
	                $contacts[] = array('name' => $name, 'phone' => $phone, 'type' => $type);
	}

	if(!$error)
	{
	    if(!isset($contractordata['consentdate']))
			$contractordata['consentdate'] = 0;

		$cid = $LMS->ContractorAdd($contractordata);
		
		$DB->Execute('DELETE FROM imessengers WHERE customerid = ?', array($contractordata['id']));
		if(isset($im))
			foreach($im as $idx => $val)
				$DB->Execute('INSERT INTO imessengers (customerid, uid, type)
					VALUES(?, ?, ?)', array($contractordata['id'], $val, $idx));

		$DB->Execute('DELETE FROM customercontacts WHERE customerid = ?', array($contractordata['id']));
		if(isset($contacts))
			foreach($contacts as $contact)
				$DB->Execute('INSERT INTO customercontacts (customerid, phone, name, type)
					VALUES(?, ?, ?, ?)', array($contractordata['id'], $contact['phone'], $contact['name'], $contact['type']));

		$SESSION->redirect('?m=contractorinfo&id='.$cid);
	}
	else
	{

		$contractorinfo = $contractordata;
		$contractorinfo['zipwarning'] = empty($zipwarning) ? 0 : 1;
		$contractorinfo['post_zipwarning'] = empty($post_zipwarning) ? 0 : 1;
		$contractorinfo['tenwarning'] = empty($tenwarning) ? 0 : 1;
		$contractorinfo['ssnwarning'] = empty($ssnwarning) ? 0 : 1;

		$SMARTY->assign('error',$error);
	}
}

else
{
	$contractorinfo['contacts'][] = array();
}

if(!isset($contractorinfo['zip']) && isset($CONFIG['phpui']['default_zip']))
	$contractorinfo['zip'] = $CONFIG['phpui']['default_zip'];
if(!isset($contractorinfo['city']) && isset($CONFIG['phpui']['default_city']))
	$contractorinfo['city'] = $CONFIG['phpui']['default_city'];
if(!isset($contractorinfo['address']) && isset($CONFIG['phpui']['default_address']))
	$contractorinfo['address'] = $CONFIG['phpui']['default_address'];


$layout['pagetitle'] = trans('Contractor Add');

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$SMARTY->assign('contractorinfo',$contractorinfo);
$SMARTY->assign('cstateslist',$LMS->GetCountryStates());
$SMARTY->assign('countrieslist',$LMS->GetCountries());
$SMARTY->display('contractoradd.html');

?>
