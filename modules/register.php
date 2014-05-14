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
 *  $Id$
 */

/*
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$LMS->UpdateRegisterData($_POST['name'], $_POST['url'], ($_POST['hidden'] == 1 ? TRUE : FALSE));
	$SESSION->redirect('?m=welcome_new');
}
*/
$layout['pagetitle'] = trans('Register your installation today! ;-)');

function formregister($forms)
{
    global $DB;
    $obj = new xajaxResponse();
    $blad = false;
    $form = $forms['register'];
    
    $obj->script("removeClassid('id_name','alerts');");
    $obj->assign("id_name_alerts","innerHTML","");
    
    $obj->script("removeClassid('id_zip','alerts');");
    $obj->assign("id_zip_alerts","innerHTML","");
    
    $obj->script("removeClassid('id_url','alerts');");
    $obj->assign("id_url_alerts","innerHTML","");
    
    $obj->script("removeClassid('id_email','alerts');");
    $obj->assign("id_email_alerts","innerHTML","");
    
    
    if (empty($form['name'])) {
	$blad = true;
	$obj->script("addClassId('id_name','alerts');");
	$obj->assign("id_name_alerts","innerHTML","Nazwa firmy jest wymagana");
    }
    
    if (!empty($form['zip']) && !check_zip($form['zip'])) {
	$blad = true;
	$obj->script("addClassId('id_zip','alerts');");
	$obj->assign("id_zip_alerts","innerHTML","Błędnie wprowadzono kod pocztowy");
    }
    
    if (!empty($form['email']) && !check_email($form['email'])) {
	$blad = true;
//	$obj->script("addClassId('id_email','alerts');");
	$obj->assign("id_email_alerts","innerHTML","Błędnie wprowadzono adres skrzynki pocztowej");
    } elseif (!empty($form['newsletter']) && empty($form['email'])) {
	$blad = true;
//	$obj->script("addClassId('id_email','alerts');");
	$obj->assign("id_email_alerts","innerHTML","Proszę wprowadzić adres skrzynki pocztowej");
    }
    
    if (!$blad) {
	$obj->assign("id_info","innerHTML","<br>Proszę czekać, dane są przesyłane<br>");
	$obj->script("xajax_sendregister(xajax.getFormValues('register'));");
    }
    
    return $obj;
}
function sendregister($forms)
{
    global $DB,$LMS, $layout;
    $obj = new xajaxResponse();
    $form = $forms['register'];
    $form['lmsversion'] = $layout['lmsvr'];
    
    if ($form['infocustomer'])
	$form['customercount'] = $DB->GetOne('SELECT COUNT(id) FROM customers WHERE status=3 AND (type=0 OR type=1) AND deleted=0;');
    else
	$form['customercount'] = 0;
    
    $reg = $LMS->UpdateRegisterData($form);

    if (empty($reg)) {
	$obj->assign("id_info","innerHTML","Wystąpił problem z rejestracją Państwa instalacji iNET LMS :(<br>Proszę spróbować ponownie.");
	
	$obj->script("document.getElementById('id_info').style.color='red';");
	
    } else {
	$obj->assign("id_info","innerHTML","Informacje o Państwa instalacji iNET LMS zostały pomyślnie przesłane :) !!!");
	$obj->script("document.getElementById('id_info').style.color='blue';");
	
	$DB->Execute('DELETE FROM dbinfo WHERE keytype LIKE ? ;',array('inetlms_regdata_%'));
	if (!$LMS->checkregister()) {
	    $DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_registers',1));
	    $DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_last_update',time()));
	    $DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_version',$layout['lmsvr']));
	}
	
	$DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_regdata_name',$form['name']));
	$DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_regdata_address',$form['address']));
	$DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_regdata_city',$form['city']));
	$DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_regdata_zip',$form['zip']));
	$DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_regdata_url',$form['url']));
	$DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_regdata_email',$form['email']));
	$DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_regdata_internet',$form['internet']));
	$DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_regdata_phone',$form['phone']));
	$DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_regdata_tv',$form['tv']));
	$DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_regdata_infocustomer',$form['infocustomer']));
	$DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_regdata_hide',$form['hide']));
	$DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_regdata_newsletter',$form['newsletter']));
	
	
	$obj->assign("id_form_data","innerHTML","");
	$obj->assign("id_send_button","innerHTML","");
    }
    return $obj;
}


$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array('formregister','sendregister'));
$SMARTY->assign('xajax',$LMS->RunXajax());

$SMARTY->assign('uiid', $LMS->GetUniqueInstallationID());
$SMARTY->assign('regdata', $LMS->GetRegisterData());
$SMARTY->assign('registers',$LMS->CheckRegister());
$SMARTY->display('register.html');

?>
