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

if(isset($_POST['nodegroupadd']))
{
	$nodegroupadd = $_POST['nodegroupadd'];
	
	foreach($nodegroupadd as $key => $value)
		$nodegroupadd[$key] = trim($value);

	if($nodegroupadd['name']=='' && $nodegroupadd['description']=='')
	{
		$SESSION->redirect('?m=networknodegrouplist');
	}
	
	if($nodegroupadd['name'] == '')
		$error['name'] = 'Nazwa grupy jest wymagana';
	
	elseif(strlen($nodegroupadd['name']) > 128)
		$error['name'] = 'Nazwa grupy jest zadługa, max 128 znaków';
	
	elseif(!preg_match('/^[._a-z0-9-]+$/i', $nodegroupadd['name']))
		$error['name'] = 'Niedozwolone znaki w nazwie grupy';
	
	elseif($DB->GetOne('SELECT 1 FROM networknodegroups WHERE UPPER(name) = ?', array(strtoupper($nodegroupadd['name']))))
		$error['name'] = 'Podana nazwa już istnieje';

	if(!$error)
	{
		$DB->Execute('INSERT INTO networknodegroups (name, description)
				VALUES (?, ?)', 
				array($nodegroupadd['name'],
					($nodegroupadd['description'] ?  $nodegroupadd['description'] : NULL),
				));
	
		if (isset($nodegroupadd['reuse'])) 
		{
			unset($nodegroupadd);
			$nodegroupadd['reuse'] = 1;
			$SMARTY->assign('nodegroupadd',$nodegroupadd);
			$SMARTY->display('networknodegroupadd.html');
		} 

		$id = $DB->GetLastInsertID('networknodegroups');
		$SESSION->redirect('?m=networknodegroupinfo&id='.$id);
	}
	
	$SMARTY->assign('error',$error);
	$SMARTY->assign('nodegroupadd',$nodegroupadd);
}

$layout['pagetitle'] = 'Nowa grupa dla węzła';

$SMARTY->display('networknodegroupadd.html');

?>
