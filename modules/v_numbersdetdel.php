<?php
if($_GET['id']) $voip->wsdl->deletenumber($_GET['id']);
$SESSION->redirect('?m=v_numbersdet&id=' . $_GET['id_rates']);
?>
