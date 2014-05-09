<?php
$voip->TrunkDel($_GET['id']);
$SESSION->redirect('?m=v_trunklist');
?>
