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

$layout['pagetitle'] = 'Słownik - Producenci i modele urządzeń sieciowych';
$listdata = $modellist = array();
$producerlist = $DB->getAll('SELECT id, name FROM netdeviceproducers ORDER BY name ASC');


if(!isset($_GET['p_id'])) 
    $SESSION->restore('ndpid', $pid); 
else 
    $pid = $_GET['p_id']; 
$SESSION->save('ndpid', $pid);

if (! isset($_GET['page']))
	$SESSION->restore('ndlpage', $_GET['page']);


if ($pid) 
    $producerinfo = $DB->getRow('SELECT p.id , p.alternative_name FROM netdeviceproducers p WHERE p.id = ?;', array($pid));
else
    $producerinfo = array();

$listdata['pid'] = $pid; // producer id


function cancel_producer()
{
    $obj = new xajaxResponse();
    
    $obj->assign("id_producer","value","");
    $obj->assign("id_producername","value","");
    $obj->assign("id_alternative_name","value","");
    $obj->assign("warn_producername","innerHTML","");
    $obj->script("removeClassId('id_producername','alerts');");
    $obj->script("document.getElementById('div_produceredit').style.display='none';");
    return $obj;
}


function add_producer()
{
    $obj = new xajaxResponse();
    $obj->script("document.getElementById('div_produceredit').style.display='';");
    $obj->script("removeClassId('id_producername','alerts');");
    $obj->assign("warn_producername","innerHTML","");
    $obj->assign("id_action_name","innerHTML",trans('New producer'));
    $obj->assign("id_producer","value","");
    $obj->assign("id_producername","value","");
    $obj->assign("id_alternative_name","value","");
    $obj->script("document.getElementById('id_producername').focus();");
    return $obj;
}


function edit_producer($id)
{
    global $DB;
    $obj = new xajaxResponse();
    
    $dane = $DB->getrow('SELECT * FROM netdeviceproducers WHERE id = ? LIMIT 1;',array($id));
    
    $obj->script("document.getElementById('div_produceredit').style.display='';");
    $obj->script("removeClassId('id_producername','alerts');");
    $obj->assign("warn_producername","innerHTML","");
    $obj->assign("id_action_name","innerHTML",trans('Producer edition')." : ".$dane['name']);
    
    $obj->assign("id_producer","value",$dane['id']);
    $obj->assign("id_producername","value",$dane['name']);
    $obj->assign("id_alternative_name","value",$dane['alternative_name']);
    $obj->script("document.getElementById('id_producername').focus();");
    return $obj;
}


function save_producer($forms)
{
    global $DB;
    $form = $forms['produceredit'];
    $obj = new xajaxResponse();
    $blad = false;
    
    $obj->assign("warn_producername","innerHTML","");
    $obj->script("removeClassId('id_producername','alerts');");
    
    if (empty($form['name'])) {
	$blad = true;
	$obj->assign("warn_producername","innerHTML",trans('name is required'));
    }
    
    if (!$blad) {
	if (!$form['id']) 
	    $blad = ($DB->getOne('SELECT 1 FROM netdeviceproducers WHERE name = ? '.$DB->Limit(1).' ;',array(strtoupper($form['name']))) ? true : false);
	else
	    $blad = ($DB->getOne('SELECT 1 FROM netdeviceproducers WHERE name = ? AND id != ? '.$DB->Limit(1).';',array(strtoupper($form['name']),$form['id'])) ? true : false);

	if ($blad) 
	    $obj->assign("warn_producername","innerHTML",trans('list the manufacturer already exists'));
    }
    
    if ($blad) {
	$obj->script("addClassId('id_producername','alerts');");
	$obj->script("document.getElementById('id_producername').focus();");
    } else {
	
	if ($form['id']) {
	    
	    if (SYSLOG) {
		$oldname = $DB->getOne('SELECT name FROM netdeviceproducers WHERE id = ? '.$DB->Limit(1).';',array($form['id']));
		if (strtoupper($oldname) != strtoupper($form['name']))
		    addlogs('Słownik Producenci: zmiana nazwy producenta z '.$oldname.' na '.$form['name'],'m=other;e=up');
	    }
	    
	    $DB->Execute('UPDATE netdeviceproducers SET name = ?, alternative_name = ? WHERE id = ?;',
		array(
		    strtoupper($form['name']),
		    ($form['alternative_name'] ? $form['alternative_name'] : NULL),
		    $form['id']
		)
	    );
	    $obj->script("xajax_cancel_producer();");
	    $obj->script("self.location.href='?m=netdevicemodels&page=1&p_id=".intval($form['id'])."';");
	
	} else {
	    
	    if (SYSLOG)
		addlogs('Słownik Producenci: dodano nowego producenta -> '.strtoupper($form['name']),'m=other;e=add');
	    
	    $DB->Execute('INSERT INTO netdeviceproducers (name,alternative_name) VALUES (?,?);',
		array(
		    strtoupper($form['name']),
		    ($form['alternative_name'] ? $form['alternative_name'] : NULL)
		)
	    );
	    
	    $obj->script("xajax_cancel_producer();");
	    $obj->script("self.location.href='?m=netdevicemodels&page=1&p_id=".intval($DB->getLastInsertId('netdeviceproducers'))."';");
	}
    }
	
    return $obj;
}

function delete_producer($id)
{
    global $DB;
    $obj = new xajaxResponse();
    
    if (SYSLOG) {
	$oldname = $DB->getOne('SELECT name FROM netdeviceproducers WHERE id = ? '.$DB->Limit(1).';',array($id));
	addlogs('Słownik Producenci: usunięcie producenta '.$oldname.' oraz wszystkich jego modeli','m=other;e=del;');
    }
    
    $DB->BeginTrans();
    $DB->Execute('DELETE FROM netdevicemodels WHERE netdeviceproducerid = ? ;',array($id));
    $DB->Execute('DELETE FROM netdeviceproducers WHERE id = ? ;',array($id));
    $DB->CommitTrans();
    
    $obj->script("self.location.href='?m=netdevicemodels&page=1&p_id=';");
    return $obj;
}


function set_active_models($id)
{
    global $DB;
    $obj = new xajaxResponse();
    $id = intval($id);
    
    $status = (!$DB->getOne('SELECT 1 FROM netdevicemodels WHERE id = ? AND active = 1 '.$DB->Limit(1).';',array($id)) ? 1 : 0);
    
    $DB->Execute('UPDATE netdevicemodels SET active = ? WHERE id = ? ;',array($status,$id));
    
    if ($status) {
	$obj->script("removeClassId('idtr".$id."','blend');");
	$obj->script("document.getElementById('idimg".$id."').src='img/access.gif';");
    } else {
	$obj->script("addClassId('idtr".$id."','blend');");
	$obj->script("document.getElementById('idimg".$id."').src='img/noaccess.gif';");
    }
    return $obj;
}

function cancel_model()
{
    $obj = new xajaxResponse();
    
    $obj->assign("id_model","value","");
    $obj->assign("id_modelname","value","");
    $obj->assign("id_model_alternative_name","value","");
    $obj->assign("warn_model_name","innerHTML","");
    $obj->script("removeClassId('id_model_name','alerts');");
    $obj->script("document.getElementById('div_modeledit').style.display='none';");
    return $obj;
}


function add_model()
{
    $obj = new xajaxResponse();
    $obj->script("document.getElementById('div_modeledit').style.display='';");
    $obj->script("removeClassId('id_model_name','alerts');");
    $obj->assign("warn_model_name","innerHTML","");
    $obj->assign("id_model_action_name","innerHTML","Nowy model");
    $obj->assign("id_model","value","");
    $obj->assign("id_model_name","value","");
    $obj->assign("id_model_alternative_name","value","");
    $obj->script("document.getElementById('id_model_name').focus();");
    return $obj;
}


function edit_model($id)
{
    global $DB;
    $obj = new xajaxResponse();
    
    $dane = $DB->getrow('SELECT * FROM netdevicemodels WHERE id = ? '.$DB->Limit(1).';',array($id));
    
    $obj->script("document.getElementById('div_modeledit').style.display='';");
    $obj->script("removeClassId('id_model_name','alerts');");
    $obj->assign("warn_model_name","innerHTML","");
    $obj->assign("id_model_action_name","innerHTML","Edycja modelu : ".$dane['name']);
    
    $obj->assign("id_model","value",$dane['id']);
    $obj->assign("id_model_name","value",$dane['name']);
    $obj->assign("id_model_alternative_name","value",$dane['alternative_name']);
    $obj->assign("id_model_ean","value",$dane['ean']);
    $obj->script("document.getElementById('id_model_name').focus();");
    return $obj;
}


function save_model($forms)
{
    global $DB;
    $form = $forms['modeledit'];
    $obj = new xajaxResponse();
    $blad = false;
    
    $obj->assign("warn_model_name","innerHTML","");
    $obj->script("removeClassId('id_model_name','alerts');");
    
    if (empty($form['name'])) {
	$blad = true;
	$obj->assign("warn_model_name","innerHTML",trans('name is required'));
    }
    
    if (!$blad) {
	if (!$form['id']) 
	    $blad = ($DB->getOne('SELECT 1 FROM netdevicemodels WHERE netdeviceproducerid = ? AND UPPER(name) = ? '.$DB->Limit(1).' ;',array($form['pid'],strtoupper($form['name']))) ? true : false);
	else
	    $blad = ($DB->getOne('SELECT 1 FROM netdevicemodels WHERE id != ? AND netdeviceproducerid = ? AND UPPER(name) = ? '.$DB->Limit(1).';',array($form['id'],$form['pid'],strtoupper($form['name']))) ? true : false);

	if ($blad) 
	    $obj->assign("warn_model_name","innerHTML","Podany model już jest w bazie danych");
    }
    
    if ($blad) {
	$obj->script("addClassId('id_model_name','alerts');");
	$obj->script("document.getElementById('id_model_name').focus();");
    } else {
	
	if ($form['id']) {
	    
	    if (SYSLOG) {
		$oldname = $DB->getOne('SELECT name FROM netdevicemodels WHERE id = ? '.$DB->Limit(1).';',array($form['id']));
		$pidname = $DB->getone('SELECT name FROM netdeviceproducers WHERE id = ? '.$DB->Limit(1).';',array($form['pid']));
		if (strtoupper($oldname) != strtoupper($form['name']))
		    addlogs('Słownik Producenci: Producent -> '.$pidname.', zmiana nazwy modelu z '.$oldname.' na '.$form['name'],'m=other;e=up');
	    }
	    
	    $DB->Execute('UPDATE netdevicemodels SET name = ?, alternative_name = ?, ean = ? WHERE id = ?;',
		array(
		    $form['name'],
		    ($form['alternative_name'] ? $form['alternative_name'] : NULL),
		    $form['ean'],
		    $form['id']
		)
	    );
	    $obj->script("xajax_cancel_model();");
	    $obj->script("self.location.href='?m=netdevicemodels&page=1&p_id=".intval($form['pid'])."';");
	
	} else {
	    
	    if (SYSLOG) {
		$pidname = $DB->getone('SELECT name FROM netdeviceproducers WHERE id = ? '.$DB->Limit(1).';',array($form['pid']));
		addlogs('Słownik Producenci: Producent -> '.$pidname.', dodano nowy model -> '.$form['name'],'m=other;e=add');
	    }
	    
	    $DB->Execute('INSERT INTO netdevicemodels (netdeviceproducerid, name, alternative_name, ean) VALUES (?, ?, ?, ?);',
		array(
		    $form['pid'],
		    $form['name'],
		    ($form['alternative_name'] ? $form['alternative_name'] : NULL),
		    $form['ean']
		)
	    );
	    
	    $obj->script("xajax_cancel_model();");
	    $obj->script("self.location.href='?m=netdevicemodels&page=1&p_id=".$form['pid']."';");
	}
    }
	
    return $obj;
}

function delete_model($id)
{
    global $DB;
    $obj = new xajaxResponse();
    
    if (SYSLOG) {
	$oldname = $DB->getOne('SELECT name FROM netdevicemodels WHERE id = ? '.$DB->Limit(1).';',array($id));
	$pidname = $DB->getRow('SELECT p.id, p.name FROM netdevicemodels m JOIN netdeviceproducers p ON (p.id = m.netdeviceproducerid) WHERE m.id = ? '.$DB->Limit(1).';',array($id));
	addlogs('Słownik Producenci: Producent -> '.$pidname['name'].', usunięcie modelu '.$oldname,'m=other;e=del;');
    }
    
    $DB->BeginTrans();
    $DB->Execute('DELETE FROM netdevicemodels WHERE id = ? ;',array($id));
    $DB->CommitTrans();
    
    $obj->script("self.location.href='?m=netdevicemodels&page=1&p_id=".$pidname['id']."';");
    return $obj;
}



$LMS->InitXajax();
$LMS->RegisterXajaxFunction(
    array(
	'cancel_producer',
	'add_producer',
	'edit_producer',
	'save_producer',
	'delete_producer',
	'set_active_models',
	'cancel_model',
	'add_model',
	'edit_model',
	'save_model',
	'delete_model',
    )
);


function getModelList($pid = NULL)
{
    global $DB;
    
    if (!$pid) return NULL;
    
    $lista = $DB->getAll('SELECT m.id, m.name, m.alternative_name, m.active, m.ean,
			(SELECT COUNT(i.id) FROM netdevices i WHERE i.netdevicemodelid = m.id) AS netdevcount,
			(SELECT COUNT(n.id) FROM nodes n WHERE n.netdevicemodelid = m.id) AS nodecount 
			FROM netdevicemodels m 
			WHERE m.netdeviceproducerid = ? 
			ORDER BY m.name ASC;',
			array($pid));
    return $lista;
}

$modellist = getModelList($pid);

$listdata['total'] = sizeof($modellist);

$page = (!$_GET['page'] ? 1 : $_GET['page']);
$pagelimit = get_conf('phpui.dictionary_pagelimit',50);
$start = ($page - 1) * $pagelimit;

$SESSION->save('ndlpage',$page);

$SMARTY->assign('xajax',$LMS->RunXajax());
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('producerlist',$producerlist);
$SMARTY->assign('modellist',$modellist);
$SMARTY->assign('producerinfo',$producerinfo);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('page',$page);
$SMARTY->assign('start',$start);
$SMARTY->display('netdevicemodels.html');
?>
