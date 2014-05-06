<?php
$layout['pagetitle'] = 'Strefy numeracyjne';

$netlist = $voip->GetNetworkList();
$listdata['size'] = $netlist['size'];
$listdata['assigned'] = $netlist['assigned'];

unset($netlist['assigned']);
unset($netlist['size']);
$listdata['total'] = sizeof($netlist);

$SMARTY->assign('listdata', $listdata);
$SMARTY->assign('netlist', $netlist);
$SMARTY->display('v_netlist.html');

?>
