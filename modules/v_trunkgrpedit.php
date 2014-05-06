<?php
$layout['pagetitle'] = 'Edytuj grupę cennikową';
if(!isset($_GET['id'])) $SESSION->redirect('?m=v_trunkgrplist');
$ca = $_POST['cennedit'];
if(isset($ca))
{
	$ca['name'] = trim($ca['name']);
	if($ca['name'] == '')
		$error['name'] = 'Nazwa grupy jest wymagana !';
	if($voip->wsdl->TrunkgrpExists($ca['name'], $ca['id']))
		$error['name'] = 'Taka grupa już istnieje !';
	if(!$error) $SESSION->redirect('?m=v_trunkgrpinfo&id=' . $voip->wsdl->TrunkgrpEdit($ca));
}
else $ca = $voip->wsdl->GetOneTrunkgrp($_GET['id']);
$SMARTY->assign('error', $error);
$SMARTY->assign('cennedit', $ca);
$SMARTY->display('v_trunkgrpedit.html');
?>
