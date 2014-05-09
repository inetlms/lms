<?php
if($_GET['id_rates'])
{
	$voip->wsdl->TrunkHoursDelete2($_GET['id'], $_GET['id_rates'], $_GET['count']);
	$SESSION->redirect('?m=v_trunkhours&id_rates=' . $_GET['id_rates'] . '&id=' . $_GET['id']);
}
else
{
	$voip->wsdl->TrunkHoursDelete($_GET['id'], $_GET['c'], $_GET['count']);
	$SESSION->redirect('?m=v_trunkhours&c=' . $_GET['c'] . '&id=' . $_GET['id']);
}
?>
