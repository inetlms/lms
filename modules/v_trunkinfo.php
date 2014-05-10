<?php
$res = $voip->GetTrunkInfo($_GET['id']);
$layout['pagetitle'] = 'VoIP Nettelekom - łącze ' . $res['username'];
$SMARTY->assign('nodedata', $res);
$SMARTY->display('v_trunkinfo.html');
?>
