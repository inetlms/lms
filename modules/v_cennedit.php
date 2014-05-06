<?php
$layout['pagetitle'] = 'Edytuj cennik';
if(!isset($_GET['id'])) $SESSION->redirect('?m=v_cennlist');
$ca = $_POST['cennedit'];
if(isset($ca))
{
	$ca['name'] = trim($ca['name']);
	if($ca['name'] == '')
		$error['name'] = 'Nazwa cennika jest wymagana !';
	if($voip->wsdl->CennExists($ca['name'], $ca['id']))
		$error['name'] = 'Taki cennik już istnieje !';
if(!$error) $SESSION->redirect('?m=v_cenninfo&id=' . $voip->wsdl->CennEdit($ca));

}
else $ca = $voip->wsdl->GetOneCenn($_GET['id']);
$SMARTY->assign('error', $error);
$SMARTY->assign('cennedit', $ca);
$SMARTY->display('v_cennedit.html');
?>
