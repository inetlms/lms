<?php
$SMARTY->assign('tarifflist', $voip->wsdl->get_tariffs());
$layout['pagetitle'] = 'VoIP Nettelekom - telefonia internetowa';
$SMARTY->display('v_tarifflist.html');
?>
