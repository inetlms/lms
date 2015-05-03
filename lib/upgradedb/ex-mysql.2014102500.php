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
    CREATE TABLE IF NOT EXISTS re_assurance (
	id int(11) NOT NULL AUTO_INCREMENT,
	idcar int(11) NOT NULL DEFAULT '0',
	idother int(11) NOT NULL DEFAULT '0',
	cuser int(11) NOT NULL DEFAULT '0',
	cdate int(11) NOT NULL DEFAULT '0',
	muser int(11) NOT NULL DEFAULT '0',
	mdate int(11) NOT NULL DEFAULT '0',
	dfrom int(11) NOT NULL DEFAULT '0' COMMENT 'data od kiedy jest ubezpieczenie',
	dto int(11) NOT NULL DEFAULT '0' COMMENT 'data do kiedy jest ubezpieczenie',
	oc tinyint(1) NOT NULL DEFAULT '1',
	ac tinyint(1) NOT NULL DEFAULT '0',
	nw tinyint(1) NOT NULL DEFAULT '0',
	assistance tinyint(1) NOT NULL DEFAULT '0',
	nrpolisy varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
	nrumowy varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
	rata1 decimal(9,2) NOT NULL DEFAULT '0.00',
	rata2 decimal(9,2) DEFAULT NULL COMMENT 'wartość składki 2',
	rata1to int(11) NOT NULL DEFAULT '0' COMMENT 'termin 1 składki',
	rata2to int(11) NOT NULL DEFAULT '0' COMMENT 'termin 2 składki',
	rata1cash tinyint(1) NOT NULL DEFAULT '0' COMMENT 'czy składka została opłacona',
	rata1cashdate int(11) NOT NULL DEFAULT '0' COMMENT 'data zapłacenia pierwszej składki',
	rata2cash tinyint(1) NOT NULL DEFAULT '0' COMMENT 'czy składka została opłacona',
	rata2cashdate int(11) NOT NULL DEFAULT '0' COMMENT 'data zapłacenia drugiej składki',
	ubezpieczyciel text COLLATE utf8_polish_ci COMMENT 'firma która obezpiecza nasze zabawki',
	asekurant text COLLATE utf8_polish_ci COMMENT 'asekurant naszego ubezpieczyciela',
	ubezpieczajacy text COLLATE utf8_polish_ci COMMENT 'ubezpieczajacy nasza zabawke',
	datazawarcia int(11) NOT NULL DEFAULT '0' COMMENT 'data zawarcia umowy',
	PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci COMMENT='ubezpieczenia';
");




$DB->Execute("
    CREATE TABLE IF NOT EXISTS re_cars (
	id int(10) unsigned NOT NULL AUTO_INCREMENT,
	dr_a varchar(10) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'numer rejestracyjny',
	dr_b int(10) DEFAULT NULL COMMENT 'data pierwszej rejestracji',
	dr_c11 varchar(128) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'nazwisko lub nazwa posiadacza dowodu rejestracyjnego',
	dr_c12 varchar(32) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'numer pesel lub regon',
	dr_c13 varchar(128) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'adres posiadacza dowodu rejestracyjnego',
	dr_c21 varchar(128) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'nazwisko lub nazwa właściciela pojazdu',
	dr_c22 varchar(32) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'numer pesel lub regon',
	dr_c23 varchar(128) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'adres właściciela pojazdu',
	dr_d1 varchar(64) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'marka pojazdu',
	dr_d2 varchar(64) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'typ pojazdu',
	dr_d3 varchar(64) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'model pojazdu',
	dr_e varchar(128) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'numer VIN, nadwozia, podwozia lub ramy',
	dr_f1 int(11) DEFAULT NULL COMMENT 'maksymalna masa całkowita',
	dr_f2 int(11) DEFAULT NULL COMMENT 'dopuszczalna masa całkowita',
	dr_f3 int(11) DEFAULT NULL COMMENT 'dopuszczalna masa całkowita zespołu pojazdów',
	dr_g int(11) DEFAULT NULL COMMENT 'masa własna pojazdu',
	dr_h int(11) DEFAULT NULL COMMENT 'data ważności dowodu',
	dr_i int(11) DEFAULT NULL COMMENT 'data wydania dowodu',
	dr_j varchar(64) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'kategoria pojazdu',
	dr_k varchar(128) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'numer świadectwa homologacji',
	dr_l tinyint(4) DEFAULT NULL COMMENT 'liczba osi',
	dr_o1 int(11) DEFAULT NULL COMMENT 'maksymalna masa całkowita przyczepy z hamulcem',
	dr_o2 int(11) DEFAULT NULL COMMENT 'maksymalna masa całkowita przyczepy bez hamulca',
	dr_p1 decimal(9,2) DEFAULT NULL COMMENT 'pojemność silnika',
	dr_p2 decimal(9,2) DEFAULT NULL COMMENT 'maksymalna moc silnika w kW',
	dr_p3 smallint(6) DEFAULT NULL COMMENT 'rodzaj paliwa',
	dr_q varchar(10) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'stosunek mocy do masy własnej',
	dr_s1 tinyint(4) DEFAULT NULL COMMENT 'liczba miejsc siedzących',
	dr_s2 tinyint(4) DEFAULT NULL COMMENT 'liczba miejs stojących',
	dr_wydajacy text COLLATE utf8_polish_ci COMMENT 'organ wydający dowód rejestracyjny',
	dr_seriadr varchar(32) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'seria i numer dowodu rejestracyjnego',
	dr_cartype int(11) DEFAULT NULL COMMENT 'rodzaj pojazdu ze słownika',
	dr_przeznaczenie varchar(32) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'przeznaczenie pojazdu',
	dr_rokprodukcji varchar(4) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'rok produkcji pojazdu',
	dr_ladownosc smallint(6) DEFAULT NULL COMMENT 'dopuszczalna ladownosc w kg',
	dr_nacisk decimal(9,2) DEFAULT NULL COMMENT 'największy dopuszczalny nacisk osi kN',
	dr_kartapojazdu varchar(32) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'numer karty pojazdu',
	dr_notes text COLLATE utf8_polish_ci COMMENT 'adnotacje urzedowe - dodatkowe informacje',
	cdate int(11) DEFAULT NULL,
	cuser int(11) DEFAULT NULL,
	mdate int(11) DEFAULT NULL,
	muser int(11) DEFAULT NULL,
	description text COLLATE utf8_polish_ci,
	forma_nabycia tinyint(4) DEFAULT NULL,
	datazakupu int(11) DEFAULT NULL,
	stanlicznika int(11) DEFAULT NULL COMMENT 'stan licznika w momencie zakupu',
	zbiornik smallint(6) DEFAULT NULL COMMENT 'pojemnosc zbiornika',
	shortname varchar(32) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'nazwa skrócona',
	status tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
");



$DB->Execute("
    CREATE TABLE IF NOT EXISTS re_dictionary_cartype (
	id int(10) unsigned NOT NULL AUTO_INCREMENT,
	name varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
	description text COLLATE utf8_polish_ci,
	active tinyint(1) NOT NULL DEFAULT '1',
	deleted tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
");



$DB->Execute("
    CREATE TABLE IF NOT EXISTS re_dictionary_event (
	id int(10) unsigned NOT NULL AUTO_INCREMENT,
	name varchar(128) COLLATE utf8_polish_ci NOT NULL,
	description text COLLATE utf8_polish_ci,
	licznik tinyint(1) NOT NULL DEFAULT '0' COMMENT 'czy wymagać podanie stanu licznika',
	koszt tinyint(1) NOT NULL DEFAULT '0' COMMENT 'czy wymagać podanie kosztów',
	paliwo tinyint(1) NOT NULL DEFAULT '0' COMMENT 'czy wymagać podania ilości paliwa',
	active tinyint(1) NOT NULL DEFAULT '1',
	deleted tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
");


$DB->Execute("
    CREATE TABLE IF NOT EXISTS re_event (
	id int(11) NOT NULL AUTO_INCREMENT,
	idcar int(11) NOT NULL DEFAULT '0',
	idother int(11) NOT NULL DEFAULT '0',
	cdate int(11) NOT NULL DEFAULT '0',
	cuser int(11) NOT NULL DEFAULT '0',
	mdate int(11) NOT NULL DEFAULT '0',
	muser int(11) NOT NULL DEFAULT '0',
	stanlicznika int(11) NOT NULL DEFAULT '0',
	datazdarzenia int(11) NOT NULL DEFAULT '0',
	litrow int(11) NOT NULL DEFAULT '0' COMMENT 'ilosc litrow przy tankowaniu',
	koszt decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT 'koszt związany z wydarzeniem',
	name varchar(128) COLLATE utf8_polish_ci NOT NULL,
	description text COLLATE utf8_polish_ci,
	eventid int(11) NOT NULL DEFAULT '0' COMMENT 'id zdarzenia ze słownika',
	PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
");


$DB->Execute("
    CREATE TABLE IF NOT EXISTS re_users (
	id int(10) unsigned NOT NULL AUTO_INCREMENT,
	iduser int(10) unsigned NOT NULL DEFAULT '0',
	idcar int(10) unsigned NOT NULL DEFAULT '0',
	idother int(10) unsigned NOT NULL DEFAULT '0',
	dfrom int(11) NOT NULL DEFAULT '0',
	dto int(11) NOT NULL DEFAULT '0',
	deleted tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
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
