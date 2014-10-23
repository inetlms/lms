<?php

/*
 *  iNET LMS
 *
 *  (C) Copyright 2001-2012 LMS Developers
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
 */




$DB->BeginTrans();
/*
if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
	array($DB->_dbname,'networks','ipnat'))) 
	    $DB->Execute("ALTER TABLE networks ADD ipnat VARCHAR( 16 ) NOT NULL DEFAULT '';");
*/

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
	array($DB->_dbname,'documents','div_shortname'))) 
{
    $DB->Execute("ALTER TABLE documents ADD div_shortname TEXT NOT NULL DEFAULT '';");
    $dl = $DB->GetAll('SELECT id, shortname FROM divisions');
    if (!empty($dl))
	foreach ($dl as $division)
	    $DB->Execute('UPDATE documents SET div_shortname = ? WHERE divisionid = ?;',
	    array(($division['shortname'] ? $division['shortname'] : ''),
	    $division['id']));
}

$DB->Execute("UPDATE docrights SET doctype = ? WHERE doctype = ?", array('-128', '-10'));
$DB->Execute("UPDATE documents SET type = ? WHERE type = ?", array('-128', '-10'));


if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'promotionassignments','optional'))) 
	$DB->Execute("ALTER TABLE promotionassignments ADD optional tinyint NOT NULL DEFAULT 0");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'promotionassignments','selectionid'))) 
    $DB->Execute("ALTER TABLE promotionassignments ADD selectionid tinyint NOT NULL DEFAULT 0");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'templates','subject'))) 
    $DB->Execute("ALTER TABLE templates ADD subject varchar(255) NOT NULL DEFAULT ''");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'documents','fullnumber'))) 
{
	$DB->Execute("ALTER TABLE documents ADD fullnumber varchar(50) DEFAULT NULL");
	$DB->Execute("ALTER TABLE documents ADD INDEX fullnumber (fullnumber)");

	$docs = $DB->GetAll('SELECT d.id, cdate, number, template FROM documents d
		JOIN numberplans n ON n.id = d.numberplanid
		WHERE numberplanid <> 0 ORDER BY id');
	
	if (!empty($docs)) {
		include(LIB_DIR . '/common.php');
		foreach ($docs as $doc) {
			$fullnumber = docnumber($doc['number'], $doc['template'], $doc['cdate']);
			$DB->Execute('UPDATE documents SET fullnumber = ? WHERE id = ?',
				array($fullnumber, $doc['id']));
		}
	}
}


if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'events','enddate'))) 
    $DB->Execute("ALTER TABLE events ADD enddate int(11) DEFAULT '0' NOT NULL");


$DB->Execute("ALTER TABLE managementurls CHANGE netdevid netdevid int(11) NULL DEFAULT NULL");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'managementurls','nodeid'))) 
{
	$DB->Execute("ALTER TABLE managementurls ADD nodeid int(11) NULL DEFAULT NULL");
	$DB->Execute("ALTER TABLE managementurls
		ADD CONSTRAINT managementurls_nodeid_fkey FOREIGN KEY (nodeid)
		REFERENCES nodes (id) ON DELETE CASCADE ON UPDATE CASCADE");
}


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014102200', 'dbvex'));
$DB->CommitTrans();

?>