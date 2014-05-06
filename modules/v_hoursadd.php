<?php
if($_GET['id_rates'])
{
	$rate = $voip->wsdl->getratebyid($_GET['id_rates']);
	$layout['pagetitle'] = $voip->wsdl->GetCennName($_GET['id']) . ' - ' . $rate[0]['desc'] . ' - edytuj godziny';
}
else
	$layout['pagetitle'] = $voip->wsdl->GetCennName($_GET['id']) . ' - ' . $voip->rategroups[$_GET['c']] . ' - edytuj godziny';

$ha = $_POST['hoursadd'];
if(isset($ha))
{
	foreach($ha as $key => $val)
		$ha[$key] = trim($val);
	$ha['price'] = str_replace(',', '.', $ha['price']);
	$days = $_POST['days'];
	if(!preg_match('/^[0-2][0-9]:[0-5][0-9]$/', $ha['from']))
		$error['from'] = 'Błędna godzina !';
	if(!preg_match('/^[0-2][0-9]:[0-5][0-9]$/', $ha['to']))
		$error['to'] = 'Błędna godzina !';
	if(!preg_match('/\d+/', $ha['price']))
		$error['price'] = 'Błędna kwota !';
	
	if(!$error) 
	{
		if($_GET['id_rates'])
		{
			$voip->wsdl->AddHours2($ha, $days, $_GET['id_rates']);
			$SESSION->redirect('?m=v_hours&id_rates=' . $_GET['id_rates'] . '&id=' . $_GET['id']);
		}
		else
		{
			$voip->wsdl->AddHours($ha, $days, $_GET['c']);
			$SESSION->redirect('?m=v_hours&c=' . $_GET['c'] . '&id=' . $_GET['id']);
		}
	}
	$SMARTY->assign('hoursadd', $ha);
	$SMARTY->assign('days', $days);
}
$SMARTY->assign('error', $error);
$SMARTY->assign('listdata', array('c' => $_GET['c'], 'id' => $_GET['id']));
$SMARTY->display('v_hoursadd.html');
?>
