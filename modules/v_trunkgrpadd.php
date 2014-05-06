<?php
$layout['pagetitle'] = 'Nowa grupa cennikowa';
$ca = $_POST['cennadd'];
if(isset($ca))
{
	$ca['name'] = trim($ca['name']);
	if($ca['name'] == '')
		$error['name'] = 'Nazwa grupy jest wymagana !';
	if($voip->wsdl->TrunkgrpExists($ca['name']))
		$error['name'] = 'Taka grupa już istnieje !';
	if(!$error) $SESSION->redirect('?m=v_trunkgrpinfo&id=' . $voip->wsdl->TrunkgrpAdd($ca['name']));
}
$SMARTY->assign('error', $error);
$SMARTY->assign('cennadd', $ca);
$SMARTY->display('v_trunkgrpadd.html');
?>
