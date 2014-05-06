<?php
if(!preg_match('/^[0-9]+$/', $_GET['id']))
{
	$SESSION->redirect('?m=v_nodelist');
}

if(!$voip->wsdl->NodeExists($_GET['id']))
	if(isset($_GET['ownerid']))
	{
		$SESSION->redirect('?m=customerinfo&id=' . $_GET['ownerid']);
	}
	else
	{
		$SESSION->redirect('?m=v_nodelist');
	}


$nodeid = $_GET['id'];
$customerid = $voip->wsdl->GetNodeOwner($nodeid);

include(MODULES_DIR . '/customer.inc.php');
include(MODULES_DIR . '/customer.voip.inc.php');

$nodeinfo = $voip->GetNode($nodeid);
$nodeinfo['ownerid'] = $customerid;
$nodeinfo['id'] = $nodeinfo['id_ast_sip'];
$nodeinfo['createdby'] = $LMS->GetUserName($nodeinfo['creatorid']);
if($nodeinfo['modifierid']) $nodeinfo['modifiedby'] = $LMS->GetUserName($nodeinfo['modifierid']);

$sub = $voip->wsdl->get_id_subscriptions();
$tar = $voip->wsdl->get_id_tariffs();
$nodeinfo['id_subscriptions'] = $sub[$nodeinfo['id_subscriptions']];
$nodeinfo['id_tariffs'] = $tar[$nodeinfo['id_tariffs']];
$SESSION->save('backto', $_SERVER['QUERY_STRING']);

if(!isset($_GET['ownerid']))
	$SESSION->save('backto', $SESSION->get('backto').'&ownerid=' . $ownerid);

$layout['pagetitle'] = 'Informacje o koncie ' . $nodeinfo['name'];

$SMARTY->assign('nodedata', $nodeinfo);
$SMARTY->display('v_nodeinfo.html');
?>
