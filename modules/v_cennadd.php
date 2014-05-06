<?php
$layout['pagetitle'] = 'Nowy cennik';
$ca = $_POST['cennadd'];
if(isset($ca))
{
	$ca['name'] = trim($ca['name']);
	if($ca['name'] == '')
		$error['name'] = 'Nazwa cennika jest wymagana !';
	if($voip->wsdl->CennExists($ca['name']))
		$error['name'] = 'Taki cennik już istnieje !';
if(!$error) $SESSION->redirect('?m=v_cenninfo&id=' . $voip->wsdl->CennAdd($ca['name']));
}
$SMARTY->assign('error', $error);
$SMARTY->assign('cennadd', $ca);
$SMARTY->display('v_cennadd.html');
?>
