<?php
if($customerinfo['isvoip'] == 1)
{
	$v = TRUE;
	$SMARTY->assign('isvoip', TRUE);

        $customerinfo = $voip->wsdl->GetCustomer($customerinfo, $customerid);
	if($setl = $CONFIG['voip']['voip_set_remb'])
	{
		if(date('Y-m-d', strtotime('+' . $setl . ' month')) >= $customerinfo['voipkoniecum'])
			$SMARTY->assign('setl', $setl);
	}
        $customersip = $voip->wsdl->GetCustomerNodes($customerid);
	$customersip['ownerid'] = $customerid;
	$SMARTY->assign('customersip', $customersip);
	$SMARTY->assign('cdr', $voip->wsdl->GetLastUserCdr($customerid));
	if($customerinfo['woj'] && $customerinfo['pow'] && $customerinfo['mia'])
	{
		$tmp = $voip->wsdl->list_woj();
		$geoloc = $tmp[$customerinfo['woj']];
		$tmp = $voip->wsdl->list_pow($customerinfo['woj']);
		$geoloc .= ' - &gt; ' . $tmp[$customerinfo['pow']];
		$tmp = $voip->wsdl->list_mia($customerinfo['pow']);
		$geoloc .= ' - &gt; ' . $tmp[$customerinfo['mia']];
		$tmp = null;
	}
	else $geoloc = '<B>BRAK !! KONIECZNIE UZUPEŁNIJ !!</B>';
	$SMARTY->assign('geoloc', $geoloc);
	$SMARTY->assign('id_tariffs', $voip->wsdl->get_id_tariffs());
	$SMARTY->assign('id_subscriptions', $voip->wsdl->get_id_subscriptions());
	$SMARTY->assign('woj',$voip->wsdl->list_woj());
	$SMARTY->assign('pow',$voip->wsdl->list_pow($customerinfo['woj']));
	$SMARTY->assign('mia',$voip->wsdl->list_mia($customerinfo['pow']));
}
?>
