<?php
/*
 * Aplikacja IPTV versja 1.2
 * 2011 ITMSOFT
 
 * Aplikacja IPTV versja 1.2
 * 2011 SGT 
 * 1.2.1 23/08/2011 19:00:00
*/

$errormsg= null;
try{
	//wydawanie stb
	if ($_POST['linkstb'] && $_POST['account_id'] && $_POST['subnet_id'] && $_POST['cust_order_id']){
		try{
			$res = $LMSTV->StbLink($customerid, $_POST['account_id'], $_POST['linkstb'], $_POST['subnet_id'], $_POST['cust_order_id']);
			$SESSION->redirect('?m=customerinfo&id='.$customerid);
		} catch (Exception $e){
			$errormsg = $e->getMessage();
		}
	}

	// dodawanie pakietow
	if ($_POST['pkglist'] && $_POST['pkglistdate']){
		try{
			$pkglist = array();
			foreach($_POST['pkglist'] as $key => $pkg){
				$pkglist[] = $key;
			}
			if (!(int)$_POST['acttype']){
				$res = (int)$LMSTV->PackagesAdd($customerid, $_POST['account_id'], $pkglist, $_POST['pkglistdate']);
			} else {
				$res = (int)$LMSTV->PackagesEntitle($customerid, $_POST['account_id'], $pkglist, $_POST['pkglistdate']);
			}
			$SESSION->redirect('?m=customerinfo&id='.$customerid);
		} catch (Exception $e){
			$errormsg = $e->getMessage();
			$tocheck = $pkglist;
			$toopen = 'tv-add-packages'.$_POST['account_id'];
		}
	}

	// zmiana pinów
	if ($_POST['cust_master_pin'] && $_POST['cust_vod_pin']){
		try {
			$LMSTV->UpdatePin($customerid, $_POST['cust_master_pin'], $_POST['cust_vod_pin']);
			$SESSION->redirect('?m=customerinfo&id='.$customerid);
		} catch (Exception $e){
			$errormsg = $e->getMessage();
		}
	}

	if ($_POST['edit_account'] == "1"){
		try{
			$res = (int)$LMSTV->AccountEdit($customerid, $_POST['account_id'], array(
		'cust_i_city' 		=> $_POST['cust_i_city'],
		'cust_i_street' 	=> $_POST['cust_i_street'],
		'cust_i_home_nr' 	=> $_POST['cust_i_home_nr'],
		'cust_i_flat' 		=> $_POST['cust_i_flat'],
		'cust_i_postal_code'=> $_POST['cust_i_postal_code'],		
			));
			$SESSION->redirect('?m=customerinfo&id='.$customerid);
		} catch (Exception $e){
			$errormsg = $e->getMessage();
		}
	}

	if ($_POST['add_account'] == "1"){
		try{
			$res = (int)$LMSTV->AccountAdd($_POST['cust_number'], $customerid, array(
		'cust_i_city' 		=> $_POST['cust_i_city'],
		'cust_i_street' 	=> $_POST['cust_i_street'],
		'cust_i_home_nr' 	=> $_POST['cust_i_home_nr'],
		'cust_i_flat' 		=> $_POST['cust_i_flat'],
		'cust_i_postal_code'=> $_POST['cust_i_postal_code'],		
			));
			$SESSION->redirect('?m=customerinfo&id='.$customerid);
		} catch (Exception $e){
			$errormsg = $e->getMessage();
		}
	}

	if ($_GET['account_del']){
		try {
			$res = (int)$LMSTV->AccountDel($customerid, $_GET['account_del']);
			$SESSION->redirect('?m=customerinfo&id='.$customerid);
		} catch (Exception $e){
			$errormsg = $e->getMessage();
		}
	}
	


	if ($_POST['subscription_termiante']){
		try {
			
			$res = (int)$LMSTV->SubscriptionTerminate($customerid, $_POST['account_id'], $_POST['subscription_termiante'], $_POST['term_date'], $_POST['term_fee'], $_POST['term_desc']);
			$SESSION->redirect('?m=customerinfo&id='.$customerid);
			
		} catch (Exception $e){
			$errormsg = $e->getMessage();
		}
	}

	if ($_GET['unlink'] && $_GET['account_id']){
		try {
			$res = (int)$LMSTV->StbUnlink($customerid, $_GET['account_id'], $_GET['unlink']);
			$SESSION->redirect('?m=customerinfo&id='.$customerid);
		} catch (Exception $e){
			$errormsg = $e->getMessage();
		}
	}

	if ($_GET['account_lock']){
		try{
			$res = (int)$LMSTV->AccountLock($customerid, $_GET['account_lock']);
			$SESSION->redirect('?m=customerinfo&id='.$customerid);
		} catch (Exception $e){
			$errormsg = $e->getMessage();
		}
	}

	if ($_GET['account_unlock']){
		try{
			$res = (int)$LMSTV->AccountUnlock($customerid, $_GET['account_unlock']);
			$SESSION->redirect('?m=customerinfo&id='.$customerid);
		} catch (Exception $e){
			$errormsg = $e->getMessage();
		}
	}

	if ($_GET['account_id'] && $_GET['activate']){
		try{
			$res = (int)$LMSTV->SubscriptionActivate($customerid, $_GET['account_id'], $_GET['activate']);
			$SESSION->redirect('?m=customerinfo&id='.$customerid);
		} catch (Exception $e){
			$errormsg = $e->getMessage();
		}
	}
	
	if ($_GET['meldinger_delete_all']){
		try{
			$res = (int)$LMSTV->MeldingerDel(null, $_GET['meldinger_delete_all']);
			$SESSION->redirect('?m=customerinfo&id='.$customerid);
		} catch (Exception $e){
			$errormsg = $e->getMessage();
		}
	}
	
	if ($_GET['meldinger_delete']){
		try{
			$res = (int)$LMSTV->MeldingerDel(array($_GET['meldinger_delete']));
			$SESSION->redirect('?m=customerinfo&id='.$customerid);
		} catch (Exception $e){
			$errormsg = $e->getMessage();
		}
	}	
	
	$cust_number = $LMSTV->ExistsInLMS($customerid);

	if (!empty($cust_number)) {
	
	$cust_data 				= $LMSTV->GetCustomer($customerid);
		
	$customertvjamboxaccounts 	= $LMSTV->CustomerGetSubscriptions($customerid, $cust_number);
	if (!empty($customertvjamboxaccounts)) {
		$subnetlist 		= $LMSTV->SubnetList(); 
		$tvmessagelist 		= $LMSTV->MessagesList($customerid, $cust_number);
	}
	$accblockednum 			= 0;

	foreach ($customertvjamboxaccounts as $key => $acc){
		if ($acc['acc_closed']) {
			unset($customertvjamboxaccounts[$key]);
		}
		if (!$acc['acc_active']){
			$accblockednum++;
		}
	}
	foreach ($customertvjamboxaccounts as $key => $acc){
		$stb_list = $LMSTV->AccountGetSTB($customerid, $acc['account_id']);
		foreach($stb_list as $keys => $stb){
			foreach($subnetlist as $keysu => $subnet){
				if ($subnet['subnet_id'] == $stb['subnet_id']){
					$stb_list[$keys]['subnet_name'] = $subnet['subnet_name'];
				}
			}
		}
		foreach ($acc['subscriptions'] as $keyp => $pkg){
			$customertvjamboxaccounts[$key]['subscriptions'][$keyp]['stb_list'] = array();
			$customertvjamboxaccounts[$key]['subscriptions'][$keyp]['stb_count'] = 0;
			//$customertvjamboxaccounts[$key]['subscriptions'][$keyp]['pkg_base'] = (bool)ereg("pkg_base", $pkg['pkg_class']);
			$customertvjamboxaccounts[$key]['subscriptions'][$keyp]['pkg_base'] = (bool)preg_match("/pkg_base/", $pkg['pkg_class']);
			$customertvjamboxaccounts[$key]['subscriptions'][$keyp]['stb_max'] = (int)str_replace(array("pkg_base", "pkg_device", "(", ")", ","), "", $pkg['pkg_class']);
			$customertvjamboxaccounts[$key]['subscriptions'][$keyp]['stb_left'] = $customertvjamboxaccounts[$key]['subscriptions'][$keyp]['stb_max'];
			foreach ($stb_list as $keys => $stb){
				if ($stb['cust_order_id'] == $pkg['cust_order_id']){
					$customertvjamboxaccounts[$key]['subscriptions'][$keyp]['stb_list'][] = $stb;
					$customertvjamboxaccounts[$key]['subscriptions'][$keyp]['stb_count']++;
					$customertvjamboxaccounts[$key]['subscriptions'][$keyp]['stb_left']--;
				}
			}
		}
		$customertvjamboxaccounts[$key]['can_add_package_list'] = $LMSTV->PackageGetAvail($customerid, $acc['account_id']);
	}
	}

	if ($_GET['tvexport'] && $_GET['id']){
		$LMSTV->CustomerExport($customerid);
		$SESSION->redirect('?m=customerinfo&id='.$customerid);
	}
	
} catch (Exception $e){
	$errormsg = "Wystąpił błąd. " . $e->getMessage();
}

$SMARTY->assign('cust_data', $cust_data);

$SMARTY->assign('customertvjamboxaccounts', $customertvjamboxaccounts);
$SMARTY->assign('accblockednum', $accblockednum);

$SMARTY->assign('subnetlist', $subnetlist);
if (!is_array($tocheck)) $tocheck = array();
$SMARTY->assign('tocheck', $tocheck);
$SMARTY->assign('toopen', $toopen);
$SMARTY->assign('tvmessagelist', $tvmessagelist);
$SMARTY->assign('todaydate', date("Y/m/d"));
$SMARTY->assign('errormsg', $errormsg);
$SMARTY->assign('smsurl', $LMSTV->smsurl);
?>
