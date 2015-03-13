<?php

/*
 * LMS iNET
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
 *  $Id: v 1.00 Sylwester Kondracki Exp $
 */

$idn = (isset($_GET['idn']) ? intval($_GET['idn']) : (isset($_POST['networknodeid']) ? intval($_POST['networknodeid']) : NULL));
if (!$LMS->CheckExistsNetworkNode($idn)) $SESSION->redirect('?m=networknodelist');


$tucklist = array(
	array('tuck' => 'base', 'name' => 'Informacje', 'link' => '?m=networknodeinfo&tuck=base&idn='.$idn),
);

$networknode = $LMS->GetNetworkNode($idn);
$SMARTY->assign('networknode',$networknode);
$SMARTY->assign('idn',$idn);

$layout['pagetitle'] = 'Węzeł : '.$networknode['name'];


$tucklist[] = array('tuck' => 'interface', 'name' => 'Interfejsy sieciowe', 'link' => '?m=networknodeinfo&tuck=interface&idn='.$idn, 'tip' => 'Lista urządzeń sieciowych przypisanych do tego węzła');
$tucklist[] = array('tuck' => 'costs', 'name' => trans('Koszty'), 'link' => '?m=networknodeinfo&tuck=costs&idn='.$idn, 'tip' => trans('Koszty związane z utrzymaniem samego węzła'));
$tucklist[] = array('tuck' => 'annex', 'name' => 'Załączniki', 'link' => '?m=networknodeinfo&tuck=annex&idn='.$idn, 'tip' => 'Załączone dokumenty, pliki itp. do węzła');
$tucklist[] = array('tuck' => 'group', 'name' => 'Grupy', 'link' => '?m=networknodeinfo&tuck=group&idn='.$idn, 'tip' => 'Grupy do jakich należy węzeł');

$SMARTY->assign('tucklist',$tucklist);

$tuck = (isset($_GET['tuck']) ? $_GET['tuck'] : NULL);
$SESSION->nowsave('net_node_tuck',$tuck);


if ($tuck == 'base') {
    
    $SMARTY->display('networknodeinfobox.html');
    die;
}

elseif ($tuck == 'interface') {
    $SESSION->nowsave('net_node_tuck',$tuck);
    $netdevlist = array();
    
    if (isset($_GET['delinterface']) && !empty($_GET['delinterface'])) {
	$DB->Execute('UPDATE netdevices SET networknodeid=0 WHERE id = ?;',array($_GET['delinterface']));
	$networknode = $LMS->GetNetworkNode($idn);
	$SMARTY->assign('networknode',$networknode);
    }
    
    if (isset($_GET['addinterface']) && !empty($_GET['addinterface'])) {
	$idi = intval($_GET['addinterface']); // id interfejsu sieciowego;
	$pri = (isset($_GET['pri']) ? $_GET['pri'] : '-1');
	$prn = (isset($_GET['prn']) ? $_GET['prn'] : '-1');
	
	$LMS->add_interface_for_networknode($idn,$idi);
	
	if ($pri != '-1') 
	    $DB->Execute('UPDATE netdevices SET invprojectid = ? WHERE id = ?;',array(($pri ? $pri : NULL),$idi));
	
	if ($prn != '-1')
	    $DB->Execute('UPDATE nodes SET invprojectid = ? WHERE netdev = ?;',array(($prn ? $prn : NULL),$idi));
	
	$networknode = $LMS->GetNetworkNode($idn);
	$SMARTY->assign('networknode',$networknode);
    }
    
    if (isset($_GET['updateinterface']) && !empty($_GET['updateinterface'])) {
	if (isset($_GET['save'])) {
	    $idi = intval($_GET['updateinterface']); // id interfejsu sieciowego;
	    $pri = (isset($_GET['pri']) ? $_GET['pri'] : '-1');
	    $prn = (isset($_GET['prn']) ? $_GET['prn'] : '-1');
	    
	    if ($pri != '-1') 
		$DB->Execute('UPDATE netdevices SET invprojectid = ? WHERE id = ?;',array(($pri ? $pri : NULL),$idi));
	
	    if ($prn != '-1')
		$DB->Execute('UPDATE nodes SET invprojectid = ? WHERE netdev = ?;',array(($prn ? $prn : NULL),$idi));
	} else {
	    $SMARTY->assign('intinfo',$DB->getRow('SELECT id, name FROM netdevices WHERE id = ? LIMIT 1;',array($_GET['updateinterface'])));
	    $SMARTY->assign('updateint',true);
	}
    }
    
    if ($count = $DB->GetAll('SELECT id FROM netdevices WHERE networknodeid = ? ORDER BY name ;',array($idn)))
	for ($i=0; $i<sizeof($count);$i++) $netdevlist[] = $LMS->GetNetDev($count[$i]['id']);
    
    $npnetdev = $DB->GetAll('SELECT id, name FROM netdevices WHERE networknodeid = 0 ORDER BY name;');
    
    $SMARTY->assign('npnetdev',$npnetdev);
    $SMARTY->assign('opencard',true);
    $SMARTY->assign('netdevlist',$netdevlist);
    $SMARTY->assign('projectlist',$DB->getAll('SELECT id,name FROM invprojects WHERE type = 0 ORDER BY name ASC;'));
    $SMARTY->display('networknodeinfonetdevbox.html');
    die;
}

elseif ($tuck == 'costs') {
    
	$layout['popup'] = $layout['ajax'] = true;
	
	$lista = $DB->GetAll('SELECT u.*,
			(SELECT login FROM users WHERE id = u.cid) AS cname,
			(SELECT login FROM users WHERE id = u.mid) AS mname 
			 FROM upkeep u WHERE u.ownerid = ? AND owner = ?;',array(intval($idn),'networknode'));
	
	$SMARTY->assign('lista',$lista);
    
	function save_costsinfo($forms)
	{
		global $DB,$idn, $AUTH;
		$obj = new xajaxResponse();
		$blad = false;
		
		$obj->script("removeClassId('costs_value','alerts');");
		$obj->script("removeClassId('costs_fromdate','alerts');");
		$obj->script("removeClassid('costs_todate','alerts');");
		$obj->script("removeClassId('costs_name','alerts');");
		$obj->assign("alert_value","innerHTML","");
		$obj->assign("alert_date","innerHTML","");
		$obj->assign("alert_name","innerHTML","");
		$obj->assign("alert_description","innerHTML","");
		
		$form = $forms['costs'];
		$form['value'] = str_replace(",",".",$form['value']);
		
		if (empty($form['value'])) 
		{
			$blad = true;
			$obj->script("addClassId('costs_value','alerts');");
			$obj->assign("alert_value","innerHTML","Proszę podać wartość");
		} 
		elseif (!is_numeric($form['value'])) 
		{
			$blad = true;
			$obj->script("addClassId('costs_value','alerts');");
			$obj->assign("alert_value","innerHTML","Proszę podać prawidłowo wartość liczbową");
		} 
		else 
		{
			$obj->assign("costs_value","value",$form['value']);
		}
		
		if (!empty($form['fromdate'])) 
		{
			if (!preg_match('/^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/',$form['fromdate'])) 
			{
				$blad = true;
				$obj->script("addClassId('costs_fromdate','alerts');");
				$obj->assign("alert_date","innerHTML","proszę podać prawidłowo datę RRRR/MM/DD");
				$obj->assign("costs_fromdate","value","");
			}
		}
		
		if (!empty($form['todate'])) 
		{
			if (!preg_match('/^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/',$form['todate'])) 
			{
				$blad = true;
				$obj->script("addClassId('costs_todate','alerts');");
				$obj->assign("alert_date","innerHTML","proszę podać prawidłowo datę RRRR/MM/DD");
				$obj->assign("costs_todate","value","");
			}
		}
		
		if (empty($form['name'])) 
		{
			$blad = true;
			$obj->script("addClassId('costs_name','alerts');");
			$obj->assign("alert_name","innerHTML","Proszę podać nazwę");
		}
		
		if (!$blad) 
		{
			
			if (!empty($form['fromdate'])) 
				$form ['fromdate'] = strtotime($form['fromdate']);
			else 
				$form['fromdate'] = 0;
			
			if (!empty($form['todate']))
				$form['todate'] = strtotime($form['todate']) + 86399;
			else
				$form['todate'] = 0;
			
			if (isset($form['id']) && intval($form['id']))
				$DB->Execute('UPDATE upkeep SET value = ?, periods = ?, name = ?, description = ?, fromdate = ?, todate = ?, mid = ?, mdate = ?
					WHERE id = ?;',array($form['value'], $form['periods'], $form['name'], ($form['description'] ? $form['description'] : NULL), $form['fromdate'],
					$form['todate'], $AUTH->id, time(), $form['id']));
			else
				$DB->execute('INSERT INTO upkeep (owner, ownerid, value, periods, name, description, fromdate, todate, cid, cdate) 
					VALUES (?,?,?,?,?,?,?,?,?,?); ', array('networknode',intval($idn), $form['value'], $form['period'], $form['name'], 
					($form['description'] ? $form['description'] : NULL), $form['fromdate'], $form['todate'], $AUTH->id, time()));
			
			$obj->script("loadAjax('id_data','?m=networknodeinfo&tuck=costs&idn=".$idn."');");
		}
		
		return $obj;
	}


	function delete_costsinfo($id)
	{
		global $DB,$idn;
		$obj = new xajaxResponse();
		$DB->Execute('DELETE FROM upkeep WHERE id = ? ;',array($id));
		$obj->script("loadAjax('id_data','?m=networknodeinfo&tuck=costs&idn=".$idn."');");
		return $obj;
	}


	function edit_costsinfo($id)
	{
		global $DB;
		$obj = new xajaxResponse();
		$service = NULL;
		
		if ($costs = $DB->GetRow('SELECT id,value,periods,name,description,fromdate,todate FROM upkeep WHERE id = ? ;',array(intval($id)))) 
		{
			$obj->script("document.getElementById('id_editcosts').style.display='';");
			$obj->assign("id_costs_title","innerHTML","Edycja pozycji");
			$obj->assign("costs_id","value",$costs['id']);
			$obj->assign("costs_value","value",$costs['value']);
			$obj->script("document.getElementById('costs_period').value=".$costs['periods']);
			
			if ($costs['fromdate']) 
				$obj->assign("costs_fromdate","value",date('Y/m/d',$costs['fromdate'])); else $obj->assign("costs_fromdate","value","");
			
			if ($costs['todate']) 
				$obj->assign("costs_todate","value",date('Y/m/d',$costs['todate'])); else $obj->assign("costs_todate","value","");
			
			$obj->assign("costs_name","value",$costs['name']);
			$obj->assign("costs_description","value",$costs['description']);
			$obj->script("window.scroll(0,0);");
		}
	return $obj;
	}


	function add_costsinfo()
	{
		$obj = new xajaxResponse();
		$obj->script("document.getElementById('id_editcosts').style.display='';");
		$obj->script("removeClassId('costs_value','alerts');");
		$obj->script("removeClassId('costs_fromdate','alerts');");
		$obj->script("removeClassid('costs_todate','alerts');");
		$obj->script("removeClassId('costs_name','alerts');");
		$obj->assign("alert_value","innerHTML","");
		$obj->assign("alert_date","innerHTML","");
		$obj->assign("alert_name","innerHTML","");
		$obj->assign("alert_description","innerHTML","");
		$obj->assign("id_costs_title","innerHTML","Dodanie nowej pozycji");
		$obj->assign("costs_id","value","");
		$obj->assign("costs_value","value","");
		$obj->script("document.getElementById('costs_period').value='';");
		$obj->assign("costs_fromdate","value","");
		$obj->assign("costs_todate","value","");
		$obj->assign("costs_name","value","");
		$obj->assign("costs_description","value","");
		$obj->script("window.scroll(0,0);");
		return $obj;
	}
	
	function hide_editcost()
	{
		$obj = new xajaxResponse();
		$obj->script("document.getElementById('costs_id').value='';");
		$obj->script("document.getElementById('costs_value').value='';");
		$obj->script("document.getElementById('costs_fromdate').value='';");
		$obj->script("document.getElementById('costs_todate').value='';");
		$obj->script("document.getElementById('costs_name').value='';");
		$obj->script("document.getElementById('costs_description').value='';");
		$obj->script("document.getElementById('id_editcosts').style.display='none';");
		return $obj;
	}

	$LMS->InitXajax();
	$LMS->RegisterXajaxFunction(array('save_costsinfo','delete_costsinfo','edit_costsinfo','add_costsinfo','hide_editcost'));
	$SMARTY->assign('xajax', $LMS->RunXajax());
	
	$SMARTY->display('networknodecosts.html');
	die;

}

elseif ($tuck == 'annex') {
    
    $layout['popup'] = $layout['ajax'] = true;
    $annex_info = array('section'=>'networknode','ownerid'=>$idn);
    include(MODULES_DIR.'/annex.inc.php');
    $SMARTY->display('annex.html');
    die;
}

elseif ($tuck == 'group') {
    $layout['popup'] = $layout['ajax'] = true;
    
    if (isset($_GET['addgroup']) && !empty($_GET['addgroup'])) {
	$DB->Execute('INSERT INTO networknodeassignments (networknodeid, networknodegroupid) VALUES (?,?);',array($idn,intval($_GET['addgroup'])));
    }
    
    if (isset($_GET['delgroup']) && !empty($_GET['delgroup'])) {
	$DB->Execute('DELETE FROM networknodeassignments WHERE networknodeid = ? AND networknodegroupid = ?;',array($idn,intval($_GET['delgroup'])));
    }
    
    $othergroups = $LMS->GetNetworkNodeGroupNamesWithoutNode($idn);
    $SMARTY->assign('othergroups',$othergroups);
    
    $groups = $DB->getAll('SELECT ng.id, ng.name, ng.description 
			    FROM networknodeassignments na 
			    JOIN networknodegroups ng ON (ng.id = na.networknodegroupid)
			    WHERE na.networknodeid = ? 
			    ORDER BY ng.name ASC;',array($idn));
    $SMARTY->assign('groups',$groups);
    
    
    $SMARTY->display('networknodegroup.html');


die;
}

$tuck = (isset($_GET['tuck']) ? $_GET['tuck'] : $SESSION->get('net_node_tuck','base'));
$tuckcount = sizeof($tucklist);

$err = true;;
for ($i=0; $i<$tuckcount; $i++) {
    if ($tucklist[$i]['tuck'] == $tuck) { $err = false; break; }
}

if ($err) $tuck = 'base';

for ($i = 0; $i < $tuckcount; $i++)
    if ($tucklist[$i]['tuck'] == $tuck) $tucklink = $tucklist[$i]['link'];


$SESSION->nowsave('net_node_tuck',$tuck);

$SMARTY->assign('tuck',$tuck);
$SMARTY->assign('tucklink',$tucklink);

$SMARTY->display('networknodeinfo.html');

?>