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

$DB->Execute("
    CREATE TABLE dictionary_devices_client (
	id INT(10) unsigned not null auto_increment,
	type varchar(128) default null,
	description text default null,
	primary key(id)
) ENGINE=InnoDB;
");

$DB->Execute("DROP VIEW IF EXISTS vnodes ;");
$DB->Execute("DROP VIEW IF EXISTS vmacs;");


if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
	array($DB->_dbname,'nodes','access_from'))) 
	    $DB->Execute("ALTER TABLE nodes ADD access_from INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'rama czasowa od kiedy komp ma byc autoryzowany';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
	array($DB->_dbname,'nodes','access_to'))) 
	    $DB->Execute("ALTER TABLE nodes ADD access_to INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'rama czasowa do kiedy komp moze byc autoryzowany';");
	
if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
	array($DB->_dbname,'nodes','typeofdevice'))) 
	    $DB->Execute("ALTER TABLE nodes ADD typeofdevice INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'rodzaj urządzenia';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
	array($DB->_dbname,'nodes','producer'))) 
		$DB->Execute("ALTER TABLE nodes ADD producer VARCHAR( 64 ) NULL DEFAULT NULL COMMENT 'numer seryjny';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
	array($DB->_dbname,'nodes','model'))) 
		$DB->Execute("ALTER TABLE nodes ADD model VARCHAR( 64 ) NULL DEFAULT NULL COMMENT 'numer seryjny';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
	array($DB->_dbname,'nodes','sn'))) 
		$DB->Execute("ALTER TABLE nodes ADD sn VARCHAR( 64 ) NULL DEFAULT NULL COMMENT 'numer seryjny';");


$DB->Execute("
CREATE VIEW IF NOT EXISTS vnodes AS 
	SELECT n.*, m.mac 
	FROM nodes n 
	LEFT JOIN vnodes_mac m ON (n.id = m.nodeid);
");
$DB->Execute("
CREATE VIEW IF NOT EXISTS vmacs AS 
	SELECT n.*, m.mac, m.id AS macid 
	FROM nodes n 
	JOIN macs m ON (n.id = m.nodeid);
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014040603', 'dbvex'));
$DB->CommitTrans();

?>