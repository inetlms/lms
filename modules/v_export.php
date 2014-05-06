<?php
$mail = $LMS->GetCustomerEmail($_GET['id']);
if($mail and !$voip->wsdl->login_exists($mail)) 
{
	if($voip->CustomerExists($_GET['id']))
	$SESSION->redirect('?m=customerinfo&id=' . $_GET['id']);
	$voip->export_user($_GET['id']);
	$SESSION->redirect('?m=customerinfo&id=' . $_GET['id']);
}
else
       echo "<font color=\"FF0000\"><b> Klient musi posiadac (unikalny) adres email aby mozna bylo go wykesportowac do bazy VOIP</b></font>  <br> <br> <a href=\"?m=customerinfo&id=" . $_GET['id'] . "\"> Powrot </a>";

?>
