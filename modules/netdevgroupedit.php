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

if( !($id = $DB->GetOne('SELECT id FROM netdevicesgroups WHERE id = ?', array(intval($_GET['id'])))))
{
	$SESSION->redirect('?m=netdevgrouplist');
}


$nodegroup = $LMS->GetNetDevGroup($id);
$nodes = $LMS->GetNetDevWithoutGroup($id);

$layout['pagetitle'] = 'Edycja grupy : '.$nodegroup['name'];

if(isset($_POST['nodegroup']))
{
	$nodegroupedit = $_POST['nodegroup'];

	foreach($nodegroupedit as $key => $value)
		$nodegroupedit[$key] = trim($value);

	$nodegroupedit['id'] = $_GET['id'];
	
	if($nodegroupedit['name'] == '')
		$error['name'] = trans('Group name required!');
	elseif(strlen($nodegroupedit['name']) > 128)
		$error['name'] = trans('Group name is too long!');
	elseif(!preg_match('/^[._a-z0-9-]+$/i', $nodegroupedit['name']))
		$error['name'] = trans('Invalid chars in group name!');
	elseif( $id != $nodegroupedit['id'])
		$error['name'] = trans('Group with name $a already exists!',$nodegroupedit['name']);

	if(!$error)
	{
		$LMS->DB->Execute('UPDATE netdevicesgroups SET name=?, description=?
				WHERE id=?',
				array($nodegroupedit['name'],
					($nodegroupedit['description'] ? $nodegroupedit['description'] : NULL),
					$nodegroupedit['id']
				));

		$SESSION->redirect('?m=netdevgroupinfo&id='.$id);
	}

	$nodegroup['description'] = $nodegroupedit['description'];
	$nodegroup['name'] = $nodegroupedit['name'];
	$SMARTY->assign('error', $error);
}

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$SMARTY->assign('nodegroup',$nodegroup);
$SMARTY->assign('nodes', $nodes);
$SMARTY->assign('nodescount', sizeof($nodes));
$SMARTY->assign('nodegroups', $LMS->GetNetDevGroupNames());
$SMARTY->display('netdevgroupedit.html');

?>
