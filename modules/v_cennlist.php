<?php
$SMARTY->assign('tarifflist', $voip->wsdl->get_cenn());
$layout['pagetitle'] = 'VOIP - lista cenników';
$SMARTY->display('v_cennlist.html');
?>
