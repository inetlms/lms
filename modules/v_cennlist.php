<?php
$SMARTY->assign('tarifflist', $voip->wsdl->get_cenn());
$layout['pagetitle'] = 'VoIP Nettelekom - lista cenników';
$SMARTY->display('v_cennlist.html');
?>
