<?php
$tariffadd = $_POST['tariffadd'];

if(isset($tariffadd))
{
	foreach($tariffadd as $key => $value)
		$tariffadd[$key] = trim($value);

	if($tariffadd['name'] == '' && $tariffadd['amount'] == '')
	{
		$SESSION->redirect('Location: ?m=v_tarifflist');
	}

	$tariffadd['amount'] = str_replace(',', '.', $tariffadd['amount']);

	if(!preg_match('/^[-]?[0-9.,]+$/', $tariffadd['amount']))
		$error['amount'] = 'Błędna wartość';
	if($tariffadd['name'] == '')
		$error['name'] = 'Nazwa jest wymagana';
	else
		if($voip->wsdl->GetTariffIDByName($tariffadd['name']))
			$error['name'] = 'Abonament o tej nazwie już istnieje';

	if(!$tariffadd['tax'])
		$tariffadd['tax'] = 23;
		
	if(!$error)
	{
		$voip->wsdl->TariffAdd($tariffadd);
		$SESSION->redirect('?m=v_tarifflist');
	}
	
}

$layout['pagetitle'] = 'Nowy abonament';

$SMARTY->assign('error', $error);
$SMARTY->assign('tariffadd', $tariffadd);

$SMARTY->display('v_tariffadd.html');

?>
