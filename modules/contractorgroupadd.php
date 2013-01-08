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


if(isset($_POST['contractorgroupadd']))
{
	$contractorgroupadd = $_POST['contractorgroupadd'];
	
	foreach($contractorgroupadd as $key => $value)
		$contractorgroupadd[$key] = trim($value);

	if($contractorgroupadd['name']=='' && $contractorgroupadd['description']=='')
	{
		$SESSION->redirect('?m=contractorgrouplist');
	}

	if($contractorgroupadd['name'] == '') $error['name'] = trans('Group name required!');
	elseif(strlen($contractorgroupadd['name']) > 255) $error['name'] = trans('Group name is too long!');
	elseif(!preg_match('/^[._a-z0-9-]+$/i', $contractorgroupadd['name']))
		$error['name'] = trans('Invalid chars in group name!');
	elseif($LMS->contractorgroupGetId($contractorgroupadd['name']))
		$error['name'] = trans('Group with name $a already exists!',$contractorgroupadd['name']);

	if(!$error)
	{
		$DB->BeginTrans();
		$gid = $LMS->contractorgroupAdd($contractorgroupadd);
		
		$DB->Execute('DELETE FROM contractorassignments WHERE contractorgroupid = ?',array(intval($gid)));

		if (!empty($_POST['selected'])) {
		    foreach($_POST['selected'] as $idx => $name)
			$DB->Execute('INSERT INTO contractorassignments (contractorgroupid, customerid) VALUES (?, ?) ;',
			    array(intval($gid),intval($idx)));
		}
		$DB->CommitTrans();
		$SESSION->redirect('?m=contractorgroupedit&id='.$gid);
	}
	$SMARTY->assign('error',$error);
	$SMARTY->assign('contractorgroupadd',$contractorgroupadd);
}

$layout['pagetitle'] = trans('New Group Contractor');
$SMARTY->display('contractorgroupadd.html');

?>
