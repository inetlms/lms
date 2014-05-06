<?php
if($_POST['cennfrom'] && $_POST['act'] == 'Skopiuj')
	$voip->wsdl->MoveCenn($_POST['cennfrom'], $_GET['id'], $_POST['grupa1']);
elseif($_POST['co'] && $_POST['cennchange'] && substr($_POST['act'], 0, 4) == 'Zmie')
	$voip->wsdl->CennChange($_GET['id'], $_POST['cennchange'], $_POST['co'], $_POST['grupa']);
$layout['pagetitle'] = $voip->wsdl->GetCennName($_GET['id']);
$t = $voip->wsdl->GetHours($_GET['id']);
$SMARTY->assign('t', $t);
$cennlist = $voip->wsdl->get_cenn();
$trunks = $voip->wsdl->GetTrunkgrpList();
$out = array();
foreach($cennlist as $val) if($val['id'] != $_GET['id']) $out[$val['id']] = $val['name'];
foreach($trunks as $val) $out['t_' . $val['id']] = $val['name'];
$SMARTY->assign('cennfrom', $out);
$SMARTY->assign('rategr', $voip->rategroups);
$cust = $voip->GetCustomersWithT($_GET['id']);
$SMARTY->assign('tariff', $cust);
$SMARTY->display('v_cenninfo.html');
?>
