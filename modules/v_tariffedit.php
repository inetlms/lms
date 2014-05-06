<?php
$tariff = $_POST['tariff'];

if(isset($tariff))
{
	foreach($tariff as $key => $value)
		if(!is_array($value)) $tariff[$key] = trim($value);

	$tariff['amount'] = str_replace(',', '.', $tariff['amount']);
	

	if($tariff['name'] == '')
		$error['name'] = 'Nazwa jest wymagana';

	if($tariff['amount'] == '')
		$error['amount'] = trans('Value required!');
	elseif(!preg_match('/^[-]?[0-9.,]+$/', $tariff['amount']))
		$error['amount'] = trans('Incorrect value!');
	
	
	if(!$tariff['tax'])
		$tariff['tax'] = 23;

	$tariff['id'] = $_GET['id'];

	if(!$error)
	{
		$voip->wsdl->TariffUpdate($tariff);
		$SESSION->redirect('?m=v_tariffinfo&id=' . $tariff['id']);
	}
	else
	{
		$adserv = $voip->GetTariff($_GET['id']);
		$tariff['addserv'] = $adserv['addserv'];
	}
}
else
	$tariff = $voip->GetTariff($_GET['id']);
	
$layout['pagetitle'] = 'Edycja abonamentu ' . $tariff['name'];
$SMARTY->assign('tariff', $tariff);
$SMARTY->assign('error', $error);
$SMARTY->display('v_tariffedit.html');
?>
