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

$DB->Execute("CREATE SEQUENCE re_assurance_id_seq;");
$DB->Execute("
    CREATE TABLE re_assurance (
	id INTEGER DEFAULT nextval('re_assurance_id_seq'::text) NOT NULL,
	idcar INTEGER NOT NULL DEFAULT '0',
	idother INTEGER NOT NULL DEFAULT '0',
	cuser INTEGER NOT NULL DEFAULT '0',
	cdate INTEGER NOT NULL DEFAULT '0',
	muser INTEGER NOT NULL DEFAULT '0',
	mdate INTEGER NOT NULL DEFAULT '0',
	dfrom INTEGER NOT NULL DEFAULT '0',
	dto INTEGER NOT NULL DEFAULT '0',
	oc SMALLINT NOT NULL DEFAULT '1',
	ac SMALLINT NOT NULL DEFAULT '0',
	nw SMALLINT NOT NULL DEFAULT '0',
	assistance SMALLINT NOT NULL DEFAULT '0',
	nrpolisy varchar(100) DEFAULT NULL,
	nrumowy varchar(100) DEFAULT NULL,
	rata1 numeric(9,2) NOT NULL DEFAULT '0.00',
	rata2 numeric(9,2) DEFAULT NULL,
	rata1to INTEGER NOT NULL DEFAULT '0',
	rata2to INTEGER NOT NULL DEFAULT '0',
	rata1cash SMALLINT NOT NULL DEFAULT '0',
	rata1cashdate INTEGER NOT NULL DEFAULT '0',
	rata2cash SMALLINT NOT NULL DEFAULT '0',
	rata2cashdate INTEGER NOT NULL DEFAULT '0',
	ubezpieczyciel text DEFAULT '',
	asekurant text DEFAULT '',
	ubezpieczajacy text DEFAULT '',
	datazawarcia INTEGER NOT NULL DEFAULT '0',
	PRIMARY KEY (id));
");



$DB->Execute("CREATE SEQUENCE re_cars_id_seq;");
$DB->Execute("
    CREATE TABLE re_cars (
	id INTEGER DEFAULT nextval('re_cars_id_seq'::text) NOT NULL,
	dr_a varchar(10) DEFAULT NULL,
	dr_b INTEGER DEFAULT NULL,
	dr_c11 varchar(128) DEFAULT NULL,
	dr_c12 varchar(32) DEFAULT NULL,
	dr_c13 varchar(128) DEFAULT NULL,
	dr_c21 varchar(128) DEFAULT NULL,
	dr_c22 varchar(32) DEFAULT NULL,
	dr_c23 varchar(128) DEFAULT NULL,
	dr_d1 varchar(64) DEFAULT NULL,
	dr_d2 varchar(64) DEFAULT NULL,
	dr_d3 varchar(64) DEFAULT NULL,
	dr_e varchar(128) DEFAULT NULL,
	dr_f1 INTEGER DEFAULT NULL,
	dr_f2 INTEGER DEFAULT NULL,
	dr_f3 INTEGER DEFAULT NULL,
	dr_g INTEGER DEFAULT NULL,
	dr_h INTEGER DEFAULT NULL,
	dr_i INTEGER DEFAULT NULL,
	dr_j varchar(64) DEFAULT NULL,
	dr_k varchar(128) DEFAULT NULL,
	dr_l smallint DEFAULT NULL,
	dr_o1 INTEGER DEFAULT NULL,
	dr_o2 INTEGER DEFAULT NULL,
	dr_p1 numeric(9,2) DEFAULT NULL,
	dr_p2 numeric(9,2) DEFAULT NULL,
	dr_p3 smallint DEFAULT NULL,
	dr_q varchar(10) DEFAULT NULL,
	dr_s1 smallint DEFAULT NULL,
	dr_s2 SMALLINT DEFAULT NULL,
	dr_wydajacy text DEFAULT '',
	dr_seriadr varchar(32) DEFAULT NULL,
	dr_cartype INTEGER DEFAULT NULL,
	dr_przeznaczenie varchar(32) DEFAULT NULL,
	dr_rokprodukcji varchar(4) DEFAULT NULL,
	dr_ladownosc smallint DEFAULT NULL,
	dr_nacisk numeric(9,2) DEFAULT NULL,
	dr_kartapojazdu varchar(32) DEFAULT NULL,
	dr_notes text DEFAULT NULL,
	cdate INTEGER DEFAULT NULL,
	cuser INTEGER DEFAULT NULL,
	mdate INTEGER DEFAULT NULL,
	muser INTEGER DEFAULT NULL,
	description text DEFAULT NULL,
	forma_nabycia smallint DEFAULT NULL,
	datazakupu INTEGER DEFAULT NULL,
	stanlicznika INTEGER DEFAULT NULL,
	zbiornik smallint DEFAULT NULL,
	shortname varchar(32) DEFAULT NULL,
	status SMALLINT NOT NULL DEFAULT '0',
	PRIMARY KEY (id));
");


$DB->Execute("CREATE SEQUENCE re_dictionary_cartype_id_seq;");
$DB->Execute("
    CREATE TABLE re_dictionary_cartype (
	id INTEGER DEFAULT nextval('re_dictionary_cartype_id_seq'::text) NOT NULL,
	name varchar(64) DEFAULT NULL,
	description text DEFAULT NULL,
	active SMALLINT NOT NULL DEFAULT '1',
	deleted SMALLINT NOT NULL DEFAULT '0',
	PRIMARY KEY (id));
");


$DB->Execute("CREATE SEQUENCE re_dictionary_event_id_seq;");
$DB->Execute("
    CREATE TABLE re_dictionary_event (
	id INTEGER DEFAULT nextval('re_dictionary_event_id_seq'::text) NOT NULL,
	name varchar(128) DEFAULT '' NOT NULL,
	description text DEFAULT NULL,
	licznik SMALLINT NOT NULL DEFAULT '0',
	koszt SMALLINT NOT NULL DEFAULT '0',
	paliwo SMALLINT NOT NULL DEFAULT '0',
	active SMALLINT NOT NULL DEFAULT '1',
	deleted SMALLINT NOT NULL DEFAULT '0',
	PRIMARY KEY (id));
");


$DB->Execute("CREATE SEQUENCE re_event_id_seq;");
$DB->Execute("
    CREATE TABLE re_event (
	id INTEGER DEFAULT nextval('re_event_id_seq'::text) NOT NULL,
	idcar INTEGER NOT NULL DEFAULT '0',
	idother INTEGER NOT NULL DEFAULT '0',
	cdate INTEGER NOT NULL DEFAULT '0',
	cuser INTEGER NOT NULL DEFAULT '0',
	mdate INTEGER NOT NULL DEFAULT '0',
	muser INTEGER NOT NULL DEFAULT '0',
	stanlicznika INTEGER NOT NULL DEFAULT '0',
	datazdarzenia INTEGER NOT NULL DEFAULT '0',
	litrow INTEGER NOT NULL DEFAULT '0',
	koszt numeric(9,2) NOT NULL DEFAULT '0.00',
	name varchar(128) DEFAULT '' NOT NULL,
	description text DEFAULT NULL,
	eventid INTEGER NOT NULL DEFAULT '0',
	PRIMARY KEY (id));
");


$DB->Execute("CREATE SEQUENCE re_users_id_seq;");
$DB->Execute("
    CREATE TABLE re_users (
	id INTEGER DEFAULT nextval('re_users_id_seq'::text) NOT NULL,
	iduser INTEGER NOT NULL DEFAULT '0',
	idcar INTEGER NOT NULL DEFAULT '0',
	idother INTEGER NOT NULL DEFAULT '0',
	dfrom INTEGER NOT NULL DEFAULT '0',
	dto INTEGER NOT NULL DEFAULT '0',
	deleted SMALLINT NOT NULL DEFAULT '0',
	PRIMARY KEY (id));
");

$DB->Execute("
INSERT INTO re_dictionary_cartype (name, description, active, deleted) VALUES
('Samochód osobowy poniżej 900cm3', NULL, 1, 0),
('Samochód specjalny - podnośnik', NULL, 1, 0),
('Ciągnik siodłowy', 'Opis', 1, 0),
('Samochód osobowy powyżej 900cm3', NULL, 1, 0);
");

$DB->Execute("
INSERT INTO re_dictionary_event (name, description, licznik, koszt, paliwo, active, deleted) VALUES
('tankowanie paliwa', NULL, 1, 1, 1, 1, 0),
('mycie', NULL, 0, 1, 0, 1, 0),
('mycie ręcznie', NULL, 0, 0, 0, 1, 0);
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014102500', 'dbvex'));
$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014032900', 'dbversion'));


$DB->CommitTrans();

?>
