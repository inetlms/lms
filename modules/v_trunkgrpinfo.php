<?php
$layout['pagetitle'] = $voip->wsdl->GetTrunkgrpName($_GET['id']);
$t = $voip->wsdl->GetTrunkHours($_GET['id']);
$SMARTY->assign('t', $t);
$SMARTY->display('v_trunkgrpinfo.html');
?>
