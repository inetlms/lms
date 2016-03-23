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
    
	$filename = 'iNETLMS_UKE_SIISv5.csv';
	$fullname = '/tmp/'.$filename;
	$idr = $_GET['idr'];
	    
	if ($file = fopen($fullname,'w'))
	{
		// DP - nasz podmiot
		$dane = 'DP,';
		$dane .= '"'.str_replace('"','',$DP['divname']).'",';
		$dane .= '"'.str_replace('-','',$DP['ten']).'",';
		$dane .= '"'.$DP['regon'].'",';
		$dane .= '"'.$DP['rpt'].'",';
		$dane .= '"'.$DP['rjst'].'",';
		$dane .= '"'.$DP['krs'].'",';
		$dane .= '"'.$DP['states'].'",';
		$dane .= '"'.$DP['districts'].'",';
		$dane .= '"'.$DP['boroughs'].'",';
		$dane .= '"'.sprintf('%07d',$DP['kod_terc']).'",';
		$dane .= '"'.$DP['city'].'",';
		$dane .= '"'.sprintf('%07s',$DP['kod_simc']).'",';
		
		if ($DP['kod_ulic'] == '99999' || empty($DP['street'])) $dane .= '"BRAK ULICY",';
		elseif ($DP['kod_ulic'] == '99998' && !empty($DP['street'])) $dane .= '"ULICA SPOZA ZAKRESU",';
		else $dane .= '"'.$DP['street'].'",';
		
		$dane .= '"'.sprintf('%05d',$DP['kod_ulic']).'",';
		$dane .= '"'.$DP['location_house'].'",';
		$dane .= '"'.$DP['zip'].'",';
		$dane .= '"'.$DP['url'].'",';
		$dane .= '"'.$DP['email'].'",';
		$dane .= '"'.($DP['accept1'] ? 'Tak' : 'Nie').'",';
		$dane .= '"'.($DP['accept2'] ? 'Tak' : 'Nie').'",';
		$dane .= '"'.($DP['accept3'] ? 'Tak' : 'Nie').'",';
		$dane .= '"'.($DP['accept4'] ? 'Tak' : 'Nie').'",';
		$dane .= '"'.($DP['accept5'] ? 'Tak' : 'Nie').'",';
		$dane .= '"'.($DP['accept6'] ? 'Tak' : 'Nie').'",';
		$dane .= '"'.$DP['contact_name'].'",';
		$dane .= '"'.$DP['contact_lastname'].'",';
		$dane .= '"'.str_replace(' ','',str_replace('-','',$DP['contact_phone'])).'",';
		$dane .= '"'.str_replace(' ','',str_replace('-','',$DP['contact_fax'])).'",';
		$dane .= '"'.$DP['contact_email'].'"';
		
		fputs($file,$dane."\n");
		
		
		// PO - podmioty obce
		if ($PO = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport=1;',array($idr,'PO'))) 
		{
			$count = sizeof($PO);
			for ($i=0;$i<$count;$i++) 
			{
				$tmp = unserialize($PO[$i]['data']);
				
				$dane = 'PO,';
				$dane .= '"'.str_replace('"','',$UKE->trim($tmp['shortname'])).'",';
				$dane .= '"'.str_replace('"','',$tmp['name']).'",';
				$dane .= '"'.str_replace('-','',$tmp['ten']).'",';
				$dane .= '"'.$tmp['regon'].'",';
				$dane .= '"'.$tmp['rpt'].'",';
				$dane .= '"'.$tmp['states'].'",';
				$dane .= '"'.$tmp['districts'].'",';
				$dane .= '"'.$tmp['boroughs'].'",';
				$dane .= '"'.sprintf('%07d',$tmp['kod_terc']).'",';
				$dane .= '"'.$tmp['city'].'",';
				$dane .= '"'.sprintf('%07d',$tmp['kod_simc']).'",';
				$dane .= '"'.(!empty($tmp['street']) ? $tmp['street'] : 'BRAK ULICY').'",';
				$dane .= '"'.sprintf('%05d',$tmp['kod_ulic']).'",';
				$dane .= '"'.$tmp['location_house'].'",';
				$dane .= '"'.$tmp['zip'].'",';
				if ($tmp['projectnumber'])
				    $dane .= '"'.str_replace('"','',$tmp['projectnumber']).'"';
				else
				    $dane .= '""';
				
				fputs($file,$dane."\n");
			}
			unset($dane);
			unset($PO);
		}
		
		
		// PROJ -> projekty EU
		if ($PROJ = $DB->getAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport = 1;',array($idr,'PROJ'))) 
		{
			$count = sizeof($PROJ);
			for ($i=0; $i < $count; $i++) 
			{
				$tmp = unserialize($PROJ[$i]['data']);
				
				$dane  = 'PR,';
				$dane .= '"'.$tmp['nrprojektu'].'",';
				$dane .= '"'.$tmp['nrumowy'].'",';
				$dane .= '"'.$tmp['tytul'].'",';
				$dane .= '"'.$tmp['program'].'",';
				$dane .= '"'.$tmp['dzialanie'].'",';
				$dane .= '"'.str_replace('"','',$tmp['firma']).'",';
				$dane .= '"'.$tmp['datapodpisania'].'",';
				$dane .= '"'.$tmp['datazakonczenia'].'",';
				$dane .= '"'.$tmp['wojewodztwo'].'",';
				$dane .= '"'.$tmp['zakres'].'"';
				
				fputs($file,$dane."\n");
			}
			unset($PROJ);
		}
		
		
		//WW - węzły własne
		if ($WW = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport = 1;',array($idr,'WW'))) 
		{
			$count = sizeof($WW);
			for ($i=0;$i<$count;$i++) 
			{
				$tmp = unserialize($WW[$i]['data']);
				$teryt = $LMS->getterytcode($tmp['location_city'],$tmp['location_street']);
				$latitude = str_replace(',','.',sprintf('%02.4f',$tmp['latitude']));
				$longitude = str_replace(',','.',sprintf('%02.4f',$tmp['longitude']));
				if ($latitude == '0.0000' || $longitude == '0.0000')
				    $latitude = $longitude = '0.0000';
				
				$dane = 'WW,';
				$dane .= '"'.$UKE->trim($WW[$i]['markid']).'",';			// D
				$dane .= '"'.$TNODE[$tmp['type']].'",';						// E
				$dane .= '"'.((($tmp['type'] == NODE_ALIEN || $tmp['type'] == NODE_FOREIGN) && $tmp['podmiot_obcy']) ? $tmp['podmiot_obcy'] : '').'",';	// f
				$dane .= '"",';									// g
				$dane .= '"'.$tmp['states'].'",';						// h
				$dane .= '"'.$tmp['districts'].'",';						// i
				$dane .= '"'.$tmp['boroughs'].'",';
				$dane .= '"'.sprintf('%07d',$tmp['kod_terc']).'",';				// k
				$dane .= '"'.$tmp['city'].'",';
				$dane .= '"'.sprintf('%07d',$tmp['kod_simc']).'",';				// m
				$dane .= '"'.(!empty($teryt['street']) ? $teryt['street'] : 'BRAK ULICY').'",';
				$dane .= '"'.sprintf('%05d',$tmp['kod_ulic']).'",';				// o
				$dane .= '"'.(empty($tmp['location_house']) ? 'b.d' : $tmp['location_house']).'",';
				$dane .= '"'.$tmp['zip'].'",';							// q
				$dane .= '"'.($latitude != '0.0000' ? $latitude : '').'",';			// r 
				$dane .= '"'.($longitude != '0.0000' ? $longitude : '').'",';			// s
				$dane .= '"'.$BUILDINGS[$tmp['buildingtype']].'",';
				$dane .= '"'.($tmp['available_surface'] == '1' ? 'Tak' : 'Nie').'",';
				$dane .= '"'.($tmp['instofanten'] == '1' ? 'Tak' : 'Nie').'",';
				$dane .= '"'.($tmp['eu'] == '1' ? 'Tak' : 'Nie').'",';
				
				if ($tmp['eu'] == '1') 
				{
					$dane .= '"'.$tmp['projectnumber'].'",';
					$dane .= '"'.$NSTATUS[$tmp['status']].'"';
				} else
					$dane .= '"",""';
				
				$c_netlink = $c_node = NULL;
				
				if ($_int = $DB->GetCol('SELECT id FROM netdevices WHERE networknodeid = ? ;',array($tmp['id']))) {
				    $int = implode(',',$_int);
				    $c_netlink = $DB->GetOne('SELECT 1 FROM netlinks WHERE src IN (?) OR dst IN (?) LIMIT 1;',array($int,$int));
				    $c_node = $DB->GetOne('SELECT 1 FROM nodes WHERE netdev IN (?) LIMIT 1;',array($int));
				}
				if ($c_netlink || $c_node )
				fputs($file,$dane."\n");
			}
			unset($WW);
		}
		
		
		//WO - węzły obce
		if ($WO = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport = 1;',array($idr,'WO'))) 
		{
			$count = sizeof($WO);
			for ($i=0;$i<$count;$i++) 
			{
				$tmp = unserialize($WO[$i]['data']);
				
				$latitude = str_replace(',','.',sprintf('%02.4f',$tmp['latitude']));
				$longtude = str_replace(',','.',sprintf('%02.4f',$tmp['longitude']));
				
				if ($latitude == '0.0000' || $longitude == '0.0000')
				    $latitude = $longitude = '0.0000';
				
				$dane = 'WO,';
				$dane .= '"'.$UKE->trim($WO[$i]['markid']).'",'; //  c
				$dane .= '"'.$tmp['podstawa'].'",';
				$dane .= '"'.$tmp['podmiot_obcy'].'",';
				$dane .= '"'.$tmp['states'].'",';
				$dane .= '"'.$tmp['districts'].'",';
				$dane .= '"'.$tmp['boroughs'].'",';
				$dane .= '"'.sprintf('%07d',$tmp['kod_terc']).'",';
				$dane .= '"'.$tmp['city'].'",';
				$dane .= '"'.sprintf('%07d',$tmp['kod_simc']).'",';
				$dane .= '"'.(!empty($tmp['street']) ? $tmp['street'] : 'BRAK ULICY').'",';
				$dane .= '"'.sprintf('%05d',$tmp['kod_ulic']).'",';
				$dane .= '"'.(empty($tmp['location_house']) ? 'b.d' : $tmp['location_house']).'",';
				$dane .= '"'.$tmp['zip'].'",';
				$dane .= '"'.($latitude != '0.0000' ? $latitude : '').'",';
				$dane .= '"'.($longitude != '0.0000' ? $longitude : '').'",';
				$dane .= '"'.$BUILDINGS[$tmp['buildingtype']].'",';
				
				if ($tmp['eu'] == '1') 
					$dane .= '"'.$tmp['projectnumber'].'"';
				else
					$dane .= '""';
				
				fputs($file,$dane."\n");
			}
			unset($WO);
		}
		
		
		// INT - Interfejsy
		if ($INT = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport = 1;',array($idr,'INT'))) 
		{
			$count = sizeof($INT);
			for ($i=0;$i<$count;$i++) 
			{
				// $dane .= '"'.$tmp[$i][''].'",';
				$tmp = unserialize($INT[$i]['data']);
				$dane = 'I,';
				
				$dane .= '"'.$UKE->trim($tmp['netnodename']).'",';
				$dane .= '"'.$UKE->trim($tmp['networknodename']).'",';
				$dane .= '"'.$tmp['backbone_layer'].'",';
				$dane .= '"'.$tmp['distribution_layer'].'",';
				$dane .= '"'.$tmp['access_layer'].'",';
				$dane .= '"'.$tmp['medium'].'",';
				$dane .= '"'.$tmp['pasmo_radiowe'].'",';
				$dane .= '"'.$tmp['technologia'].'",';
				$dane .= '"'.$tmp['max_to_net'].'",';
				$dane .= '"'.$tmp['max_to_user'].'",';
				$dane .= '"'.$tmp['ports'].'",';
				$dane .= '"'.$tmp['use_ports'].'",';
				$dane .= '"'.$tmp['empty_ports'].'",';
				$dane .= '"'.$tmp['sharing'].'",';
				$dane .= '"'.$tmp['projectnumber'].'",';
				$dane .= '"'.$tmp['status'].'"';
				

				    fputs($file,$dane."\n");
			}
			
			unset($INT);
		}
	
	    // SR
	    if ($int = $DB->GetAll('SELECT data FROM uke_data WHERE rapid=? AND mark=? AND useraport=1;',array($idr,'INT')))
	    {
		$intout = array();
		
		for ($i=0; $i<sizeof($int); $i++) 
		{
		    $tmp = unserialize($int[$i]['data']);
		    
		    if ($tmp['access_layer'] == 'Tak' && $tmp['linktype'] == LINKTYPES_RADIO) 
		    {
			$dane = 'Z,';
			
			$dane .= '"'.$i.'_'.$tmp['id'].'",';
			$dane .= '"'.$UKE->trim($tmp['networknodename']).'",';
			$dane .= '"'.$UKE->trim($tmp['netnodename']).'",';
			$dane .= '"Nie",';
			$dane .= '"",';
			$dane .= '"0",';
			$dane .= '"360",';
			$dane .= '"20",';
			$dane .= '"'.($tmp['linktechnology'] == 101 ? '1000' : '500').'",';
			$dane .= '"'.$tmp['max_to_user'].'",';
			$dane .= '"'.$tmp['projectnumber'].'",';
			$dane .= '"'.$tmp['status'].'"';
			fputs($file,$dane."\n");
		    }
		}
	    }
	    
	    
	    // LK (PL) - Linie kablowe
	    if ($LK = $DB->GetAll('SELECT * FROM uke_data WHERE rapid = ? AND mark = ? AND useraport = 1;',array($idr,'LK'))) {
		$count = sizeof($LK);
		
		for ($i=0;$i<$count;$i++) {
		    $tmp = unserialize($LK[$i]['data']);
		    $dane = 'LK,';
		    $dane .= '"'.$UKE->trim($tmp['identyfikator']).'",';		// D
		    $dane .= '"'.$tmp['wlasnosc'].'",';			// E
		    $dane .= '"'.$tmp['obcy'].'",';			// F
		    $dane .= '"'.$tmp['rodzaja'].'",';			// G
		    $dane .= '"'.$UKE->trim($tmp['identyfikatora']).'",';		// H
		    $dane .= '"'.$tmp['rodzajb'].'",';			// I
		    $dane .= '"'.$UKE->trim($tmp['identyfikatorb']).'",';		// J
		    $dane .= '"'.$tmp['medium'].'",';			// K
		    $dane .= '"'.$tmp['typwlokna'].'",';		// L
		    $dane .= '"'.$tmp['liczbawlokien'].'",';		// M
		    $dane .= '"'.$tmp['wlokienused'].'",';		// N
		    $dane .= '"'.$tmp['eu'].'",';			// O
		    $dane .= '"'.$tmp['dostepnapasywna'].'",';		// P
		    $dane .= '"'.$tmp['rodzajpasywnej'].'",';		// Q
		    $dane .= '"'.$tmp['sharingfiber'].'",';		// R
		    $dane .= '"'.$tmp['sharingmaxwlokna'].'",';		// S
		    $dane .= '"'.$tmp['sharingprzepustowosc'].'",';	// T
		    $dane .= '"'.$tmp['rodzajtraktu'].'",';		// U
		    $dane .= '"'.$tmp['dlugosckabla'].'"';		// V
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
		    
		    $dane .= '"'.$UKE->trim($tmp['identyfikator']).'",';		// D
		    $dane .= '"'.$UKE->trim($tmp['identyfikatora']).'",';		// E
		    $dane .= '"'.$UKE->trim($tmp['identyfikatorb']).'",';		// F
		    $dane .= '"'.$tmp['medium'].'",';			// G
		    $dane .= '"'.$tmp['pozwolenie'].'",';		// H
		    $dane .= '"'.$tmp['pasmo'].'",';			// I
		    $dane .= '"'.$tmp['system'].'",';		// J
		    $dane .= '"'.$tmp['przepustowosc'].'",';		// K
		    $dane .= '"'.$tmp['sharing'].'"';			// L
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
		    
		    $dane .= '"'.$UKE->trim($tmp['identyfikator']).'",';
		    $dane .= '"'.$tmp['wlasnosc'].'",';
		    $dane .= '"'.$tmp['obcy'].'",';
		    $dane .= '"'.$UKE->trim($tmp['identyfikatora']).'",';
		    $dane .= '"'.$UKE->trim($tmp['identyfikatorb']).'",';
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
		    $dane .= $UKE->trim($tmp['identyfikator']).',';
		    $dane .= '"'.$tmp['wlasnosc'].'",';
		    $dane .= '"'.$tmp['formaobca'].'",';
		    $dane .= '"'.$UKE->trim($tmp['identyfikatorobcy']).'",';
		    $dane .= '"'.$UKE->trim($tmp['networknode']).'",';
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
		    $dane .= '"'.($tmp['location_house'] ? $tmp['location_house'] : 'Brak numeru').'",';
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
		if (strtoupper($US[$i]['int']) == 'Nie')
		    $staty['noint']++;
	    }
	    
	    for ($i=0;$i<$count;$i++) 
	    {
		    $tmp = unserialize($US[$i]['data']);
		    $dane = 'U,';
		    $dane .= ($i + 1).',';
		    $dane .= $UKE->trim($tmp['identyfikator']).',';
		    $dane .= $tmp['isdn'].',';
		    $dane .= $tmp['voip'].',';
		    $dane .= $tmp['telmobile'].',';
		    $dane .= $tmp['int'].',';
		    $dane .= $tmp['intmobile'].',';
		    $dane .= $tmp['iptv'].',';
		    $dane .= ',';// 10
		    $dane .= ($tmp['custype'] == '1' && strtoupper($tmp['int']) == 'Nie' ? 1 : 0).',';
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
		    
		    $dane .= ($tmp['custype'] != '1' && strtoupper($tmp['int']) == 'Nie' ? 1 : 0).',';					// 22
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
	
} else echo "dupa z raportu :/";

?>