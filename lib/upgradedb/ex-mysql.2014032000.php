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
 * ex-mysql.2014032000.php
 */



$DB->BeginTrans();

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'networknode','available'))) 
	$DB->Execute("ALTER TABLE networknode ADD available_surface TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'możliwość udostępnienia powierzchni obcym';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'networknode','eu'))) 
	$DB->Execute("ALTER TABLE networknode ADD eu TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'współfinansowany przez EU';");


$DB->Execute("
CREATE TABLE IF NOT EXISTS uke (
    id int(10) unsigned NOT NULL AUTO_INCREMENT,
    report_type varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
    divisionid int(11) NOT NULL DEFAULT '0',
    reportyear smallint(6) NOT NULL DEFAULT '0' COMMENT 'raport za rok',
    divname varchar(128) COLLATE utf8_polish_ci DEFAULT NULL,
    ten varchar(16) COLLATE utf8_polish_ci DEFAULT NULL,
    regon varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
    rpt varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
    rjst varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
    krs varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
    states varchar(100) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'województwo',
    districts varchar(100) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'powiat',
    boroughs varchar(100) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'gmina',
    city varchar(100) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'miejscowość',
    zip varchar(6) COLLATE utf8_polish_ci DEFAULT NULL,
    street varchar(100) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'adres , ulica',
    location_city varchar(10) COLLATE utf8_polish_ci DEFAULT NULL,
    location_street varchar(10) COLLATE utf8_polish_ci DEFAULT NULL,
    location_house varchar(10) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'numer domu',
    location_flat varchar(10) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'numer mieszkania',
    kod_terc int(11) NOT NULL DEFAULT '0',
    kod_simc int(11) NOT NULL DEFAULT '0',
    kod_ulic int(11) NOT NULL DEFAULT '0',
    url varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
    email varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
    accept1 tinyint(1) NOT NULL DEFAULT '0',
    accept2 tinyint(1) NOT NULL DEFAULT '0',
    accept3 tinyint(1) NOT NULL DEFAULT '0',
    accept4 tinyint(1) NOT NULL DEFAULT '0',
    accept5 tinyint(1) NOT NULL DEFAULT '0',
    accept6 tinyint(1) NOT NULL DEFAULT '0',
    contact_name varchar(128) COLLATE utf8_polish_ci DEFAULT NULL,
    contact_lastname varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
    contact_phone varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
    contact_fax varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
    contact_email varchar(255) COLLATE utf8_polish_ci DEFAULT '',
    closed tinyint(1) NOT NULL DEFAULT '0',
    passwd varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
    description text COLLATE utf8_polish_ci,
PRIMARY KEY (id)
) ENGINE=InnoDB;
");

$DB->Execute("
CREATE TABLE IF NOT EXISTS uke_data (
    id int(11) NOT NULL AUTO_INCREMENT,
    rapid int(11) NOT NULL DEFAULT '0' COMMENT 'id raportu',
    mark varchar(4) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'oznaczenie',
    markid varchar(255) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'identyfikator',
    useraport tinyint(1) NOT NULL DEFAULT '1' COMMENT 'czy rekord uwzględnić w raporcie',
    data text COLLATE utf8_polish_ci,
    PRIMARY KEY (id),
    KEY rapid (rapid,mark)
) ENGINE=InnoDB;
");



$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014032000', 'dbvex'));
$DB->CommitTrans();

?>