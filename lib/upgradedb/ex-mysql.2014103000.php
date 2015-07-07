<?php

/*
 * LMS version 1.11-git
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
$DB->Execute("
    CREATE TABLE IF NOT EXISTS teleline (
	id int(10) unsigned NOT NULL AUTO_INCREMENT,
	name varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
	description text COLLATE utf8_polish_ci,
	active tinyint(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'netlinks','layer'))) 
    $DB->Execute("ALTER TABLE netlinks ADD layer TINYINT( 1 ) NULL DEFAULT NULL COMMENT 'warstwa sieci, szkielet, dystrybucja, dostep';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'netlinks','teleline'))) 
$DB->Execute("ALTER TABLE netlinks ADD teleline INT NOT NULL DEFAULT 0 COMMENT 'id lini telekomunikacyjnej';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'netlinks','distance'))) 
$DB->Execute("ALTER TABLE netlinks ADD distance INT NOT NULL DEFAULT 0 COMMENT 'długość linku';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'netlinks','distanceoptical'))) 
$DB->Execute("ALTER TABLE netlinks ADD distanceoptical INT NOT NULL DEFAULT 0 COMMENT 'długość optyczna lub fizyczna linku, dla połączeń światłowodowych lub kablowych';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'netlinks','tracttype'))) 
$DB->Execute("ALTER TABLE netlinks ADD tracttype TINYINT(1) DEFAULT NULL COMMENT 'rodzaj traktu';");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014103000', 'dbvex'));

$DB->CommitTrans();

?>
