<?php
$api = new floAPI($voip->config['voip_as_login'], $voip->config['voip_as_pass'], $voip->config['voip_as_host']);
$api->request('COMMAND', array('COMMAND' => 'core restart now'), false);
sleep(5);
$SESSION->redirect('?m=v_state');
?>
