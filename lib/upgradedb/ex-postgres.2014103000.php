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

$DB->Execute("CREATE SEQUENCE teleline_id_seq;");
$DB->Execute("
    CREATE TABLE IF NOT EXISTS teleline (
	id INTEGER DEFAULT nextval('teleline_id_seq'::text) NOT NULL,
	name varchar(64) DEFAULT NULL,
	description text DEFAULT NULL,
	active SMALLINT NOT NULL DEFAULT '1',
	PRIMARY KEY (id));
");

$DB->Execute("ALTER TABLE netlinks ADD layer SMALLINT DEFAULT NULL;");
$DB->Execute("ALTER TABLE netlinks ADD teleline INTEGER NOT NULL DEFAULT 0;");
$DB->Execute("ALTER TABLE netlinks ADD distance INTEGER NOT NULL DEFAULT 0;");
$DB->Execute("ALTER TABLE netlinks ADD distanceoptical INTEGER NOT NULL DEFAULT 0;");
$DB->Execute("ALTER TABLE netlinks ADD tracttype SMALLINT DEFAULT NULL;");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014103000', 'dbvex'));

$DB->CommitTrans();

?>
