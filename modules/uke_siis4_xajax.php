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


function add_siis4($forms)
{
    global $DB,$LMS,$UKE;
    $obj = new xajaxResponse();
    
    $form = $forms['rapdata'];
    $blad = false;
    
    $obj->script("removeClassId('id_divisionid','alerts');");
    $obj->script("removeClassId('id_divname','alerts');");
    $obj->script("removeClassId('id_ten','alerts');");
    $obj->assign("id_ten_alerts","innerHTML","");
    $obj->script("removeClassId('id_regon','alerts');");
    $obj->assign("id_regon_alerts","innerHTML","");
    $obj->script("removeClassId('id_krs','alerts');");
    $obj->script("removeClassId('id_rpt','alerts');");
    $obj->assign("id_rpt_alerts","innerHTML","");
    $obj->script("removeClassId('location','alerts');");
    $obj->script("removeClassId('id_states','alerts');");
    $obj->script("removeClassId('id_districts','alerts');");
    $obj->script("removeClassId('id_boroughs','alerts');");
    $obj->script("removeClassId('id_city','alerts');");
    $obj->script("removeClassId('id_street','alerts');");
    $obj->script("removeClassId('id_location_house','alerts');");
    $obj->script("removeClassId('id_zip','alerts');");
    $obj->assign("id_zip_alerts","innerHTML","");
    $obj->script("removeClassId('id_email','alerts');");
    $obj->assign("id_email_alerts","innerHTML","");
    $obj->script("removeClassId('id_contact_name','alerts');");
    $obj->script("removeClassId('id_contact_lastname','alerts');");
    $obj->script("removeClassId('id_contact_phone','alerts');");
    $obj->script("removeClassId('id_contact_email','alerts');");
    $obj->assign("id_contact_email_alerts","innerHTML","");
    
    if (!$form['divisionid']) {
	$obj->script("addClassId('id_divisionid','alerts');");
	$blad = true;
    } 
    
    if (!$form['divname']) {
	$obj->script("addClassId('id_divname','alerts');");
	$blad = true;
    }
    
    if (!$form['ten']) {
	$obj->script("addClassId('id_ten','alerts');");
	$blad = true;
    } elseif (!check_ten($form['ten'])) {
	$obj->script("addClassId('id_ten','alerts');");
	$obj->assign('id_ten_alerts','innerHTML','Błędny numer NIP');
	$blad = true;
    }
    
    if (!$form['regon']) {
	$obj->script("addClassId('id_regon','alerts');");
	$blad = true;
    } elseif (!check_regon($form['regon'])) {
	$obj->script("addClassId('id_regon','alerts');");
	$obj->assign('id_regon_alerts','innerHTML','Błędny numer REGON');
	$blad = true;
    }
    
    if (!$form['krs']) {
	$obj->script("addClassId('id_krs','alerts');");
	$blad = true;
    }
    
    if (!$form['rpt']) {
	$obj->script("addClassId('id_rpt','alerts');");
	$blad = true;
    } elseif (!is_natural($form['rpt'])) {
	$obj->script("addClassId('id_rpt','alerts');");
	$obj->assign('id_rpt_alerts','innerHTML','Błednie podano numer RPT');
	$blad = true;
    }
    
    if (!$form['teryt']) {
	
	if (!$form['states']) {
	    $obj->script("addClassId('id_states','alerts');");
	    $blad = true;
	}
	
	if (!$form['districts']) {
	    $obj->script("addClassId('id_districts','alerts');");
	    $blad = true;
	}
	
	if (!$form['boroughs']) {
	    $obj->script("addClassId('id_boroughs','alerts');");
	    $blad = true;
	}
	
	if (!$form['city']) {
	    $obj->script("addClassId('id_city','alerts');");
	    $blad = true;
	}
	
	
    } elseif (!$form['location']) {
	$obj->script("addClassId('location','alerts');");
	$blad = true;
    }
    
    if (!$form['location_house']) {
	    $obj->script("addClassId('id_location_house','alerts');");
	    $blad = true;
    }
    
    if (!$form['zip']) {
	$obj->script("addClassId('id_zip','alerts');");
	$blad = true;
    } elseif (!check_zip($form['zip'])) {
	$obj->script("addClassId('id_zip','alerts');");
	$obj->assign("id_zip_alerts","innerHTML","Błędny kod pocztowy");
	$blad = true;
    }
    
    if (!$form['email']) {
	$obj->script("addClassId('id_email','alerts');");
	$blad = true;
    } elseif (!check_email($form['email'])) {
	$obj->script("addClassId('id_email','alerts');");
	$obj->assign("id_email_alerts","innerHTML","Błędny adres e-mail");
	$blad = true;
    }
    
    if (!$form['contact_name']) {
	$obj->script("addClassId('id_contact_name','alerts');");
	$blad = true;
    }
    
    if (!$form['contact_lastname']) {
	$obj->script("addClassId('id_contact_lastname','alerts');");
	$blad = true;
    }
    
    if (!$form['contact_phone']) {
	$obj->script("addClassId('id_contact_phone','alerts');");
	$blad = true;
    }
    
    if (!$form['contact_email']) {
	$obj->script("addClassId('id_contact_email','alerts');");
	$blad = true;
    } elseif (!check_email($form['contact_email'])) {
	$obj->script("addClassId('id_contact_email','alerts');");
	$obj->assign("id_contact_email_alerts","innerHTML","Błędny adres email");
	$blad = true;
    }
    
    if (!$blad) {
	
	if ($form['teryt']) 
	{
		$data = $LMS->GetTerytCode($form['location_city'],$form['location_street']);
		$form['states'] = $data['name_states'];
		$form['districts'] = $data['name_districts'];
		$form['boroughs'] = $data['name_boroughs'];
		$form['city'] = $data['name_city'];
		$form['street'] = $data['name_street'];
		$form['kod_terc'] = $data['kod_terc'];
		$form['kod_simc'] = $data['kod_simc'];
		$form['kod_ulic'] = $data['kod_ulic'];
		unset($data);
	} else {
	    $form['kod_terc'] = $form['kod_simc'] = $form['kod_ulic'] = 0;
	    $form['location_city'] = $form['location_street'] = NULL;
	}
	
	if ($form['action'] == 'add') {
	    $idr = $UKE->add_siis4($form);
	    //$obj->script("self.location.href='?m=uke_siis4_info&tuck=&idr=".$idr."';");
	    $obj->script("self.location.href='?m=uke_siis4';");
	}
	elseif ($form['action'] == 'edit') {
	    $UKE->update_siis4($form);
	    $obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=DP&idr=".$form['id']."');");
	}
	
	
    }
    
    return $obj;
}

function add_PO($forms)
{
    global $DB,$LMS,$UKE;
    $obj = new xajaxResponse();
    
    $form = $forms['poedit'];
    $blad = false;
    
    $obj->script("removeClassId('id_shortname','alerts');");
    $obj->assign("id_shortname_alerts","innerHTML","");
    $obj->script("removeClassId('id_name','alerts');");
    $obj->assign("id_name_alerts","innerHTML","");
    
    
    $obj->script("removeClassId('id_ten','alerts');");
    $obj->assign("id_ten_alerts","innerHTML","");
    
    $obj->script("removeClassId('id_regon','alerts');");
    $obj->assign("id_regon_alerts","innerHTML","");
    
    $obj->script("removeClassId('id_rpt','alerts');");
    $obj->assign("id_rpt_alerts","innerHTML","");

    $obj->script("removeClassId('location','alerts');");
    $obj->script("removeClassId('id_states','alerts');");
    $obj->script("removeClassId('id_districts','alerts');");
    $obj->script("removeClassId('id_boroughs','alerts');");
    $obj->script("removeClassId('id_city','alerts');");
    $obj->script("removeClassId('id_street','alerts');");
    $obj->script("removeClassId('id_location_house','alerts');");
    $obj->script("removeClassId('id_zip','alerts');");
    $obj->assign("id_zip_alerts","innerHTML","");
    
    if (!$form['shortname']) {
	$obj->script("addClassId('id_shortname','alerts');");
	$obj->assign("id_shortname_alets","innerHTML","Identyfikator jest wymagany");
	$blad = true;
    } 
    
    if (!$form['name']) {
	$obj->script("addClassId('id_name','alerts');");
	$obj->assign("id_name_alets","innerHTML","Nazwa firmy jest wymagana");
	$blad = true;
    }
    
    if (!$form['ten']) {
	$obj->script("addClassId('id_ten','alerts');");
	$blad = true;
    } elseif (!check_ten($form['ten'])) {
	$obj->script("addClassId('id_ten','alerts');");
	$obj->assign('id_ten_alerts','innerHTML','Błędny numer NIP');
	$blad = true;
    }
    
    if (!$form['regon']) {
	$obj->script("addClassId('id_regon','alerts');");
	$blad = true;
    } elseif (!check_regon($form['regon'])) {
	$obj->script("addClassId('id_regon','alerts');");
	$obj->assign('id_regon_alerts','innerHTML','Błędny numer REGON');
	$blad = true;
    }
    
    if (!$form['rpt']) {
	$obj->script("addClassId('id_rpt','alerts');");
	$blad = true;
    } elseif (!is_natural($form['rpt'])) {
	$obj->script("addClassId('id_rpt','alerts');");
	$obj->assign('id_rpt_alerts','innerHTML','Błednie podano numer RPT');
	$blad = true;
    }
    
    if (!$form['teryt']) {
	
	if (!$form['states']) {
	    $obj->script("addClassId('id_states','alerts');");
	    $blad = true;
	}
	
	if (!$form['districts']) {
	    $obj->script("addClassId('id_districts','alerts');");
	    $blad = true;
	}
	
	if (!$form['boroughs']) {
	    $obj->script("addClassId('id_boroughs','alerts');");
	    $blad = true;
	}
	
	if (!$form['city']) {
	    $obj->script("addClassId('id_city','alerts');");
	    $blad = true;
	}
	
	
    } elseif (!$form['location']) {
	$obj->script("addClassId('location','alerts');");
	$blad = true;
    }
    
    if (!$form['location_house']) {
	    $obj->script("addClassId('id_location_house','alerts');");
	    $blad = true;
    }
    
    if (!$form['zip']) {
	$obj->script("addClassId('id_zip','alerts');");
	$blad = true;
    } elseif (!check_zip($form['zip'])) {
	$obj->script("addClassId('id_zip','alerts');");
	$obj->assign("id_zip_alerts","innerHTML","Błędny kod pocztowy");
	$blad = true;
    }

    if (!$blad) {
	
	if ($form['teryt']) 
	{
		$data = $LMS->GetTerytCode($form['location_city'],$form['location_street']);
		$form['states'] = $data['name_states'];
		$form['districts'] = $data['name_districts'];
		$form['boroughs'] = $data['name_boroughs'];
		$form['city'] = $data['name_city'];
		$form['street'] = $data['name_street'];
		$form['kod_terc'] = $data['kod_terc'];
		$form['kod_simc'] = $data['kod_simc'];
		$form['kod_ulic'] = $data['kod_ulic'];
		unset($data);
	} else {
	    $form['kod_terc'] = $form['kod_simc'] = $form['kod_ulic'] = 0;
	    $form['location_city'] = $form['location_street'] = NULL;
	}
	
	$data = array();
	$data['id'] = $form['id'];
	$data['rapid'] = $form['idr'];
	$data['mark'] = 'PO';
	$data['markid'] = $form['shortname'];
	$action = $form['action'];
	unset($form['id']);
	unset($form['action']);
	$data['data'] = serialize($form);
	
	
	if ($action == 'add') {
	    
	    $UKE->add_siis4_data_po($data);
	    $obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=PO&idr=".$data['rapid']."');");
	}
	elseif ($action == 'edit') {
	    $UKE->update_siis4_data_po($data);
	    $obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=PO&idr=".$data['rapid']."');");
	}
	
	
    }
    
    return $obj;
}



    function set_PO_useraport($idr,$id,$set)
    {
	global $DB;
	$obj = new xajaxResponse();
	
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',
	    array(
		($set ? 1 : 0),
		$id,
	    )
	);
	
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=PO&idr=".$idr."');");
	
	return $obj;
    }


    function view_select_po_to_ww($idw,$idr)
    {
	global $DB;
	$obj = new xajaxResponse();
	
	$polist = $DB->GetAll('SELECT id,markid FROM uke_data WHERE rapid = ? AND mark=? AND useraport=1;',array($idr,'PO'));
	$available_surface = $DB->GetOne('SELECT available_surface FROM networknode WHERE id = ? LIMIT 1;',array($idw));
	
	if ($idw)
	    $obj->assign("id_view_add_po_to_ww","innerHTML","<a href=\"javascript:void(0);\" onclick=\"xajax_add_po_to_ww('".$idr."','".$idw."',document.getElementById('id_select_po').value);\">Dodaj węzeł do raportu</a>");
	else
	    $obj->assign("id_view_add_po_to_ww","innerHTML","");
	
	if ($polist && $available_surface) {
	    $tmp = "";
	    $tmp .= "<span class='tiphelp' onmouseover=\"popup('Dostawcy usług i podmioty udostępniające lub współdzielące infrastrukturę (Podmoty Obce).<br>Wybranie podmiotu oznacza że węzeł jest współdzielony z innym podmiotem.');\" onmouseout=\"pophide();\"><b>Podmiot:</b> </span>";
	    $tmp .= "<select id='id_select_po' style='cursor:pointer;'>";
	    $tmp .= "<option value=''></option>";
	    for ($i=0;$i<sizeof($polist);$i++)
		$tmp .= "<option value='".$polist[$i]['id']."'>".$polist[$i]['markid']."</option>";
	    $tmp .= "</select>";
	    
	    $obj->assign('id_view_select_po','innerHTML',$tmp);
	} else {
	    $obj->assign('id_view_select_po','innerHTML','<input type="hidden" id="id_select_po" value="">');
	}
	
	return $obj;
    }
    
    
    function add_po_to_ww($idr,$idw,$idp=NULL)
    {
	global $DB,$LMS;
	$obj = new xajaxResponse();
	
	$networknode = $DB->GetRow('SELECT id,name,type,states,districts,boroughs,city,street,zip,location_city,
			location_street,location_house,longitude,latitude,buildingtype,instofanten,available_surface,eu 
			FROM networknode WHERE id = ? LIMIT 1',array($idw));
	
	
	if ($networknode['location_city']) {
	    $tmp = $LMS->getterytcode($networknode['location_city'],$networknode['location_street']);
	    $networknode['kod_terc'] = $tmp['kod_terc'];
	    $networknode['kod_simc'] = $tmp['kod_simc'];
	    $networknode['kod_ulic'] = $tmp['kod_ulic'];
	    $networknode['street'] = $tmp['street'];
	} else {
	    $networknode['kod_terc'] = $networknode['kod_simc'] = $networknode['kod_ulic'] = NULL;
	}
	
	if ($idp) {
	    $networknode['foreign_entity'] = $DB->GetOne('SELECT markid FROM uke_data WHERE id = ? LIMIT 1;',array($idp));
	} else {
	    $networknode['foreign_entity'] = NULL;
	}
	
	$networknode['colocation'] = NULL;
	
	$data = serialize($networknode);
	
	$DB->Execute('INSERT INTO uke_data (rapid,mark,markid,useraport,data) VALUE (?,?,?,?,?);',
		array(
		    $idr,'WW',$networknode['name'],1,$data
		)
	);
	
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=WW&idr=".$idr."');");
	
	return $obj;
    
    }
    
    function set_ww_useraport($idr,$id,$set)
    {
	global $DB;
	$obj = new xajaxResponse();
	
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',
	    array(
		($set ? 1 : 0),
		$id,
	    )
	);
	
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=WW&idr=".$idr."');");
	
	return $obj;
    }


    function view_select_po_to_wo($idw,$idr)
    {
	global $DB,$PODSTAWA;
	$obj = new xajaxResponse();
	
	$polist = $DB->GetAll('SELECT id,markid FROM uke_data WHERE rapid = ? AND mark=? AND useraport=1;',array($idr,'PO'));
	$available_surface = $DB->GetOne('SELECT available_surface FROM networknode WHERE id = ? LIMIT 1;',array($idw));
	
	if ($idw) {
	    $obj->assign("id_view_add_po_to_wo","innerHTML","<a href=\"javascript:void(0);\" onclick=\"xajax_add_po_to_wo('".$idr."','".$idw."',document.getElementById('id_select_po').value,document.getElementById('id_podstawa_wo').value);\">Dodaj węzeł do raportu</a>");
	    $tmp = "<span class='tiphelp' onmouseover=\"popup('Podstawa i forma korzystania z infrastruktury innego podmiotu');\" onmouseout=\"pophide();\"><b>Podstawa:</b></span> <input type='text' style='width:400px;' id='id_podstawa_wo' value=''>";
	    $tmp .= "&nbsp;&nbsp;<select style='minwidth:200px;' onchange=\"document.getElementById('id_podstawa_wo').value = this.value;\">";
	    $tmp .= "<option value=''></option>";
	    foreach ($PODSTAWA as $item => $key) 
		$tmp .= "<option value='".$key."'>".$key."</option>";
	    $tmp .= "</select>";
	    $obj->assign("id_view_podstawa_wo","innerHTML",$tmp);
	} else {
	    $obj->assign("id_view_add_po_to_wo","innerHTML","");
	    $obj->assign("id_view_podstawa_wo","innerHTML","<input type='hidden' id='id_podstawa_wo' value=''>");
	}
	
	if ($polist && $available_surface) {
	    $tmp = "";
	    $tmp .= "<span class='tiphelp' onmouseover=\"popup('Dostawcy usług i podmioty udostępniające lub współdzielące infrastrukturę (Podmoty Obce).<br>Wybranie podmiotu oznacza że węzeł jest współdzielony z innym podmiotem.');\" onmouseout=\"pophide();\"><b>Podmiot:</b> </span>";
	    $tmp .= "<select id='id_select_po' style='cursor:pointer;'>";
	    $tmp .= "<option value=''></option>";
	    for ($i=0;$i<sizeof($polist);$i++)
		$tmp .= "<option value='".$polist[$i]['id']."'>".$polist[$i]['markid']."</option>";
	    $tmp .= "</select>";
	    
	    $obj->assign('id_view_select_po','innerHTML',$tmp);
	} else {
	    $obj->assign('id_view_select_po','innerHTML','<input type="hidden" id="id_select_po" value="">');
	}
	
	return $obj;
    }
    
    
    function add_po_to_wo($idr,$idw,$idp=NULL,$podstawa=NULL)
    {
	global $DB,$LMS;
	$obj = new xajaxResponse();
	$blad = false;
	$obj->script("removeClassId('id_select_po','alerts');");
	$obj->script("removeClassId('id_podstawa_wo','alerts');");
	
	if (!$idp) {
	    $blad = true;
	    $obj->script("addClassId('id_select_po','alerts');");
	}
	
	if (!$podstawa) {
	    $blad = true;
	    $obj->script("addClassId('id_podstawa_wo','alerts');");
	}
	
	if (!$blad) {
	
	    $networknode = $DB->GetRow('SELECT id,name,type,states,districts,boroughs,city,street,zip,location_city,
			location_street,location_house,longitude,latitude,buildingtype 
			FROM networknode WHERE id = ? LIMIT 1',array($idw));
	
	    $networknode['foreign_entity'] = $DB->GetOne('SELECT markid FROM uke_data WHERE id = ? LIMIT 1;',array($idp));
	
	    $networknode['podstawa'] = $podstawa;
	    
	    if ($networknode['location_city']) {
		$tmp = $LMS->getterytcode($networknode['location_city'],$networknode['location_street']);
	        $networknode['kod_terc'] = $tmp['kod_terc'];
		$networknode['kod_simc'] = $tmp['kod_simc'];
		$networknode['kod_ulic'] = $tmp['kod_ulic'];
	    } else {
		$networknode['kod_terc'] = $networknode['kod_simc'] = $networknode['kod_ulic'] = NULL;
	    }
	
	    $data = serialize($networknode);
	
	    $DB->Execute('INSERT INTO uke_data (rapid,mark,markid,useraport,data) VALUE (?,?,?,?,?);',
		array(
		    $idr,'WO',$networknode['name'],1,$data
		)
	    );
	
	    $obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=WO&idr=".$idr."');");
	}
	
	return $obj;
	
    }
    
    function set_wo_useraport($idr,$id,$set)
    {
	global $DB;
	$obj = new xajaxResponse();
	
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',
	    array(
		($set ? 1 : 0),
		$id,
	    )
	);
	
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=WO&idr=".$idr."');");
	
	return $obj;
    }
    
    
    
    function import_interfaces($idr) 
    {
	global $DB,$UKE;
	$obj = new xajaxResponse();
	
	$nd = $DB->GetAll('SELECT nd.id, nd.ports, nd.name AS netnodename, nn.name AS networknodename, nn.backbone_layer, nn.distribution_layer, nn.access_layer, nn.sharing, 
		(CASE WHEN nlsrccable.nlsrccount IS NULL THEN 0 ELSE nlsrccable.nlsrccount END) + (CASE WHEN nldstcable.nldstcount IS NULL THEN 0 ELSE nldstcable.nldstcount END) AS cabledistports,
		(CASE WHEN nlsrcradio.nlsrccount IS NULL THEN 0 ELSE nlsrcradio.nlsrccount END) + (CASE WHEN nldstradio.nldstcount IS NULL THEN 0 ELSE nldstradio.nldstcount END) AS radiodistports,
		(CASE WHEN nlsrcfiber.nlsrccount IS NULL THEN 0 ELSE nlsrcfiber.nlsrccount END) + (CASE WHEN nldstfiber.nldstcount IS NULL THEN 0 ELSE nldstfiber.nldstcount END) AS fiberdistports,
		(CASE WHEN pndpcable.portcount IS NULL THEN 0 ELSE pndpcable.portcount END) AS cablepersonalaccessports,
		(CASE WHEN cndpcable.portcount IS NULL THEN 0 ELSE cndpcable.portcount END) AS cablecommercialaccessports,
		(CASE WHEN pndpradio.portcount IS NULL THEN 0 ELSE pndpradio.portcount END) AS radiopersonalaccessports,
		(CASE WHEN cndpradio.portcount IS NULL THEN 0 ELSE cndpradio.portcount END) AS radiocommercialaccessports,
		(CASE WHEN pndpfiber.portcount IS NULL THEN 0 ELSE pndpfiber.portcount END) AS fiberpersonalaccessports,
		(CASE WHEN cndpfiber.portcount IS NULL THEN 0 ELSE cndpfiber.portcount END) AS fibercommercialaccessports
		FROM netdevices nd 
		LEFT JOIN networknode nn ON (nn.id = nd.networknodeid) 
		LEFT JOIN uke_data u ON (u.markid = nn.name)
		LEFT JOIN (SELECT netlinks.src AS src, COUNT(netlinks.id) AS nlsrccount FROM netlinks WHERE type = 0 GROUP BY netlinks.src) nlsrccable ON nd.id = nlsrccable.src 
		LEFT JOIN (SELECT netlinks.dst AS dst, COUNT(netlinks.id) AS nldstcount FROM netlinks WHERE type = 0 GROUP BY netlinks.dst) nldstcable ON nd.id = nldstcable.dst 
		LEFT JOIN (SELECT netlinks.src AS src, COUNT(netlinks.id) AS nlsrccount FROM netlinks WHERE type = 1 GROUP BY netlinks.src) nlsrcradio ON nd.id = nlsrcradio.src 
		LEFT JOIN (SELECT netlinks.dst AS dst, COUNT(netlinks.id) AS nldstcount FROM netlinks WHERE type = 1 GROUP BY netlinks.dst) nldstradio ON nd.id = nldstradio.dst 
		LEFT JOIN (SELECT netlinks.src AS src, COUNT(netlinks.id) AS nlsrccount FROM netlinks WHERE type = 2 GROUP BY netlinks.src) nlsrcfiber ON nd.id = nlsrcfiber.src 
		LEFT JOIN (SELECT netlinks.dst AS dst, COUNT(netlinks.id) AS nldstcount FROM netlinks WHERE type = 2 GROUP BY netlinks.dst) nldstfiber ON nd.id = nldstfiber.dst 
		LEFT JOIN (SELECT netdev, COUNT(port) AS portcount FROM nodes LEFT JOIN customers ON customers.id = nodes.ownerid WHERE customers.type = 0 AND linktype = 0 GROUP BY netdev) pndpcable ON pndpcable.netdev = nd.id 
		LEFT JOIN (SELECT netdev, COUNT(port) AS portcount FROM nodes LEFT JOIN customers ON customers.id = nodes.ownerid WHERE customers.type = 1 AND linktype = 0 GROUP BY netdev) cndpcable ON cndpcable.netdev = nd.id 
		LEFT JOIN (SELECT netdev, COUNT(port) AS portcount FROM nodes LEFT JOIN customers ON customers.id = nodes.ownerid WHERE customers.type = 0 AND linktype = 1 GROUP BY netdev) pndpradio ON pndpradio.netdev = nd.id 
		LEFT JOIN (SELECT netdev, COUNT(port) AS portcount FROM nodes LEFT JOIN customers ON customers.id = nodes.ownerid WHERE customers.type = 1 AND linktype = 1 GROUP BY netdev) cndpradio ON cndpradio.netdev = nd.id 
		LEFT JOIN (SELECT netdev, COUNT(port) AS portcount FROM nodes LEFT JOIN customers ON customers.id = nodes.ownerid WHERE customers.type = 0 AND linktype = 2 GROUP BY netdev) pndpfiber ON pndpfiber.netdev = nd.id 
		LEFT JOIN (SELECT netdev, COUNT(port) AS portcount FROM nodes LEFT JOIN customers ON customers.id = nodes.ownerid WHERE customers.type = 1 AND linktype = 2 GROUP BY netdev) cndpfiber ON cndpfiber.netdev = nd.id 
		WHERE nd.networknodeid > 0 AND u.useraport=1 AND (u.mark = ? OR u.mark = ?) AND EXISTS (SELECT id FROM netlinks nl WHERE nl.src = nd.id OR nl.dst = nd.id) 
		ORDER BY nd.name',array('WW','WO'));
	
	if ($nd) {
	    $count = sizeof($nd);
	    for ($i=0;$i<$count;$i++) {
		$nd[$i]['personalaccessports'] = $nd[$i]['cablepersonalaccessports'] + $nd[$i]['radiopersonalaccessports'] + $nd[$i]['fiberpersonalaccessports'];
		$nd[$i]['commercialaccessports'] = $nd[$i]['cablecommercialaccessports'] + $nd[$i]['radiocommercialaccessports'] + $nd[$i]['fibercommercialaccessports'];
	    }
	}
	
	$dane = array();
	for ($i=0;$i<$count;$i++) {
	    $dane[$i]['id'] = $nd[$i]['id'];
	    $dane[$i]['netnodename'] = $nd[$i]['netnodename'];
	    $dane[$i]['networknodename'] = $nd[$i]['networknodename'];
	    $dane[$i]['backbone_layer'] = 'Nie';
	
	    if ($nd[$i]['cabledistports'] + $nd[$i]['radiodistports'] + $nd[$i]['fiberdistports'] > 0 || $nd[$i]['personalaccessports'] + $nd[$i]['commercialaccessports'] == 0) {
		
		if ($nd[$i]['cabledistports']) {
			$dane[$i]['distribution_layer'] = 'Tak';
			$dane[$i]['access_layer'] = 'Nie';
			$dane[$i]['medium'] = 'kablowe parowe miedziane';
			$dane[$i]['pasmo_radiowe'] = '';
			$dane[$i]['technologia'] = 'Ethernet';
			$dane[$i]['max_to_net'] = '100';
			$dane[$i]['max_to_user'] = '100';
			$dane[$i]['ports'] = $nd[$i]['cabledistports'];
			$dane[$i]['use_ports'] = $nd[$i]['cabledistports'];
			$dane[$i]['empty_ports'] = 0;
			$dane[$i]['sharing'] = 'Nie';
		}
		
		if ($nd[$i]['radiodistports']) {
			$dane[$i]['distribution_layer'] = 'Tak';
			$dane[$i]['access_layer'] = 'Nie';
			$dane[$i]['medium'] = 'radiowe';
			$dane[$i]['pasmo_radiowe'] = '5.5';
			$dane[$i]['technologia'] = 'Ethernet';
			$dane[$i]['max_to_net'] = '54';
			$dane[$i]['max_to_user'] = '54';
			$dane[$i]['ports'] = $nd[$i]['radiodistports'];
			$dane[$i]['use_ports'] = $nd[$i]['radiodistports'];
			$dane[$i]['empty_ports'] = 0;
			$dane[$i]['sharing'] = 'Nie';
		}
		
		if ($nd[$i]['fiberdistports']) {
			$dane[$i]['distribution_layer'] = 'Tak';
			$dane[$i]['access_layer'] = 'Nie';
			$dane[$i]['medium'] = 'światłowodowe';
			$dane[$i]['pasmo_radiowe'] = '';
			$dane[$i]['technologia'] = 'Ethernet';
			$dane[$i]['max_to_net'] = '1000';
			$dane[$i]['max_to_user'] = '1000';
			$dane[$i]['ports'] = $nd[$i]['fiberdistports'];
			$dane[$i]['use_ports'] = $nd[$i]['fiberdistports'];
			$dane[$i]['empty_ports'] = 0;
			$dane[$i]['sharing'] = 'Nie';
		}
	    }
	
	    if ($nd[$i]['cablepersonalaccessports'] + $nd[$i]['cablecommercialaccessports']) {
		$dane[$i]['distribution_layer'] = 'Nie';
		$dane[$i]['access_layer'] = 'Tak';
		$dane[$i]['medium'] = 'kablowe parowe miedziane';
		$dane[$i]['pasmo_radiowe'] = '';
		$dane[$i]['technologia'] = 'Ethernet';
		$dane[$i]['max_to_net'] = '100';
		$dane[$i]['max_to_user'] = '100';
		$dane[$i]['ports'] = ($nd[$i]['ports'] - $nd[$i]['cabledistports'] - $nd[$i]['radiodistports'] - $nd[$i]['fiberdistports'] - $nd[$i]['radiopersonalaccessports'] - $nd[$i]['radiocommercialaccessports'] - $nd[$i]['fiberpersonalaccessports'] - $nd[$i]['fibercommercialaccessports']);
		$dane[$i]['use_ports'] = ($nd[$i]['cablepersonalaccessports'] + $nd[$i]['cablecommercialaccessports']);
		$dane[$i]['empty_ports'] = ($nd[$i]['ports'] - $nd[$i]['cabledistports'] - $nd[$i]['radiodistports'] - $nd[$i]['fiberdistports'] - $nd[$i]['personalaccessports'] - $nd[$i]['commercialaccessports']);
		$dane[$i]['sharing'] = 'Nie';
	    }
	
	
	    if ($nd[$i]['radiopersonalaccessports'] + $nd[$i]['radiocommercialaccessports']) {
		$dane[$i]['distribution_layer'] = 'Nie';
		$dane[$i]['access_layer'] = 'Tak';
		$dane[$i]['medium'] = 'radiowe';
		$dane[$i]['pasmo_radiowe'] = '2.4';
		$dane[$i]['technologia'] = 'Ethernet';
		$dane[$i]['max_to_net'] = '54';
		$dane[$i]['max_to_user'] = '54';
		$dane[$i]['ports'] = ($nd[$i]['radiopersonalaccessports'] + $nd[$i]['radiocommercialaccessports']);
		$dane[$i]['use_ports'] = ($nd[$i]['radiopersonalaccessports'] + $nd[$i]['radiocommercialaccessports']);
		$dane[$i]['empty_ports'] = 0;
		$dane[$i]['sharing'] = 'Nie';
	    }
	
	
	    if ($nd[$i]['fiberpersonalaccessports'] + $nd[$i]['fibercommercialaccessports']) {
		$dane[$i]['distribution_layer'] = 'Nie';
		$dane[$i]['access_layer'] = 'Tak';
		$dane[$i]['medium'] = 'światłowodowe';
		$dane[$i]['pasmo_radiowe'] = '';
		$dane[$i]['technologia'] = 'Ethernet';
		$dane[$i]['max_to_net'] = '100';
		$dane[$i]['max_to_user'] = '100';
		$dane[$i]['ports'] = ($nd[$i]['fiberpersonalaccessports'] + $nd[$i]['fibercommercialaccessports']);
		$dane[$i]['use_ports'] = ($nd[$i]['fiberpersonalaccessports'] + $nd[$i]['fibercommercialaccessports']);
		$dane[$i]['empty_ports'] = 0;
		$dane[$i]['sharing'] = 'Nie';
	    }

    } // end for

//$tmp = array();

//for ($i=0;$i<sizeof($dane);$i++) {
//    $tmp[] = 'I,' . implode(',',$dane[$i]);
//}

	$DB->Execute('DELETE FROM uke_data WHERE rapid = ? AND mark = ?;',array($idr,'INT'));
	
	for ($i=0;$i<sizeof($dane);$i++) {
	    $tmp['rapid'] = $idr;
	    $tmp['mark'] = 'INT';
	    $tmp['markid'] = $dane[$i]['id'];
	    $tmp['useraport'] = 1;
	    $tmp['data'] = serialize($dane[$i]);
	    $UKE->add_siis4_data($tmp);
	}
	$obj->script("alert('Dane zostały ponownie zaimportowane');");
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=INT&idr=".$idr."');");
	return $obj;
    }


    function set_int_useraport($idr,$id,$set)
    {
	global $DB;
	$obj = new xajaxResponse();
	
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',
	    array(
		($set ? 1 : 0),
		$id,
	    )
	);
	
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=INT&idr=".$idr."');");
	
	return $obj;
    }
    
    
    function import_LK($idr)
    {
	global $DB,$linktypes;
	$obj = new xajaxResponse();
	
	$netdevices = $DB->GetAll('SELECT markid AS id FROM uke_data WHERE rapid=? AND mark=? AND useraport=? ORDER BY markid;',array($idr,'INT',1));
	
	$processed = array();
	$netlinks = array();
	
	if ($netdevices) foreach ($netdevices as $netdevice) {
		if ($ndnetlinks = $DB->GetAll("SELECT src, dst, type, speed FROM netlinks WHERE src = ? OR dst = ? ORDER BY src",array($netdevice['id'], $netdevice['id']))) {
		    foreach ($ndnetlinks as $netlink) {
			$idnet = $netdevice['id'];
			$srcnet = $netlink['src'];
			$dstnet = $netlink['dst'];
			$netnodeid = array($srcnet, $dstnet);
			sort($netnodeid);
			$netnodelinkid = implode('_',$netnodeid);
			if (!isset($processed[$netnodelinkid])) {
			    
			    if ($netlink['src'] == $netdevice['id']) {
				if ($idnet != $dstnet) {
				    $netlinks[] = array(
					'type' 	=> $netlink['type'], 
					'speed' => $netlink['speed'], 
					'src' 	=> $idnet, 
					'dst' 	=> $dstnet,
				    );
				    $processed[$netnodelinkid] = true;
				    $netnodes[$idnet]['distports']++;
				}
			    } else if ($idnet != $srcnet) {
				    $netlinks[] = array(
					'type' 	=> $netlink['type'], 
					'speed' => $netlink['speed'], 
					'src' 	=> $idnet, 
					'dst' 	=> $srcnet,
				    );
				    $processed[$netnodelinkid] = true;
				    $netnodes[$idnet]['distports']++;
				}
			}
		    }
		}
	}
	
	if ($netlinks)
	{
	$DB->Execute('DELETE FROM uke_data WHERE rapid=? AND mark = ? ;',array($idr,'LK'));
	foreach ($netlinks as $netlink)
	
	{
	    if ($netlink['src'] != $netlink['dst'])
	    {
		if ($netlink['type'] != 1) 
		{
		    $LK = array(
		    'identyfikator'	=> $netlink['src'].'_'.$netlink['dst'],
		    'wlasnosc'		=> 'własna',
		    'obcy'		=> '',
		    'rodzaja'		=> 'węzeł własny',
		    
		    'identyfikatora'	=> $DB->GetOne('SELECT nn.name FROM networknode nn JOIN netdevices nd ON (nn.id = nd.networknodeid) WHERE nd.id = ? LIMIT 1;',array($netlink['src'])),
		    
		    'rodzajb'		=> 'węzeł własny',
		    
		    'identyfikatorb'	=> $DB->GetOne('SELECT nn.name FROM networknode nn JOIN netdevices nd ON (nn.id = nd.networknodeid) WHERE nd.id = ? LIMIT 1;',array($netlink['src'])),
		    
		    'medium'		=> $linktypes[$netlink['type']]['technologia'],
		    'typwlokna'		=> ($netlink['type'] == 2 ? $linktypes[$netlink['type']]['typ'] : ''),
		    'liczbalwokien'	=> ($netlink['type'] == 2 ? implode(',', array_fill(0, 2, $linktypes[$netlink['type']]['liczba_jednostek'])) : ''),
		    'wlokienused'	=> '',
		    'eu'		=> 'Nie',
		    'dostepnapasywna'	=> 'Brak danych',
		    'rodzajpasywnej'	=> '',
		    'sharingfiber'	=> ($netlink['type'] == 2 ? "Nie" : ""),
		    'sharingwlokna'	=> '',
		    'sharingprzepustowosc'	=> 'Nie',
		    'rodzajtraktu'	=> $linktypes[$netlink['type']]['trakt'],
		    'dlugosckabla'	=> ($netlink['type'] == 2 ? "0.1" : ""),
		    );
		    
		
		$DB->Execute('INSERT INTO uke_data (rapid, mark, markid,useraport,data) VALUE (?,?,?,?,?);',
		    array($idr,'LK',$LK['identyfikator'],1,serialize($LK))
		);
		
		}
	    }
	}
	}
	$obj->script("alert('Dane zostały ponownie zaimportowane');");
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=LK&idr=".$idr."');");
	return $obj;
    }
    
    
    function import_LB($idr)
    {
	global $DB,$linktypes;
	$obj = new xajaxResponse();
	
	$netdevices = $DB->GetAll('SELECT markid AS id FROM uke_data WHERE rapid=? AND mark=? AND useraport=? ORDER BY markid;',array($idr,'INT',1));
	
	$processed = array();
	$netlinks = array();
	
	if ($netdevices) foreach ($netdevices as $netdevice) {
		if ($ndnetlinks = $DB->GetAll("SELECT src, dst, type, speed FROM netlinks WHERE src = ? OR dst = ? ORDER BY src",array($netdevice['id'], $netdevice['id']))) {
		    foreach ($ndnetlinks as $netlink) {
			$idnet = $netdevice['id'];
			$srcnet = $netlink['src'];
			$dstnet = $netlink['dst'];
			$netnodeid = array($srcnet, $dstnet);
			sort($netnodeid);
			$netnodelinkid = implode('_',$netnodeid);
			if (!isset($processed[$netnodelinkid])) {
			    
			    if ($netlink['src'] == $netdevice['id']) {
				if ($idnet != $dstnet) {
				    $netlinks[] = array(
					'type' 	=> $netlink['type'], 
					'speed' => $netlink['speed'], 
					'src' 	=> $idnet, 
					'dst' 	=> $dstnet,
				    );
				    $processed[$netnodelinkid] = true;
				    $netnodes[$idnet]['distports']++;
				}
			    } else if ($idnet != $srcnet) {
				    $netlinks[] = array(
					'type' 	=> $netlink['type'], 
					'speed' => $netlink['speed'], 
					'src' 	=> $idnet, 
					'dst' 	=> $srcnet,
				    );
				    $processed[$netnodelinkid] = true;
				    $netnodes[$idnet]['distports']++;
				}
			}
		    }
		}
	}
	
	if ($netlinks)
	{
	$DB->Execute('DELETE FROM uke_data WHERE rapid=? AND mark = ? ;',array($idr,'LB'));
	foreach ($netlinks as $netlink)
	{
	    if ($netlink['src'] != $netlink['dst'])
	    {
		if ($netlink['type'] == 1) 
		{
		    $LB = array(
		    'identyfikator' 	=> $netlink['src'].'_'.$netlink['dst'],
		    'identyfikatora'	=> $DB->GetOne('SELECT nn.name FROM networknode nn JOIN netdevices nd ON (nn.id = nd.networknodeid) WHERE nd.id = ? LIMIT 1;',array($netlink['src'])),
		    'identyfikatorb'	=> $DB->GetOne('SELECT nn.name FROM networknode nn JOIN netdevices nd ON (nn.id = nd.networknodeid) WHERE nd.id = ? LIMIT 1;',array($netlink['dst'])),
		    'medium'		=> 'radiowe na częstotliwości ogólnodostępnej',
		    'pozwolenie'	=> '',
		    'pasmo'		=> $linktypes[$netlink['type']]['pasmo'],
		    'system'		=> $linktypes[$netlink['type']]['typ'],
		    'przepustowosc'	=> $linktypes[$netlink['type']]['szybkosc_radia'],
		    'sharing'		=> 'Nie',
		);
		
		$DB->Execute('INSERT INTO uke_data (rapid, mark, markid,useraport,data) VALUE (?,?,?,?,?);',
		    array($idr,'LB',$LB['identyfikator'],1,serialize($LB))
		);
		
		}
	    }
	}
	}
	$obj->script("alert('Dane zostały ponownie zaimportowane');");
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=LB&idr=".$idr."');");

	return $obj;
    }
    
    function import_POL($idr)
    {
	global $DB,$linktypes;
	$obj = new xajaxResponse();
	
	$netdevices = $DB->GetAll('SELECT markid AS id FROM uke_data WHERE rapid=? AND mark=? AND useraport=? ORDER BY markid;',array($idr,'INT',1));
	
	$processed = array();
	$netlinks = array();
	
	if ($netdevices) foreach ($netdevices as $netdevice) {
		if ($ndnetlinks = $DB->GetAll("SELECT src, dst, type, speed FROM netlinks WHERE src = ? OR dst = ? ORDER BY src",array($netdevice['id'], $netdevice['id']))) {
		    foreach ($ndnetlinks as $netlink) {
			$idnet = $netdevice['id'];
			$srcnet = $netlink['src'];
			$dstnet = $netlink['dst'];
			$netnodeid = array($srcnet, $dstnet);
			sort($netnodeid);
			$netnodelinkid = implode('_',$netnodeid);
			if (!isset($processed[$netnodelinkid])) {
			    
			    if ($netlink['src'] == $netdevice['id']) {
				if ($idnet != $dstnet) {
				    $netlinks[] = array(
					'type' 	=> $netlink['type'], 
					'speed' => $netlink['speed'], 
					'src' 	=> $idnet, 
					'dst' 	=> $dstnet,
				    );
				    $processed[$netnodelinkid] = true;
				    $netnodes[$idnet]['distports']++;
				}
			    } else if ($idnet != $srcnet) {
				    $netlinks[] = array(
					'type' 	=> $netlink['type'], 
					'speed' => $netlink['speed'], 
					'src' 	=> $idnet, 
					'dst' 	=> $srcnet,
				    );
				    $processed[$netnodelinkid] = true;
				    $netnodes[$idnet]['distports']++;
				}
			}
		    }
		}
	}
	
	if ($netlinks)
	{
	
	$DB->Execute('DELETE FROM uke_data WHERE rapid=? AND mark = ? ;',array($idr,'POL'));
	foreach ($netlinks as $netlink)
	{
	    if ($netlink['src'] != $netlink['dst'])
	    {
		
		$POL = array(
		    'identyfikator' 	=> $netlink['src'].'_'.$netlink['dst'],
		    'wlasnosc'		=> 'Własna',
		    'obcy'		=> '',
		    'identyfikatora'	=> $DB->GetOne('SELECT nn.name FROM networknode nn JOIN netdevices nd ON (nn.id = nd.networknodeid) WHERE nd.id = ? LIMIT 1;',array($netlink['src'])),
		    'identyfikatorb'	=> $DB->GetOne('SELECT nn.name FROM networknode nn JOIN netdevices nd ON (nn.id = nd.networknodeid) WHERE nd.id = ? LIMIT 1;',array($netlink['dst'])),
		    'backbone_layer'	=> 'Nie',
		    'distribution_layer' => 'Tak',
		    'access_layer'	=> 'Nie',
		    'szerokopasmowe'	=> 'Tak',
		    'glosowe'		=> 'Nie',
		    'inne'		=> 'Nie',
		    'speed'		=> floor($netlink['speed'] / 1000),
		    'speednet'		=> floor($netlink['speed'] / 1000),
		);
		
		$DB->Execute('INSERT INTO uke_data (rapid, mark, markid,useraport,data) VALUE (?,?,?,?,?);',
		    array($idr,'POL',$POL['identyfikator'],1,serialize($POL)));
		
		
	    }
	}
	
	}
	$obj->script("alert('Dane zostały ponownie zaimportowane');");
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=POL&idr=".$idr."');");

	return $obj;
    }

    function set_lk_useraport($idr,$id,$set)
    {
	global $DB;
	$obj = new xajaxResponse();
	
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',
	    array(
		($set ? 1 : 0),
		$id,
	    )
	);
	
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=LK&idr=".$idr."');");
	
	return $obj;
	}


    function set_lb_useraport($idr,$id,$set)
    {
	global $DB;
	$obj = new xajaxResponse();
	
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',
	    array(
		($set ? 1 : 0),
		$id,
	    )
	);
	
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=LB&idr=".$idr."');");
	
	return $obj;
    }
    
    function set_pol_useraport($idr,$id,$set)
    {
	global $DB;
	$obj = new xajaxResponse();
	
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',
	    array(
		($set ? 1 : 0),
		$id,
	    )
	);
	
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=POL&idr=".$idr."');");
	
	return $obj;
    }
    
    
    function import_ZAS($idr)
    {
	global $DB,$linktypes, $LMS;
	$obj = new xajaxResponse();
	
	$nodelist = array();
	
	$nd = $DB->GetAll('SELECT nd.id, nd.ports, nd.name AS netnodename, nn.name AS networknodename, nn.backbone_layer, nn.distribution_layer, nn.access_layer, nn.sharing, 
		(CASE WHEN nlsrccable.nlsrccount IS NULL THEN 0 ELSE nlsrccable.nlsrccount END) + (CASE WHEN nldstcable.nldstcount IS NULL THEN 0 ELSE nldstcable.nldstcount END) AS cabledistports,
		(CASE WHEN nlsrcradio.nlsrccount IS NULL THEN 0 ELSE nlsrcradio.nlsrccount END) + (CASE WHEN nldstradio.nldstcount IS NULL THEN 0 ELSE nldstradio.nldstcount END) AS radiodistports,
		(CASE WHEN nlsrcfiber.nlsrccount IS NULL THEN 0 ELSE nlsrcfiber.nlsrccount END) + (CASE WHEN nldstfiber.nldstcount IS NULL THEN 0 ELSE nldstfiber.nldstcount END) AS fiberdistports,
		(CASE WHEN pndpcable.portcount IS NULL THEN 0 ELSE pndpcable.portcount END) AS cablepersonalaccessports,
		(CASE WHEN cndpcable.portcount IS NULL THEN 0 ELSE cndpcable.portcount END) AS cablecommercialaccessports,
		(CASE WHEN pndpradio.portcount IS NULL THEN 0 ELSE pndpradio.portcount END) AS radiopersonalaccessports,
		(CASE WHEN cndpradio.portcount IS NULL THEN 0 ELSE cndpradio.portcount END) AS radiocommercialaccessports,
		(CASE WHEN pndpfiber.portcount IS NULL THEN 0 ELSE pndpfiber.portcount END) AS fiberpersonalaccessports,
		(CASE WHEN cndpfiber.portcount IS NULL THEN 0 ELSE cndpfiber.portcount END) AS fibercommercialaccessports
		FROM netdevices nd 
		LEFT JOIN networknode nn ON (nn.id = nd.networknodeid) 
		LEFT JOIN uke_data u ON (u.markid = nn.name)
		LEFT JOIN (SELECT netlinks.src AS src, COUNT(netlinks.id) AS nlsrccount FROM netlinks WHERE type = 0 GROUP BY netlinks.src) nlsrccable ON nd.id = nlsrccable.src 
		LEFT JOIN (SELECT netlinks.dst AS dst, COUNT(netlinks.id) AS nldstcount FROM netlinks WHERE type = 0 GROUP BY netlinks.dst) nldstcable ON nd.id = nldstcable.dst 
		LEFT JOIN (SELECT netlinks.src AS src, COUNT(netlinks.id) AS nlsrccount FROM netlinks WHERE type = 1 GROUP BY netlinks.src) nlsrcradio ON nd.id = nlsrcradio.src 
		LEFT JOIN (SELECT netlinks.dst AS dst, COUNT(netlinks.id) AS nldstcount FROM netlinks WHERE type = 1 GROUP BY netlinks.dst) nldstradio ON nd.id = nldstradio.dst 
		LEFT JOIN (SELECT netlinks.src AS src, COUNT(netlinks.id) AS nlsrccount FROM netlinks WHERE type = 2 GROUP BY netlinks.src) nlsrcfiber ON nd.id = nlsrcfiber.src 
		LEFT JOIN (SELECT netlinks.dst AS dst, COUNT(netlinks.id) AS nldstcount FROM netlinks WHERE type = 2 GROUP BY netlinks.dst) nldstfiber ON nd.id = nldstfiber.dst 
		LEFT JOIN (SELECT netdev, COUNT(port) AS portcount FROM nodes LEFT JOIN customers ON customers.id = nodes.ownerid WHERE customers.type = 0 AND linktype = 0 GROUP BY netdev) pndpcable ON pndpcable.netdev = nd.id 
		LEFT JOIN (SELECT netdev, COUNT(port) AS portcount FROM nodes LEFT JOIN customers ON customers.id = nodes.ownerid WHERE customers.type = 1 AND linktype = 0 GROUP BY netdev) cndpcable ON cndpcable.netdev = nd.id 
		LEFT JOIN (SELECT netdev, COUNT(port) AS portcount FROM nodes LEFT JOIN customers ON customers.id = nodes.ownerid WHERE customers.type = 0 AND linktype = 1 GROUP BY netdev) pndpradio ON pndpradio.netdev = nd.id 
		LEFT JOIN (SELECT netdev, COUNT(port) AS portcount FROM nodes LEFT JOIN customers ON customers.id = nodes.ownerid WHERE customers.type = 1 AND linktype = 1 GROUP BY netdev) cndpradio ON cndpradio.netdev = nd.id 
		LEFT JOIN (SELECT netdev, COUNT(port) AS portcount FROM nodes LEFT JOIN customers ON customers.id = nodes.ownerid WHERE customers.type = 0 AND linktype = 2 GROUP BY netdev) pndpfiber ON pndpfiber.netdev = nd.id 
		LEFT JOIN (SELECT netdev, COUNT(port) AS portcount FROM nodes LEFT JOIN customers ON customers.id = nodes.ownerid WHERE customers.type = 1 AND linktype = 2 GROUP BY netdev) cndpfiber ON cndpfiber.netdev = nd.id 
		WHERE nd.networknodeid > 0 AND u.useraport=1 AND (u.mark = ? OR u.mark = ?) AND EXISTS (SELECT id FROM netlinks nl WHERE nl.src = nd.id OR nl.dst = nd.id) 
		ORDER BY nd.name',array('WW','WO'));
	
	if ($nd) 
	{
	    $count = sizeof($nd);
	    
	    $DB->Execute('DELETE FROM uke_data WHERE rapid = ? AND mark=? ;',array($idr,'ZAS'));
	    
	    for ($i=0;$i<$count;$i++) {
		$nd[$i]['personalaccessports'] = $nd[$i]['cablepersonalaccessports'] + $nd[$i]['radiopersonalaccessports'] + $nd[$i]['fiberpersonalaccessports'];
		$nd[$i]['commercialaccessports'] = $nd[$i]['cablecommercialaccessports'] + $nd[$i]['radiocommercialaccessports'] + $nd[$i]['fibercommercialaccessports'];
		$nodelist[] = $nd[$i]['id'];
	    }
	
	    $ranges = $DB->GetAll("SELECT n.id, n.linktype, n.location_street, n.location_city, n.location_house , n.longitude, n.latitude, 
		(SELECT zip FROM pna WHERE pna.cityid = n.location_city AND (pna.streetid IS NULL OR (pna.streetid IS NOT NULL AND pna.streetid = n.location_street)) LIMIT 1) AS location_zip " 
		.", t.id AS tariffid "
		.", CASE t.type
			WHEN ".TARIFF_INTERNET." THEN 'INT'
			WHEN ".TARIFF_PHONE." THEN 'TEL'
			WHEN ".TARIFF_TV." THEN 'TV'
			ELSE 'INT'
		    END AS servicetypes "
		.",t.downceil AS downstream, t.upceil AS upstream "
		.", ne.name AS networknode "
		.", c.type AS custype "
		."FROM nodes n 
		JOIN nodeassignments na ON (na.nodeid = n.id)
		JOIN assignments a ON (a.id = na.assignmentid)
		JOIN tariffs t ON (t.id = a.tariffid)
		JOIN netdevices nd ON (nd.id = n.netdev)
		JOIN networknode ne ON (ne.id = nd.networknodeid)
		JOIN customers c ON (c.id = n.ownerid)
		WHERE n.ownerid > 0 AND n.location_city <> 0 AND n.creationdate < ? AND n.netdev IN (".implode(',',$nodelist).") 
		AND t.type IN (".TARIFF_INTERNET.",".TARIFF_PHONE.",".TARIFF_TV.") 
		AND a.suspended = 0 AND a.period IN (".implode(',', array(YEARLY, HALFYEARLY, QUARTERLY, MONTHLY)).") 
		AND (a.datefrom = 0 OR a.datefrom < ?NOW?) AND (a.dateto = 0 OR a.dateto > ?NOW?) 
		ORDER BY n.id ;",array(REPORT_DATE_RANGE));
		
	
	    $rang = array();
	
	    for ($i=0;$i<sizeof($ranges);$i++)
	    {
		$rang[$i] = $ranges[$i];
		$location = $LMS->getterytcode($ranges[$i]['location_city'],$ranges[$i]['location_street']);
		$rang[$i]['states'] = $location['name_states'];
		$rang[$i]['districts'] = $location['name_districts'];
		$rang[$i]['boroughs'] = $location['name_boroughs'];
		$rang[$i]['city'] = $location['name_city'];
		$rang[$i]['street'] = $location['name_street'];
		$rang[$i]['kod_terc'] = $location['kod_terc'];
		$rang[$i]['kod_simc'] = $location['kod_simc'];
		$rang[$i]['kod_ulic'] = $location['kod_ulic'];
		
		
	    }
	    
	    $count = sizeof($rang);
	    for ($i=0;$i<$count;$i++) {
		$dane = array(
		    'identyfikator'		=> $rang[$i]['id'],
		    'wlasnosc'			=> 'Własna',
		    'formaobca'			=> '',
		    'identyfikatorobcy'		=> '',
		    'networknode'		=> $rang[$i]['networknode'],
		    'states'			=> $rang[$i]['states'],
		    'districts'			=> $rang[$i]['districts'],
		    'boroughs'			=> $rang[$i]['boroughs'],
		    'kod_terc'			=> $rang[$i]['kod_terc'],
		    'city'			=> $rang[$i]['city'],
		    'kod_simc'			=> $rang[$i]['kod_simc'],
		    'street'			=> $rang[$i]['street'],
		    'kod_ulic'			=> $rang[$i]['kod_ulic'],
		    'location_house'		=> $rang[$i]['location_house'],
		    'zip'			=> $rang[$i]['location_zip'],
		    'latitude'			=> $rang[$i]['latitude'],
		    'longitude'			=> $rang[$i]['longitude'],
		    'medium'			=> $linktypes[$rang[$i]['linktype']]['technologia'],
		    'dostepowa'			=> $linktypes[$rang[$i]['linktype']]['technologia_dostepu'],
		    'isdn'			=> 'Nie',
		    'voip'			=> ($rang[$i]['servicetypes'] == 'TEL' ? 'Tak' : 'Nie'),
		    'telmobile'			=> 'Nie',
		    'int'			=> ($rang[$i]['servicetypes'] == 'INT' ? 'Tak' : 'Nie'),
		    'intmobile'			=> 'Nie',
		    'iptv'			=> ($rang[$i]['servicetypes'] == 'TV' ? 'Tak' : 'Nie'),
		    'otherservice'		=> 'Nie',
		    'downstream'		=> round($rang[$i]['downstream'] / 1000),
		    'downstreammobile'		=> 0,
		    'custype'			=> $rang[$i]['custype'],
		);
		
		$DB->Execute('INSERT INTO uke_data (rapid, mark, markid,useraport,data) VALUE (?,?,?,?,?);',
		    array($idr,'ZAS',$dane['identyfikator'],1,serialize($dane)));
		
	    }
	}
	
	$obj->script("alert('Dane zostały ponownie zaimportowane');");
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=ZAS&idr=".$idr."');");
	
	
	return $obj;
    }
    
    function set_zas_useraport($idr,$id,$set)
    {
	global $DB;
	$obj = new xajaxResponse();
	
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',
	    array(
		($set ? 1 : 0),
		$id,
	    )
	);
	
	$obj->script("loadAjax('id_data','?m=uke_siis4_info&tuck=ZAS&idr=".$idr."');");
	
	return $obj;
    }

$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array(
    'add_siis4',
    'add_PO',
    'set_po_useraport',
    'view_select_po_to_ww',
    'add_po_to_ww',
    'set_ww_useraport',
    'view_select_po_to_wo',
    'add_po_to_wo',
    'set_wo_useraport',
    'import_interfaces',
    'set_int_useraport',
    'import_lk',
    'import_lb',
    'import_pol',
    'set_lk_useraport',
    'set_lb_useraport',
    'set_pol_useraport',
    'set_zas_useraport',
    'import_zas',
));
$SMARTY->assign('xajax', $LMS->RunXajax());

?>