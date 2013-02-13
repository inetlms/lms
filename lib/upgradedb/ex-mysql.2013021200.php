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

$DB->Execute("ALTER TABLE netdevices CHANGE monit_port monit_port VARCHAR( 5 ) NULL DEFAULT NULL ;");
$DB->Execute("UPDATE netdevices SET monit_port = NULL WHERE monit_port = '8728' OR monit_port = '0';");
$DB->Execute("ALTER TABLE messageitems ADD firstread INT(11) NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE messageitems ADD lastread INT(11) NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE messageitems ADD isread TINYINT( 1 ) NOT NULL DEFAULT '0';");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2013021200', 'dbvex'));

$DB->CommitTrans();
?>