<?php

//echo "<pre>"; print_r($_POST); echo "</pre>"; die;


if(isset($_POST['netdev']))
{
	$netdevdata = $_POST['netdev'];
//	$netdevdata['name'] = $_POST['name'];
//	$netdevdata['teryt'] = $_POST['teryt'];
	if($netdevdata['ports'] == '') $netdevdata['ports'] = 1;
	if(empty($netdevdata['clients'])) $netdevdata['clients'] = 0;
	else $netdevdata['clients'] = intval($netdevdata['clients']);
	if($netdevdata['purchasedate'] != '') $netdevdata['purchasetime'] = strtotime($netdevdata['purchasedate']);
	if($netdevdata['guaranteeperiod'] == -1) $netdevdata['guaranteeperiod'] = NULL;
	if(!isset($netdevdata['shortname'])) $netdevdata['shortname'] = '';
	if(!isset($netdevdata['secret'])) $netdevdata['secret'] = '';
	if(!isset($netdevdata['community'])) $netdevdata['community'] = '';
	if(!isset($netdevdata['nastype'])) $netdevdata['nastype'] = 0;
	if (empty($netdevdata['teryt'])) 
	{
		$netdevdata['location_city'] = null;
		$netdevdata['location_street'] = null;
		$netdevdata['location_house'] = null;
		$netdevdata['location_flat'] = null;
	}
	
	if ($netdevdata['networknodeid'] && (!$netdevdata['backbone_layer'] || !$netdevdata['distribution_layer'] || !$netdevdata['access_layer'] || !$netdevdata['sharing']))
	{
	    if ($tmp = $DB->GetRow('SELECT backbone_layer, distribution_layer, access_layer, sharing FROM networknode WHERE id = ? LIMIT 1;',array(intval($netdevdata['networknodeid']))))
	    foreach ($tmp as $key => $item)
		$netdevdata[$key] = $item;
	}
	
	if (!$netdevdata['backbone_layer'] && !$netdevdata['distribution_layer'] && !$netdevdata['access_layer'])
	{
	    $netdevdata['backbone_layer'] = 0;
	    $netdevdata['distribution_layer'] = $netdevdata['access_layer'] = 1;
	}
	
//	$netdevdata['name'] = (str_replace(" ","_",$netdevdata['name']));
	
	if (!$netdevdata['devtype'])
	    $netdevdata['managed'] = NIE;
	
	$DB->LockTables('netdevices');
	if (!isset($netdevdata['name']) || empty($netdevdata['name'])) {
	    $maxnr = $DB->getOne('SELECT MAX(id) FROM netdevices');
	    $maxnr++;
	    $netdevdata['name'] = 'INT_'.sprintf('%04.d',$maxnr);
	}
	
	$netdevid = $LMS->NetDevAdd($netdevdata);
	$DB->UnlockTables();
	
	if (SYSLOG) addlogs('Dodano nowy interfejs sieciowy '.$netdevdata['name'],'e=add;m=netdev;');
	$SESSION->redirect('?m=netdevinfo&id='.$netdevid);
} else
    $netdev = array('devtype'=>DEV_ACTIVE,'managed'=>TAK,'sharing'=>NIE,'modular'=>NIE,'backbone_layer'=>NIE,'distribution_layer'=>TAK,'access_layer'=>TAK);

$layout['pagetitle'] = 'Nowe urządzenie sieciowe';

$SMARTY->assign('networknodelist',$LMS->GetListnetworknode());

$annex_info = array('section'=>'netdev','ownerid'=>0);
$SMARTY->assign('annex_info',$annex_info);
include(MODULES_DIR . '/netdevxajax.inc.php');

if (chkconfig($CONFIG['phpui']['ewx_support'])) $SMARTY->assign('channels', $DB->GetAll('SELECT id, name FROM ewx_channels ORDER BY name'));

$SMARTY->assign('action','add');
$SMARTY->assign('netdevinfo',$netdev);
$SMARTY->assign('nastype', $LMS->GetNAStypes());
$SMARTY->assign('devicestype',$LMS->GetDictionaryDevicesClientofType());
$SMARTY->assign('projectlist',$DB->getAll('SELECT id,name FROM invprojects WHERE type = 0 ORDER BY name ASC;'));
$SMARTY->display('netdevedit.html');

?>