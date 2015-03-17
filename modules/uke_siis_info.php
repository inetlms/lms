<?php


/*
 *  iNET LMS
 *
 *  (C) Copyright 2012 LMS iNET Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  Sylwester Kondracki Exp $
*/

$idr = (isset($_GET['idr']) ? intval($_GET['idr']) : (isset($_POST['idr']) ? intval($_POST['idr']) : NULL));

if (!$idr)
    $SESSION->redirect("?m=uke_siis");

include(LIB_DIR.'/UKE.class.php');

$SMARTY->assign('idr',$idr);
$rapdata = $UKE->GetRaportInfo($idr);

$layout['pagetitle'] = 'Informacja o raporcie';


$tucklist[] = array('tuck' => 'DP','name' => 'DP','link' => '?m=uke_siis_info&tuck=DP&idr='.$idr, 'tip'=> 'Podmiot przekazujący informacje oraz osoba kontaktowa',);
$tucklist[] = array('tuck' => 'PO','name' => 'OB','link' => '?m=uke_siis_info&tuck=PO&idr='.$idr,'tip' => 'Dostawcy usług i podmioty udostępniające lub współdzielące infrastrukturę',);
if ($rapdata['version'] >= '5')
    $tucklist[] = array('tuck' => 'PROJ','name' => 'PROJ','link' => '?m=uke_siis_info&tuck=PROJ&idr='.$idr,'tip' => 'Projekty Unijne');
$tucklist[] = array('tuck' => 'WW','name' => 'WW','link' => '?m=uke_siis_info&tuck=WW&idr='.$idr,'tip' => 'Charakterystyka własnych lub współdzielonych z innymi podmiotami węzłów sieci telekomunikacyjnej',);
$tucklist[] = array('tuck' => 'WO','name' => 'WO','link' => '?m=uke_siis_info&tuck=WO&idr='.$idr,'tip' => 'Charakterystyka węzłów sieci telekomunikacyjnej innych podmiotów dla potrzeb przekazania informacji o punktach styku między sieciami lub punktów świadczenia usług przez dostawców',);
$tucklist[] = array('tuck' => 'INT','name' => 'INT','link' => '?m=uke_siis_info&tuck=INT&idr='.$idr,'tip' => 'Informacje o interfejsach węzłów własnych lub współdzielonych z innymi podmiotami i ich wykorzystanie',);
 // w.g spec siis (PL)
$tucklist[] = array('tuck' => 'LK','name' => 'LK','link' => '?m=uke_siis_info&tuck=LK&idr='.$idr,'tip' => 'Charakterystyka elementów infrastuktury telekomunikacyjnej stanowiących miedziane linie kablowe, światłowodowe linie kablowe lub ciemne włókna z wyłączeniem instalacji telekomunikacyjnej budynku',);
 // w.g spec siis (PL)
$tucklist[] = array('tuck' => 'LB','name' => 'LB','link' => '?m=uke_siis_info&tuck=LB&idr='.$idr,'tip' => 'Charakterystyka elementów infrastruktury telekomunikacyjnej stanowiących linie bezprzewodowe z wyłączeniem instalacji telekomunikacyjnej budynku',);
 // w.g spec siis (PL)
$tucklist[] = array('tuck' => 'POL','name' => 'POL','link' => '?m=uke_siis_info&tuck=POL&idr='.$idr,'tip' => 'Charakterystyka połączeń pomiędzy węzłami sieci',);
 // w.g spec siis (PL)
$tucklist[] = array('tuck' => 'ZAS','name' => 'ZAS','link' => '?m=uke_siis_info&tuck=ZAS&idr='.$idr,'tip' => 'Charakterystyka adresów budynków lub budowli, w których występuje zakończenie sieci przewodowej lub zainstalowany jest terminal użytkownika końcowego bezprzewodowej sieci dostępowej');
$tucklist[] = array('tuck' => 'BASE','name' => 'Pomoc','link' => '?m=uke_siis_info&tuck=BASE&idr='.$idr,'tip' => 'Opis przeprowadzenia raportu',);

$SMARTY->assign('tucklist',$tucklist);

$tuck = (isset($_GET['tuck']) ? $_GET['tuck'] : NULL);
$SESSION->nowsave('uke_siis_info_tuck',$tuck);


if ($tuck == 'DP') {
    
    $SMARTY->assign('rapdata',$rapdata);
    $SMARTY->display('uke_siis_info_DP.html');
    die;
}

elseif ($tuck == 'PO') {
    
    if (!isset($_GET['action']) || empty($_GET['action']))
	$action = 'lista';
    else
	$action = $_GET['action'];

    include(MODULES_DIR.'/uke_siis_xajax.php');
    
    if ($action == 'delete') {
	
	if (isset($_GET['id']) && !empty($_GET['id']))
	    $DB->Execute('DELETE FROM uke_data WHERE id = ?;',array($_GET['id']));
	$action = 'lista';
    }
    
    if ($action == 'lista') {
	$polist = $UKE->getPOList($idr);
	$SMARTY->assign('polist',$polist);
    }
    
    if ($action == 'edit') {
	$poinfo = $UKE->getpoinfo($_GET['id']);
	$SMARTY->assign('poinfo',$poinfo);
    }


    $SMARTY->assign('action',$action);
    $SMARTY->display('uke_siis_info_PO.html');
    die;
}

elseif ($tuck == 'PROJ') {
    
    include(MODULES_DIR.'/uke_siis_xajax.php');
    
    $projlist = $DB->getAll('SELECT * FROM invprojects WHERE siis = 1 ORDER BY name ASC');
    
    $SMARTY->assign('projlist',$projlist);
    $SMARTY->assign('projects',$UKE->getProjList($idr));
    $SMARTY->display('uke_siis_info_PROJ.html');

die;
}

elseif ($tuck == 'WW') {
    
    include(MODULES_DIR.'/uke_siis_xajax.php');
    
    if (!isset($_GET['action']) || empty($_GET['action'])) $action = 'lista'; else $action = $_GET['action'];
    
    if ($action == 'delete') {
	
	if (isset($_GET['id']) && !empty($_GET['id']))
	    $DB->Execute('DELETE FROM uke_data WHERE id = ?;',array($_GET['id']));
	
	$action = 'lista';
    }
    
    $nnodelist = $UKE->getWWList($idr);
    $SMARTY->assign('nnodelist',$nnodelist);
    
    if ($nnodelist) {
	$tmp = array();
	for ($i=0;$i<sizeof($nnodelist);$i++)
	    $tmp[] = $nnodelist[$i]['idw'];
	$tmp = implode(',',$tmp);
	$networknodelist = $DB->GetAll('SELECT n.id,n.name,n.available_surface FROM networknode n WHERE n.type = ? AND n.id NOT IN ('.$tmp.') ORDER BY n.name;',array(NODE_OWN)); // lista węzłów do przypisania
    }
    else
	$networknodelist = $DB->GetAll('SELECT n.id,n.name,n.available_surface FROM networknode n WHERE n.type = ? ORDER BY n.name;',array(NODE_OWN)); // lista węzłów do przypisania
    $SMARTY->assign('networknodelist',$networknodelist);
    
    $SMARTY->display('uke_siis_info_WW.html');
    die;
}

elseif ($tuck == 'WO') {
    include(MODULES_DIR.'/uke_siis_xajax.php');
    
    if (!isset($_GET['action']) || empty($_GET['action'])) $action = 'lista'; else $action = $_GET['action'];
    
    if ($action == 'delete') {
	
	if (isset($_GET['id']) && !empty($_GET['id']))
	    $DB->Execute('DELETE FROM uke_data WHERE id = ?;',array($_GET['id']));
	
	$action = 'lista';
    }
    
    $nnodelist = $UKE->getWOList($idr);
    $SMARTY->assign('nnodelist',$nnodelist);
    
    if ($nnodelist) {
	$tmp = array();
	for ($i=0;$i<sizeof($nnodelist);$i++)
	    $tmp[] = $nnodelist[$i]['idw'];
	$tmp = implode(',',$tmp);
	$networknodelist = $DB->GetAll('SELECT n.id,n.name,n.available_surface FROM networknode n WHERE n.type = ? AND n.id NOT IN ('.$tmp.') ORDER BY n.name;',array(NODE_FOREIGN)); // lista węzłów do przypisania
    }
    else
	$networknodelist = $DB->GetAll('SELECT n.id,n.name,n.available_surface FROM networknode n WHERE n.type = ? ORDER BY n.name;',array(NODE_FOREIGN)); // lista węzłów do przypisania
    $SMARTY->assign('networknodelist',$networknodelist);
    
    $SMARTY->display('uke_siis_info_WO.html');
    die;
}

elseif ($tuck == 'INT') { // generator -> INT
    
    include(MODULES_DIR.'/uke_siis_xajax.php');
    
    $intlist = $UKE->getINTList($idr);
    $SMARTY->assign('intlist',$intlist);
    
    $SMARTY->display('uke_siis_info_INT.html');
    die;
}

elseif ($tuck == 'LK') { // generator -> PL
     
    include(MODULES_DIR.'/uke_siis_xajax.php');
    
    $lklist = $UKE->getlklist($idr);
    $SMARTY->assign('lklist',$lklist);
    
    $SMARTY->display('uke_siis_info_LK.html');
    die;
}

elseif ($tuck == 'LB') { // generator -> LR
     
    include(MODULES_DIR.'/uke_siis_xajax.php');
    
    $lblist = $UKE->getlblist($idr);
    $SMARTY->assign('lblist',$lblist);
    
    $SMARTY->display('uke_siis_info_LB.html');
    die;
}

elseif ($tuck == 'POL') { // generator -> POL
     
    include(MODULES_DIR.'/uke_siis_xajax.php');
    
    $pollist = $UKE->getpollist($idr);
    $SMARTY->assign('pollist',$pollist);
    
    $SMARTY->display('uke_siis_info_POL.html');
    die;
}

elseif ($tuck == 'ZAS') { // generator -> POL
     
    include(MODULES_DIR.'/uke_siis_xajax.php');
    
    $zaslist = $UKE->getzaslist($idr);
    $SMARTY->assign('zaslist',$zaslist);
    
    $SMARTY->display('uke_siis_info_ZAS.html');
    die;
}

if ($tuck == 'BASE') {
    
    $SMARTY->display('uke_siis_info_BASE.html');
    die;
}




$tuck = (isset($_GET['tuck']) ? $_GET['tuck'] : $SESSION->get('uke_siis_info_tuck','DP'));
$tuckcount = sizeof($tucklist);
$err = true;

for ($i=0; $i<$tuckcount; $i++)
    if ($tucklist[$i]['tuck'] == $tuck) { $err = false; break; }

if ($err) $tuck = 'DP';

for ($i=0; $i<$tuckcount; $i++)
    if ($tucklist[$i]['tuck'] == $tuck) $tucklink = $tucklist[$i]['link'];

$SESSION->nowsave('uke_siis_info_tuck',$tuck);

$SMARTY->assign('tuck',$tuck);
$SMARTY->assign('tucklink',$tucklink);


$SMARTY->display('uke_siis_info.html');
?>