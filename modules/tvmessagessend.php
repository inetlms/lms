<?php
/*
 * Aplikacja IPTV versja 1.2
 * 2011 ITMSOFT
 * 1.2.1 23/08/2011 19:00:00
 
 *  Modyfikacja: Aplikacja IPTV versja 1.2
 *  2014 SGT
 *  1.2.1 23/08/2011 19:00:00  
*/

$layout['pagetitle'] = trans('Wyślij wiadomość');

$res = null;

if ($_POST['m'] && $_POST['msg_body'] && $_POST['msg_body_extended'] && $_POST['msg_teaser_valid_from']){
	try{
		$res = $LMSTV->MeldingerSend($_POST['customerid'], 
			$_POST['msg_body'], $_POST['msg_teaser_valid_from'], $_POST['msg_teaser_valid_to'], 
			$_POST['msg_body_extended'], $_POST['msg_valid_from'], $_POST['msg_valid_to'], 
			$_POST['msg_prio'], $_POST['msg_show_priority']);
	} catch (Exception $e){
		$errormsg = $e->getMessage();
	}
}

if ($_GET['customerid']){
	$customerid = (int)$_GET['customerid'];
	$customerdata = $LMSTV->GetCustomer($customerid);
}

$today = Date("Y/m/d");

$SMARTY->assignByRef('customerid', $customerid);
$SMARTY->assignByRef('customerdata', $customerdata);
$SMARTY->assignByRef('msg_body', $msg_body);
$SMARTY->assignByRef('msg_body_extended', $msg_body_extended);
$SMARTY->assignByRef('msg_show_priority', $msg_show_priority);
$SMARTY->assignByRef('msg_prio', $msg_prio);
$SMARTY->assignByRef('msg_teaser_valid_from', $today);
$SMARTY->assignByRef('msg_teaser_valid_to', $today);
$SMARTY->assignByRef('msg_valid_from', $today);
$SMARTY->assignByRef('msg_valid_to', $today);
$SMARTY->assignByRef('send_to', $res);
$SMARTY->assignByRef('smsurl', $LMSTV->smsurl);

$SMARTY->display('tvmessagessend.html');
?>
