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
$_PATH = dirname(__FILE__);

if (!$idr)
    $SESSION->redirect("?m=uke_siis");

include(LIB_DIR.'/UKE.class.php');

$SMARTY->assign('idr',$idr);
$rapdata = $UKE->GetRaportInfo($idr);

$layout['pagetitle'] = 'Informacja o raporcie';


$tucklist[] = array('tuck' => 'DP','name' => 'DP','link' => '?m=uke_siis_info&tuck=DP&idr='.$idr, 'tip'=> 'Podmiot przekazujący informacje oraz osoba kontaktowa',);
$tucklist[] = array('tuck' => 'PROJ','name' => 'PROJ','link' => '?m=uke_siis_info&tuck=PROJ&idr='.$idr,'tip' => 'Projekty Unijne');
$tucklist[] = array('tuck' => 'PO','name' => 'OB','link' => '?m=uke_siis_info&tuck=PO&idr='.$idr,'tip' => 'Dostawcy usług i podmioty udostępniające lub współdzielące infrastrukturę',);
$tucklist[] = array('tuck' => 'KOL', 'name' => 'KOL','link'=>'?m=uke_siis_info&tuck=KOL&idr='.$idr,'tip'=>'Kolokacje',);
$tucklist[] = array('tuck' => 'WW','name' => 'WW','link' => '?m=uke_siis_info&tuck=WW&idr='.$idr,'tip' => 'Charakterystyka własnych lub współdzielonych z innymi podmiotami węzłów sieci telekomunikacyjnej',);
$tucklist[] = array('tuck' => 'WO','name' => 'WO','link' => '?m=uke_siis_info&tuck=WO&idr='.$idr,'tip' => 'Charakterystyka węzłów sieci telekomunikacyjnej innych podmiotów dla potrzeb przekazania informacji o punktach styku między sieciami lub punktów świadczenia usług przez dostawców',);
$tucklist[] = array('tuck' => 'INT','name' => 'INT','link' => '?m=uke_siis_info&tuck=INT&idr='.$idr,'tip' => 'Informacje o interfejsach węzłów własnych lub współdzielonych z innymi podmiotami i ich wykorzystanie',);
$tucklist[] = array('tuck' => 'SR', 'name' => 'SR', 'link'=>'?m=uke_siis_info&tuck=SR&idr='.$idr, 'tip'=>'Sektory radiowe');
$tucklist[] = array('tuck' => 'EL', 'name' => 'EL', 'link' => '?m=uke_siis_info&tuck=EL&idr='.$idr,'tip'=>'Elementy łączenia kabli');
$tucklist[] = array('tuck' => 'PS', 'name' => 'PS', 'link' => '?m=uke_siis_info&tuck=PS&idr='.$idr,'tip'=>'Punkty Styku');
$tucklist[] = array('tuck' => 'LK','name' => 'LP','link' => '?m=uke_siis_info&tuck=LK&idr='.$idr,'tip' => 'Charakterystyka elementów infrastuktury telekomunikacyjnej stanowiących miedziane linie kablowe, światłowodowe linie kablowe lub ciemne włókna z wyłączeniem instalacji telekomunikacyjnej budynku',);
$tucklist[] = array('tuck' => 'LB','name' => 'RL','link' => '?m=uke_siis_info&tuck=LB&idr='.$idr,'tip' => 'Charakterystyka elementów infrastruktury telekomunikacyjnej stanowiących linie bezprzewodowe z wyłączeniem instalacji telekomunikacyjnej budynku',);
$tucklist[] = array('tuck' => 'POL','name' => 'POL','link' => '?m=uke_siis_info&tuck=POL&idr='.$idr,'tip' => 'Charakterystyka połączeń pomiędzy węzłami sieci',);
$tucklist[] = array('tuck' => 'ZAS','name' => 'ZAS','link' => '?m=uke_siis_info&tuck=ZAS&idr='.$idr,'tip' => 'Charakterystyka adresów budynków lub budowli, w których występuje zakończenie sieci przewodowej lub zainstalowany jest terminal użytkownika końcowego bezprzewodowej sieci dostępowej');
$tucklist[] = array('tuck' => 'US','name' => 'US','link' => '?m=uke_siis_info&tuck=US&idr='.$idr, 'tip'=>'Charakterystyka świadczonych usług w budynkach objętych zasięgiem sieci');
$tucklist[] = array('tuck' => 'IDO','name' => 'IDO','link' => '?m=uke_siis_info&tuck=IDO&idr='.$idr,'tip'=>'Inwestycje w sieci dostępowe');
$tucklist[] = array('tuck' => 'BASE','name' => 'Pomoc','link' => '?m=uke_siis_info&tuck=BASE&idr='.$idr,'tip' => 'Opis przeprowadzenia raportu',);

$SMARTY->assign('tucklist',$tucklist);

$tuck = (isset($_GET['tuck']) ? $_GET['tuck'] : NULL);
$SESSION->nowsave('uke_siis_info_tuck',$tuck);


if ($tuck == 'DP') { // dane o podmiocie przekazującym dane
	
	$SMARTY->assign('rapdata',$rapdata);
	$SMARTY->display('uke_siis_info_DP.html');
	
	die;
}

// ***********************************************************************************************************************************************************************************************************
elseif ($tuck == 'PROJ') { // projeky
	
	include($_PATH.'/uke_siis_xajax.php');
	
	$projlist = $DB->getAll('SELECT * FROM invprojects WHERE siis = 1 ORDER BY name ASC');
	
	$SMARTY->assign('projlist',$projlist);
	$SMARTY->assign('projects',$UKE->getProjList($idr));
	$SMARTY->display('uke_siis_info_PROJ.html');
	
	die;
}


// ***********************************************************************************************************************************************************************************************************
elseif ($tuck == 'PO') { // OB podmioty obce
	
	include($_PATH.'/uke_siis_xajax.php');
	
	if (!isset($_GET['action']) || empty($_GET['action']))
		$action = 'lista';
	else
		$action = $_GET['action'];
	
	if ($action == 'delete') 
	{
		if (isset($_GET['id']) && !empty($_GET['id']))
			$DB->Execute('DELETE FROM uke_data WHERE id = ?;',array($_GET['id']));
		$action = 'lista';
	}
	
	if ($action == 'lista') 
	{
		$polist = $UKE->getPOList($idr);
		$SMARTY->assign('polist',$polist);
	}
	
	if ($action == 'edit') 
	{
		$poinfo = $UKE->getpoinfo($_GET['id']);
		$SMARTY->assign('poinfo',$poinfo);
	}
	
	$SMARTY->assign('action',$action);
	$SMARTY->assign('projectlist',$UKE->getProjList($idr));
	$SMARTY->display('uke_siis_info_PO.html');
	
	die;
}

// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'KOL') {
	
	include($_PATH.'/uke_siis_xajax.php');
	
	$SMARTY->display('uke_siis_info_KOL.html');
	
	die;
}

// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'WW') {
	
	include($_PATH.'/uke_siis_xajax.php');
	
	if (!isset($_GET['action']) || empty($_GET['action'])) 
		$action = 'lista'; 
	else 
		$action = $_GET['action'];
	
	if ($action == 'delete') 
	{
		if (isset($_GET['id']) && !empty($_GET['id']))
			$DB->Execute('DELETE FROM uke_data WHERE id = ?;',array($_GET['id']));
		
		$action = 'lista';
	}
	
	$nnodelist = $UKE->getWWList($idr);
	$SMARTY->assign('nnodelist',$nnodelist);
	
	if ($nnodelist) 
	{
		$tmp = array();
		
		for ($i=0; $i<sizeof($nnodelist); $i++)
			$tmp[] = $nnodelist[$i]['idw'];
		
		$tmp = implode(',',$tmp);
		$networknodelist = $DB->GetAll('SELECT n.id,n.name,n.available_surface FROM networknode n WHERE (n.type = ? OR n.type = ?) AND n.id NOT IN ('.$tmp.') ORDER BY n.name;',array(NODE_OWN,NODE_FOREIGN)); // lista węzłów do przypisania
	}
	else
		$networknodelist = $DB->GetAll('SELECT n.id,n.name,n.available_surface FROM networknode n WHERE (n.type = ? OR n.type = ?) ORDER BY n.name;',array(NODE_OWN,NODE_FOREIGN)); // lista węzłów do przypisania
	
	$SMARTY->assign('networknodelist',$networknodelist);
	$SMARTY->display('uke_siis_info_WW.html');
	
	die;
}

// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'WO') {
	
	include($_PATH.'/uke_siis_xajax.php');
	
	if (!isset($_GET['action']) || empty($_GET['action'])) 
		$action = 'lista'; 
	else 
		$action = $_GET['action'];
	
	if ($action == 'delete') 
	{
		if (isset($_GET['id']) && !empty($_GET['id']))
			$DB->Execute('DELETE FROM uke_data WHERE id = ?;',array($_GET['id']));
		
		$action = 'lista';
	}
	
	$nnodelist = $UKE->getWOList($idr);
	$SMARTY->assign('nnodelist',$nnodelist);
	
	if ($nnodelist) 
	{
		$tmp = array();
		
		for ($i=0; $i<sizeof($nnodelist); $i++)
			$tmp[] = $nnodelist[$i]['idw'];
		
		$tmp = implode(',',$tmp);
		$networknodelist = $DB->GetAll('SELECT n.id,n.name,n.available_surface FROM networknode n WHERE n.type = ? AND n.id NOT IN ('.$tmp.') ORDER BY n.name;',array(NODE_ALIEN)); // lista węzłów do przypisania
	}
	else
		$networknodelist = $DB->GetAll('SELECT n.id,n.name,n.available_surface FROM networknode n WHERE n.type = ? ORDER BY n.name;',array(NODE_ALIEN)); // lista węzłów do przypisania
	
	$SMARTY->assign('networknodelist',$networknodelist);
	$SMARTY->display('uke_siis_info_WO.html');
	
	die;
}

// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'INT') { // generator -> INT interfejsy sieciowe
	
	include($_PATH.'/uke_siis_xajax.php');
	
	$intlist = $UKE->getINTList($idr);
	
	$SMARTY->assign('intlist',$intlist);
	$SMARTY->display('uke_siis_info_INT.html');
	
	die;
}


// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'SR') { // sektory radiowe
	
	$SMARTY->display('uke_siis_info_SR.html');
	
	die;
}


// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'EL') { // elementy łączenia kabli
	
	$SMARTY->display('uke_siis_info_EL.html');
	
	die;
}

// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'PS') { // punkty styku
	
	$SMARTY->display('uke_siis_info_PS.html');
	
	die;
}


// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'LK') { // generator -> PL
        
	include($_PATH.'/uke_siis_xajax.php');
	
	$lklist = $UKE->getlklist($idr);
	
	$SMARTY->assign('lklist',$lklist);
	$SMARTY->display('uke_siis_info_LK.html');
	
	die;
}


// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'LB') { // generator -> LR
	
	include($_PATH.'/uke_siis_xajax.php');
	
	$lblist = $UKE->getlblist($idr);
	
	$SMARTY->assign('lblist',$lblist);
	$SMARTY->display('uke_siis_info_LB.html');
	
	die;
}


// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'POL') { // generator -> POL
	
	include($_PATH.'/uke_siis_xajax.php');
	
	$pollist = $UKE->getpollist($idr);
	
	$SMARTY->assign('pollist',$pollist);
	$SMARTY->display('uke_siis_info_POL.html');
	
	die;
}


// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'ZAS') { // generator -> POL
	
	include($_PATH.'/uke_siis_xajax.php');
	
	$zaslist = $UKE->getzaslist($idr);
	
	$SMARTY->assign('zaslist',$zaslist);
	$SMARTY->display('uke_siis_info_ZAS.html');
	
	die;
}


// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'US') {
	
	$SMARTY->display('uke_siis_info_US.html');
	
	die;
}


// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'IDO') {
	
	$SMARTY->display('uke_siis_info_IDO.html');
	
	die;
}


// **********************************************************************************************************************************************************************************************************
elseif ($tuck == 'BASE') {
	
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
