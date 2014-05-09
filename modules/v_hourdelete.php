<?php
if($_GET['id_rates'])
{
	$voip->wsdl->HoursDelete2($_GET['id'], $_GET['id_rates'], $_GET['count']);
	$SESSION->redirect('?m=v_hours&id_rates=' . $_GET['id_rates'] . '&id=' . $_GET['id']);
}
else
{
	$voip->wsdl->HoursDelete($_GET['id'], $_GET['c'], $_GET['count']);
	$SESSION->redirect('?m=v_hours&c=' . $_GET['c'] . '&id=' . $_GET['id']);
}
?>
