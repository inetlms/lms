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
    CREATE TABLE dictionary_cnote (
	id INT(10) unsigned not null auto_increment,
	name varchar(128) default null,
	description text default null,
	primary key(id)
) ENGINE=InnoDB;
");

$DB->Execute("
INSERT INTO dictionary_cnote (name,description) VALUES
('stwierdzono pomyłkę w cenie',''),
('stwierdzono pomyłkę w kwocie podatku',''),
('stwierdzono pomyłkę w stawce podatku',''),
('stwierdzono pomyłkę w innej pozycji faktury',''),
('stwierdzono pomyłkę, umowa została rozwiązana, usługa nie została wykonana',''),
('stwierdzono pomyłkę, umowa została rozwiązana, usługa została wykonana częściowo',''),
('stwierdzono pomyłkę, umowa została zawieszona, usługa nie została wykonana',''),
('stwierdzono pomyłkę, dwukrotnie zafakturowano tą samą sprzedaż',''),
('udzielono rabatu',''),
('udzielono opustów i obniżek cen',''),
('dokonano zwrotu towarów i opakowań',''),
('dokonano zwrotu całości lub części zapłaty nabywcy',''),
('podwyższono cenę','');
");

$DB->addconfig('invoices','create_pdf_file_proforma','0');

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015010700', 'dbvex'));
$DB->CommitTrans();

?>