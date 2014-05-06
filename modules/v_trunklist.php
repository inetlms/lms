<?php
$SMARTY->assign('trunklist', $voip->GetTrunkList());
$layout['pagetitle'] = 'VOIP - lista łącz';
$SMARTY->display('v_trunklist.html');
?>
