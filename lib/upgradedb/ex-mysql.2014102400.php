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
	CREATE TABLE IF NOT EXISTS templates (
		id int(11)		NOT NULL auto_increment,
		type tinyint		NOT NULL,
		name varchar(50)	NOT NULL,
		message	text		DEFAULT '' NOT NULL,
		PRIMARY KEY (id),
		UNIQUE KEY name (type, name)
	) ENGINE=InnoDB;
");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'templates','subject'))) 
    $DB->Execute("ALTER TABLE templates ADD subject varchar(255) NOT NULL DEFAULT ''");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014102400', 'dbversion'));

$DB->CommitTrans();

?>
