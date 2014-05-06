<?php
if(!$voip->NetworkExists($_GET['id']))
{
	$SESSION->redirect('?m=v_netlist');
}

$network = $voip->GetNetworkRecord($_GET['id']);

if($network['assigned'])
	$error['delete'] = TRUE;

if(!$error)
{
	if($_GET['is_sure'])
	{
		$voip->NetworkDelete($network['id']);
		$SESSION->redirect('?m=' . $SESSION->get('lastmodule') . '&id=' . $_GET['id']);
	}
	else
	{
		$layout['pagetitle'] = trans('Removing network $a', strtoupper($network['name']));
		$SMARTY->display('header.html');
		echo '<H1>' . $layout['pagetitle'] . '</H1>';
		echo '<P>' . trans('Are you sure, you want to delete that network?') . '</P>';
		echo '<A href="?m=v_netdel&id=' . $network['id'] . '&is_sure=1">' . trans('Yes, I am sure.') . '</A>';
		$SMARTY->display('footer.html');
	}
}
else
{
	$layout['pagetitle'] = trans('Info Network: $a', $network['name']);
	$SMARTY->assign('network', $network);
	$SMARTY->assign('error', $error);
	$SMARTY->display('v_netinfo.html');
}

?>
