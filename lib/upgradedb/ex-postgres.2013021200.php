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

$DB->Execute("ALTER TABLE netdevices ALTER COLUMN monit_port TYPE VARCHAR( 5 );");
$DB->Execute("ALTER TABLE netdevices ALTER monit_port SET DEFAULT NULL ;");
$DB->Execute("UPDATE netdevices SET monit_port = NULL WHERE monit_port = '8728' OR monit_port = '0';");
$DB->Execute("ALTER TABLE messageitems ADD firstread integer NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE messageitems ADD lastread integer NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE messageitems ADD isread smallint NOT NULL DEFAULT '0';");
$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2013021200', 'dbvex'));

$DB->CommitTrans();
?>