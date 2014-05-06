<?php
$layout['pagetitle'] = 'Wirtualna centrala';
$customer = array();
$customer = $voip->wsdl->GetCustomer($customer, $voip->wsdl->GetNodeOwner($_GET['id']));
if($customer['virtualpbx'] != 't') $SESSION->redirect('?m=v_nodeinfo&id=' . $_GET['id']);
$voip->parse_dialplan();
if($_POST['id'] && $_POST['pbx'])
{
	$voip->add_to_dialplan($_POST['pbx'], $_POST['id']);
	$voip->reload_dialplan();
}
$res = $voip->wsdl->search_dialplan($_GET['id'], $voip->dialplan);
$SMARTY->assign('pbx', $res);
$SMARTY->display('v_vpbx.html');
?>
