<?php

/*
 * LMS iNET
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

$DB->Execute("CREATE SEQUENCE networknode_id_seq;");

$DB->Execute("
CREATE TABLE networknode (
    id integer DEFAULT nextval('networknode_id_seq'::text) NOT NULL,
    name varchar(100) DEFAULT NULL,
    type smallint DEFAULT 1 NOT NULL,
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
    backbone_layer smallint DEFAULT NULL,
    distribution_layer smallint DEFAULT NULL,
    access_layer smallint DEFAULT NULL,
    sharing smallint DEFAULT 0 NOT NULL,
    buildingtype smallint DEFAULT NULL,
    collocationid integer DEFAULT 0 NOT NULL,
    description text DEFAULT NULL,
    cdate integer DEFAULT 0 NOT NULL,
    mdate integer DEFAULT 0 NOT NULL,
    cuser integer DEFAULT 0 NOT NULL,
    muser integer DEFAULT 0 NOT NULL,
    deleted smallint DEFAULT 0  NOT NULL,
    disabled smallint DEFAULT 0 NOT NULL,
    room_area integer DEFAULT 0 NOT NULL,
    room_area_empty integer DEFAULT 0 NOT NULL,
    technical_floor smallint DEFAULT 0 NOT NULL,
    technical_ceiling smallint DEFAULT 0 NOT NULL,
    air_conditioning smallint DEFAULT 0 NOT NULL,
    telecommunication smallint DEFAULT 1 NOT NULL,
    instmast smallint DEFAULT 0 NOT NULL,
    instofanten smallint DEFAULT 0 NOT NULL,
    foreign_entity smallint DEFAULT 0 NOT NULL,
    entity_fiber_end smallint DEFAULT 0 NOT NULL,
    sharing_fiber smallint DEFAULT 0 NOT NULL,
    dc12 smallint DEFAULT 0 NOT NULL,
    dc24 smallint DEFAULT 0 NOT NULL,
    dc48 smallint DEFAULT 0 NOT NULL,
    ac230 smallint DEFAULT 1 NOT NULL,
    height_anten smallint DEFAULT 0 NOT NULL,
    service_broadband smallint DEFAULT 1 NOT NULL,
    service_voice smallint DEFAULT 0 NOT NULL,
    service_other text DEFAULT NULL,
    total_bandwidth integer DEFAULT 0 NOT NULL,
    bandwidth_broadband integer DEFAULT 0 NOT NULL,
    PRIMARY KEY (id))
");

$DB->Execute("CREATE SEQUENCE upkeep_id_seq;");
$DB->Execute("
CREATE TABLE upkeep (
    id integer DEFAULT nextval('upkeep_id_seq'::text) NOT NULL,
    owner varchar(32) DEFAULT '' NOT NULL,
    ownerid integer DEFAULT 0 NOT NULL,
    value numeric(9,2) DEFAULT '0.00' NOT NULL,
    sumvalue numeric(9,2) DEFAULT '0.00' NOT NULL,
    periods integer DEFAULT 0 NOT NULL,
    name varchar(255) DEFAULT '' NOT NULL,
    description text DEFAULT NULL,
    fromdate integer DEFAULT 0 NOT NULL,
    todate integer DEFAULT 0 NOT NULL,
    cid integer DEFAULT 0 NOT NULL,
    mid integer DEFAULT 0 NOT NULL,
    cdate integer DEFAULT 0 NOT NULL,
    mdate integer DEFAULT 0 NOT NULL,
    PRIMARY KEY (id))
");
$DB->Execute("CREATE INDEX upkeep_owner_idx ON upkeep (owner,ownerid);");


$DB->Execute("CREATE SEQUENCE uploadfiles_id_seq;");
$DB->Execute("
CREATE TABLE uploadfiles (
    id integer DEFAULT nextval('uploadfiles_id_seq'::text) NOT NULL,
    section varchar(20) DEFAULT NULL,
    ownerid integer DEFAULT NULL,
    description text DEFAULT NULL,
    filename varchar(255) DEFAULT NULL,
    filetype varchar(100) DEFAULT NULL,
    filesize bigint DEFAULT NULL,
    filemd5sum varchar(40) DEFAULT NULL,
    filenamesave varchar(255) DEFAULT NULL,
    cdate integer DEFAULT NULL,
    userid integer DEFAULT NULL,
    PRIMARY KEY (id))
");
$DB->Execute("CREATE INDEX uploadfiles_section_idx ON uploadfiles (section,ownerid);");

$DB->Execute("ALTER TABLE netdevices ADD networknodeid INTEGER DEFAULT 0 NOT NULL;");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014031000', 'dbvex'));
$DB->CommitTrans();
?>
