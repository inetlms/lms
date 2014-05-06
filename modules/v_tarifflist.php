<?php
$SMARTY->assign('tarifflist', $voip->wsdl->get_tariffs());
$layout['pagetitle'] = 'VOIP - telefonia internetowa';
$SMARTY->display('v_tarifflist.html');
?>
