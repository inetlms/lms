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
 */

$networknodeid = (isset($_GET['id']) ? $_GET['id'] : (isset($_POST['networknodeid']) ? $_POST['networknodeid'] : NULL));

if ($networknodeid) 
	$networknodeinfo = $LMS->Getnetworknode($networknodeid);
else
{
	$networknodeinfo = array(
	
	    'type'			=> NODE_OWN,
	    'backbone_layer'		=> NIE,
	    'distribution_layer'	=> TAK,
	    'access_layer'		=> TAK,
	
	);
}

$SMARTY->assign('cstateslist', $LMS->GetCountryStates());
$SMARTY->assign('countrieslist', $LMS->GetCountries());
$SMARTY->assign('networknodeinfo',$networknodeinfo);
$SMARTY->assign('networknodeid',$networknodeid);

function basisdata_from_collocation($idc)
{
    global $LMS,$networknodeid,$DB;
    $layoot['popup'] = true;
    $obj = new xajaxResponse();

    if ($colinf = $LMS->GetCollocation($idc)) {
	
	if ($colinf['teryt']) {
		$obj->script("document.getElementById('teryt').checked = true;");
		$obj->script("check_use_teryt();");
		$obj->assign("location","value",$colinf['location']);
		$obj->script("document.getElementById('location').readOnly = true;");
	} else {
		$obj->script("document.getElementById('teryt').checked = false;");
		$obj->script("check_use_teryt();");
		$obj->script("document.getElementById('id_states').readOnly = true;");
		$obj->script("document.getElementById('id_districts').readOnly = true;");
		$obj->script("document.getElementById('id_boroughs').readOnly = true;");
		$obj->script("document.getElementById('id_city').readOnly = true;");
		$obj->script("document.getElementById('id_street').readOnly = true;");
	}
	
	$obj->script("document.getElementById('id_location_house').readOnly = true;");
	$obj->script("document.getElementById('id_location_flat').readOnly = true;");
	$obj->script("document.getElementById('id_view_used_teryt').style.display = 'none';");
	$obj->assign("id_location_city","value",$colinf['location_city']);
	$obj->assign("id_location_street","value",$colinf['location_street']);
	$obj->assign("id_location_house","value",$colinf['location_house']);
	$obj->assign("id_location_flat","value",$colinf['location_flat']);
	$obj->assign("id_states","value",$colinf['states']);
	$obj->assign("id_districts","value",$colinf['districts']);
	$obj->assign("id_boroughs","value",$colinf['boroughs']);
	$obj->assign("id_city","value",$colinf['city']);
	$obj->assign("id_street","value",$colinf['street']);
	$obj->assign("id_zip","value",$colinf['zip']);
	$obj->assign("id_cadastral_parcel","value",$colinf['cadastral_parcel']);
	$obj->assign("id_latitude","value",$colinf['latitude']);
	$obj->assign("id_longitude","value",$colinf['longitude']);
	$obj->script("document.getElementById('id_zip').readOnly = true;");
	$obj->script("document.getElementById('id_cadastral_parcel').readOnly = true;");
	$obj->script("document.getElementById('id_latitude').readOnly = true;");
	$obj->script("document.getElementById('id_longitude').readOnly = true;");
	$obj->script("document.getElementById('id_latitude_map').style.display = 'none';");
	$obj->script("document.getElementById('id_longitude_map').style.display = 'none';");
	
    } else {
	$loc = $DB->GetRow('SELECT states, districts, boroughs, city, street, zip, location_city, location_street, 
			    location_house, location_flat, cadastral_parcel, longitude, latitude 
			    FROM networknode WHERE id = ? LIMIT 1;',array($networknodeid));
	
	$location = $loc['city'];
	
	if (!empty($loc['location_street']) && $loc['location_street'] != '' && $loc['location_street'] != '99999' && $loc['location_street'] != '99998')
	    $location .= ', '.$loc['street'].' '.$loc['location_house'];
	
	if (!empty($loc['location_flat']) && $loc['location_flat'] != '')
	    $location .= '/'.$loc['location_flat'];
	
	if (!empty($loc['location_city']))
	{
	    $obj->script("document.getElementById('id_view_used_teryt').style.display = '';");
	    $obj->script("document.getElementById('teryt').checked = true;");
	    $obj->assign("location","value",$location);
	    $obj->assign("id_location_city","value",$loc['location_city']);
	    $obj->assign("id_location_street","value",$loc['location_street']);
	    $obj->script("document.getElementById('location').readOnly = true;");
	}
	else
	{
	    $obj->script("document.getElementById('id_view_used_teryt').style.display = '';");
	    $obj->script("document.getElementById('teryt').checked = false;");
	    $obj->assign("location","value",'');
	    $obj->assign("id_location_city","value",NULL);
	    $obj->assign("id_location_street","value",NULL);
	    $obj->assign("id_states","value",$loc['states']);
	    $obj->assign("id_districts","value",$loc['districts']);
	    $obj->assign("id_city","value",$loc['city']);
	    $obj->assign("id_street","value",$loc['street']);
	}
	
	$obj->script("check_use_teryt();");
	
	
	$obj->assign("id_location_house","value",$loc['location_house']);
	$obj->assign("id_location_flat","value",$loc['location_flat']);
	$obj->assign("id_zip","value",$loc['zip']);
	$obj->assign("id_cadastral_parcel","value",$loc['cadastral_parcel']);
	$obj->assign("id_latitude","value",$loc['latitude']);
	$obj->assign("id_longitude","value",$loc['longitude']);
	
	
	$obj->script("document.getElementById('id_location_house').readOnly = false;");
	$obj->script("document.getElementById('id_location_flat').readOnly = false;");
	$obj->script("document.getElementById('id_states').readOnly = false;");
	$obj->script("document.getElementById('id_districts').readOnly = false;");
	$obj->script("document.getElementById('id_boroughs').readOnly = false;");
	$obj->script("document.getElementById('id_city').readOnly = false;");
	$obj->script("document.getElementById('id_street').readOnly = false;");
	$obj->script("document.getElementById('id_zip').readOnly = false;");
	$obj->script("document.getElementById('id_cadastral_parcel').readOnly = false;");
	$obj->script("document.getElementById('id_latitude').readOnly = false;");
	$obj->script("document.getElementById('id_longitude').readOnly = false;");
	$obj->script("document.getElementById('id_latitude_map').style.display = '';");
	$obj->script("document.getElementById('id_longitude_map').style.display = '';");
	
    }
	
    return $obj;
}

function validate_networknode($forms)
{
    global $DB;
    $blad = false;
    $warn = false;
    $obj = new xajaxResponse();
    
    $form = $forms['networknode'];
    $form['name'] = $forms['name'];
    $form['id'] = $forms['networknodeid'];
    
    $obj->script("removeClassId('id_name','alerts');");
    $obj->assign("id_name_alerts","innerHTML","");
    $obj->script("removeClassId('location','alerts');");
    $obj->assign("id_location_alerts","innerHTML","");
    $obj->script("removeClassId('id_location_house','alerts');");
    $obj->assign("id_location_house_alerts","innerHTML","");
    $obj->script("removeClassId('id_zip','alerts');");
    $obj->assign("id_zip_alerts","innerHTML","");
    $obj->script("removeClassId('id_states','alerts');");
    $obj->assign("id_states_alerts","innerHTML","");
    $obj->script("removeClassId('id_districts','alerts');");
    $obj->assign("id_districts_alerts","innerHTML","");
    $obj->script("removeClassId('id_boroughs','alerts');");
    $obj->assign("id_boroughs_alerts","innerHTML","");
    $obj->script("removeClassId('id_city','alerts');");
    $obj->assign("id_city_alerts","innerHTML","");
    $obj->script("removeClassId('id_street','alerts');");
    $obj->assign("id_street_alerts","innerHTML","");
    $obj->script("removeClassId('id_cadastral_parcel','alerts');");
    $obj->assign("id_cadastral_parcel_alerts","innerHTML","");
    $obj->script("removeClassId('id_latitude','alerts');");
    $obj->assign("id_latitude_alerts","innerHTML","");
    $obj->script("removeClassId('id_longitude','alerts');");
    $obj->assign("id_longitude_alerts","innerHTML","");
    $obj->script("removeClassId('id_buildingtype','alerts');");
    $obj->assign("id_buildingtype_alerts","innerHTML","");
    $obj->script("removeClassId('id_total_bandwidth','alerts');");
    $obj->assign("id_total_bandwidth_alerts","innerHTML","");
    $obj->script("removeClassId('id_bandwidth_broadband','alerts');");
    $obj->assign("id_bandwidth_broadband_alerts","innerHTML","");
    
    if (empty($form['name'])) {
	$blad = true;
	$obj->script("addClassId('id_name','alerts');");
	$obj->assign("id_name_alerts","innerHTML","Pole wymagane");
    } else {
	
	if (!empty($form['id'])) {
	    
		if ($DB->GetOne('SELECT 1 FROM networknode WHERE UPPER(name) = ? AND id != ? '.$DB->Limit(1).' ;',array(strtoupper($form['name']),$form['id']))) {
		    $blad = true;
		    $obj->script("addClassid('id_name','alerts');");
		    $obj->assign("id_name_alerts","innerHTML","Podana nazwa już istnieje");
		}
	
	} else {
		
		if ($DB->GetOne('SELECT 1 FROM networknode WHERE UPPER(name) = ? '.$DB->Limit(1).' ;',array(strtoupper($form['name'])))) {
		    $blad = true;
		    $obj->script("addClassid('id_name','alerts');");
		    $obj->assign("id_name_alerts","innerHTML","Podana nazwa już istnieje");
		}
	}
    }
    
    if ($forms['teryt']) {
	
	if (empty($form['location']) || empty($form['location_city'])) {
	    $blad = true;
	    $obj->script("addClassId('location','alerts');");
	    $obj->assign("id_location_alerts","innerHTML","Pole wymagane");
	}
	
	if (empty($form['location_house'])) {
	    $blad = true;
	    $obj->script("addClassId('id_location_house','alerts');");
	    $obj->assign("id_location_house_alerts","innerHTML","Pole wymagane");
	}
	
	if (empty($form['latitude'])) {
		$warn = true;
		$obj->script("document.getElementById('id_latitude_alerts').style.color='blue';");
		$obj->assign("id_latitude_alerts","innerHTML","Zalecane podanie szerokości");
	    }
	    
	    if (empty($form['longitude'])) {
		$warn = true;
		$obj->script("document.getElementById('id_longitude_alerts').style.color='blue';");
		$obj->assign("id_longitude_alerts","innerHTML","Zalecane podanie długości");
	    }
	
	
	
    } else {
	if (empty($form['states'])) {
	    $blad = true;
	    $obj->script("addClassId('id_states','alerts');");
	    $obj->assign("id_states_alerts","innerHTML","Pole wymagane");
	}
	
	if (empty($form['districts'])) {
	    $blad = true;
	    $obj->script("addClassId('id_districts','alerts');");
	    $obj->assign("id_districts_alerts","innerHTML","Pole wymagane");
	}
	
	if (empty($form['boroughs'])) {
	    $blad = true;
	    $obj->script("addClassId('id_boroughs','alerts');");
	    $obj->assign("id_boroughs_alerts","innerHTML","Pole wymagane");
	}
	
	if (empty($form['city'])) {
	    $blad = true;
	    $obj->script("addClassId('id_city','alerts');");
	    $obj->assign("id_city_alerts","innerHTML","Pole wymagane");
	}
	
	if (empty($form['street']) || empty($form['location_house'])) {
	    $warn = true;
	    $obj->assign("id_cadastral_parcel_alerts","innerHTML","Zalecane jest podanie nr. działki");
	    
	    if (empty($form['latitude'])) {
		$blad = true;
		$obj->script("addClassId('id_latitude','alerts');");
		$obj->script("document.getElementById('id_latitude_alerts').style.color='red';");
		$obj->assign("id_latitude_alerts","innerHTML","Pole wymagane");
	    }
	    
	    if (empty($form['longitude'])) {
		$blad = true;
		$obj->script("addClassId('id_longitude','alerts');");
		$obj->script("document.getElementById('id_longitude_alerts').style.color='red';");
		$obj->assign("id_longitude_alerts","innerHTML","Pole wymagane");
	    }
	} else {
	    if (empty($form['latitude'])) {
		$warn = true;
		$obj->script("document.getElementById('id_latitude_alerts').style.color='blue';");
		$obj->assign("id_latitude_alerts","innerHTML","Zalecane podanie szerokości");
	    }
	    
	    if (empty($form['longitude'])) {
		$warn = true;
		$obj->script("document.getElementById('id_longitude_alerts').style.color='blue';");
		$obj->assign("id_longitude_alerts","innerHTML","Zalecane podanie długości");
	    }
	}
    }
    
    if (empty($form['zip'])) {
	    $blad = true;
	    $obj->script("addClassId('id_zip','alerts');");
	    $obj->assign("id_zip_alerts","innerHTML","Pole wymagane");
    } elseif (!check_zip($form['zip'])) {
	    $blad = true;
	    $obj->script("addClassId('id_zip','alerts');");
	    $obj->assign("id_zip_alerts","innerHTML","Błędny kod pocztowy");
    }
    
    if (empty($form['buildingtype'])) {
	$blad = true;
	$obj->script("addClassId('id_buildingtype','alerts');");
	$obj->assign("id_buildingtype_alerts","innerHTML","Pole wymagane");
    }
    
    
    
    if (!empty($form['total_bandwidth']) && !is_numeric($form['total_bandwidth'])) {
	$blad = true;
	$obj->script("addClassId('id_total_bandwidth','alerts');");
	$obj->script("document.getElementById('id_total_bandwidth_alerts').style.color='red';");
	$obj->assign("id_total_bandwidth_alerts","innerHTML","Błędnie podana wartość");
    }
    
    if (empty($form['bandwidth_broadband'])) {
	$warn = true;
	$obj->script("document.getElementById('id_bandwidth_broadband_alerts').style.color='blue';");
	$obj->assign("id_bandwidth_broadband_alerts","innerHTML","Podaj przepustowość dla Internetu");
    } elseif (!is_numeric($form['bandwidth_broadband'])) {
	$blad = true;
	$obj->script("addClassId('id_bandwidth_broadband','alerts');");
	$obj->script("document.getElementById('id_bandwidth_broadband_alerts').style.color='red';");
	$obj->assign("id_bandwidth_broadband_alerts","innerHTML","Błędnie podana wartość");
    } elseif (!empty($form['bandwidth_broadband']) && empty($form['total_bandwidth'])) {
	$obj->script("document.getElementById('id_total_bandwidth').value = document.getElementById('id_bandwidth_broadband').value;");
    } 
    
    if (empty($form['total_bandwidth']) && empty($form['bandwidth_broadband'])) {
	$warn = true;
	$obj->script("document.getElementById('id_total_bandwidth_alerts').style.color='blue';");
	$obj->assign("id_total_bandwidth_alerts","innerHTML","Podaj całkowitą przepustowość węzła");
    }
    
    if (!empty($form['total_bandwidth']) && !empty($form['bandwidth_broadband']) && $form['bandwidth_broadband'] > $form['total_bandwidth']) {
	$blad = true;
	$obj->script("addClassId('id_bandwidth_broadband','alerts');");
	$obj->script("document.getElementById('id_bandwidth_broadband_alerts').style.color='red';");
	$obj->assign("id_bandwidth_broadband_alerts","innerHTML","Przepustowość jest wyższa dla całkowitej przepustowości");
    }
    
    
    if ($warn) {
	$str = "<input type='checkbox' name='networknode[notwarn]' value='1' id='id_notwarn'";
	if ($form['notwarn']) $str .= " checked";
	$str .= "><label for='id_notwarn' style='color:blue;'><b>Ignoruj ostrzeżenia</b></label>";
	$obj->assign("id_warn_view","innerHTML",$str);
    } else {
	$obj->assign("id_warn_view","innerHTML","");
    }
    
    if (!$blad) {
	if ($warn && !$form['notwarn'])
	    $blad = true;
    }
    
    if (!$blad)
	$obj->script("document.networknode.submit();");
    
    
    
    return $obj;
}

$LMS->InitXajax();
$LMS->RegisterXajaxFunction(
    array(
	'basisdata_from_collocation',
	'validate_networknode',
    )
);
$SMARTY->assign('xajax',$LMS->RunXajax());

?>