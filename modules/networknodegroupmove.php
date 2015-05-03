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

$from = !empty($_GET['from']) ? intval($_GET['from']) : 0;
$to = !empty($_GET['to']) ? intval($_GET['to']) : 0;

if($DB->GetOne('SELECT id FROM networknodegroups WHERE id = ?', array($from)) 
	&& $DB->GetOne('SELECT id FROM networknodegroups WHERE id = ?', array($to)) 
	&& $_GET['is_sure'] == 1)
{
	$DB->BeginTrans();
	
	$DB->Execute('INSERT INTO networknodeassignments (networknodegroupid, networknodeid)
			SELECT ?, networknodeid 
			FROM networknodeassignments a
			JOIN networknode n ON (a.networknodeid = n.id)
	                WHERE a.networknodegroupid = ?
			AND NOT EXISTS (SELECT 1 FROM networknodeassignments na
				WHERE na.networknodeid = a.networknodeid AND na.networknodegroupid = ?)',
			array($to, $from, $to));
	
	$DB->Execute('DELETE FROM networknodeassignments WHERE networknodegroupid = ?', array($from));

        $DB->CommitTrans();
	
	$SESSION->redirect('?m=networknodegroupinfo&id='.$to);
}
else
	header('Location: ?'.$SESSION->get('backto'));

?>
