<?php
$SESSION->save('backto', $_SERVER['QUERY_STRING']);

if(isset($_POST['search']))
	$nodesearch = $_POST['search'];

if(!isset($nodesearch))
	$SESSION->restore('v_nodesearch', $nodesearch);
else
	$SESSION->save('v_nodesearch', $nodesearch);

if(isset($_GET['search'])) 
{
	$layout['pagetitle'] = trans('SIP Search Results');

	$nodelist = $voip->wsdl->GetNodeList($nodesearch);
	$listdata['total'] = count($nodelist);

	$SMARTY->assign('nodelist', $nodelist);
	$SMARTY->assign('listdata', $listdata);
	
	if($listdata['total'] == 1)
		$SESSION->redirect('?m=v_nodeinfo&id=' . $nodelist[0]['id']);
	else
		$SMARTY->display('v_nodesearchresults.html');
}
?>
