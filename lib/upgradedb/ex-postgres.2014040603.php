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

$DB->Execute("CREATE SEQUENCE dictionary_devices_client_id_seq;");
$DB->Execute("
    CREATE TABLE dictionary_devices_client (
	id INTEGER default nextval('dictionary_devices_client_id_seq'::text) NOT NULL,
	type varchar(128) default null,
	description text default null,
	primary key(id));
");

$DB->Execute("DROP VIEW vnodes ;");
$DB->Execute("DROP VIEW vmacs;");


$DB->Execute("ALTER TABLE nodes ADD access_from INTEGER DEFAULT 0;");
$DB->Execute("ALTER TABLE nodes ADD access_to INTEGER DEFAULT 0;");
$DB->Execute("ALTER TABLE nodes ADD typeofdevice INTEGER DEFAULT 0;");
$DB->Execute("ALTER TABLE nodes ADD producer VARCHAR( 64 ) DEFAULT NULL;");
$DB->Execute("ALTER TABLE nodes ADD model VARCHAR( 64 ) DEFAULT NULL;");
$DB->Execute("ALTER TABLE nodes ADD sn VARCHAR( 64 ) DEFAULT NULL;");


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


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014040603', 'dbvex'));
$DB->CommitTrans();

?>