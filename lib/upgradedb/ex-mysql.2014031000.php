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
 * ex-mysql.2014031000.php
 */



$DB->BeginTrans();

$DB->Execute("
CREATE TABLE IF NOT EXISTS networknode (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
    type tinyint(1) NOT NULL DEFAULT '1',
    states varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
    districts varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
    boroughs varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
    city varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
    street varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
    zip varchar(6) COLLATE utf8_polish_ci DEFAULT NULL,
    location_city int(11) DEFAULT NULL,
    location_street int(11) DEFAULT NULL,
    location_house varchar(8) COLLATE utf8_polish_ci DEFAULT NULL,
    location_flat varchar(8) COLLATE utf8_polish_ci DEFAULT NULL,
    cadastral_parcel varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
    longitude decimal(10,6) DEFAULT NULL,
    latitude decimal(10,6) DEFAULT NULL,
    backbone_layer tinyint(1) DEFAULT NULL,
    distribution_layer tinyint(1) DEFAULT NULL,
    access_layer tinyint(1) DEFAULT NULL,
    sharing tinyint(1) NOT NULL DEFAULT '0',
    buildingtype tinyint(1) DEFAULT NULL,
    collocationid int(11) NOT NULL DEFAULT '0',
    description text COLLATE utf8_polish_ci,
    cdate int(11) unsigned NOT NULL DEFAULT '0',
    mdate int(11) unsigned NOT NULL DEFAULT '0',
    cuser int(11) unsigned NOT NULL DEFAULT '0',
    muser int(11) unsigned NOT NULL DEFAULT '0',
    deleted tinyint(1) NOT NULL DEFAULT '0',
    disabled tinyint(1) NOT NULL DEFAULT '0',
    room_area int(10) unsigned NOT NULL DEFAULT '0',
    room_area_empty int(10) unsigned NOT NULL DEFAULT '0',
    technical_floor tinyint(1) NOT NULL DEFAULT '0',
    technical_ceiling tinyint(1) NOT NULL DEFAULT '0',
    air_conditioning tinyint(1) NOT NULL DEFAULT '0',
    telecommunication tinyint(1) NOT NULL DEFAULT '1',
    instmast tinyint(1) NOT NULL DEFAULT '0',
    instofanten tinyint(1) NOT NULL DEFAULT '0',
    foreign_entity smallint(5) unsigned NOT NULL DEFAULT '0',
    entity_fiber_end smallint(5) unsigned NOT NULL DEFAULT '0',
    sharing_fiber tinyint(4) NOT NULL DEFAULT '0',
    dc12 tinyint(1) NOT NULL DEFAULT '0',
    dc24 tinyint(1) NOT NULL DEFAULT '0',
    dc48 tinyint(1) NOT NULL DEFAULT '0',
    ac230 tinyint(1) NOT NULL DEFAULT '1',
    height_anten smallint(5) unsigned NOT NULL DEFAULT '0',
    service_broadband tinyint(1) NOT NULL DEFAULT '1',
    service_voice tinyint(1) NOT NULL DEFAULT '0',
    service_other text COLLATE utf8_polish_ci,
    total_bandwidth int(10) unsigned NOT NULL DEFAULT '0',
    bandwidth_broadband int(10) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    KEY deleted (deleted,disabled),
    KEY collocationid (collocationid)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
");

$DB->Execute("
CREATE TABLE IF NOT EXISTS upkeep (
    id int(11) NOT NULL AUTO_INCREMENT,
    owner varchar(32) COLLATE utf8_polish_ci NOT NULL,
    ownerid int(11) NOT NULL DEFAULT '0',
    value decimal(9,2) NOT NULL DEFAULT '0.00',
    sumvalue decimal(9,2) NOT NULL DEFAULT '0.00',
    periods int(11) NOT NULL DEFAULT '0',
    name varchar(255) COLLATE utf8_polish_ci NOT NULL,
    description text COLLATE utf8_polish_ci,
    fromdate int(11) NOT NULL DEFAULT '0' COMMENT 'naliczaj od',
    todate int(11) NOT NULL DEFAULT '0' COMMENT 'naliczaj do',
    cid int(11) NOT NULL DEFAULT '0',
    mid int(11) NOT NULL DEFAULT '0',
    cdate int(11) NOT NULL DEFAULT '0',
    mdate int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    KEY owner (owner,ownerid)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci COMMENT='koszty utrzymania';
");


$DB->Execute("
CREATE TABLE IF NOT EXISTS uploadfiles (
    id int(10) unsigned NOT NULL AUTO_INCREMENT,
    section varchar(20) COLLATE utf8_polish_ci DEFAULT NULL,
    ownerid int(11) DEFAULT NULL,
    description text COLLATE utf8_polish_ci,
    filename varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
    filetype varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
    filesize bigint(20) DEFAULT NULL,
    filemd5sum varchar(40) COLLATE utf8_polish_ci DEFAULT NULL,
    filenamesave varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
    cdate int(11) DEFAULT NULL,
    userid int(11) DEFAULT NULL,
    PRIMARY KEY (id),
    KEY section (section,ownerid)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
");

$DB->Execute("ALTER TABLE netdevices ADD networknodeid INT UNSIGNED NOT NULL DEFAULT '0';");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014031000', 'dbvex'));
$DB->CommitTrans();

?>