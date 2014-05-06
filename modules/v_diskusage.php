<?php
if(isset($_POST['email']) and check_email($_POST['email']))
{
	$voip->wsdl->UpdateAdminEmail($_POST['email']);
	$SESSION->redirect('?m=v_diskusage');
}

setlocale(LC_NUMERIC, 'C');

if(isset($_POST['du']) and !empty($_POST['du']))
{
	$err = $voip->wsdl->UpdateQuota($_POST['du']);
	if($err)
		$SMARTY->assign('err', $err);
	else
		$SESSION->redirect('?m=v_diskusage');
}
$layout['pagetitle'] = trans('Disk usage');
$c = $voip->wsdl->GetCustomers('surname');
$s = $voip->wsdl->GetSettings();
$maxlimit = $s[8];
foreach($c as $val) $maxlimit -= $val['quotamax'];
if($maxlimit < 0) $maxlimit = 0;
$SMARTY->assign('quotaleft', $maxlimit);
if(isset($s[9]))
	$SMARTY->assign('email', $s[9]);
$SMARTY->assign('du', $c);
$SMARTY->display('v_diskusage.html');
?>
