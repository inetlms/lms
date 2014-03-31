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

$DB->Execute("
CREATE TABLE collocation (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
    states varchar(100) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'wojewodztwo',
    districts varchar(100) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'powiat',
    boroughs varchar(100) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'gmina',
    city varchar(100) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'miasto',
    street varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
    zip varchar(6) COLLATE utf8_polish_ci DEFAULT NULL,
    location_city int(11) DEFAULT NULL,
    location_street int(11) DEFAULT NULL,
    location_house varchar(8) COLLATE utf8_polish_ci DEFAULT NULL,
    location_flat varchar(8) COLLATE utf8_polish_ci DEFAULT NULL,
    cadastral_parcel varchar(100) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'numer działki ewidencyjnej',
    longitude decimal(10,6) DEFAULT NULL,
    latitude decimal(10,6) DEFAULT NULL,
    sum_collocation tinyint(4) DEFAULT NULL COMMENT 'liczba kolokacji w budynku',
    room_area int(11) NOT NULL DEFAULT '0' COMMENT 'powierzchnia pomieszczeń udostępnianych do kolokacji w m2',
    technical_floor tinyint(1) NOT NULL DEFAULT '0' COMMENT 'wyposażenie pomieszczeń kolokacji w podłogę techniczną 0-nie 1-tak',
    technical_ceiling tinyint(1) NOT NULL DEFAULT '0' COMMENT 'wyposażenie pomieszczeń kolokacji w sufit techniczny',
    air_conditioning tinyint(1) NOT NULL DEFAULT '0' COMMENT 'wyposarzenie pomieszczeń koilokacji w klime',
    telecommunication tinyint(1) NOT NULL DEFAULT '1' COMMENT 'dostepność przyłącza telekomunikacyjnego 0-nie 1-tak',
    cdate int(11) NOT NULL,
    mdate int(11) NOT NULL,
    cuser int(11) NOT NULL,
    muser int(11) NOT NULL,
    description text COLLATE utf8_polish_ci,
    buildingtype tinyint(1) DEFAULT NULL COMMENT 'typ budynku',
    instmast tinyint(1) NOT NULL DEFAULT '0' COMMENT 'możliwość instalacji masztu',
    room_area_empty int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'powierzchnia wona',
    foreign_entity smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT 'liczba podmiotów obcych w kolokacji',
    entity_fiber_end smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'liczba podmiotów posiadających zakończenia światłowodowe',
    instofanten tinyint(1) NOT NULL DEFAULT '0' COMMENT 'możliwość instalacji anten',
    dc12 tinyint(1) NOT NULL DEFAULT '0' COMMENT 'wyposażenie w gwarantowane zasilanie DC12V',
    dc24 tinyint(1) NOT NULL DEFAULT '0' COMMENT 'wyposażenie w gwarantowane zasilanie DC24V',
    dc48 tinyint(1) NOT NULL DEFAULT '0' COMMENT 'wyposażenie w gwarantowane zasilanie DC48V',
    ac230 tinyint(1) NOT NULL DEFAULT '1' COMMENT 'wyposażenie w gwarantowane zasilanie AC230V',
    sharing_fiber tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Możliwość udostępnienia przyłącza światłowodowego',
    height_anten smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'wysokość zawieszenia anten nad poziomem gruntu',
    PRIMARY KEY (id),
    UNIQUE KEY name (name)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014033100', 'dbvex'));
$DB->CommitTrans();

?>