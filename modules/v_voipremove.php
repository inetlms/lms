<?php
$voip->DeleteCustomer($_GET['id']);
$SESSION->redirect('?m=customerinfo&id=' . $_GET['id']);
?>
