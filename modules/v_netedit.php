<?php
if(!$voip->NetworkExists($_GET['id']))
{
	$SESSION->redirect('?m=v_netlist');
}

if($SESSION->is_set('v_ntlp.'.$_GET['id']) && ! isset($_GET['page']))
	$SESSION->restore('v_ntlp.'.$_GET['id'], $_GET['page']);

$SESSION->save('v_ntlp.'.$_GET['id'], $_GET['page']);
	
$network = $voip->GetNetworkRecord($_GET['id'],$_GET['page'], $LMS->CONFIG['phpui']['networkhosts_pagelimit']);

if(isset($_POST['networkdata']))
{
	$networkdata = $_POST['networkdata'];

	foreach($networkdata as $key => $value)
		$networkdata[$key] = trim($value);
		
	$networkdata['id'] = $_GET['id'];
	if($networkdata['name']=='')
		$error['name'] = trans('Network name is required!');
	if($networkdata['start'] == '' || !preg_match('/^0[0-9]{9}$/', $networkdata['start']))
		$error['start'] = 'Niewłaściwy numer';
	if($networkdata['end'] == '' || !preg_match('/^0[0-9]{9}$/', $networkdata['end']))
		$error['end'] = 'Niewłaściwy numer';
	if($networkdata['start'] >= $networkdata['end'] || $networkdata['end'] - $networkdata['start'] > 1000)
		$error['end'] = 'Niewłaściwy numer';

	if(!$error)
	{
		$voip->NetworkUpdate($networkdata);
		$SESSION->redirect('?m=v_netinfo&id=' . $networkdata['id']);
	}	

	$network['address'] = $networkdata['address'];
	$network['size'] = $networkdata['size'];
}
$layout['pagetitle'] = trans('Network Edit: $a', $network['name']);
$SMARTY->assign('unlockedit', TRUE);
$SMARTY->assign('network', $network);
$SMARTY->assign('error', $error);
$SMARTY->display('v_netinfo.html');
?>
