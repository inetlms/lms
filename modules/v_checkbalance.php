<?php
$users = $voip->wsdl->GetCustomerNames();
foreach($users as $us)
{
	$voip->wsdl->UpdateCustomerBalance($us['id'], -$LMS->GetCustomerBalance($us['id']));
}
$t = $LMS->GetTaxes();
if(is_array($t)) foreach($t as $val) if($val['id'] == $voip->config['taxid']) $voip->wsdl->UpdateTax($val['value']);
$SESSION->redirect('?' . $SESSION->get('backto'));
?>
