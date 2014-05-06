<?php
if($_GET['id'])
	$layout['pagetitle'] = 'Edytuj strefę numeracyjną';
else
	$layout['pagetitle'] = 'Nowa strefa numeracyjna';
if($d = $_POST['n'])
{
	if($voip->wsdl->checkrate($d))
	{
		if($d['id'])
			$voip->wsdl->editrate($d);
		else
			$d['id'] = $voip->wsdl->addrate($d);
		$SESSION->redirect('?m=v_numbers&id=' . $d['id']);
	}
	else
		$error['name'] = 'Podana nazwa już istnieje w bazie';
}
if($_GET['id']) 
{
	$rate = $voip->wsdl->getratebyid($_GET['id']);
	$SMARTY->assign('n', $rate[0]);
}
$SMARTY->assign('af', array('NIE', 'TAK'));
$SMARTY->assign('rategroups', $voip->rategroups);
$SMARTY->display('v_numbersadd.html');
?>
