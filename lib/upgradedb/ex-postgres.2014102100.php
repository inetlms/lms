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

$DB->Execute("ALTER TABLE networks ADD ipnat VARCHAR( 16 ) DEFAULT '';");
$DB->Execute("ALTER TABLE tariffs ADD relief NUMERIC(9,2) NOT NULL DEFAULT '0.00';");

$DB->Execute("DROP VIEW IF EXISTS vnodes;");
$DB->Execute("DROP VIEW IF EXISTS vmacs;");


$DB->Execute("ALTER TABLE nodes ADD blockade SMALLINT DEFAULT 0;");

$DB->Execute("
    CREATE VIEW vnodes AS
    SELECT n.*, m.mac
    FROM nodes n
    LEFT JOIN (SELECT nodeid, array_to_string(array_agg(mac), ',') AS mac
        FROM macs GROUP BY nodeid) m ON (n.id = m.nodeid);
");
$DB->Execute("
CREATE VIEW vmacs AS 
	SELECT n.*, m.mac, m.id AS macid 
	FROM nodes n 
	JOIN macs m ON (n.id = m.nodeid);
");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014102100', 'dbvex'));
$DB->CommitTrans();

?>