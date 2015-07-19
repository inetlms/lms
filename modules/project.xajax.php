<?php

/*
 *  iNET LMS
 *
 *  (C) Copyright 2012-2015 LMS Developers
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
 *  Sylwester Kondracki
 *  sylwester.kondracki@gmail.com
 *  gadu-gadu : 6164816
*/


function save_project($forms)
{
	global $DB, $AUTH;
	$obj = new xajaxResponse();
	$blad = false;
	$form = $forms['projectdata'];
	
	$obj->script("removeClassId('id_division','alerts');");
	$obj->assign("warn_division","innerHTML","");
	$obj->script("removeClassId('id_states','alerts');");
	$obj->assign("warn_states","innerHTML","");
	$obj->script("removeClassId('id_program','alerts');");
	$obj->assign("warn_program","innerHTML","");
	$obj->script("removeClassId('id_action','alerts');");
	$obj->assign("warn_action","innerHTML","");
	$obj->script("removeClassId('id_contractdate','alerts');");
	$obj->assign("warn_contractdate","innerHTML","");
	$obj->script("removeClassId('id_fromdate','alerts');");
	$obj->assign("warn_fromdate","innerHTML","");
	$obj->script("removeClassId('id_todate','alerts');");
	$obj->assign("warn_todate","innerHTML","");
	$obj->script("removeClassId('id_value','alerts');");
	$obj->assign("warn_value","innerHTML","");
	$obj->script("removeClassId('id_ownvalue','alerts');");
	$obj->assign("warn_ownvalue","innerHTML","");
	$obj->script("removeClassId('id_status','alerts');");
	$obj->assign("warn_status","innerHTML","");
	$obj->script("removeClassId('id_number','alerts');");
	$obj->assign("warn_number","innerHTML","");
	$obj->script("removeClassId('id_contract','alerts');");
	$obj->assign("warn_contract","innerHTML","");
	$obj->script("removeClassId('id_name','alerts');");
	$obj->assign("warn_name","innerHTML","");
	$obj->script("removeClassId('id_title','alerts');");
	$obj->assign("warn_title","innerHTML","");
	$obj->script("removeClassId('id_scope','alerts');");
	$obj->assign("warn_scope","innerHTML","");
	
	
	if (empty($form['division'])) {
		$blad = true;
		$obj->script("addClassId('id_division','alerts');");
		$obj->assign("warn_division","innerHTML",trans("Dane wymagane"));
	}
	
	if (empty($form['states'])) {
		$blad = true;
		$obj->script("addClassId('id_states','alerts');");
		$obj->assign("warn_states","innerHTML",trans("Dane wymagane"));
	}
	
	if ($form['eu'] == 1) {
		if ($form['program'] == '-1') {
			$blad = true;
			$obj->script("addClassId('id_program','alerts');");
			$obj->assign("warn_program","innerHTML","Dane wymagane");
		}
		
		if ($form['action'] == '-1') {
			$blad = true;
			$obj->script("addClassId('id_action','alerts');");
			$obj->assign("warn_action","innerHTML","Dane wymagane");
		}
		
		if (empty($form['contractdate'])) {
			$blad = true;
			$obj->script("addClassId('id_contractdate','alerts');");
			$obj->assign("warn_contractdate","innerHTML","Data wymagana");
		} elseif(!check_date($form['contractdate'])) {
			$blad = true;
			$obj->script("addClassId('id_contractdate','alerts');");
			$obj->assign("warn_contractdate","innerHTML","Błędnie podana data");
		} else
			$form['contractdate'] = str_replace('-','/',$form['contractdate']);
		
		if (empty($form['todate'])) {
			$blad = true;
			$obj->script("addClassId('id_todate','alerts');");
			$obj->assign("warn_todate","innerHTML","Data wymagana");
		} elseif(!check_date($form['todate'])) {
			$blad = true;
			$obj->script("addClassId('id_todate','alerts');");
			$obj->assign("warn_todate","innerHTML","Błędnie podana data");
		} else
			$form['todate'] = str_replace('-','/',$form['todate']);
		
		if (empty($form['contract'])) {
			$blad = true;
			$obj->script("addClassId('id_contract','alerts');");
			$obj->assign("warn_contract","innerHTML","Dane wymagane");
		} else {
			if ($form['id']) 
				$isset = $DB->getOne('SELECT 1 FROM invprojects WHERE UPPER(contract) = ? AND id != ? '.$DB->Limit(1).';',array(strtoupper($form['contract']),intval($form['id'])));
			else
				$isset = $DB->getOne('SELECT 1 FROM invprojects WHERE UPPER(contract) = ? '.$DB->Limit(1).';',array(strtoupper($form['contract'])));
			
			if ($isset) {
				$blad = true;
				$obj->script("addClassId('id_contract','alerts');");
				$obj->assign("warn_contract","innerHTML","Podany numer umowy już istnieje");
			}
		}
		
		if (empty($form['title'])) {
			$blad = true;
			$obj->script("addClassId('id_title','alerts');");
			$obj->assign("warn_title","innerHTML","Dane wymagane");
		}
		
		if (empty($form['scope'])) {
			$blad = true;
			$obj->script("addClassId('id_scope','alerts');");
			$obj->assign("warn_scope","innerHTML","Dane wymagane");
		}
		
	} else { // eu
		
		if (!empty($form['todate']) && !check_date($form['todate'])) {
			$blad = true;
			$obj->script("addClassId('id_todate','alerts');");
			$obj->assign("warn_todate","innerHTML","Błędnie podana data");
		} else
			$form['todate'] = str_replace('-','/',$form['todate']);
	}
	
	if (!empty($form['fromdate']) && !check_date($form['fromdate'])) {
		$blad = true;
		$obj->script("addClassId('id_fromdate','alerts');");
		$obj->assign("warn_fromdate","innerHTML","Błędnie podana data");
	} else 
		$form['fromdate'] = str_replace('-','/',$form['fromdate']);
	
	$form['value'] = str_replace(',','.',$form['value']);
	$form['ownvalue'] = str_replace(',','.',$form['ownvalue']);
	
	if (!empty($form['value']) && !check_natural($form['value'])) {
		$blad = true;
		$obj->script("addClassId('id_value','alerts');");
		$obj->assign("warn_value","innerHTML","Błędnie podana wartość");
	} elseif (!empty($form['value']) && check_natural($form['value'])) {
		$obj->assign("id_value","value",$form['value']);
	}
	
	if (!empty($form['ownvalue']) && !check_natural($form['ownvalue'])) {
		$blad = true;
		$obj->script("addClassId('id_ownvalue','alerts');");
		$obj->assign("warn_ownvalue","innerHTML","Błędnie podana wartość");
	} elseif (!empty($form['ownvalue']) && check_natural($form['ownvalue'])) {
		$obj->assign("id_ownvalue","value",$form['ownvalue']);
	}
    
	if ($form['status'] == '-1') {
		$blad = true;
		$obj->script("addClassId('id_status','alerts');");
		$obj->assign("warn_status","innerHTML","Wybierz status");
	}
	
	if (empty($form['number'])) {
		$blad = true;
		$obj->script("addClassId('id_number','alerts');");
		$obj->assign("warn_number","innerHTML","Numer jest wymagany");
	} else {
		if ($form['id']) 
			$isset = $DB->getOne('SELECT 1 FROM invprojects WHERE UPPER(number) = ? AND id != ? '.$DB->Limit(1).';',array(strtoupper($form['number']),intval($form['id'])));
		else
			$isset = $DB->getOne('SELECT 1 FROM invprojects WHERE UPPER(number) = ? '.$DB->Limit(1).';',array(strtoupper($form['number'])));
		
		if ($isset) {
			$blad = true;
			$obj->script("addClassId('id_number','alerts');");
			$obj->assign("warn_number","innerHTML","Numer już istnieje");
		}
	}
	
	if (empty($form['name'])) {
		$blad = true;
		$obj->script("addClassId('id_name','alerts');");
		$obj->assign("warn_name","innerHTML","Nazwa projektu jest wymagana");
	} else {
		if ($form['id'])
			$isset = $DB->getOne('SELECT 1 FROM invprojects WHERE UPPER(name) = ? AND id != ? '.$DB->limit(1).';',array(strtoupper($form['name']),intval($form['id'])));
		else
			$isset = $DB->getOne('SELECT 1 FROM invprojects WHERE UPPER(name) = ? '.$DB->limit(1).';',array(strtoupper($form['name'])));
		
		if ($isset) {
			$blad = true;
			$obj->script("addClassId('id_name','alerts');");
			$obj->assign("warn_name","innerHTML","Podana nazwa już istnieje");
		}
	}
	
	
	if (!$blad) {
		
		if (empty($form['contractdate'])) $contractdate = 0;
		else $contractdate = strtotime($form['contractdate'].' 00:00:00');
		
		if (empty($form['fromdate'])) $fromdate = 0;
		else $fromdate = strtotime($form['fromdate'].' 00:00:00');
		
		if (empty($form['todate'])) $todate = 0;
		else $todate = strtotime($form['todate'].' 23:59:29');
		
		$title = str_replace("\n"," ",$form['title']);
		$scope = str_replace("\n"," ",$form['scope']);
		
		if (!$form['eu']) 
		    $form['program'] = $form['action'] = $contractdate = 0;
		
		if ($form['id']) {
			$DB->Execute('UPDATE invprojects SET name=?, number=?, contract=?, title=?, program=?, action=?,
				division=?, contractdate=?, fromdate=?, todate=?, states=?, scope=?, value=?, ownvalue=?, 
				status=?, eu=?, description=?, siis=?, mdate=?, muser=? WHERE id = ?;', 
				array(
				    ($form['name'] ? $form['name'] : ''),
				    ($form['number'] ? $form['number'] : ''),
				    ($form['contract'] ? $form['contract'] : ''),
				    ($title ? $title : ''),
				    ($form['program'] ? $form['program'] : 0),
				    ($form['action'] ? $form['action'] : 0),
				    ($form['division'] ? $form['division'] : ''),
				    ($contractdate ? $contractdate : 0),
				    ($fromdate ? $fromdate : 0),
				    ($todate ? $todate : 0),
				    ($form['states'] ? $form['states'] : ''),
				    ($scope ? $scope : ''),
				    ($form['value'] ? str_replace(',','.',$form['value']) : '0.00'),
				    ($form['ownvalue'] ? str_replace(',','.',$form['ownvalue']) :'0.00'),
				    ($form['status'] ? $form['status'] : 0),
				    ($form['eu'] ? 1 : 0),
				    ($form['description'] ? $form['description'] : ''),
				    ($form['siis'] ? 1 : 0),
				    time(), $AUTH->id,
				    $form['id'],
				)
			);
		} else {
			$DB->Execute('INSERT INTO invprojects (name, type, number, contract, title, program, action, division, contractdate, 
				    fromdate, todate, states, scope, value, ownvalue, status, eu, description, siis, cdate, mdate, cuser, muser) 
				    VALUES (?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, 0) ;',
				array(
				    ($form['name'] ? $form['name'] : ''),
				    ($form['number'] ? $form['number'] : ''),
				    ($form['contract'] ? $form['contract'] : ''),
				    ($title ? $title : ''),
				    ($form['program'] ? $form['program'] : 0),
				    ($form['action'] ? $form['action'] : 0),
				    ($form['division'] ? $form['division'] : ''),
				    ($contractdate ? $contractdate : 0),
				    ($fromdate ? $fromdate : 0),
				    ($todate ? $todate : 0),
				    ($form['states'] ? $form['states'] : ''),
				    ($scope ? $scope : ''),
				    ($form['value'] ? str_replace(',','.',$form['value']) : '0.00'),
				    ($form['ownvalue'] ? str_replace(',','.',$form['ownvalue']) :'0.00'),
				    ($form['status'] ? $form['status'] : 0),
				    ($form['eu'] ? 1 : 0),
				    ($form['description'] ? $form['description'] : ''),
				    ($form['siis'] ? 1 : 0),
				    time(), $AUTH->id,
				)
			);
			
			$form['id'] = $DB->getLastInsertId('invprojects');
		}
		
		$obj->script("self.location.href='?m=projectinfo&id=".$form['id']."';");
	}
	
	
	return $obj;
}


function display_required($eu)
{
	$obj = new xajaxResponse();
	if ($eu == 1) {
		$obj->script("document.getElementById('tr_program').style.display='';");
		$obj->script("document.getElementById('tr_action').style.display='';");
		$obj->script("document.getElementById('tr_contractdate').style.display='';");
		$obj->script("addClassId('id_todate','required');");
		$obj->script("document.getElementById('tr_contract').style.display='';");
		$obj->script("addClassId('id_name','required');");
		$obj->script("addClassId('id_title','required');");
		$obj->script("addClassId('id_scope','required');");
		$obj->script("document.getElementById('id_title').placeholder='".trans('-- REQUIRED --')."';");
		$obj->script("document.getElementById('id_scope').placeholder='".trans('-- REQUIRED --')."';");
	} else {
		$obj->script("document.getElementById('tr_program').style.display='none';");
		$obj->script("document.getElementById('tr_action').style.display='none';");
		$obj->script("document.getElementById('tr_contractdate').style.display='none';");
		$obj->script("removeClassId('id_todate','required');");
		$obj->script("document.getElementById('tr_contract').style.display='none';");
		$obj->script("removeClassId('id_name','required');");
		$obj->script("removeClassId('id_title','required');");
		$obj->script("removeClassId('id_scope','required');");
		$obj->script("document.getElementById('id_title').placeholder='';");
		$obj->script("document.getElementById('id_scope').placeholder='';");
		$obj->script("removeClassId('id_title','alerts');");	$obj->assign("warn_title","innerHTML","");
		$obj->script("removeClassId('id_scope','alerts');");	$obj->assign("warn_scope","innerHTML","");
		$obj->script("removeClassId('id_todate','alerts');");	$obj->assign("warn_todate","innerHTML","");
	}
	
	return $obj;
}


function select_action($program,$def = NULL)
{
	global $DB,$PROJECTACTION;
	$obj = new xajaxResponse();
	
	$tab = $PROJECTACTION[$program];
	
	if (sizeof($tab) > 0)
	{
		$text =  '<select name="projectdata[action]" id="id_action" style="min-width:120px;">';
		$text .= '<option value="0"></option>';
		
		foreach ($tab as $key => $item) {
			$text .= '<option value="'.$key.'"';
			if ($def && $key == $def) $text .= ' selected';
			$text .= '>'.$item.'</option>';
		}
		
		$text .= '</select>';
	} else { 
		
		$text =  '<select name="projectdata[action]" id="id_action" style="min-width:120px;" disabled>';
		$text .= '<option value="0">wybierz program</option>';
		$text .= '</select>';
	}
	
	$obj->assign("select_action","innerHTML",$text);
	
	return $obj;
}


function set_default($id)
{
    global $DB;
    $obj = new xajaxResponse();
    
    if (!$id)
	return $obj;
    
    
    $info = $DB->getRow('SELECT program, action, eu FROM invprojects WHERE id = ? '.$DB->Limit(1).';',array($id));
    
    if ($info['eu']) {
	if (!$info['action']) $info['action'] = '0';
	$obj->script("xajax_display_required('1');");
	$obj->script("document.getElementById('id_program').value = '".$info['program']."';");
	$obj->script("document.getElementById('id_program').className = '';");
	$obj->script("xajax_select_action('".$info['program']."','".$info['action']."');");
    }
    
    return $obj;
}


$LMS->InitXajax();

$LMS->RegisterXajaxFunction(
    array(
	'save_project', 
	'display_required',
	'select_action',
	'set_default',
    )
);

$SMARTY->assign('xajax', $LMS->RunXajax());

?>
