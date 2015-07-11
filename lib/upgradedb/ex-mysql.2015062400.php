<?php

/*
 *  iNET LMS 
 *
 *  (C) Copyright 2001-2013 LMS Developers
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

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
    array($DB->_dbname,'nodes','authtype'))) 
{

    $DB->Execute("DROP VIEW IF EXISTS vnodes");
    $DB->Execute("DROP VIEW IF EXISTS vmacs");

    $DB->Execute("ALTER TABLE nodes ADD COLUMN authtype tinyint(4) DEFAULT 0 NOT NULL");
    $DB->Execute("CREATE INDEX authtype ON nodes(authtype)");


    $DB->Execute("CREATE OR REPLACE VIEW vnodes AS
	SELECT n.*, m.mac
	FROM nodes n
	LEFT JOIN vnodes_mac m ON (n.id = m.nodeid)");
    
    $DB->Execute("CREATE OR REPLACE VIEW vmacs AS
	SELECT n.*, m.mac, m.id AS macid
	FROM nodes n
	JOIN macs m ON (n.id = m.nodeid)");
}

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015062400', 'dbvex'));

$DB->CommitTrans();

?>
