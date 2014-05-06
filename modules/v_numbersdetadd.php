<?php
$idr = $_GET['id_rates'];
if(!$idr) $SESSION->redirect('?m=v_numbers');
$rate = $voip->wsdl->getratebyid($idr);
$layout['pagetitle'] = 'Wzorzec dla strefy ' . $rate[0]['desc'];

if($d = $_POST['n'])
{
	$d['pattern'] = str_replace('x', 'X', $d['pattern']);
	if(!preg_match('/^[0-9X]{3,}$/', $d['pattern'])) $error['name'] = 'Błędny wzorzec';
	if($voip->wsdl->checkifpatternexists($d)) $error['name'] = 'Podany wzorzec już istnieje';
	if(!$error)
	{
		if($d['id']) $voip->wsdl->editnumber($d);
		else $voip->wsdl->addnumber($d);
		$SESSION->redirect('?m=v_numbersdet&id=' . $d['id_rates']);
	}
}

if($_GET['id'])
	$SMARTY->assign('n', $voip->wsdl->getnumberbyid($_GET['id']));
else
	$SMARTY->assign('n', array('id_rates' => $idr));
if($error)
{
	$SMARTY->assign('error', $error);
	$SMARTY->assign('n', array('id_rates' => $idr, 'pattern' => $d['pattern']));
}
$SMARTY->display('v_numbersdetadd.html');
?>
