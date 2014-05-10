<?php
$SMARTY->assign('trunklist', $voip->GetTrunkList());
$layout['pagetitle'] = 'VoIP Nettelekom - lista łącz';
$SMARTY->display('v_trunklist.html');
?>
