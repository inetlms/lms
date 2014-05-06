<?php
if($_GET['id_rates'])
{
	$rate = $voip->wsdl->getratebyid($_GET['id_rates']);
	$layout['pagetitle'] = $voip->wsdl->GetCennName($_GET['id']) . ' - ' . $rate[0]['desc'];
	$hours = $voip->wsdl->GetHourDetails2($_GET['id'], $_GET['id_rates']);
}
else
{
	$layout['pagetitle'] = $voip->wsdl->GetCennName($_GET['id']) . ' - ' . $voip->rategroups[$_GET['c']];
	$hours = $voip->wsdl->GetHourDetails($_GET['id'], $_GET['c']);
}
$SMARTY->assign('hours', $hours);
$SMARTY->assign('listdata', $_GET);
$SMARTY->display('v_hours.html');
?>
