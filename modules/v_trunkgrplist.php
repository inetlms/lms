<?php
$SMARTY->assign('trunkgrplist', $voip->wsdl->GetTrunkgrpList());
$layout['pagetitle'] = 'VoIP Nettelekom - lista grup cennikowych';
$SMARTY->display('v_trunkgrplist.html');
?>
