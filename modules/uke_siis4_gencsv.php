<?php

if (
    (isset($_GET['action']) && $_GET['action'] == 'gencsv') &&
    (isset($_GET['idr']) && !empty($_GET['idr'])) &&
    ($DP = $DB->GetRow('SELECT * FROM uke WHERE id = ?;',array($_GET['idr'])))
)
{
    include(LIB_DIR.'/UKE.class.php');
    
    $borough_types = array(
	1 => 'gm. miejska',
	2 => 'gm. wiejska',
	3 => 'gm. miejsko-wiejska',
	4 => 'gm. miejsko-wiejska',
	5 => 'gm. miejsko-wiejska',
	8 => 'dzielnica gminy Warszawa-Centrum',
	9 => 'dzielnica',
    );

    $linktypes = array(
	array(
		'linia' 		=> "kablowa", 
		'trakt' 		=> "podziemny", 
		'technologia' 		=> "kablowe parowe miedziane", 
		'typ' 			=> "UTP",
		'pasmo' 		=> "", 
		'szybkosc_radia' 	=> "",
		'technologia_dostepu' 	=> "100 Mb/s Fast Ethernet", 
		'szybkosc' 		=> "100", 
		'liczba_jednostek' 	=> "1",
		'jednostka' 		=> "linie w kablu",
		'specyficzne' 		=> array('szybkosc_dystrybucyjna' => "100"),
	),
	array(
		'linia' 		=> "bezprzewodowa", 
		'trakt' 		=> "NIE DOTYCZY", 
		'technologia' 		=> "radiowe", 
		'typ' 			=> "WiFi",
		'pasmo' 		=> "5.5", 
		'szybkosc_radia' 	=> "100",
		'technologia_dostepu' 	=> "WiFi - 2,4 GHz", 
		'szybkosc' 		=> "54", 
		'liczba_jednostek' 	=> "1",
		'jednostka' 		=> "kanały",
		'specyficzne' 		=> array('szybkosc_dystrybucyjna' => "100"),
	),
	array(
		'linia' 		=> "kablowa", 
		'trakt' 		=> "podziemny w kanalizacji", 
		'technologia' 		=> "światłowodowe", 
		'typ' 			=> "G.652", 
		'pasmo' 		=> "", 
		'szybkosc_radia' 	=> "",
		'technologia_dostepu' 	=> "100 Mb/s Fast Ethernet", 
		'szybkosc' 		=> "100", 
		'liczba_jednostek' 	=> "2",
		'jednostka' 		=> "włókna",
		'specyficzne' 		=> array('szybkosc_dystrybucyjna' => "1000"),
	),
    );
    
	$filename = 'iNETLMS_UKE_SIISv4.csv';
	$fullname = '/tmp/'.$filename;
	$idr = $_GET['idr'];
	    
	if ($file = fopen($fullname,'w'))
	{
	    // DP - nasz podmiot
	    $dane = 'DP,';
	    $dane .= '"'.$DP['divname'].'",';
	    $dane .= str_replace('-','',$DP['ten']).',';
	    $dane .= $DP['regon'].',';
	    $dane .= $DP['rpt'].',';
	    $dane .= $DP['rjst'].',';
	    $dane .= $DP['krs'].',';
	    $dane .= '"'.$DP['states'].'",';
	    $dane .= '"'.$DP['districts'].'",';
	    $dane .= '"'.$DP['boroughs'].'",';
	    $dane .= sprintf('%07d',$DP['kod_terc']).',';
	    $dane .= '"'.$DP['city'].'",';
	    $dane .= sprintf('%07s',$DP['kod_simc']).',';

	    if ($DP['kod_ulic'] == '99999' || empty($DP['street'])) {
		$dane .= 'BRAK ULICY,';
	    } elseif ($DP['kod_ulic'] == '99998' && !empty($DP['street'])) {
		$dane .= 'ULICA SPOZA ZAKRESU,';
	    } else {
		$dane .= '"'.$DP['street'].'",';
	    }

	    $dane .= sprintf('%05d',$DP['kod_ulic']).',';
	    $dane .= '"'.$DP['location_house'].'",';
	    $dane .= $DP['zip'].',';
	    $dane .= '"'.$DP['url'].'",';
	    $dane .= '"'.$DP['email'].'",';
	    $dane .= ($DP['accept1'] ? 'Tak,' : 'Nie,');
	    $dane .= ($DP['accept2'] ? 'Tak,' : 'Nie,');
	    $dane .= ($DP['accept3'] ? 'Tak,' : 'Nie,');
	    $dane .= ($DP['accept4'] ? 'Tak,' : 'Nie,');
	    $dane .= ($DP['accept5'] ? 'Tak,' : 'Nie,');
	    $dane .= ($DP['accept6'] ? 'Tak,' : 'Nie,');
	    $dane .= '"'.$DP['contact_name'].'",';
	    $dane .= '"'.$DP['contact_lastname'].'",';
	    $dane .= str_replace(' ','',str_replace('-','',$DP['contact_phone'])).',';
	    $dane .= str_replace(' ','',str_replace('-','',$DP['contact_fax'])).',';
	    $dane .= '"'.$DP['contact_email'].'"';
	    
	    fputs($file,$dane."\n");
	    
	    // PO - podmioty obce
	    if ($PO = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport=1;',array($idr,'PO'))) {
		$count = sizeof($PO);
		for ($i=0;$i<$count;$i++) {
		    $dane = 'PO,';
		    $tmp = unserialize($PO[$i]['data']);
		    
		    $dane .= '"'.$tmp['shortname'].'",';
		    $dane .= '"'.$tmp['name'].'",';
		    $dane .= '"'.str_replace('-','',$tmp['ten']).'",';
		    $dane .= $tmp['regon'].',';
		    $dane .= $tmp['rpt'].',';
		    $dane .= '"'.$tmp['states'].'",';
		    $dane .= '"'.$tmp['districts'].'",';
		    $dane .= '"'.$tmp['boroughs'].'",';
		    $dane .= sprintf('%07d',$tmp['kod_terc']).',';
		    $dane .= '"'.$tmp['city'].'",';
		    $dane .= sprintf('%07d',$tmp['kod_simc']).',';
		    $dane .= '"'.(!empty($tmp['street']) ? $tmp['street'] : 'BRAK ULICY').'",';
		    $dane .= sprintf('%05d',$tmp['kod_ulic']).',';
		    $dane .= '"'.$tmp['location_house'].'",';
		    $dane .= $tmp['zip'];
		    fputs($file,$dane."\n");
		}
		unset($dane);
		unset($PO);
	    }
	    
	    //WW - węzły własne
	    if ($WW = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport = 1;',array($idr,'WW'))) {
		$count = sizeof($WW);
		for ($i=0;$i<$count;$i++) {
		    
		    $dane = 'WW,';
		    $tmp = unserialize($WW[$i]['data']);
		    
		    $teryt = $LMS->getterytcode($tmp['location_city'],$tmp['location_street']);
		    
		    
		    $dane .= '"'.$WW[$i]['markid'].'",';
		    $dane .= '"'.$TNODE[$tmp['type']].'",';
		    $dane .= '"'.$tmp['foreign_entity'].'",';
		    $dane .= '"",';
		    $dane .= '"'.$tmp['states'].'",';
		    $dane .= '"'.$tmp['districts'].'",';
		    $dane .= '"'.$tmp['boroughs'].'",';
		    $dane .= sprintf('%07d',$tmp['kod_terc']).',';
		    $dane .= '"'.$tmp['city'].'",';
		    $dane .= sprintf('%07d',$tmp['kod_simc']).',';
		    $dane .= '"'.(!empty($teryt['street']) ? $teryt['street'] : 'BRAK ULICY').'",';
		    $dane .= sprintf('%05d',$tmp['kod_ulic']).',';
		    $dane .= '"'.$tmp['location_house'].'",';
		    $dane .= '"'.$tmp['zip'].'",';
		    $dane .= str_replace(',','.',sprintf('%02.4f',$tmp['latitude'])).',';
		    $dane .= str_replace(',','.',sprintf('%02.4f',$tmp['longitude'])).',';
		    $dane .= '"'.$BUILDINGS[$tmp['buildingtype']].'",';
		    $dane .= ($tmp['available_surface'] == '1' ? 'Tak' : 'Nie').',';
		    $dane .= ($tmp['instofanten'] == '1' ? 'Tak' : 'Nie').',';
		    $dane .= ($tmp['eu'] == '1' ? 'Tak' : 'Nie');
		    fputs($file,$dane."\n");
		}
		unset($WW);
	    }
	    
	    //WO - węzły obce
	    if ($WO = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport = 1;',array($idr,'WO'))) {
		$count = sizeof($WO);
		for ($i=0;$i<$count;$i++) {
		    $dane = 'WO,';
		    $tmp = unserialize($WO[$i]['data']);
		    $dane .= '"'.$WO[$i]['markid'].'",';
		    $dane .= '"'.$tmp['podstawa'].'",';
		    $dane .= '"'.$tmp['foreign_entity'].'",';
		    $dane .= '"'.$tmp['states'].'",';
		    $dane .= '"'.$tmp['districts'].'",';
		    $dane .= '"'.$tmp['boroughs'].'",';
		    $dane .= sprintf('%07d',$tmp['kod_terc']).',';
		    $dane .= '"'.$tmp['city'].'",';
		    $dane .= sprintf('%07d',$tmp['kod_simc']).',';
		    $dane .= '"'.(!empty($tmp['street']) ? $tmp['street'] : 'BRAK ULICY').'",';
		    $dane .= sprintf('%05d',$tmp['kod_ulic']).',';
		    $dane .= '"'.$tmp['location_house'].'",';
		    $dane .= '"'.$tmp['zip'].'",';
		    $dane .= str_replace(',','.',sprintf('%02.4f',$tmp['latitude'])).',';
		    $dane .= str_replace(',','.',sprintf('%02.4f',$tmp['longitude'])).',';
		    $dane .= '"'.$BUILDINGS[$tmp['buildingtype']].'"';
		    fputs($file,$dane."\n");
		}
		unset($WO);
	    }
	    
	    // INT - Interfejsy
	    
	    if ($INT = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport = 1;',array($idr,'INT'))) {
		$count = sizeof($INT);
		
		for ($i=0;$i<$count;$i++) 
		{
		    // $dane .= '"'.$tmp[$i][''].'",';
		    $tmp = unserialize($INT[$i]['data']);
		    $dane = 'I,';
		    $dane .= '"'.$tmp['netnodename'].'",';
		    $dane .= '"'.$tmp['networknodename'].'",';
		    $dane .= '"'.$tmp['backbone_layer'].'",';
		    $dane .= '"'.$tmp['distribution_layer'].'",';
		    $dane .= '"'.$tmp['access_layer'].'",';
		    $dane .= '"'.$tmp['medium'].'",';
		    $dane .= '"'.$tmp['pasmo_radiowe'].'",';
		    $dane .= '"'.$tmp['technologia'].'",';
		    $dane .= $tmp['max_to_net'].',';
		    $dane .= $tmp['max_to_user'].',';
		    $dane .= $tmp['ports'].',';
		    $dane .= $tmp['use_ports'].',';
		    $dane .= $tmp['empty_ports'].',';
		    $dane .= $tmp['sharing'];
		    fputs($file,$dane."\n");
		}
		
		unset($INT);
	    }
	    
	    // LK (PL) - Linie kablowe
	    if ($LK = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport = 1;',array($idr,'LK'))) {
		$count = sizeof($LK);
		
		for ($i=0;$i<$count;$i++) {
		    $tmp = unserialize($LK[$i]['data']);
		    $dane = 'LK,';
		    $dane .= '"'.$tmp['identyfikator'].'",';
		    $dane .= '"'.$tmp['wlasnosc'].'",';
		    $dane .= '"'.$tmp['obcy'].'",';
		    $dane .= '"'.$tmp['rodzaja'].'",';
		    $dane .= '"'.$tmp['identyfikatora'].'",';
		    $dane .= '"'.$tmp['rodzajb'].'",';
		    $dane .= '"'.$tmp['identyfikatorb'].'",';
		    $dane .= '"'.$tmp['medium'].'",';
		    $dane .= '"'.$tmp['typwlokna'].'",';
		    $dane .= '"'.$tmp['liczbalwokien'].'",';
		    $dane .= '"'.$tmp['wlokienused'].'",';
		    $dane .= '"'.$tmp['eu'].'",';
		    $dane .= '"'.$tmp['dostepnapasywna'].'",';
		    $dane .= '"'.$tmp['rodzajpasywnej'].'",';
		    $dane .= '"'.$tmp['sharingfiber'].'",';
		    $dane .= '"'.$tmp['sharingwlokna'].'",';
		    $dane .= '"'.$tmp['sharingprzepustowosc'].'",';
		    $dane .= '"'.$tmp['rodzajtraktu'].'",';
		    $dane .= '"'.$tmp['dlugosckabla'].'"';
		    fputs($file,$dane."\n");
		}
		unset($LK);
	    }
	    
	    // LB -> genertotr RL
	    if ($LB = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport = 1;',array($idr,'LB'))) {
		$count = sizeof($LB);
		
		for ($i=0;$i<$count;$i++) {
		    $tmp = unserialize($LB[$i]['data']);
		    $dane = 'LB,';
		    
		    $dane .= '"'.$tmp['identyfikator'].'",';
		    $dane .= '"'.$tmp['identyfikatora'].'",';
		    $dane .= '"'.$tmp['identyfikatorb'].'",';
		    $dane .= '"'.$tmp['medium'].'",';
		    $dane .= '"'.$tmp['pozwolenie'].'",';
		    $dane .= '"'.$tmp['pasmo'].'",';
		    $dane .= '"'.$tmp['system'].'",';
		    $dane .= '"'.$tmp['przepustowosc'].'",';
		    $dane .= '"'.$tmp['sharing'].'"';
		    fputs($file,$dane."\n");
		}
		unset($LB);
	    }
	
	// POL
	
	if ($POL = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport = 1;',array($idr,'POL'))) {
		$count = sizeof($POL);
		
		for ($i=0;$i<$count;$i++) {
		    $tmp = unserialize($POL[$i]['data']);
		    $dane = 'P,';
		    
		    $dane .= '"'.$tmp['identyfikator'].'",';
		    $dane .= '"'.$tmp['wlasnosc'].'",';
		    $dane .= '"'.$tmp['obcy'].'",';
		    $dane .= '"'.$tmp['identyfikatora'].'",';
		    $dane .= '"'.$tmp['identyfikatorb'].'",';
		    $dane .= '"'.$tmp['backbone_layer'].'",';
		    $dane .= '"'.$tmp['distribution_layer'].'",';
		    $dane .= '"'.$tmp['access_layer'].'",';
		    $dane .= '"'.$tmp['szerokopasmowe'].'",';
		    $dane .= '"'.$tmp['glosowe'].'",';
		    $dane .= '"'.$tmp['inne'].'",';
		    $dane .= $tmp['speed'].',';
		    $dane .= $tmp['speednet'];
		    
		    fputs($file,$dane."\n");
		}
		unset($POL);
	    }

// ZAS
	if ($ZAS = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport=1;',array($idr,'ZAS'))) {
	    $count = sizeof($ZAS);
	    
	    for ($i=0;$i<$count;$i++) 
	    {
		    $tmp = unserialize($ZAS[$i]['data']);
		    $dane = 'ZS,';
		    $dane .= $tmp['identyfikator'].',';
		    $dane .= '"'.$tmp['wlasnosc'].'",';
		    $dane .= '"'.$tmp['formaobca'].'",';
		    $dane .= '"'.$tmp['identyfikatorobcy'].'",';
		    $dane .= '"'.$tmp['networknode'].'",';
		    $dane .= '"'.$tmp['states'].'",';
		    $dane .= '"'.$tmp['districts'].'",';
		    $dane .= '"'.$tmp['boroughs'].'",';
		    $dane .= sprintf('%07d',$tmp['kod_terc']).',';
		    $dane .= '"'.$tmp['city'].'",';
		    $dane .= sprintf('%07d',$tmp['kod_simc']).',';
		    if (empty($tmp['street']) && $tmp['kod_ulic'] == '99999') $tmp2 = 'BRAK ULICY';
		    elseif (!empty($tmp['street']) && $tmp['kod_ulic'] == '99998') $tmp2 = 'ULICA SPOZA ZAKRESU';
		    else $tmp2 = $tmp['street'];
		    $dane .= '"'.$tmp2.'",';
		    $dane .= sprintf('%05d',$tmp['kod_ulic']).',';
		    $dane .= '"'.$tmp['location_house'].'",';
		    $dane .= '"'.$tmp['zip'].'",';
		    $dane .= (!empty($tmp['latitude']) ? str_replace(',','.',sprintf('%02.4f',$tmp['latitude'])) : '').',';
		    $dane .= (!empty($tmp['longitude']) ? str_replace(',','.',sprintf('%02.4f',$tmp['longitude'])) : '').',';
		    $dane .= '"'.$tmp['medium'].'",';
		    $dane .= '"'.$tmp['dostepowa'].'",';
		    $dane .= '"'.$tmp['isdn'].'",';
		    $dane .= '"'.$tmp['voip'].'",';
		    $dane .= '"'.$tmp['telmobile'].'",';
		    $dane .= '"'.$tmp['int'].'",';
		    $dane .= '"'.$tmp['intmobile'].'",';
		    $dane .= '"'.$tmp['iptv'].'",';
		    $dane .= '"'.$tmp['otherservice'].'",';
		    $dane .= '"'.$tmp['downstream'].'",';
		    $dane .= $tmp['downstreammobile'];
		    fputs($file,$dane."\n");
	    }
	}


	if ($US = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport=1;',array($idr,'ZAS'))) {
	    $count = sizeof($US);
	    
	    $staty = array(
		'notint' => 0,		// US / 11
	    );
	    for ($i=0;$i<$count;$i++) {
		if (strtoupper($US[$i]['int']) == 'NIE')
		    $staty['noint']++;
	    }
	    
	    for ($i=0;$i<$count;$i++) 
	    {
		    $tmp = unserialize($US[$i]['data']);
		    $dane = 'U,';
		    $dane .= ($i + 1).',';
		    $dane .= $tmp['identyfikator'].',';
		    $dane .= 'Nie,';
		    $dane .= $tmp['voip'].',';
		    $dane .= 'Nie,';
		    $dane .= $tmp['int'].',';
		    $dane .= 'Nie,';
		    $dane .= $tmp['iptv'].',';
		    $dane .= ',';									// 10
		    $dane .= ($tmp['custype'] == '1' && strtoupper($tmp['int']) == 'NIE' ? 1 : 0).',';
		    $dane .= ($tmp['custype'] == '1' ? 0 : ($tmp['downstream'] <= '1' ? 1 : 0)).',';	// 12
		    $dane .= ($tmp['custype'] == '1' ? 0 : ($tmp['downstream'] > '1' && $tmp['downstream'] < '2' ? 1 : 0)).',';	// 13
		    $dane .= ($tmp['custype'] == '1' ? 0 : ($tmp['downstream'] == '2' ? 1 : 0)).',';	// 14
		    $dane .= ($tmp['custype'] == '1' ? 0 : ($tmp['downstream'] > '2' && $tmp['downstream'] <= '10' ? 1 : 0)).',';	// 15
		    $dane .= ($tmp['custype'] == '1' ? 0 : ($tmp['downstream'] > '10' && $tmp['downstream'] <= '20' ? 1 : 0)).',';	// 16
		    $dane .= ($tmp['custype'] == '1' ? 0 : ($tmp['downstream'] > '20' && $tmp['downstream'] < '30' ? 1 : 0)).',';	// 17
		    $dane .= ($tmp['custype'] == '1' ? 0 : ($tmp['downstream'] == '30' ? 1 : 0)).',';	// 18
		    $dane .= ($tmp['custype'] == '1' ? 0 : ($tmp['downstream'] > '30' && $tmp['downstream'] < '100' ? 1 : 0)).',';	// 19
		    $dane .= ($tmp['custype'] == '1' ? 0 : ($tmp['downstream'] == '100' ? 1 : 0)).',';	// 20
		    $dane .= ($tmp['custype'] == '1' ? 0 : ($tmp['downstream'] > '100' ? 1 : 0)).',';	// 21
		    
		    $dane .= ($tmp['custype'] != '1' && strtoupper($tmp['int']) == 'NIE' ? 1 : 0).',';					// 22
		    $dane .= ($tmp['custype'] != '1' ? 0 : ($tmp['downstream'] <= '1' ? 1 : 0)).',';					// 23
		    $dane .= ($tmp['custype'] != '1' ? 0 : ($tmp['downstream'] > '1' && $tmp['downstream'] < '2' ? 1 : 0)).',';		// 24
		    $dane .= ($tmp['custype'] != '1' ? 0 : ($tmp['downstream'] == '2' ? 1 : 0)).',';					// 25
		    $dane .= ($tmp['custype'] != '1' ? 0 : ($tmp['downstream'] > '2' && $tmp['downstream'] <= '10' ? 1 : 0)).',';	// 26
		    $dane .= ($tmp['custype'] != '1' ? 0 : ($tmp['downstream'] > '10' && $tmp['downstream'] <= '20' ? 1 : 0)).',';	// 27
		    $dane .= ($tmp['custype'] != '1' ? 0 : ($tmp['downstream'] > '20' && $tmp['downstream'] < '30' ? 1 : 0)).',';	// 28
		    $dane .= ($tmp['custype'] != '1' ? 0 : ($tmp['downstream'] == '30' ? 1 : 0)).',';					// 29
		    $dane .= ($tmp['custype'] != '1' ? 0 : ($tmp['downstream'] > '30' && $tmp['downstream'] < '100' ? 1 : 0)).',';	// 30
		    $dane .= ($tmp['custype'] != '1' ? 0 : ($tmp['downstream'] == '100' ? 1 : 0)).',';					// 31
		    $dane .= ($tmp['custype'] != '1' ? 0 : ($tmp['downstream'] > '100' ? 1 : 0));					// 32
		    
		    fputs($file,$dane."\n");
	    }
	}

	    fclose($file);

	    header("Content-Type: applicaton/force-download");
	    header("Content-Type: application/ocet-stream");
	    header("Content-Type: application/download");
	    header("Content-Disposition: attachment; filename=\"".$filename."\"");
	    header("Accept-Ranges: bytes");
	    header("Content-Transfer-Encoding: binary");
	    header("Content-Length : ".filesize($fullname)."");
	    readfile($fullname);

	}
	
} else echo "dupa";

?>