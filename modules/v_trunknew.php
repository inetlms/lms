<?php
$nodedata = $_POST['nodedata'];

if(isset($nodedata))
{
	foreach($nodedata as $key => $value)
		$nodedata[$key] = trim($value);

	if($nodedata['name'] == '')
		$error['name'] = 'Nazwa łącza jest wymagana !';
	elseif(strlen($nodedata['name']) > 16)
		$error['name'] = 'Nazwa łącza jest zbyt długa (max 16 znaków) !';
	elseif($voip->GetTrunkIDByName($nodedata['name']))
		$error['name'] = trans('Specified name is in use!');
	elseif(!eregi('^[_a-z0-9-]+$', $nodedata['name']))
		$error['name'] = trans('Specified name contains forbidden characters!');
	elseif($nodedata['username'] == '')
		$error['username'] = 'Login jest wymagany !';
	elseif($nodedata['defaultip'] == '')
		$error['defaultip'] = 'Adres IP jest wymagany !';
	elseif(!eregi('^[0-9\.]+$', $nodedata['defaultip']))
		$error['defaultip'] = trans('Specified name contains forbidden characters!');
	elseif($nodedata['dial_string'] == '')
		$error['dial_string'] = 'Dial string jest wymagany !';
	
	if(strlen($nodedata['secret']) > 32)
		$error['secret'] = trans('Password is too long (max.32 characters)!');

	if(!$error)
		$SESSION->redirect('?m=v_trunkinfo&id=' . $voip->TrunkAdd($nodedata));
}


$layout['pagetitle'] = trans('Nowe łącze');
$tr = $voip->GetTrunkgrpList();
$trunkgroups = array();
foreach($tr as $val)
	$trunkgroups[$val['id']] = $val['name'];
	$SMARTY->assign('trunkgroups', $trunkgroups);
$SMARTY->assign('yesno1', array('0' => 'Nie', '1' => 'Tak'));
$SMARTY->assign('yesno', array('no' => 'Nie', 'yes' => 'Tak'));
$SMARTY->assign('dtmfmode', array('rfc2833' => 'rfc2833', 'inband' => 'inband', 'info' => 'info', 'auto' => 'auto'));
$SMARTY->assign('nat', array('yes' => 'Tak', 'no' => 'Nie', 'never' => 'Nigdy', 'route' => 'Route'));
$SMARTY->assign('error', $error);
$SMARTY->assign('nodedata', $nodedata);
$SMARTY->display('v_trunknew.html');
?>
