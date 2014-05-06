<?php
if(!$voip->wsdl->GetCustomersWithTariff($_GET['id']) && $_GET['is_sure'] = '1')
	$voip->wsdl->TariffDelete($_GET['id']);
$SESSION->redirect('?m=v_tarifflist');
?>
