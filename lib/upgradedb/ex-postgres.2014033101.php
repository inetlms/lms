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

$DB->Execute("ALTER TABLE networknode ADD available_surface SMALLINT DEFAULT '0';");
$DB->Execute("ALTER TABLE networknode ADD eu SMALLINT DEFAULT '0';");

$DB->Execute("CREATE SEQUENCE uke_id_seq;");
$DB->Execute("
CREATE TABLE uke (
    id integer default nextval('uke_id_seq'::text) not null,
    report_type varchar(32) DEFAULT NULL,
    divisionid integer DEFAULT '0',
    reportyear smallint DEFAULT '0',
    divname varchar(128) DEFAULT NULL,
    ten varchar(16) DEFAULT NULL,
    regon varchar(255) DEFAULT NULL,
    rpt varchar(64) DEFAULT NULL,
    rjst varchar(64) DEFAULT NULL,
    krs varchar(64) DEFAULT NULL,
    states varchar(100) DEFAULT NULL,
    districts varchar(100) DEFAULT NULL,
    boroughs varchar(100) DEFAULT NULL,
    city varchar(100) DEFAULT NULL,
    zip varchar(6) DEFAULT NULL,
    street varchar(100) DEFAULT NULL,
    location_city varchar(10) DEFAULT NULL,
    location_street varchar(10) DEFAULT NULL,
    location_house varchar(10) DEFAULT NULL,
    location_flat varchar(10) DEFAULT NULL,
    kod_terc integer DEFAULT '0',
    kod_simc integer DEFAULT '0',
    kod_ulic integer DEFAULT '0',
    url varchar(255) DEFAULT NULL,
    email varchar(255) DEFAULT NULL,
    accept1 smallint DEFAULT '0',
    accept2 smallint DEFAULT '0',
    accept3 smallint DEFAULT '0',
    accept4 smallint DEFAULT '0',
    accept5 smallint DEFAULT '0',
    accept6 smallint DEFAULT '0',
    contact_name varchar(128) DEFAULT NULL,
    contact_lastname varchar(100) DEFAULT NULL,
    contact_phone varchar(64) DEFAULT NULL,
    contact_fax varchar(64) DEFAULT NULL,
    contact_email varchar(255) DEFAULT '',
    closed smallint DEFAULT '0',
    passwd varchar(255) DEFAULT NULL,
    description text default null,
PRIMARY KEY (id));
");

$DB->Execute("CREATE SEQUENCE uke_data_id_seq;");
$DB->Execute("
CREATE TABLE uke_data (
    id integer default nextval('uke_data_id_seq'::text) NOT NULL,
    rapid integer DEFAULT '0',
    mark varchar(4) DEFAULT NULL,
    markid varchar(255) DEFAULT NULL,
    useraport smallint DEFAULT '1',
    data text default null,
    PRIMARY KEY (id));
");

$DB->Execute("CREATE SEQUENCE collocation_id_seq;");
$DB->Execute("
CREATE TABLE collocation (
    id integer default nextval('collocation_id_seq'::text) NOT NULL,
    name varchar(100) DEFAULT NULL,
    states varchar(100) DEFAULT NULL,
    districts varchar(100) DEFAULT NULL,
    boroughs varchar(100) DEFAULT NULL,
    city varchar(100) DEFAULT NULL,
    street varchar(100) DEFAULT NULL,
    zip varchar(6) DEFAULT NULL,
    location_city integer DEFAULT NULL,
    location_street integer DEFAULT NULL,
    location_house varchar(8) DEFAULT NULL,
    location_flat varchar(8) DEFAULT NULL,
    cadastral_parcel varchar(100) DEFAULT NULL,
    longitude numeric(10,6) DEFAULT NULL,
    latitude numeric(10,6) DEFAULT NULL,
    sum_collocation smallint DEFAULT NULL,
    room_area integer DEFAULT '0',
    technical_floor smallint DEFAULT '0',
    technical_ceiling smallint DEFAULT '0',
    air_conditioning smallint DEFAULT '0',
    telecommunication smallint DEFAULT '1',
    cdate integer DEFAULT '0',
    mdate integer DEFAULT '0',
    cuser integer DEFAULt '0',
    muser integer DEFAULT '0',
    description text default null,
    buildingtype smallint DEFAULT NULL,
    instmast smallint DEFAULT '0',
    room_area_empty integer DEFAULT '0',
    foreign_entity smallint DEFAULT '0',
    entity_fiber_end smallint DEFAULT '0',
    instofanten smallint DEFAULT '0',
    dc12 smallint DEFAULT '0',
    dc24 smallint DEFAULT '0',
    dc48 smallint DEFAULT '0',
    ac230 smallint DEFAULT '1',
    sharing_fiber smallint DEFAULT '0',
    height_anten smallint DEFAULT '0',
    PRIMARY KEY (id),
    UNIQUE (name));
");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014033101', 'dbvex'));
$DB->CommitTrans();
?>