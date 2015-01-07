<?php

/*
 *  iNET LMS
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
 *  $Id$ 2015 by Sylwester Kondracki
 */

$layout['title'] = 'Słownik - Powody faktur korygujących';

function dictionary_cnote_add()
{
    global $SMARTY;
    $obj = new xajaxresponse();
    $obj->assign('id_edit_dictionary_cnote','innerHTML',$SMARTY->fetch('dictionarycnoteedit.html'));
    return $obj;
}

function dictionary_cnote_edit($id)
{
    global $SMARTY,$DB;
    $obj = new xajaxResponse();
    
    $cnoteinfo = $DB->GetRow('SELECT * FROM dictionary_cnote WHERE id = ? '.$DB->Limit(1).';',array($id));
    $SMARTY->assign('cnoteinfo',$cnoteinfo);
    $obj->assign('id_edit_dictionary_cnote','innerHTML',$SMARTY->fetch('dictionarycnoteedit.html'));
    return $obj;
}

function dictionary_cnote_cancel()
{
    $obj = new xajaxResponse();
    $obj->assign('id_edit_dictionary_cnote','innerHTML','');
    return $obj;
}

function dictionary_cnote_save($forms=NULL)
{
    global $DB,$SMARTY;
    $form = $forms['editdictionarycnote'];
    $obj = new xajaxResponse();
    $obj->script("removeClassId('id_edit_dictionary_cnote_type','alerts');");
    $obj->assign("id_alerts","innerHTML","");
    
    $blad = false;
    
    if (empty($form['type'])) {
	$blad = true;
	$obj->script("addClassId('id_edit_dictionary_cnote_type','alerts');");
    }
    
    if (!$blad && !$form['id']) {

	$tmp = $DB->GetOne('SELECT 1 FROM dictionary_cnote WHERE name = ? '.$DB->Limit(1).' ;',array($form['type']));
	
	if ($tmp)
	    $obj->assign("id_alerts","innerHTML","W słowniku już istnieje taki wpis");
	else {
	    $DB->Execute('INSERT INTO dictionary_cnote (name,description) VALUES (?,?) ;',
		array(
		    $form['type'],
		    ($form['description'] ? $form['description'] : NULL)
		)
	    );
	    $obj->script("self.location.href='?m=dictionarycnote';");
	}
	
    }
    
    if (!$blad && $form['id']) {
	
	$tmp = $DB->GetOne('SELECT 1 FROM dictionary_cnote WHERE UPPER(name) = ? AND id!=? '
		.$DB->Limit(1).' ;',array(strtoupper($form['type']),$form['id']));
	
	if ($tmp)
	    $obj->assign("id_alerts","innerHTML","W słowniku już istnieje taki wpis");
	else {
	    $DB->Execute('UPDATE dictionary_cnote SET name=?, description=? WHERE id=? ;',
		array(
		    $form['type'],
		    ($form['description'] ? $form['description'] : NULL),
		    $form['id']
		)
	    );
	    $obj->script("self.location.href='?m=dictionarycnote';");
	}
    }
    
    return $obj;
}

function dictionary_cnote_deleted($id = NULL)
{
    global $DB;
    $obj = new xajaxResponse();
    
    if ($id) {
	
	$DB->Execute('DELETE FROM dictionary_cnote WHERE id = ? ;',array($id));
	$obj->script("self.location.href='?m=dictionarycnote';");
    }
	
    return $obj;
}


$LMS->InitXajax();
$LMS->RegisterXajaxFunction(
	array(
		'dictionary_cnote_add', 
		'dictionary_cnote_edit',
		'dictionary_cnote_cancel',
		'dictionary_cnote_save',
		'dictionary_cnote_deleted',
	)
);
$SMARTY->assign('xajax', $LMS->RunXajax());

$SMARTY->assign('action',$action);

$SMARTY->assign('cnotelist',$LMS->getListDictionaryCnote());
$SMARTY->display('dictionarycnote.html');

?>