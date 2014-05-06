<?php
if(isset($_POST['netadd']))
{
	$netadd = $_POST['netadd'];
	
	foreach($netadd as $key => $value)
	{
		$netadd[$key] = trim($value);
	}

	if($netadd['name'] == '')
		$error['name'] = trans('Network name is required!');
	
	if($netadd['start'] == '' || !preg_match('/^0[0-9]{9}$/', $netadd['start']))
		$error['start'] = 'Niewłaściwy numer';
	if($netadd['count'] == '' || !preg_match('/^[0-9]+$/', $netadd['count']) || $netadd['count'] > 1000)
		$error['count'] = 'Niewłaściwy zakres';

	if(!$error)
	{
		$SESSION->redirect('?m=v_netinfo&id=' . $voip->NetworkAdd($netadd));
	}

	$SMARTY->assign('error', $error);
	$SMARTY->assign('netadd', $netadd);
}

$layout['pagetitle'] = 'Nowa strefa numeracyjna';
$SMARTY->display('v_netadd.html');

?>
