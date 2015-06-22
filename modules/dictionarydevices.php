<?php

/*
 *  iNET LMS
 *
 *  (C) Copyright 2012-2015 iNET LMS Developers
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
 *
*/

$layout['title'] = 'Słownik - rodzaje urządzeń';

function dictionary_devices_add()
{
    global $SMARTY;
    $obj = new xajaxresponse();
    $obj->assign('id_edit_dictionary_devices','innerHTML',$SMARTY->fetch('dictionarydevicesedit.html'));
    return $obj;
}

function dictionary_devices_edit($id)
{
    global $SMARTY,$DB;
    $obj = new xajaxResponse();
    
    $devicesinfo = $DB->GetRow('SELECT * FROM dictionary_devices_client WHERE id = ? '.$DB->Limit(1).';',array($id));
    $SMARTY->assign('devicesinfo',$devicesinfo);
    $obj->assign('id_edit_dictionary_devices','innerHTML',$SMARTY->fetch('dictionarydevicesedit.html'));
    return $obj;
}

function dictionary_devices_cancel()
{
    $obj = new xajaxResponse();
    $obj->assign('id_edit_dictionary_devices','innerHTML','');
    return $obj;
}

function dictionary_devices_save($forms=NULL)
{
    global $DB,$SMARTY;
    $form = $forms['editdictionarydevices'];
    $obj = new xajaxResponse();
    $obj->script("removeClassId('id_edit_dictionary_devices_type','alerts');");
    $obj->assign("id_alerts","innerHTML","");
    
    $blad = false;
    
    if (empty($form['type'])) {
	$blad = true;
	$obj->script("addClassId('id_edit_dictionary_devices_type','alerts');");
    }
    
    if (!$blad && !$form['id']) {

	$tmp = $DB->GetOne('SELECT 1 FROM dictionary_devices_client WHERE type = ? '.$DB->Limit(1).' ;',array(strtoupper($form['type'])));
	
	if ($tmp)
	    $obj->assign("id_alerts","innerHTML","W słowniku już istnieje taki wpis");
	else {
	    $DB->Execute('INSERT INTO dictionary_devices_client (type,description) VALUES (?,?) ;',
		array(
		    strtoupper($form['type']),
		    ($form['description'] ? $form['description'] : NULL)
		)
	    );
	    $obj->script("self.location.href='?m=dictionarydevices';");
	}
	
    }
    
    if (!$blad && $form['id']) {
	
	$tmp = $DB->GetOne('SELECT 1 FROM dictionary_devices_client WHERE type = ? AND id!=? '
		.$DB->Limit(1).' ;',array(strtoupper($form['type']),$form['id']));
	
	if ($tmp)
	    $obj->assign("id_alerts","innerHTML","W słowniku już istnieje taki wpis");
	else {
	    $DB->Execute('UPDATE dictionary_devices_client SET type=?, description=? WHERE id=? ;',
		array(
		    strtoupper($form['type']),
		    ($form['description'] ? $form['description'] : NULL),
		    $form['id']
		)
	    );
	    $obj->script("self.location.href='?m=dictionarydevices';");
	}
    }
    
    return $obj;
}

function dictionary_devices_deleted($id = NULL)
{
    global $DB;
    $obj = new xajaxResponse();
    
    if ($id) {
	
	$DB->Execute('DELETE FROM dictionary_devices_client WHERE id = ? ;',array($id));
	$obj->script("self.location.href='?m=dictionarydevices';");
    }
	
    return $obj;
}


$LMS->InitXajax();
$LMS->RegisterXajaxFunction(
	array(
		'dictionary_devices_add', 
		'dictionary_devices_edit',
		'dictionary_devices_cancel',
		'dictionary_devices_save',
		'dictionary_devices_deleted',
	)
);
$SMARTY->assign('xajax', $LMS->RunXajax());

$SMARTY->assign('action',$action);

$SMARTY->assign('deviceslist',$LMS->getListDictionaryDevices());
$SMARTY->display('dictionarydevices.html');

?>