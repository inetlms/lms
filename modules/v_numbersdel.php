<?php
if($_GET['id']) $voip->wsdl->ratedel($_GET['id']);
$SESSION->redirect('?m=v_numbers');
?>
