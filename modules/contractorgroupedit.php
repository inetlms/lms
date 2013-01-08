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
 *  $Id: v 1.00 2012/12/20 22:01:35 Sylwester Kondracki Exp $
 */

if(!$LMS->contractorgroupExists($_GET['id']))
{
	$SESSION->redirect('?m=contractorgrouplist');
}

$contractorgroup = $LMS->ContractorgroupGet($_GET['id']);

$layout['pagetitle'] = trans('Group Edit: $a', $contractorgroup['name']);

if(isset($_POST['contractorgroupedit']))
{

	$contractorgroupedit = $_POST['contractorgroupedit'];
	foreach($contractorgroupedit as $key => $value)
		$contractorgroupedit[$key] = trim($value);

	$contractorgroupedit['id'] = (int)$_GET['id'];

	if($contractorgroupedit['name'] == '') $error['name'] = trans('Group name required!');
	elseif(strlen($contractorgroupedit['name']) > 255) $error['name'] = trans('Group name is too long!');
	elseif(!preg_match('/^[._a-z0-9-]+$/i', $contractorgroupedit['name'])) $error['name'] = trans('Invalid chars in group name!');
	elseif(($id = $LMS->contractorgroupGetId($contractorgroupedit['name'])) && $id != $contractorgroupedit['id'])
		$error['name'] = trans('Group with name $a already exists!',$customergroupedit['name']);

	if(!$error)
	{
		$DB->BeginTrans();
		$LMS->contractorgroupUpdate($contractorgroupedit);
		$DB->Execute('DELETE FROM contractorassignments WHERE contractorgroupid = ?',array(intval($_GET['id'])));

		if (!empty($_POST['selected'])) {
		    foreach($_POST['selected'] as $idx => $name)
			$DB->Execute('INSERT INTO contractorassignments (contractorgroupid, customerid) VALUES (?, ?) ;',
			    array(intval($_GET['id']),intval($idx)));
		}

		$DB->CommitTrans();
		$SESSION->redirect('?m=contractorgrouplist');
	}

	$contractorgroup['description'] = $contractorgroupedit['description'];
	$contractorgroup['name'] = $contractorgroupedit['name'];
	
	
}
else
{
    $contractorgroup['groups'] = $DB->GetAllByKey('
	
	SELECT c.id, '.$DB->Concat('c.lastname', "' '", 'c.name') . ' AS name 
	FROM contractorassignments, contractorview c 
	WHERE c.id = customerid AND contractorgroupid = ?
    ', 'id', array($_GET['id']));
}

$available = $DB->GetAllByKey('
    SELECT id, '.$DB->Concat('c.lastname', "' '", 'c.name') . ' AS name 
    FROM contractorview c WHERE deleted = 0 '
    .(!empty($contractorgroup['groups']) ? ' OR id IN ('.implode(',',array_keys($contractorgroup['groups'])).')' : '')
    .' ORDER BY name ','id',array(intval($_GET['id'])));

$SESSION->save('backto', $_SERVER['QUERY_STRING']);
$SMARTY->assign('contractorgroup',$contractorgroup);
$SMARTY->assign('error', $error);
$SMARTY->assign('available', $available);
$SMARTY->display('contractorgroupedit.html');

?>
