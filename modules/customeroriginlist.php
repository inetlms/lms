<?php

/*
 * iNET LMS
 *
 *  (C) Copyright 2001-2012 LMS Developers
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
 *  $Id$ 2014 by Sylwester Kondracki
 */

$listdata = $LMS->GetOriginList();
$layout['pagetitle'] = 'Słownik źródeł pochodzenia klienta';

function editorigin_cancel()
{
    $obj = new xajaxResponse();
    $obj->script("removeClassId('editorigin_name','alerts');");
    $obj->assign("alert_name","innerHTML","");
    $obj->assign("editorigin_id","value","");
    $obj->assign("editorigin_name","value","");
    $obj->assign("editorigin_description","value","");
    $obj->script("document.getElementById('id_editorigin').style.display='none';");
    return $obj;
}

function editorigin_add()
{
    $obj = new xajaxResponse();
    $obj->assign("infoaction","innerHTML","Nowe źródło pochodzenia");
    $obj->script("removeClassId('editorigin_name','alerts');");
    $obj->assign("alert_name","innerHTML","");
    $obj->assign("editorigin_id","value","");
    $obj->assign("editorigin_name","value","");
    $obj->assign("editorigin_description","value","");
    $obj->script("document.getElementById('id_editorigin').style.display='';");
    $obj->script("document.getElementById('editorigin_name').focus();");
    return $obj;
}

function editorigin_edit($id = NULL)
{
    global $DB;
    $obj = new xajaxResponse();
    (int)$id;
    
    if ($tmp = $DB->GetRow('SELECT * FROM customerorigin WHERE id = ? LIMIT 1;',array($id))) {
	$obj->script("document.getElementById('id_editorigin').style.display='';");
	$obj->assign("infoaction","innerHTML","Edycja źródła pochodzenia : ".strtoupper($tmp['name']));
	$obj->assign("editorigin_id","value",$tmp['id']);
	$obj->assign("editorigin_name","value",$tmp['name']);
	$obj->assign("editorigin_description","value",$tmp['description']);
	$obj->script("document.getElementById('editorigin_name').focus();");
    }
    return $obj;
}

function editorigin_save($forms = NULL)
{
    global $DB;
    $obj = new xajaxResponse();
    $blad = false;
    $form = $forms['editorigin'];
    
    (int)$form['id'];
    
    if (!$form['id'] || empty($form['id'])) 
	$form['id'] = 0;
    
    $obj->script("removeClassId('editorigin_name','alerts');");
    $obj->assign("alert_name","innerHTML","");
    
    if (empty($form['name'])) {
	$blad = true;
	$obj->script("addClassId('editorigin_name','alerts');");
	$obj->assign("alert_name","innerHTML","Nazwa jest wymagana");
    } elseif ($DB->GetOne('SELECT 1 FROM customerorigin WHERE UPPER(name) = UPPER(?) AND id != ? LIMIT 1;',array($form['name'],$form['id']))) {
	$blad = true;
	$obj->script("addClassId('editorigin_name','alerts');");
	$obj->assign("alert_name","innerHTML","Podana nazwa jest użyta");
    }
    
    if (!$blad) {
	if ($form['id']) {
	    $DB->Execute('UPDATE customerorigin SET name = ?, description = ? WHERE id = ? ;',array($form['name'],($form['description'] ? $form['description'] : NULL),$form['id']));
	} else {
	    $DB->Execute('INSERT INTO customerorigin (name,description) VALUES (?,?);',array($form['name'],($form['description'] ? $form['description'] : NULL),$form['id']));
	}
	$obj->script("self.location.href='?m=customeroriginlist';");
    }
    return $obj;
}

function editorigin_del($id = NULL)
{
    global $DB;
    $obj = new xajaxResponse();
    (int)$id;
    $DB->Execute('DELETE FROM customerorigin WHERE id = ? ;',array($id));
    $obj->script("self.location.href='?m=customeroriginlist';");
    return $obj;
}

$SMARTY->assign('listdata',$listdata);

$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array('editorigin_cancel','editorigin_add','editorigin_save','editorigin_edit','editorigin_del'));
$SMARTY->assign('xajax', $LMS->RunXajax());

$SMARTY->display('customeroriginlist.html');

?>
