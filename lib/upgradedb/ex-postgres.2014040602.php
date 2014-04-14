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

$DB->addconfig('netdevices','force_connection','1');
$DB->addconfig('netdevices','force_network_to_host','0');
$DB->addconfig('netdevices','force_network_gateway','1');
$DB->addconfig('netdevices','force_network_dns','1');

$DB->Execute("CREATE SEQUENCE tablica_id_seq;");
$DB->Execute("
CREATE TABLE tablica (
    id INTEGER DEFAULT nextval('tablica_id_seq'::text) NOT NULL ,
    ownerid INTEGER default 0,
    cdate INTEGER DEFAULT 0,
    mdate INTEGER DEFAULT 0,
    edate INTEGER DEFAULT 0',
    prio SMALLINT DEFAULT 1',
    description VARCHAR (255) DEFAULT NULL,
    message TEXT DEFAULT '',
    archive SMALLINT DEFAULT 0,
    active SMALLINT DEFAULT 1,
    readmessage SMALLINT DEFAULT 0,
    deleted SMALLINT NOT NULL DEFAULT 0,
    highlight SMALLINT default 0,
    PRIMARY KEY(id));
");
$DB->Execute("CREATE INDEX tablica_ownerid_idx ON tablica (ownerid);");
$DB->Execute("CREATE INDEX tablica_deleted_idx ON tablica (deleted, active);");


$DB->Execute("CREATE SEQUENCE tablicaassign_id_seq;");
$DB->Execute("
CREATE TABLE tablicaassign (
    id INTEGER DEFAULT nextval('tablicaassign_id_seq'::text) NOT NULL,
    idtablica INTEGER DEFAULT 0,
    iduser INTEGER DEFAULT 0,
    useredit SMALLINT DEFAULT 0,
    userdel SMALLINT DEFAULT 0,
    deleted SMALLINT DEFAULT 0,
    readmessage SMALLINT DEFAULT 0,
    highlight SMALLINT DEFAULT 0,
    PRIMARY KEY(id));
");
$DB->Execute("CREATE INDEX tablicaassign_idtablica_idx ON tablicaassign (idtablica);");
$DB->Execute("CREATE INDEX tablicaassign_deleted_idx ON tablicaassign (deleted,iduser);");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014040602', 'dbvex'));
$DB->CommitTrans();

?>