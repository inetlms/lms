<?php
$SMARTY->assign('trunkgrplist', $voip->wsdl->GetTrunkgrpList());
$layout['pagetitle'] = 'VOIP - lista grup cennikowych';
$SMARTY->display('v_trunkgrplist.html');
?>
