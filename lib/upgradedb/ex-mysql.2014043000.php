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


if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','devtype'))) 
    $DB->Execute('ALTER TABLE netdevices ADD devtype TINYINT( 1 ) NOT NULL DEFAULT 1 ;'); // 0-pasywne 1-aktywne

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','managed'))) 
    $DB->Execute('ALTER TABLE netdevices ADD managed TINYINT(1) NOT NULL DEFAULT 1 ;'); // czy urządzenie jest zarządzalne

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','sharing'))) 
    $DB->Execute('ALTER TABLE netdevices ADD sharing TINYINT( 1 ) NOT NULL DEFAULT 0 ;'); // czy są udostępniane porty (interfejsy) dla innych ISP

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','modular'))) 
    $DB->Execute('ALTER TABLE netdevices ADD modular TINYINT( 1 ) NOT NULL DEFAULT 0 ;'); // czy urz. ma budowę modułową, 

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','backbone_layer'))) 
    $DB->Execute('ALTER TABLE netdevices ADD backbone_layer TINYINT( 1 ) NOT NULL DEFAULT 0 ;');	// warstwa szkieletowa

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','distribution_layer'))) 
    $DB->Execute('ALTER TABLE netdevices ADD distribution_layer TINYINT( 1 ) NOT NULL DEFAULT 1 ;');	// warstwa dystrybucyjna

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','access_layer'))) 
    $DB->Execute('ALTER TABLE netdevices ADD access_layer TINYINT( 1 ) NOT NULL DEFAULT 1 ;');	// warstwa dostępowa

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','typeofdevice'))) 
    $DB->Execute('ALTER TABLE netdevices ADD typeofdevice INT UNSIGNED NOT NULL DEFAULT 0;');	// rodzaj urządzenia


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014043000', 'dbvex'));
$DB->CommitTrans();

?>