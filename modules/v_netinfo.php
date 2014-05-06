<?php
if(!$voip->NetworkExists($_GET['id']))
{
	$SESSION->redirect('?m=v_netlist');
}

$page = isset($_GET['page']) ? $_GET['page'] : 1;

if($SESSION->is_set('v_ntlp.'.$_GET['id']) && !isset($_GET['page']))
	$SESSION->restore('v_ntlp.'.$_GET['id'], $page);

$SESSION->save('v_ntlp.'.$_GET['id'], $page);

$network = $voip->GetNetworkRecord($_GET['id'], $page, $LMS->CONFIG['phpui']['networkhosts_pagelimit']);

$layout['pagetitle'] = 'Informacje o strefie ' . $network['name'];

$SMARTY->assign('network', $network);
$SMARTY->display('v_netinfo.html');

?>
