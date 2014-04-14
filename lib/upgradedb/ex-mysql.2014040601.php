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

$DB->Execute("
CREATE TABLE IF NOT EXISTS notatnik (
	id int(11) NOT NULL AUTO_INCREMENT,
	iduser int(11) NOT NULL DEFAULT 0,
	data int(11) NOT NULL DEFAULT 0,
	datazmiany int(11) NOT NULL DEFAULT 0,
	prio tinyint(1) NOT NULL DEFAULT '1',
	opis varchar(100) COLLATE utf8_polish_ci NOT NULL,
	tresc text COLLATE utf8_polish_ci NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB;
");
$DB->Execute("ALTER TABLE notatnik ADD INDEX (iduser);");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014040601', 'dbvex'));
$DB->CommitTrans();

?>