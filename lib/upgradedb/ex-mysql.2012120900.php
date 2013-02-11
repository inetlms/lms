<?php

/*
 * LMS version 1.11-git (EXPANDED)
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

$DB->Execute("CREATE TABLE syslog (
    id bigint NOT NULL AUTO_INCREMENT,
    cdate integer default 0 not null,
    uid integer default null,
    cid integer default null,
    nid integer default null,
    module smallint default null,
    event smallint default null,
    msg varchar(255) default null,
    diff text default null,
    PRIMARY KEY (id),
    INDEX cdate (cdate),
    INDEX uid (uid),
    INDEX module (module),
    INDEX event (event)
    ) ENGINE=MyISAM;
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2012120900', 'dbvex'));

$DB->CommitTrans();

?>
