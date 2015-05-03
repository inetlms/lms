<?php

/*
 * LMS version 1.11-git
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

$DB->Execute("
CREATE TABLE IF NOT EXISTS nastypes (
    	id int(11) NOT NULL auto_increment,
	name varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (id),
	UNIQUE KEY name (name)
) ENGINE=MyISAM");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'nodes','nas'))) 
$DB->Execute("ALTER TABLE nodes ADD nas tinyint(1) NOT NULL DEFAULT '0'");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','shortname'))) 
$DB->Execute("ALTER TABLE netdevices ADD shortname varchar(32) NOT NULL DEFAULT ''");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','nastype'))) 
$DB->Execute("ALTER TABLE netdevices ADD nastype int(11) NOT NULL DEFAULT '0'");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','clients'))) 
$DB->Execute("ALTER TABLE netdevices ADD clients int(11) NOT NULL DEFAULT '0'");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','secret'))) 
$DB->Execute("ALTER TABLE netdevices ADD secret varchar(60) NOT NULL DEFAULT ''");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','community'))) 
$DB->Execute("ALTER TABLE netdevices ADD community varchar(50) NOT NULL DEFAULT ''");

$DB->Execute("CREATE VIEW nas AS 
        SELECT no.id, inet_ntoa(no.ipaddr) nasname, nd.shortname, nd.nastype type,
	nd.clients ports, nd.secret, nd.community, nd.description 
        FROM nodes no 
        JOIN netdevices nd ON (no.netdev = nd.id) 
        WHERE no.nas = 1");

$DB->Execute("INSERT INTO nastypes (name) VALUES ('mikrotik_snmp')");
$DB->Execute("INSERT INTO nastypes (name) VALUES ('cisco')");
$DB->Execute("INSERT INTO nastypes (name) VALUES ('computone')");
$DB->Execute("INSERT INTO nastypes (name) VALUES ('livingston')");
$DB->Execute("INSERT INTO nastypes (name) VALUES ('max40xx')");
$DB->Execute("INSERT INTO nastypes (name) VALUES ('multitech')");
$DB->Execute("INSERT INTO nastypes (name) VALUES ('netserver')");
$DB->Execute("INSERT INTO nastypes (name) VALUES ('pathras')");
$DB->Execute("INSERT INTO nastypes (name) VALUES ('patton')");
$DB->Execute("INSERT INTO nastypes (name) VALUES ('portslave')");
$DB->Execute("INSERT INTO nastypes (name) VALUES ('tc')");
$DB->Execute("INSERT INTO nastypes (name) VALUES ('usrhiper')");
$DB->Execute("INSERT INTO nastypes (name) VALUES ('other')");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2009040200', 'dbversion'));

?>
