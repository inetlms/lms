<?php

$idc = ($_GET['idc'] ? $_GET['idc'] : ($_POST['idc'] ? $_POST['idc'] : NULL));

if (!$RE->CheckIssetCar($idc) || !$carinfo = $RE->getcar($idc)) {
    header("Location: ?m=re_carlist");
}

$SMARTY->assign('idc',$idc);
$SMARTY->assign('carinfo',$carinfo);
$layout['backto'] = 're_carinfo';


$tucklist = array(
	
	array('tuck' => 'base', 'name' => 'Informacje', 'link' => '?m=re_carinfo&tuck=base&idc='.$idc, 'tip' => 'Podstawowe informacje o pojeździe'),
	array('tuck' => 'assurance', 'name' => 'Ubezpieczenia', 'link' => '?m=re_carinfo&tuck=assurance&idc='.$idc, 'tip' => 'Informacje o ubezpieczeniu'),
//	array('tuck' => 'service', 'name' => 'Serwis', 'link' => '?m=re_carinfo&tuck=service&idc='.$idc, 'tip' => 'Lista czynności serwisowych związanych z normalnym użytkowaniem pojazdu'),
//	array('tuck' => 'technical', 'name' => 'Przeglądy techniczne', 'link' => '?m=re_carinfo&tuck=technical&idc='.$idc, 'tip' => 'Lista przeglądów technicznych'),
	array('tuck' => 'event', 'name' => 'Zdarzenia', 'link' => '?m=re_carinfo&tuck=event&idc='.$idc, 'tip' => 'Lista pozostałych zdarzeń / czynności związanych z pojazdem'),
	array('tuck' => 'users', 'name' => trans('Users'), 'link' => '?m=re_carinfo&tuck=users&idc='.$idc, 'tip' => 'Osoby odpowiedzialne za pojazd'),
	array('tuck' => 'annex', 'name' => 'Załączniki', 'link' => '?m=re_carinfo&tuck=users&idc='.$idc, 'tip' => 'Załączone zdjęcia / skany dokumenów itp'),
        array('tuck' => 'chart', 'name' => 'Wykres spalania', 'link' => '?m=re_carinfo&tuck=chart&idc='.$idc, 'tip' => 'Wykres liniowy zużycia paliwa.'),
//	array('tuck' => 'report', 'name' => 'Raporty', 'link' => '?m=re_carinfo&tuck=report&idc='.$idc, 'tip' => ''),
);

$tuck = ($_GET['tuck'] ? $_GET['tuck'] : ($_POST['tuck'] ? $_POST['tuck'] : $SESSION->get('re_carinfo_tuck')));

if (!$tuck)
    $tuck = 'base';

$SESSION->nowsave('re_carinfo_tuck',$tuck);

for ($i=0; $i<sizeof($tucklist); $i++) {
    if ($tucklist[$i]['tuck'] == $tuck) $tucklink = $tucklist[$i]['link'];
}

$SMARTY->assign('tuck',$tuck);
$SMARTY->assign('tucklink',$tucklink);
$SMARTY->assign('tucklist',$tucklist);


$layout['pagetitle'] = $carinfo['dr_d1'].' '.$carinfo['dr_d3'];

if ($tuck == 'base') {

	if (isset($_GET['edit'])) {
		$layout['pagetitle'] .= ' - Edycja pojazdu';
		$SMARTY->assign('action','edit');
		$LMS->InitXajax();
		include(MODULES_DIR.'/re_car.inc.php');
		$SMARTY->assign('xajax',$LMS->RunXajax());
		$SMARTY->assign('userlist',$LMS->getusernames());
		$SMARTY->assign('cartype',$RE->getdictionarycartypelist(true));
		$SMARTY->assign('carinfo',$carinfo);
	} else {
		$layout['pagetitle'] .= ' - Dane pojazdu';
		$SMARTY->assign('action',NULL);
	}

} elseif ($tuck == 'assurance') {
	
	if (isset($_GET['del']) && intval($_GET['del']) && $_GET['is_sure'] == '1') {
	    $RE->delassurance($_GET['del']);
	    header("Location: ?m=re_carinfo&tuck=assurance&idc=".$_GET['idc']);
	}
	
	if (isset($_GET['add']) ||(isset($_GET['edit']) && intval($_GET['edit']))) {
	    if (isset($_GET['edit'])) {
		$SMARTY->assign('assuranceinfo',$RE->getAssurance($_GET['edit']));
	    }
	    $SMARTY->assign('editassurance',1);
	    $LMS->InitXajax();
	    include(MODULES_DIR.'/re_car.inc.php');
	    $SMARTY->assign('xajax',$LMS->RunXajax());
	} else {
	    $assurancelist = $RE->getlistassurancecar($idc);
	    $SMARTY->assign('assurancelist',$assurancelist);
	}
	
	$layout['pagetitle'] .= ' - '.trans('Insurance');
	
	
} elseif ($tuck == 'service') {
	$layout['pagetitle'] .= ' - Serwis';

} elseif ($tuck == 'technical') {
	$layput['pagetitle'] .= ' - Przeglądy techniczne';

} elseif ($tuck == 'event') {
	$layout['pagetitle'] .= ' - Zdarzenia';
	
	if (isset($_GET['del']) && !empty($_GET['del']) && intval($_GET['del']) && isset($_GET['is_sure']) && $_GET['is_sure'] == '1')
	    $RE->deleteevent($_GET['del']);
	
	if (isset($_GET['add']) || isset($_GET['edit'])) {
	    
	    if (isset($_GET['add']))
		$SMARTY->assign('akcja','add');
	    else 
		$SMARTY->assign('akcja','edit');
	    
	    $eventinfo = array();
	    if (isset($_GET['edit']))
		$eventinfo = $RE->getEvent($_GET['edit']);
	    
	    $SMARTY->assign('eventinfo',$eventinfo);
	    $SMARTY->assign('dicevent',$DB->GetAll('SELECT id, name FROM re_dictionary_event WHERE active=1 ORDER BY name ASC'));
	    $LMS->InitXajax();
	    include(MODULES_DIR.'/re_car.inc.php');
	    $SMARTY->assign('xajax',$LMS->RunXajax());
	}
	
	$SMARTY->assign('eventlist',$RE->GetEventList($idc,NULL));
	$SMARTY->assign('userlist',$DB->GetAllByKey('SELECT id,name FROM users;','id'));
	$SMARTY->assign('eventdiclist',$DB->GetAllbyKey('SELECT id,name FROM re_dictionary_event;','id'));

} elseif ($tuck == 'users') {

	$layout['pagetitle'] .= ' - '.trans('Members of vehicle');
	
	if (isset($_POST['caruser'])) {
	    $form = $_POST['caruser'];
	    if ($form['id'])
		$RE->updateuserscar($form);
	    else
		$RE->adduserscar($form);
	}
	
	if (isset($_GET['deluser']) && isset($_GET['is_sure']) && !empty($_GET['deluser']) && $_GET['is_sure'] == '1') {
	    $DB->Execute('DELETE FROM re_users WHERE id = ?;',array($_GET['deluser']));
	}
	
	if (isset($_GET['edituser'])) {
	    $userinfo = $RE->getusercar($_GET['edituser']);
	}
	else
	    $userinfo = array();
	$SMARTY->assign('userinfo',$userinfo);
	
	$users = $RE->getuserscar($idc);
	$SMARTY->assign('users',$users);
	
	
	if (isset($_GET['adduser']) || isset($_GET['edituser'])) {
	    $userlist = $DB->GetAll('SELECT id,login,name FROM users ORDER BY login ASC;');
	    $SMARTY->assign('userlist',$userlist);
	    $SMARTY->assign('edituser',1);
	}

} elseif ($tuck == 'annex') {

	$layout['pagetitle'] .= ' - załączone dokumenty';
	$annex_info = array('section' => 're_cars', 'ownerid' => $idc);
	include(MODULES_DIR.'/annex.inc.php');
	$SMARTY->assign('incannex',1);
	$SMARTY->assign('incannexlink',1);

} elseif ($tuck == 'report') {

} elseif ( $tuck == 'chart') {
    
    $carData = $RE->getCarFuleConsumptionData($idc);
    
    $averageConsumption = $RE->getCarAverageConsumption($idc);
    
    foreach ( $carData as &$row){
        
        $row['spalanie'] = round($averageConsumption['spalanie']/100 * $row['przejechane'],2);
        
    }
    
    $SMARTY->assign('averageConsumption',$averageConsumption['spalanie']);
    $SMARTY->assign('carData',$carData);

    
}
    



$SMARTY->display('re_carinfo.html');
?>