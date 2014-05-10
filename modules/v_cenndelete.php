<?php
$voip->wsdl->CennDelete($_GET['id']);
$SESSION->redirect('?m=v_cennlist');
?>
