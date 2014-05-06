<?php
if(isset($_POST['id_subscriptions']))
	$voip->wsdl->massch_s($_POST['id_subscriptions'], $_GET['ownerid']);
elseif(isset($_POST['id_tariffs']))
	$voip->wsdl->massch_t($_POST['id_tariffs'], $_GET['ownerid']);
$SESSION->redirect('?m=customerinfo&id=' . $_GET['ownerid']);
?>
