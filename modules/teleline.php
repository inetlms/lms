<?php

/*
 * iNET LMS version 1.0.3
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
 *  $Id: Sylwester Kondracki Exp $
 */

$layout['pagetitle'] = 'Linie telekomunikacyjne';

// kasowanie linii
if (isset($_GET['del']) && isset($_GET['is_sure']) && isset($_GET['id']) && intval($_GET['is_sure']) === 1 && intval($_GET['id']))
{
    $id = intval($_GET['id']);
    $LMS->delTeleLine($id);
    $SESSION->redirect('?m=teleline');
}

if (isset($_GET['setaccess']) && isset($_GET['id']) && intval($_GET['id']))
{
    $DB->Execute('UPDATE teleline SET active=? WHERE id = ?;',array(($_GET['setaccess'] ? 1 : 0),intval($_GET['id'])));
}

if (isset($_GET['add'])) $action = 'add';
elseif (isset($_GET['edit'])) $action = 'edit';
elseif (isset($_GET['info'])) $action = 'info';
else $action = 'lista';

if ($action === 'edit' || $action==='info') $lineinfo = $DB->GetRow('SELECT * FROM teleline WHERE id = ? ;',array(intval($_GET['id']))); else $lineinfo = NULL;

if ($action === 'edit' || $action==='add')
{
    function update_teleline($forms)
    {
	global $DB,$LMS,$lineinfo;
	$obj = new xajaxResponse();
	
	    $form = $forms['lineedit'];
	    $blad = false;
	    
	    if (!isset($form['name']) || $form['name']=='') { 
		$blad = true; 
		$obj->script("addClassId('lineedit_name','alerts');"); 
	    }
		else $obj->script("removeClassId('lineedit_name','alerts');");
	    
	    // sprawdzamy czy nazwa czasem nie istnieje
	    
	    if (!$blad)
	    {
		
		if ($DB->GetOne('SELECT 1 FROM teleline WHERE UPPER(name) = ? '.($form['id'] ? 'AND id != '.intval($form['id']) : '').' LIMIT 1;',array(strtoupper($form['name'])))) 
		{
		    $blad = true; 
		    $obj->script("addClassId('lineedit_name','alerts');"); 
		    $obj->script("alert('Linia o podanej nazwie już istnieje.');");
		}
		else $obj->script("removeClassId('lineedit_name','alerts');");
	    } 
	    
	    
	    // zapisujemy dane
	    if (!$blad) {
	        
	        if ($form['id'])
		    $LMS->updateTeleLine($form);
		else
		    $LMS->addTeleLine($form);
		
		if (SYSLOG) {
		    if ($form['id'])
			addlogs('Dodanie nowej linii telekomunikacyjnej '.$form['name'],'e=add;m=netdev;');
		    else
			addlogs('Aktualizacja linii telekomunikacyjnej '.$form['name'],'e=up;m=netdev;');
		}
		
		$obj->assign("teleline_edit","innerHTML","");
		$obj->script("self.location.href='?m=teleline';");
	    }
	
	

	return $obj;
    }
    
    $SMARTY->assign('lineinfo',$lineinfo);
    
    $LMS->InitXajax();
    $LMS->RegisterXajaxFunction(array('update_teleline'));
    $SMARTY->assign('xajax', $LMS->RunXajax());
}

$SMARTY->assign('action',$action);
$teleline = $LMS->getteleline();
$SMARTY->assign('teleline',$teleline);


$SMARTY->display('teleline.html');
?>