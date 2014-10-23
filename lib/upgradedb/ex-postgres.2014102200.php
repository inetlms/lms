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

if (!$DB->GetOne("SELECT 1 FROM information_schema.columns WHERE table_name = ? AND column_name= ?;",array('customers','url')))
    $DB->Execute("ALTER TABLE customers ADD url VARCHAR( 255 ) DEFAULT NULL ;");



if (!$DB->GetOne("SELECT 1 FROM information_schema.columns WHERE table_name = ? AND column_name= ?;",array('documents','div_shortname')))
{
	$DB->Execute("ALTER TABLE documents ADD div_shortname TEXT NOT NULL DEFAULT ''");
	$dl = $DB->GetAll("SELECT id, shortname FROM divisions");
	
	if (!empty($dl))
		foreach ($dl as $division)
			$DB->Execute("UPDATE documents SET div_shortname = ?
				WHERE divisionid = ?", array(
				($division['shortname'] ? $division['shortname'] : ''),
				$division['id']));
}

$DB->Execute("UPDATE docrights SET doctype = ? WHERE doctype = ?", array('-128', '-10'));
$DB->Execute("UPDATE documents SET type = ? WHERE type = ?", array('-128', '-10'));


if (!$DB->GetOne("SELECT 1 FROM information_schema.columns WHERE table_name = ? AND column_name= ?;",array('promotionassignments','optional')))
	$DB->Execute("ALTER TABLE promotionassignments ADD optional smallint NOT NULL DEFAULT 0");

if (!$DB->GetOne("SELECT 1 FROM information_schema.columns WHERE table_name = ? AND column_name= ?;",array('promotionassignments','selectionid')))
	$DB->Execute("ALTER TABLE promotionassignments ADD selectionid smallint NOT NULL DEFAULT 0");


if (!$DB->GetOne("SELECT 1 FROM information_schema.columns WHERE table_name = ? AND column_name= ?;",array('templates','subject')))
	$DB->Execute("ALTER TABLE templates ADD subject varchar(255) NOT NULL DEFAULT ''");


if (!$DB->GetOne("SELECT 1 FROM information_schema.columns WHERE table_name = ? AND column_name= ?;",array('documents','fullnumber')))
{
	$DB->Execute("ALTER TABLE documents ADD fullnumber varchar(50) DEFAULT NULL");
	$DB->Execute("CREATE INDEX documents_fullnumber_idx ON documents (fullnumber)");
	
	$docs = $DB->GetAll('SELECT d.id, cdate, number, template FROM documents d
		JOIN numberplans n ON n.id = d.numberplanid
		WHERE numberplanid <> 0 ORDER BY id');
	if (!empty($docs)) 
	{
		include(LIB_DIR . '/common.php');
		foreach ($docs as $doc) {
			$fullnumber = docnumber($doc['number'], $doc['template'], $doc['cdate']);
			$DB->Execute('UPDATE documents SET fullnumber = ? WHERE id = ?',
				array($fullnumber, $doc['id']));
		}
	}
}


if (!$DB->GetOne("SELECT 1 FROM information_schema.columns WHERE table_name = ? AND column_name= ?;",array('events','enddate')))
	$DB->Execute("ALTER TABLE events ADD enddate integer NOT NULL DEFAULT 0");


$DB->Execute("ALTER TABLE managementurls ALTER COLUMN netdevid DROP NOT NULL");

if (!$DB->GetOne("SELECT 1 FROM information_schema.columns WHERE table_name = ? AND column_name= ?;",array('managementurls','nodeid')))
{
	$DB->Execute("ALTER TABLE managementurls ADD nodeid integer DEFAULT NULL");
	$DB->Execute("ALTER TABLE managementurls
		ADD CONSTRAINT managementurls_nodeid_fkey FOREIGN KEY (nodeid)
		REFERENCES nodes (id) ON DELETE CASCADE ON UPDATE CASCADE");
}


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014102200', 'dbvex'));
$DB->CommitTrans();

?>