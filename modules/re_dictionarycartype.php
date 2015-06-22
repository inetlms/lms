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

$layout['pagetitle'] = 'Słownik - Rodzaj pojazdów';

if (isset($_GET['delete']) && isset($_GET['is_sure']) && isset($_GET['id']) && $_GET['is_sure'] == '1' && !empty($_GET['id'])) {
    $RE->deletedDictionaryCarType($_GET['id']);
}
$cartype = NULL;

$akcja = 'lista';

function add_car_type() {
    global $SMARTY,$akcja;
    $obj = new xajaxResponse();
    
    $SMARTY->assign('akcja','add');
    $SMARTY->assign('cartype',NULL);
    $obj->assign('id_edit_car_type','innerHTML',$SMARTY->fetch('re_dictionarycartype.html'));
    $obj->script("document.getElementById('id_name').focus();");
    return $obj;
}

function edit_car_type($id) {
    global $SMARTY,$akcja,$RE;
    $obj = new xajaxResponse();
    
    $SMARTY->assign('akcja','edit');
    $SMARTY->assign('cartype',$RE->getDictionaryCarType($id));
    $obj->assign('id_edit_car_type','innerHTML',$SMARTY->fetch('re_dictionarycartype.html'));
    $obj->script("document.getElementById('id_name').focus();");
    
    return $obj;
}

function save_car_type($forms) {
    global $DB,$SMARTY,$action,$RE;
    $obj = new xajaxResponse();
    $blad = false;
    
    $form = $forms['cartype'];
    
    $obj->script("removeClassid('id_name','alerts');");
    $obj->assign("id_name_alerts","innerHTML","");
    
    if (empty($form['name'])) {
	$blad = true;
	$obj->script("addClassId('id_name','alerts');");
	$obj->assign("id_name_alerts","innerHTML","nazwa jest wymagana");
	$obj->script("document.getElementById('id_name').focus();");
    } elseif ($RE->CheckIssetDictionaryCarType($form['name'],($form['id'] ? $form['id'] : NULL))) {
	$blad = true;
	$obj->script("addClassId('id_name','alerts');");
	$obj->assign("id_name_alerts","innerHTML","podana nazwa jest już w słowniku");
	$obj->script("document.getElementById('id_name').focus();");
    }
    
    if (!$blad) {
	if (isset($form['id']) && !empty($form['id'])) {
	    $RE->updateDictionaryCarType($form);
	} else {
	    $RE->addDictionaryCarType($form);
	}
	
	$obj->script("self.location.href='?m=re_dictionarycartype';");
    }
    return $obj;
}

function set_active_car_type($id) {
    global $DB;
    $obj = new xajaxResponse();
	$active = $DB->GetOne('SELECT active FROM re_dictionary_cartype WHERE id = ? '.$DB->limit(1).' ;',array($id));
	
	if($active) {
	    $obj->script("addClassId('id_carlist_tr_".$id."','blend');");
	    $obj->script("document.getElementById('id_img_active_".$id."').src='img/noaccess.gif';");
	    $DB->Execute('UPDATE re_dictionary_cartype SET active = 0 WHERE id = ?;',array($id));
	} else {
	    $obj->script("removeClassId('id_carlist_tr_".$id."','blend');");
	    $obj->script("document.getElementById('id_img_active_".$id."').src='img/access.gif';");
	    $DB->Execute('UPDATE re_dictionary_cartype SET active = 1 WHERE id = ?;',array($id));
	}
    
    return $obj;
}

$carlist = $RE->GetDictionaryCarTypeList();

$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array('add_car_type','edit_car_type','save_car_type','set_active_car_type'));
$SMARTY->assign('xajax',$LMS->RunXajax());

$SMARTY->assign('akcja',$akcja);
$SMARTY->assign('carlist',$carlist);
$SMARTY->display('re_dictionarycartype.html');
?>
