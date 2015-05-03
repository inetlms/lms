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

$DB->Execute("CREATE SEQUENCE notatnik_id_seq;");
$DB->Execute("
CREATE TABLE notatnik (
	id INTEGER DEFAULT nextval('notatnik_id_seq'::text) NOT NULL,
	iduser integer default 0,
	data integer default 0,
	datazmiany integer default 0,
	prio smallint NOT NULL DEFAULT '1',
	opis varchar(100) default '' NOT NULL,
	tresc text default '' NOT NULL,
	PRIMARY KEY (id)
);
");

$DB->Execute("CREATE INDEX notatnik_iduser_idx ON notatnik (iduser);");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014040601', 'dbvex'));
$DB->CommitTrans();

?>