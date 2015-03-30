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
/*
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
*/
function show_procent($id,$current,$max)
{
    $obj = new xajaxResponse();
    
    $procent = round(ceil(($current * 100)/$max));
    if ($current >= $max) $procent = 100;
    $obj->assign($id,'innerHTML',"<b>".$procent." %</b>");
    $_max = ($max-1);
    if ($current >= $_max)
	$obj->assign($id,'innerHTML','');
    
    return $obj;
}

function refresh_ww($idr,$mess = 0)
{
	global $DB;
	$obj = new xajaxResponse();
	
	if ($ww = $DB->GetAll('SELECT id, useraport, data FROM uke_data WHERE rapid = ? AND mark=? ORDER BY markid ASC;',array($idr,'WW')))
	{
		$_ww = array();
		for ($i=0; $i<sizeof($ww); $i++) {
			$_tmp = unserialize($ww[$i]['data']);
			$_ww[] = array(
				'id' => $_tmp['id'],
				'useraport' => $ww[$i]['useraport'],
				'podmiot_obcy' => $_tmp['podmiot_obcy'],
				'idp' => $DB->GetOne('SELECT id FROM uke_data WHERE rapid=? AND mark=? AND markid=? LIMIT 1;',array($idr,'PO',$_tmp['podmiot_obcy'])),
			);
		}
		
		$DB->Execute('DELETE FROM uke_data WHERE rapid=? AND mark=?;',array($idr,'WW'));
		
		if ($_ww) {
		    $count = sizeof($_ww);
			for ($i=0; $i<$count; $i++) {
				$obj->script("xajax_add_po_to_ww('".$idr."','".$_ww[$i]['id']."','".$_ww[$i]['idp']."',0,".$_ww[$i]['useraport'].");");
				usleep(400);
				$obj->script("xajax_show_procent('id_refresh_show',".($i+1).",".$count.");");
			}
		}
	} 
	return $obj;
}

function refresh_wo($idr,$mess = 0)
{
	global $DB;
	$obj = new xajaxResponse();
	
	if ($wo = $DB->GetAll('SELECT id, useraport, data FROM uke_data WHERE rapid = ? AND mark=? ORDER BY markid ASC;',array($idr,'WO')))
	{
		$_wo = array();
		for ($i=0; $i<sizeof($wo); $i++) {
			$_tmp = unserialize($wo[$i]['data']);
			$_wo[] = array(
				'id' => $_tmp['id'],
				'useraport' => $wo[$i]['useraport'],
				'podmiot_obcy' => $_tmp['podmiot_obcy'],
				'podstawa' => $_tmp['podstawa'],
				'idp' => $DB->GetOne('SELECT id FROM uke_data WHERE rapid=? AND mark=? AND markid=? LIMIT 1;',array($idr,'PO',$_tmp['podmiot_obcy'])),
			);
		}
		
		$DB->Execute('DELETE FROM uke_data WHERE rapid=? AND mark=?;',array($idr,'WO'));
		
		if ($_wo) {
		    $count = sizeof($_wo);
			for ($i=0; $i<$count; $i++) {
				$obj->script("xajax_add_po_to_wo('".$idr."','".$_wo[$i]['id']."','".$_wo[$i]['idp']."','".$_wo[$i]['podstawa']."',0,".$_wo[$i]['useraport'].");");
				usleep(400);
				$obj->script("xajax_show_procent('id_refresh_show',".($i+1).",".$count.");");
			}
		}
	} 
	return $obj;
}


function import_division($id)
{
	global $DB,$SMARTY;
	$obj = new xajaxResponse();
	
	if ($id) {
		$div = $DB->getRow('SELECT * FROM divisions WHERE id = ? LIMIT 1;',array($id));
		$obj->assign("id_divname","value",$div['name']);
		$obj->script("removeClassId('id_divname','alerts');");
		
		if ($div['ten']) {
			$obj->assign("id_ten","value",$div['ten']);
			$obj->script("removeClassId('id_ten','alerts');");
		}
		if ($div['regon']) {
			$obj->assign("id_regon","value",$div['regon']);
			$obj->script("removeClassId('id_regon','alerts');");
		}
		if ($div['rbe']) {
			$obj->assign("id_krs","value",$div['rbe']);
		}
		if ($div['rpt']) {
			$obj->assign("id_rpt","value",$div['rpt']);
			$obj->script("removeClassId('id_rpt','alerts');");
		}
		if ($div['rjpt']) {
			$obj->assign("id_rjst","value",$div['rjpt']);
		}
		if ($div['city']) {
			$obj->assign("id_city","value",$div['city']);
			$obj->script("removeClassId('id_city','alerts');");
		}
		if ($div['address']) {
			$obj->assign("id_street","value",$div['address']);
		}
		if ($div['zip']) {
			$obj->assign("id_zip","value",$div['zip']);
			$obj->script("removeClassId('id_zip','alerts');");
		}
		if ($div['url']) {
			$obj->assign("id_url","value",$div['url']);
		}
		if ($div['email']) {
			$obj->assign("id_email","value",$div['email']);
			$obj->script("removeClassId('id_email','alerts');");
		}
		
	} else {
		
		$obj->assign("id_divname","value","");
		$obj->script("addClassId('id_divname','alerts');");
		$obj->assign("id_ten","value","");
		$obj->script("addClassId('id_ten','alerts');");
		$obj->assign("id_regon","value","");
		$obj->script("addClassId('id_regon','alerts');");
		$obj->assign("id_krs","value","");
		$obj->assign("id_rpt","value","");
		$obj->script("addClassId('id_rpt','alerts');");
		$obj->assign("id_rjst","value","");
		$obj->assign("id_city","value","");
		$obj->script("addClassId('id_city','alerts');");
		$obj->assign("id_street","value","");
		$obj->script("addClassId('id_street','alerts');");
		$obj->assign("id_zip","value","");
		$obj->script("addClassId('id_zip','alerts');");
		$obj->assign("id_url","value","");
		$obj->assign("id_email","value","");
		$obj->script("addClassId('id_email','alerts');");
	}
	
	return $obj;
}



function add_siis($forms)
{
	global $DB,$LMS,$UKE;
	$obj = new xajaxResponse();
	
	$form = $forms['rapdata'];
	$blad = false;
	
	$obj->script("removeClassId('id_divisionid','alerts');");
	$obj->script("removeClassId('id_reportyear','alerts');");
	$obj->assign("id_reportyear","innerHTML","");
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
	
	if (!$form['divname']) {
		$obj->script("addClassId('id_divname','alerts');");
		$blad = true;
	}
	
	if (!$form['reportyear']) {
		$obj->script("addClassId('id_reportyear','alerts');");
		$blad = true;
	} elseif (!intval($form['reportyear'])) {
		$obj->script("addClassId('id_reportyear','alerts');");
		$obj->assign("id_reportyear_alerts","innerHTML","Błędna data");
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
			$idr = $UKE->add_siis($form);
			$obj->script("self.location.href='?m=uke_siis';");
		} elseif ($form['action'] == 'edit') {
			$UKE->update_siis($form);
			$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=DP&idr=".$form['id']."');");
		}
	}
	
	return $obj;
}



function add_PO($forms) // podmio obcy
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
			
			$UKE->add_siis_data_po($data);
			$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=PO&idr=".$data['rapid']."');");
			
		} elseif ($action == 'edit') {
			
			$UKE->update_siis_data_po($data);
			$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=PO&idr=".$data['rapid']."');");
		}
	}
	
	return $obj;
}



function set_PO_useraport($idr,$id,$set)
{
	global $DB;
	$obj = new xajaxResponse();
	
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',
		array(($set ? 1 : 0),$id));
	
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=PO&idr=".$idr."');");
	
	return $obj;
}



function add_all_ww($idr=NULL)
{
	global $DB,$UKE;
	$obj = new xajaxResponse();
	
	if (!$idr)
		return $obj;
	
	$ww = $UKE->getwwlist($idr);
	
	if ($ww) {
		$tmp = array();
		
		for ($i=0; $i<sizeof($ww); $i++)
			$tmp[] = $ww[$i]['idw'];
		
		$tmp = implode(',',$tmp);
	} else 
		$tmp = NULL;
	
	$wwlist = $DB->getAll('SELECT n.id FROM networknode n WHERE n.type = ? '
			.($tmp ? ' AND n.id NOT IN ('.$tmp.') ' : '')
			.' ORDER BY n.name ASC;',array(NODE_OWN));
	
	if ($wwlist) {
		for ($i=0; $i<sizeof($wwlist); $i++)
			$obj->script("xajax_add_po_to_ww('".$idr."','".$wwlist[$i]['id']."','',0);");
	}
	
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=WW&idr=".$idr."');");
	
	return $obj;
	
}



function view_select_po_to_ww($idw,$idr)
{
	global $DB;
	$obj = new xajaxResponse();
	
	$polist = $DB->GetAll('SELECT id,markid FROM uke_data WHERE rapid = ? AND mark=? AND useraport=1;',array($idr,'PO'));
	$types = $DB->getOne('SELECT type FROM networknode WHERE id = ? LIMIT 1;',array($idw));
	
	if (($idw && $types == NODE_OWN) || ($idw && $types == NODE_FOREIGN && $polist)) {
		$obj->assign("id_view_add_po_to_ww","innerHTML","<a href=\"javascript:void(0);\" onclick=\"xajax_add_po_to_ww('".$idr."','".$idw."',document.getElementById('id_select_po').value);\">Dodaj węzeł do raportu</a>");
	} else {
		$obj->assign("id_view_add_po_to_ww","innerHTML","");
	}
	
	if (!$polist && $types == NODE_FOREIGN) {
		$obj->assign("id_view_select_po","innerHTML","<font color='red'>Brak podmiotów obcych, uzupełnij zakładkę <b>OB</b></font>");
	} elseif ($polist && $types == NODE_FOREIGN) {
		$tmp = "";
		$tmp .= "<span class='tiphelp' onmouseover=\"popup('Dostawcy usług i podmioty udostępniające lub współdzielące infrastrukturę (Podmoty Obce).<br>Wybranie podmiotu oznacza że węzeł jest współdzielony z innym podmiotem.');\" onmouseout=\"pophide();\"><b>Podmiot:</b> </span>";
		$tmp .= "<select id='id_select_po' style='cursor:pointer;min-width:250px;'>";
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



function add_po_to_ww($idr,$idw,$idp=NULL,$redirect = true,$useraport=1)
{
	global $DB,$LMS;
	$obj = new xajaxResponse();
	$blad = false;
	
	$networknode = $DB->GetRow('SELECT n.*, p.number AS projectnumber 
				    FROM networknode n 
				    LEFT JOIN invprojects p ON (p.id = n.invprojectid) 
				    WHERE n.id = ? LIMIT 1',array($idw));
	
	
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
		$networknode['podmiot_obcy'] = $DB->GetOne('SELECT markid FROM uke_data WHERE id = ? LIMIT 1;',array($idp));
	} else {
		$networknode['podmiot_obcy'] = NULL;
	}
	
	if ($networknode['type'] == NODE_FOREIGN && !$idp) {
		$blad = true;
		$obj -> script("alert('Wybierz podmiot obcy dla węzła');");
	}
	
	if (!$blad) {
		
		$data = serialize($networknode);
		$DB->Execute('INSERT INTO uke_data (rapid,mark,markid,useraport,data) VALUES (?,?,?,?,?);',array($idr,'WW',$networknode['name'],($useraport ? 1 : 0),$data));
		
		if ($redirect)
		    $obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=WW&idr=".$idr."');");
	}
	
	return $obj;
}


function set_ww_useraport($idr,$id,$set)
{
	global $DB;
	$obj = new xajaxResponse();
	
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',array(($set ? 1 : 0),$id));
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=WW&idr=".$idr."');");
	
	return $obj;
}


function view_select_po_to_wo($idw,$idr)
{
	global $DB,$PODSTAWA;
	$obj = new xajaxResponse();
	
	$polist = $DB->GetAll('SELECT id,markid FROM uke_data WHERE rapid = ? AND mark=? AND useraport=1;',array($idr,'PO')); // podmioty obce
	$types = $DB->GetOne('SELECT types FROM networknode WHERE id = ? LIMIT 1;',array($idw));
	
	if ($polist && $idw) {
		$obj->assign("id_view_add_po_to_wo","innerHTML","<a href=\"javascript:void(0);\" onclick=\"xajax_add_po_to_wo('".$idr."','".$idw."',document.getElementById('id_select_po').value,document.getElementById('id_podstawa_wo').value);\">Dodaj węzeł do raportu</a>");
		$tmp = "<span class='tiphelp' onmouseover=\"popup('Podstawa i forma korzystania z infrastruktury innego podmiotu');\" onmouseout=\"pophide();\"><b>Podstawa:</b></span> <input type='text' style='width:400px;' id='id_podstawa_wo' value=''>";
		$tmp .= "&nbsp;&nbsp;<select style='min-width:200px;' id='id_select_po' onchange=\"document.getElementById('id_podstawa_wo').value = this.value;\">";
		$tmp .= "<option value=''></option>";
		
		foreach ($PODSTAWA as $item => $key) 
			$tmp .= "<option value='".$key."'>".$key."</option>";
		
		$tmp .= "</select>";
		$obj->assign("id_view_podstawa_wo","innerHTML",$tmp);
	} else {
		$obj->assign("id_view_add_po_to_wo","innerHTML","");
		$obj->assign("id_view_podstawa_wo","innerHTML","<input type='hidden' id='id_podstawa_wo' value=''>");
	}
	
	if ($polist) {
		$tmp = "";
		$tmp .= "<span class='tiphelp' onmouseover=\"popup('Dostawcy usług i podmioty udostępniające lub współdzielące infrastrukturę (Podmoty Obce).<br>Wybranie podmiotu oznacza że węzeł jest współdzielony z innym podmiotem.');\" onmouseout=\"pophide();\"><b>Podmiot:</b> </span>";
		$tmp .= "<select id='id_select_po' style='cursor:pointer;min-width:200px;'>";
		$tmp .= "<option value=''></option>";
		
		for ($i=0;$i<sizeof($polist);$i++)
			$tmp .= "<option value='".$polist[$i]['id']."'>".$polist[$i]['markid']."</option>";
		
		$tmp .= "</select>";
		$obj->assign('id_view_select_po','innerHTML',$tmp);
	} else {
		$obj->assign('id_view_select_po','innerHTML','<input type="hidden" id="id_select_po" value=""><font color="red">Brak podmiotów obcych, uzupełnij zakładkę <b>OB</b></font>');
	}
	
	return $obj;
}



function add_po_to_wo($idr,$idw,$idp=NULL,$podstawa=NULL,$redirect=true,$useraport=1)
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
		
		$networknode = $DB->GetRow('SELECT n.*, p.number AS projectnumber 
					FROM networknode n 
					LEFT JOIN invprojects p ON (p.id = n.invprojectid) 
					WHERE n.id = ? LIMIT 1;',array($idw));
		
		$networknode['podmiot_obcy'] = $DB->GetOne('SELECT markid FROM uke_data WHERE id = ? LIMIT 1;',array($idp));
		
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
		
		$DB->Execute('INSERT INTO uke_data (rapid,mark,markid,useraport,data) VALUES (?,?,?,?,?);',
			array($idr,'WO',$networknode['name'],($useraport ? 1 : 0),$data));
		
		if ($redirect)
		    $obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=WO&idr=".$idr."');");
	}
	
	return $obj;
	
}


function set_wo_useraport($idr,$id,$set)
{
	global $DB;
	$obj = new xajaxResponse();
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',array(($set ? 1 : 0),$id));
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=WO&idr=".$idr."');");
	return $obj;
}



function add_interface_ww($idr,$idw) // dodaje interfejsy z odpowiedniego węzła
{
	global $DB;
	$obj = new xajaxResponse();
	
	return $obj;
}

function refresh_int($idr)
{
    global $DB,$UKE;
    $obj = new xajaxResponse();
    
    $DB->Execute('DELETE FROM uke_data WHERE rapid = ? AND mark = ?;',array($idr,'INT'));
    
    $ww = $UKE->getidwwuseraport($idr);
    $idww = implode(',',$ww);
    
    $wo = $UKE->getidwouseraport($idr);
    $idwo = implode(',',$wo);
    
    $int = $DB->GetCol('SELECT id FROM netdevices WHERE (networknodeid IN ('.$idww.')) '.($idwo ? ' OR (networknodeid IN ('.$idwo.'))' : '').';');
    $count = sizeof($int);

    for ($j=0; $j<$count; $j++) {
	    add_interface($idr,$int[$j],0);
	}

    $obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=INT&idr=".$idr."');");
    return $obj;
}




function add_interface($idr,$idi,$xaj=1) // idi -> id interfejsu
{
	global $DB,$UKE,$LINKTYPES,$LINKTECHNOLOGIES,$NSTATUS;
	if ($xaj == 1) $obj = new xajaxResponse();
	
	$useww = $UKE->getidwwuseraport($idr);
	$idww = implode(',',$useww);
	
	$usewo = $UKE->getidwouseraport($idr);
	$idwo = implode(',',$usewo);
	
	$netlink = $DB->GetAll('SELECT l.type, l.speed, l.technology, l.tracttype, n.networknodeid 
		FROM netlinks l
		JOIN netdevices n ON (n.id = l.src OR n.id = l.dst) 
		WHERE (l.src = ? OR l.dst = ?) 
		AND (n.networknodeid IN ('.$idww.') '.($idwo ? ' OR n.networknodeid IN ('.$idwo.')' : '').') 
		GROUP BY n.networknodeid;',array($idi,$idi));
	
	$nodelink = $DB->GetAll('SELECT n.linktype AS type, n.linkspeed AS speed, n.linktechnology AS technology, d.networknodeid 
		FROM nodes n 
		JOIN netdevices d ON (d.id = n.netdev)
		WHERE n.ownerid > 0 AND n.netdev = ? 
		AND (d.networknodeid IN ('.$idww.') '.($idwo ? ' OR d.networknodeid IN ('.$idwo.')' : '').')
		;',array($idi));
	
	$int = $DB->GetRow('SELECT i.id, i.name , i.networknodeid, i.invprojectid, i.status, i.ports, 
		w.id AS idw, w.name AS networknodename, w.type, w.invprojectid AS networknodeprojectid, w.status AS networknodestatus 
		FROM netdevices i 
		LEFT JOIN networknode w ON (w.id = i.networknodeid)
		WHERE i.id = ? ;',array($idi));
	
	if ($int['invprojectid'] > '1') {
	    $project = $DB->GetOne('SELECT number FROM invprojects WHERE id = ? LIMIT 1;',array($int['invprojectid']));
	    $int['projectnumber'] = $project;
	    $int['status'] = $NSTATUS[$int['status']];
	} elseif ($int['invprojectid'] == '1') {
	    $project = $DB->GetOne('SELECT number FROM invprojects WHERE id = ? LIMIT 1;',array($int['networknodeprojectid']));
	    $int['projectnumber'] = $project;
	    $int['status'] = $NSTATUS[$int['networknodestatus']];
	} else {
	    $int['projectnumber'] = '';
	    $int['status'] = '';
	}
	
	$result = array();
	$dy = $do = array();
	$netlink_count = sizeof($netlink);
	$nodelink_count = sizeof($nodelink);
	
	// warstwa dystrybucyjna
	for ($i=0; $i<$netlink_count; $i++) {
	    
	    if (!$netlink[$i]['technology']) {
		    
		    if ($netlink[$i]['type'] == LINKTYPES_FIBER) $netlink[$i]['technology'] = 205;
		    elseif ($netlink[$i]['type'] == LINKTYPES_RADIO) {
				if (!$netlink[$i]['speed']) $netlink[$i]['speed'] = 100000;
				if ($netlink[$i]['speed'] <= 30000) $netlink[$i]['technology'] = 100; 
				else $netlink[$i]['technology'] = 101;
		    }
		    elseif ($netlink[$i]['type'] == LINKTYPES_CABLE) $netlink[$i]['technology'] = 7;
		    else $netlink[$i]['technology'] = 53;
	    }
	    $dy[$netlink[$i]['type']][$netlink[$i]['technology']] = array('count'=>0,'speed'=>0);
	}
	
	for ($i=0; $i<$netlink_count; $i++) {
	    $dy[$netlink[$i]['type']][$netlink[$i]['technology']]['count']++;
	    if ($netlink[$i]['speed'] > $dy[$netlink[$i]['type']][$netlink[$i]['technology']]['speed'])
		$dy[$netlink[$i]['type']][$netlink[$i]['technology']]['speed'] = $netlink[$i]['speed'];
	}
	
	// warstwa dostępowa
	for ($i=0; $i<$nodelink_count; $i++) {
	    
	    if (!$nodelink[$i]['technology']) {
		    
		    if ($nodelink[$i]['type'] == LINKTYPES_FIBER) $nodelink[$i]['technology'] = 205;
		    elseif ($nodelink[$i]['type'] == LINKTYPES_RADIO) {
				if (!$nodelink[$i]['speed']) $nodelink[$i]['speed'] = 100000;
				if ($nodelink[$i]['speed'] <= 30000) $nodelink[$i]['technology'] = 100; 
				else $nodelink[$i]['technology'] = 101;
		    }
		    elseif ($nodelink[$i]['type'] == LINKTYPES_CABLE) $nodelink[$i]['technology'] = 7;
		    else $nodelink[$i]['technology'] = 53;
	    }
	    $do[$nodelink[$i]['type']][$nodelink[$i]['technology']] = array('count'=>0,'speed'=>0);
	    
	}
	
	for ($i=0; $i<$nodelink_count; $i++) {
	    $do[$nodelink[$i]['type']][$nodelink[$i]['technology']]['count']++;
	    if ($nodelink[$i]['speed'] > $do[$nodelink[$i]['type']][$nodelink[$i]['technology']]['speed'])
		$do[$nodelink[$i]['type']][$nodelink[$i]['technology']]['speed'] = $nodelink[$i]['speed'];
	}
	
	
	foreach ($dy as $item => $sub)
	    foreach ($sub as $item2 => $key) {
	    $pasmo = '';
	    if ($item == LINKTYPES_RADIO) {
		    if ($item2 == 101) $pasmo = '5.5'; else $pasmo = '2.2';
	    }
	    
	    $result[] = array(
		'id'			=> $int['id'],
		'netnodename'		=> $int['name'].'_DY_'.$item.'_'.$item2,
		'networknodename'	=> $int['networknodename'],
		'networknodeid'		=> $int['idw'],
		'backbone_layer' 	=> 'Nie',
		'distribution_layer' 	=> 'Tak',
		'access_layer' 		=> 'Nie',
		'medium'		=> $LINKTYPES[$item],
		'pasmo_radiowe'		=> $pasmo,
		'technologia'		=> $LINKTECHNOLOGIES[$item][$item2],
		'max_to_net'		=> floor($key['speed']/1000),
		'max_to_user'		=> floor($key['speed']/1000),
		'ports'			=> $key['count'],
		'use_ports' 		=> $key['count'],
		'empty_ports'		=> 0,
		'sharing' 		=> 'Nie',
		'projectnumber'		=> $int['projectnumber'],
		'status'		=> $int['status'],
	    );
	}
	
	
	foreach ($do as $item => $sub)
	    foreach ($sub as $item2 => $key) {
	    $pasmo = '';
	    if ($item == LINKTYPES_RADIO) {
		    if ($item2 == 101) $pasmo = '5.5'; else $pasmo = '2.2';
	    }
	    
	    $result[] = array(
		'id'			=> $int['id'],
		'netnodename'		=> $int['name'].'_DO_'.$item.'_'.$item2,
		'networknodename'	=> $int['networknodename'],
		'networknodeid'		=> $int['idw'],
		'backbone_layer' 	=> 'Nie',
		'distribution_layer' 	=> 'Nie',
		'access_layer' 		=> 'Tak',
		'linktype'		=> $item,
		'linktechnology'	=> $item2,
		'medium'		=> $LINKTYPES[$item],
		'pasmo_radiowe'		=> $pasmo,
		'technologia'		=> $LINKTECHNOLOGIES[$item][$item2],
		'max_to_net'		=> floor($key['speed']/1000),
		'max_to_user'		=> floor($key['speed']/1000),
		'ports'			=> $key['count'],
		'use_ports' 		=> $key['count'],
		'empty_ports'		=> 0,
		'sharing' 		=> 'Nie',
		'projectnumber'		=> $int['projectnumber'],
		'status'		=> $int['status'],
	    );
	}
	
	for ($i=0; $i<sizeof($result); $i++) {
	    $markid = $result[$i]['id'];
	    $data = serialize($result[$i]);
	    $DB->Execute('INSERT INTO uke_data (rapid, mark, markid, useraport, data) VALUES (?,?,?,?,?);',array(
		$idr,'INT',$markid,1,$data
	    ));
	}
	if ($xaj == 1 ) return $obj;
}


function import_interfaces($idr,$redirect=true)
{
    global $DB,$UKE;
    $obj = new xajaxResponse();
    $DB->Execute('DELETE FROM uke_data WHERE rapid = ? AND mark = ?;',array($idr,'INT'));
    $ww = $UKE->getidwwuseraport($idr);
//    $obj->script("alert('".$ww[2]."');");
    for ($i=0; $i<sizeof($ww); $i++) {
	$int = $DB->getAll('SELECT id FROM netdevices WHERE networknodeid = ? ;',array($ww[$i]));
	for ($j=0; $j<sizeof($int); $j++) {
	    $obj->script("xajax_add_interface('".$idr."','".$int[$j]['id']."');");
	}
    }
    
    if ($redirect)
	    $obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=INT&idr=".$idr."');");
    
    return $obj;
}



function set_int_useraport($idr,$id,$set)
{
	global $DB;
	$obj = new xajaxResponse();
	
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',array(($set ? 1 : 0),$id));
	
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=INT&idr=".$idr."');");
	
	return $obj;
}


function import_LK($idr)
{
	global $DB,$UKE,$TNODE,$LINKTYPES,$LINKTECHNOLOGIES,$TRACTTYPE;
	$obj = new xajaxResponse();
	
	$DB->Execute('DELETE FROM uke_data WHERE rapid=? AND mark = ? ;',array($idr,'LK'));
	
	$useww = $UKE->getidwwuseraport($idr);
	$idww = implode(',',$useww);
	
	$usewo = $UKE->getidwouseraport($idr);
	$idwo = implode(',',$usewo);
	
	$netdevices = $DB->GetCol('SELECT markid AS id 
			FROM uke_data 
			WHERE rapid=? AND mark=? AND useraport=? 
			GROUP BY markid 
			ORDER BY markid ASC;',array($idr,'INT',1));
	
	$netdev = implode(',',$netdevices);
	
	$nl = $DB->GetAll('SELECT l.src, l.dst, l.type, l.speed, l.technology, l.tracttype,
			wa.name AS networknode_name_a, wa.type AS networknode_type_a, wa.latitude AS latitude_a, wa.longitude AS longitude_a, 
			wb.name AS networknode_name_b, wb.type AS networknode_type_b,  wb.latitude AS latitude_b, wb.longitude AS longitude_b 
			FROM netlinks l 
			JOIN netdevices nda ON (nda.id = l.src) 
			JOIN netdevices ndb ON (ndb.id = l.dst)
			JOIN (
			    SELECT nna.id, nna.name, nna.type, nna.latitude, nna.longitude 
			    FROM networknode nna 
			) wa ON (wa.id = nda.networknodeid)
			JOIN (
			    SELECT nnb.id, nnb.name, nnb.type, nnb.latitude, nnb.longitude 
			    FROM networknode nnb 
			) wb ON (wb.id = ndb.networknodeid)
			    
			WHERE (l.type = ? OR l.type=? OR l.type=?) 
			AND (l.src IN ('.$netdev.')) AND (l.dst IN ('.$netdev.')) 
			AND (wa.id IN ('.$idww.') '.($idwo ? ' OR wa.id IN ('.$idwo.')' : '').') 
			AND (wb.id IN ('.$idww.') '.($idwo ? ' OR wb.id IN ('.$idwo.')' : '').') 
			AND wa.id != wb.id 
			;',array(LINKTYPES_FIBER,LINKTYPES_CABLE,LINKTYPES_CABLE_COAXIAL));
	
	$lp = array();
	for ($i=0; $i<sizeof($nl); $i++) {
	    
	    if ($nl[$i]['latitude_a'] && $nl[$i]['longitude_a'] && $nl[$i]['latitude_b'] && $nl[$i]['longitude_b']) {
		$distance = calculate_distance_gps($nl[$i]['latitude_a'],$nl[$i]['longitude_a'],$nl[$i]['latitude_b'],$nl[$i]['longitude_b']);
	    } else $distance = '0.1';
	    
	    $lp[] = array(
		'identyfikator'		=> $nl[$i]['src'].'_'.$nl[$i]['dst'],			// D
		'wlasnosc'		=> 'Własna',						// E
		'obcy'			=> '',							// F
		'rodzaja'		=> $TNODE[$nl[$i]['networknode_type_a']],		// G
		'identyfikatora'	=> str_replace(' ','_',$nl[$i]['networknode_name_a']),	// H
		'rodzajb'		=> $TNODE[$nl[$i]['networknode_type_b']],		// I
		'identyfikatorb'	=> str_replace(' ','_',$nl[$i]['networknode_name_b']),	// J
		'medium'		=> $LINKTYPES[$nl[$i]['type']],				// K
		'typwlokna'		=> ($nl[$i]['type'] == LINKTYPES_FIBER ? 'G.652' : ''),	// L
		'liczbawlokien'		=> ($nl[$i]['type'] == LINKTYPES_FIBER ? '2' : ''),	// M
		'wlokienused'		=> ($nl[$i]['type'] == LINKTYPES_FIBER ? '2' : ''),	// N
		'eu'			=> 'Nie',						// O
		'dostepnapasywna'	=> 'Brak danych', 					// P
		'rodzajpasywne'		=> '',							// Q
		'sharingfiber'		=> ($nl[$i]['type'] == LINKTYPES_FIBER ? 'Nie' : ''),	// R
		'sharingmaxwlokna'	=> '',							// S
		'sharingprzepustowosc'	=> 'Nie',						// T
		'rodzajtraktu'		=> ($nl[$i]['tracttype'] ? $TRACTTYPE[$nl[$i]['tracttype']] : 'podziemny w kanalizacji'), // U
		'dlugosckabla'		=> $distance,						// V
		'latitudea'		=> $nl[$i]['latitude_a'],
		'longitudea'		=> $nl[$i]['longitude_a'],
		'latitudeb'		=> $nl[$i]['latitude_b'],
		'longitudeb'		=> $nl[$i]['longitude_b'],
		'tracttype'		=> $nl[$i]['tracttype'],
		
	    );
	}
	
	if ($lp) {
	    for ($i=0; $i<sizeof($lp); $i++) 
		$DB->Execute('INSERT INTO uke_data (rapid, mark, markid,useraport,data) VALUES (?,?,?,?,?);',
		    array($idr,'LK',$lp[$i]['identyfikator'],1,serialize($lp[$i])));
	}
	
	
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=LK&idr=".$idr."');");
	return $obj;
}

function import_LB($idr)
{
	global $DB,$UKE;
	$obj = new xajaxResponse();
	
	$DB->Execute('DELETE FROM uke_data WHERE rapid=? AND mark = ? ;',array($idr,'LB'));
	
	$useww = $UKE->getidwwuseraport($idr);
	$idww = implode(',',$useww);
	
	$usewo = $UKE->getidwouseraport($idr);
	$idwo = implode(',',$usewo);
	
	$netdevices = $DB->GetCol('SELECT markid AS id 
			FROM uke_data 
			WHERE rapid=? AND mark=? AND useraport=? 
			GROUP BY markid 
			ORDER BY markid ASC;',array($idr,'INT',1));
	
	$netdev = implode(',',$netdevices);
	
	$nl = $DB->GetAll('SELECT l.src, l.dst, l.type, l.speed, l.technology,
			wa.name AS networknode_name_a, wa.latitude AS latitude_a, wa.longitude AS longitude_a, 
			wb.name AS networknode_name_b, wb.latitude AS latitude_b, wb.longitude AS longitude_b 
			FROM netlinks l 
			JOIN netdevices nda ON (nda.id = l.src) 
			JOIN netdevices ndb ON (ndb.id = l.dst) 
			JOIN (
			    SELECT nna.id, nna.name, nna.latitude, nna.longitude 
			    FROM networknode nna 
			) wa ON (wa.id = nda.networknodeid)
			JOIN (
			    SELECT nnb.id, nnb.name, nnb.latitude, nnb.longitude 
			    FROM networknode nnb 
			) wb ON (wb.id = ndb.networknodeid)
			    
			WHERE l.type = ? 
			AND (l.src IN ('.$netdev.')) AND (l.dst IN ('.$netdev.')) 
			AND (wa.id IN ('.$idww.') '.($idwo ? 'OR wa.id IN ('.$idwo.')' : '').') 
			AND (wb.id IN ('.$idww.') '.($idwo ? 'OR wb.id IN ('.$idwo.')' : '').') 
			AND wa.id != wb.id 
			;',array(LINKTYPES_RADIO));
	
	
	$lb = array();
	
	for ($i=0; $i<sizeof($nl); $i++) {
	    
	    $distance = '0.1';
	    $pasmo = '5.5';
	    
	    if ($nl[$i]['latitude_a'] && $nl[$i]['longitude_a'] && $nl[$i]['latitude_b'] && $nl[$i]['longitude_b']) {
		$distance = calculate_distance_gps($nl[$i]['latitude_a'],$nl[$i]['longitude_a'],$nl[$i]['latitude_b'],$nl[$i]['longitude_b']);
	    }
	    $lb[] = array(
		'identyfikator'		=> $nl[$i]['src'].'_'.$nl[$i]['dst'],				// D
		'identyfikatora'	=> str_replace(' ','_',$nl[$i]['networknode_name_a']),		// E
		'identyfikatorb'	=> str_replace(' ','_',$nl[$i]['networknode_name_b']),		// F
		'medium'		=> 'radiowe na częstotliwości ogólnodostępnej',			// G
		'pozwolenie'		=> '',								// H
		'pasmo'			=> $pasmo,							// I
		'system'		=> 'WiFi',							// J
		'przepustowosc'		=> floor($nl[$i]['speed']/1000),					// K
		'sharing'		=> 'Nie',
		'dlugoscpolaczenia'	=> $distance,
		'latitudea'		=> $nl[$i]['latitude_a'],
		'longitudea'		=> $nl[$i]['longitude_a'],
		'latitudeb'		=> $nl[$i]['latitude_b'],
		'longitudeb'		=> $nl[$i]['longitude_b'],
		
	    );
	}
	
	if ($lb) {
	    for ($i=0; $i<sizeof($lb); $i++) 
		$DB->Execute('INSERT INTO uke_data (rapid, mark, markid,useraport,data) VALUES (?,?,?,?,?);',
		    array($idr,'LB',$lb[$i]['identyfikator'],1,serialize($lb[$i])));
	}

	
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=LB&idr=".$idr."');");
	return $obj;
}


function import_POL($idr)
{
	global $DB,$UKE,$LAYERTYPE;
	$obj = new xajaxResponse();
	$DB->Execute('DELETE FROM uke_data WHERE rapid=? AND mark = ? ;',array($idr,'POL'));
	
	$_tmp = $DB->GetCol('SELECT markid FROM uke_data WHERE rapid = ? AND (mark=? OR mark=?);',array($idr,'LB','LK'));
	
	$result = $pol = $_pol = array();
	
	for ($i=0; $i<sizeof($_tmp); $i++) {
	    $tmp = explode('_',$_tmp[$i]);
	    $_pol[] = array('src'=>$tmp[0],'dst'=>$tmp[1]);
	}
	
	for ($i=0; $i<sizeof($_pol); $i++)
	    $pol[] = $DB->GetRow('SELECT l.src, l.dst, l.speed, l.layer,
			wa.name AS networknode_name_a, wb.name AS networknode_name_b 
			FROM netlinks l 
			JOIN netdevices nda ON (nda.id = l.src) 
			JOIN netdevices ndb ON (ndb.id = l.dst)
			JOIN (
			    SELECT nna.id, nna.name 
			    FROM networknode nna 
			) wa ON (wa.id = nda.networknodeid)
			JOIN (
			    SELECT nnb.id, nnb.name 
			    FROM networknode nnb 
			) wb ON (wb.id = ndb.networknodeid)
			    
			WHERE l.src = ? AND l.dst = ?
			;',array($_pol[$i]['src'],$_pol[$i]['dst']));
	
	for ($i=0; $i<sizeof($pol); $i++) {
		
		if (empty($pol[$i]['layer']))
		    $pol[$i]['layer'] = LAYER_DISTRIBUTION;
		
		$pol[$i]['speed'] = floor($pol[$i]['speed']/1000);
		
		$result[] = array(
		    'identyfikator'		=> $pol[$i]['src'].'_'.$pol[$i]['dst'],		// D
		    'wlasnosc'			=> 'Własna',					// E
		    'obcy'			=> '',						// F
		    'identyfikatora'		=> str_replace(' ','_',$pol[$i]['networknode_name_a']), // G
		    'identyfikatorb'		=> str_replace(' ','_',$pol[$i]['networknode_name_b']), // H
		    'backbone_layer'		=> ($pol[$i]['layer'] == LAYER_BACKBONE ? 'Tak' : 'Nie'), // I
		    'distribution_layer'	=> ($pol[$i]['layer'] == LAYER_DISTRIBUTION ? 'Tak' : 'Nie'), // J
		    'access_layer'		=> ($pol[$i]['layer'] == LAYER_ACCESS ? 'Tak' : 'Nie'), // K
		    'szerokopasmowe'		=> 'Tak',					// L
		    'glosowe'			=> 'Nie',
		    'inne'			=> 'Nie',
		    'speed'			=> $pol[$i]['speed'],
		    'speednet'			=> $pol[$i]['speed'],
		    
		);
	}
	
	for ($i=0; $i<sizeof($result); $i++) {
	    $DB->Execute('INSERT INTO uke_data (rapid, mark, markid,useraport,data) VALUES (?,?,?,?,?);',
		    array($idr,'POL',$result[$i]['identyfikator'],1,serialize($result[$i])));
	}
	
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=POL&idr=".$idr."');");
	
	return $obj;
}

function set_lk_useraport($idr,$id,$set)
{
	global $DB;
	$obj = new xajaxResponse();
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',array(($set ? 1 : 0),$id));
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=LK&idr=".$idr."');");
	return $obj;
}


function set_lb_useraport($idr,$id,$set)
{
	global $DB;
	$obj = new xajaxResponse();
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',array(($set ? 1 : 0),$id));
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=LB&idr=".$idr."');");
	return $obj;
}


function set_pol_useraport($idr,$id,$set)
{
	global $DB;
	$obj = new xajaxResponse();
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',array(($set ? 1 : 0),$id));
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=POL&idr=".$idr."');");
	return $obj;
}



function import_ZAS($idr)
{
	global $DB,$LINKTYPES, $LMS, $UKE, $LINKTECHNOLOGIES;
	$obj = new xajaxResponse();
	
	$getww = $UKE->getidwwuseraport($idr);
	$getwo = $UKE->getidwouseraport($idr);
	$getint = $UKE->getIdINTUseRaport($idr);
	
	$idww = implode(',',$getww);
	$idwo = implode(',',$getwo);
	$idint = implode(',',$getint);
	
	$_tarifftype = array(TARIFF_INTERNET,TARIFF_PHONE,TARIFF_TV,TARIFF_PHONE_ISDN,TARIFF_PHONE_MOBILE,TARIFF_INTERNET_MOBILE);
	$tarifftype = implode(',',$_tarifftype);
	
	
	$DB->Execute('DELETE FROM uke_data WHERE rapid = ? AND mark=? ;',array($idr,'ZAS'));
	
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
		LEFT JOIN networknode nn ON (nn.id = nd.networknodeid) '
		.'LEFT JOIN (SELECT netlinks.src AS src, COUNT(netlinks.id) AS nlsrccount FROM netlinks WHERE type = 0 GROUP BY netlinks.src) nlsrccable ON nd.id = nlsrccable.src 
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
		WHERE nd.networknodeid > 0 '
		.' AND (nd.id IN ('.$idint.')) '
		.' AND (nn.id IN ('.$idww.') '.($idwo ? 'OR nn.id IN ('.$idwo.')' : '').') '
		.'AND EXISTS (SELECT id FROM netlinks nl WHERE nl.src = nd.id OR nl.dst = nd.id) 
		ORDER BY nd.name');
	
	
	if ($nd) 
	{
	    $count = sizeof($nd);
	    
	    for ($i=0;$i<$count;$i++) {
		$nd[$i]['personalaccessports'] = $nd[$i]['cablepersonalaccessports'] + $nd[$i]['radiopersonalaccessports'] + $nd[$i]['fiberpersonalaccessports'];
		$nd[$i]['commercialaccessports'] = $nd[$i]['cablecommercialaccessports'] + $nd[$i]['radiocommercialaccessports'] + $nd[$i]['fibercommercialaccessports'];
		$nodelist[] = $nd[$i]['id'];
	    }
	
	    $report_date = $DB->GetOne('SELECT reportyear FROM uke WHERE id=? LIMIT 1;',array($idr));
	    $reportdate = strtotime($report_date.'/12/31 23:59:59');
	    
	    
	    $ranges = $DB->GetAll("SELECT n.id, n.linktype, n.linkspeed, n.linktechnology, n.location_street, n.location_city, n.location_house , n.longitude, n.latitude, 
		(SELECT zip FROM pna WHERE pna.cityid = n.location_city AND (pna.streetid IS NULL OR (pna.streetid IS NOT NULL AND pna.streetid = n.location_street)) LIMIT 1) AS location_zip " 
		.", t.id AS tariffid "
		.", CASE t.type
			WHEN ".TARIFF_INTERNET." THEN 'INT' 
			WHEN ".TARIFF_PHONE." THEN 'TEL' 
			WHEN ".TARIFF_TV." THEN 'TV' 
			WHEN ".TARIFF_PHONE_ISDN." THEN 'TELISDN' 
			WHEN ".TARIFF_PHONE_MOBILE." THEN 'TELMOBILE' 
			WHEN ".TARIFF_INTERNET_MOBILE." THEN 'INTMOBILE' 
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
		AND (t.type IN (".$tarifftype.")) 
		AND a.suspended = 0 AND a.period IN (".implode(',', array(YEARLY, HALFYEARLY, QUARTERLY, MONTHLY)).") 
		AND (a.datefrom = 0 OR a.datefrom < ?NOW?) AND (a.dateto = 0 OR a.dateto > ?NOW?) 
		ORDER BY n.id ;",array($reportdate));
		
	
	    $rang = array();
//	if ($rang) $obj->script("alert('dupa');");
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
	    $_rang = array();
	    for ($i=0;$i<$count;$i++) {
		
		
//		if (!$rang[$i]['linktype']) $rang[$i]['linktype'] = LINKTYPES_RADIO;
		
		if (!$rang[$i]['linkspeed']) {
		    if ($rang[$i]['linktype'] == LINKTYPES_FIBER) $rang[$i]['linkspeed'] = 1000000;
		    else $rang[$i]['linkspeed'] = 100000;
		}
		
		if ( ($rang[$i]['linktype'] == LINKTYPES_CABLE) && (!$rang[$i]['linktechnology'] || (!in_array($rang[$i]['linktechnology'],array(1,2,3,4,5,6,7,8,9,10,11,12))))) 
		{
		    $rang[$i]['linktechnology'] = 7;
		} 
		
		elseif ( ($rang[$i]['linktype'] == LINKTYPES_FIBER) && (!$rang[$i]['linktechnology'] || (!in_array($rang[$i]['linktechnology'],array(208,209,203,204,205,206,210,207,200,201,202,211,212,213,214,215))))) 
		{
		    $rang[$i]['linktechnology'] = 205;
		}
		elseif ( ($rang[$i]['linktype'] == LINKTYPES_CABLE_COAXIAL) && (!$rang[$i]['linktechnology'] || (!in_array($rang[$i]['linktechnology'],array(50,51,52,53))))) 
		{
		    $rang[$i]['linktechnology'] = 53;
		    $rang[$i]['linkspeed'] = 10000;
		}
		else 
		    $rang[$i]['linktype'] = LINKTYPES_RADIO;
		    
		if (($rang[$i]['linktype'] == LINKTYPES_RADIO) && (!$rang[$i]['linktechnology'] || (!in_array($rang[$i]['linktechnology'],array(100,101,113,104,102,103,105,106,107,108,109,110,111,112,114,115))))) 
		{
		    if ($rang[$i]['linkspeed'] <= 30000) $rang[$i]['linktechnology'] = 100; // WiFi - 2,4 GHz
		    else $rang[$i]['linktechnology'] = 101; // WiFi - 5 GHz
		}
		
		
		$link_type = $LINKTYPES[$rang[$i]['linktype']];
		$link_tech = $LINKTECHNOLOGIES[$rang[$i]['linktype']][$rang[$i]['linktechnology']];
		
		$dane = array(
		    // zakładka ZAS
		    'identyfikator'		=> $rang[$i]['id'],						// D
		    'wlasnosc'			=> 'Własna',							// E
		    'formaobca'			=> '',								// F
		    'identyfikatorobcy'		=> '',								// G
		    'networknode'		=> $rang[$i]['networknode'],					// H
		    'states'			=> $rang[$i]['states'],						// I
		    'districts'			=> $rang[$i]['districts'],					// J
		    'boroughs'			=> $rang[$i]['boroughs'],					// K
		    'kod_terc'			=> $rang[$i]['kod_terc'],					// L
		    'city'			=> $rang[$i]['city'],						// M
		    'kod_simc'			=> $rang[$i]['kod_simc'],					// N
		    'street'			=> $rang[$i]['street'],						// O
		    'kod_ulic'			=> $rang[$i]['kod_ulic'],					// P
		    'location_house'		=> ($rang[$i]['location_house'] ? $rang[$i]['location_house'] : 'Brak numeru'),		// Q
		    'zip'			=> $rang[$i]['location_zip'],					// R
		    'latitude'			=> $rang[$i]['latitude'],					// S
		    'longitude'			=> $rang[$i]['longitude'],					// T
		    'medium'			=> $link_type,							// U
		    'dostepowa'			=> $link_tech,							// V
		    'isdn'			=> ($rang[$i]['servicetypes'] == 'TELISDN' ? 'Tak' : 'Nie'),	// W
		    'voip'			=> ($rang[$i]['servicetypes'] == 'TEL' ? 'Tak' : 'Nie'),	// X
		    'telmobile'			=> ($rang[$i]['servicetypes'] == 'TELMOBILE' ? 'Tak' : 'Nie'),	// Y
		    'int'			=> ($rang[$i]['servicetypes'] == 'INT' ? 'Tak' : 'Nie'),	// Z
		    'intmobile'			=> ($rang[$i]['servicetypes'] == 'INTMOBILE' ? 'Tak' : 'Nie'),	// AA
		    'iptv'			=> ($rang[$i]['servicetypes'] == 'TV' ? 'Tak' : 'Nie'),		// AB
		    'otherservice'		=> '',//($rang[$i]['servicetypes'] == 'OTHER' ? 'Tak' : 'Nie'),	// AC
		    'downstream'		=> floor($rang[$i]['linkspeed'] / 1000),			// AD
		    'downstreammobile'		=> 0,								// AF
		    //dla zakładki US
		    'custype'			=> $rang[$i]['custype'],
		    
		);
		
		if (!in_array($rang[$i]['id'],$_rang)) {
		    $_rang[] = $rang[$i]['id'];
		    $DB->Execute('INSERT INTO uke_data (rapid, mark, markid,useraport,data) VALUES (?,?,?,?,?);',
			array($idr,'ZAS',$dane['identyfikator'],1,serialize($dane)));
		}
	    }
	}
	
	
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=ZAS&idr=".$idr."');");
	
	
	return $obj;
    }
    
    function set_zas_useraport($idr,$id,$set)
    {
	global $DB;
	$obj = new xajaxResponse();
	
	$DB->Execute('UPDATE uke_data SET useraport = ? WHERE id = ? ;',array(($set ? 1 : 0),$id,));
	
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=ZAS&idr=".$idr."');");
	
	return $obj;
    }
    
    
    function add_project($idr,$idp)
    {
	global $DB,$UKE, $PROJECTPROGRAM, $PROJECTACTION;
	$obj = new xajaxResponse();
	
	if ($idr && $proj = $DB->GetRow('SELECT * FROM invprojects WHERE id=? LIMIT 1;',array($idp)))
	{
	
	$dane = array(
	    'identyfikator' => 'PROJ'.sprintf('%02.d',$proj['id']),
	    'nazwa' => $proj['name'],
	    'nrprojektu' => $proj['number'],
	    'nrumowy' => $proj['contract'],
	    'tytul' => $proj['title'],
	    'program' => $PROJECTPROGRAM[$proj['program']],
	    'dzialanie' => $PROJECTACTION[$proj['program']][$proj['action']],
	    'firma' => $proj['division'],
	    'datapodpisania' => date('Y-m-d',$proj['contractdate']),
	    'datazakonczenia' => date('Y-m-d',$proj['todate']),
	    'wojewodztwo' => $proj['states'],
	    'zakres' => $proj['scope']
	);
	
	$DB->Execute('INSERT INTO uke_data (rapid, mark, markid,useraport,data) VALUES (?,?,?,?,?);',
		    array($idr,'PROJ',$dane['identyfikator'],1,serialize($dane)));
	
	}
	
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=PROJ&idr=".$idr."');");
	
	return $obj;
    }
    
    function del_project($idr,$idp)
    {
	global $DB;
	$obj = new xajaxResponse();
	
	$DB->Execute('DELETE FROM uke_data WHERE id = ? ;',array($idp));
	$obj->script("loadAjax('id_data','?m=uke_siis_info&tuck=PROJ&idr=".$idr."');");
	
	return $obj;
    }

$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array(
    'import_division',
    'add_siis',
    'add_PO',
    'set_po_useraport',
    'view_select_po_to_ww',
    'add_po_to_ww',
    'set_ww_useraport',
    'add_all_ww', 		// dodanie wszystkich własnych węzłów
    'view_select_po_to_wo',
    'add_po_to_wo',
    'set_wo_useraport',
    'add_interface_ww',
    'add_interface',
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
    'add_project',
    'del_project',
    'show_procent',
    'refresh_ww',
    'refresh_wo',
    'refresh_int',
));

$SMARTY->assign('xajax', $LMS->RunXajax());

?>