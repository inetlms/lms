<?php
$tariff = $voip->GetTariff($_GET['id']);
$layout['pagetitle'] = 'Informacje o abonamencie ' . $tariff['name'];
$SESSION->save('backto', $_SERVER['QUERY_STRING']);
$SMARTY->assign('tariff', $tariff);
$SMARTY->assign('tariffs', $voip->wsdl->Get_Tariffs());
$SMARTY->display('v_tariffinfo.html');
?>
