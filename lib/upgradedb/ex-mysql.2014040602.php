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

$DB->addconfig('netdevices','force_connection',1);
$DB->addconfig('netdevices','force_network_to_host','0');
$DB->addconfig('netdevices','force_network_gateway','1');
$DB->addconfig('netdevices','force_network_dns','1');

$DB->Execute("
CREATE TABLE tablica (
    id INT (11) NOT NULL AUTO_INCREMENT ,
    ownerid INT (11) not null default 0 COMMENT 'id wlasciciela',
    cdate INT (11) not NULL DEFAULT 0 COMMENT 'data utworzenia',
    mdate INT (11) NOT NULL DEFAULT 0 COMMENT 'data modyfikacji',
    edate INT (11) NOT NULL DEFAULT 0 COMMENT 'data wygasniecia',
    prio TINYINT (1) NOT NULL DEFAULT 1 COMMENT 'priorytet',
    description VARCHAR (255) DEFAULT NULL COMMENT 'opis, temat',
    message TEXT NOT NULL DEFAULT '' COMMENT 'wiadomosc',
    archive TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'czy wiadomosc jest juz archiwalna',
    active TINYINT (1) NOT NULL DEFAULT 1 COMMENT 'czy wiadomosc jest aktywna',
    readmessage TINYINT (1) NOT NULL DEFAULT 0 COMMENT 'czy wiadomosc odczytana',
    deleted TINYINT (1) NOT NULL DEFAULT 0,
    highlight tinyint (1) not null default 0 COMMENT 'wyroznij wiadomosc',
    PRIMARY KEY(id)
) ENGINE = InnoDB;
");

$DB->Execute("ALTER TABLE tablica ADD INDEX ( ownerid );");
$DB->Execute("ALTER TABLE tablica ADD INDEX ( deleted, active );");

$DB->Execute("
CREATE TABLE tablicaassign (
    id INT (11) NOT NULL AUTO_INCREMENT,
    idtablica INT (11) NOT NULL DEFAULT 0,
    iduser INT (11) NOT NULL DEFAULT 0,
    useredit TINYINT (1) NOT NULL DEFAULT 0,
    userdel TINYINT (1) NOT NULL DEFAULT 0,
    deleted TINYINT (1) NOT NULL DEFAULT 0,
    readmessage TINYINT (1) NOT NULL DEFAULT 0,
    highlight TINYINT (1) NOT NULL DEFAULT 0,
    PRIMARY KEY(id)
) ENGINE = InnoDB;
");

$DB->Execute("ALTER TABLE tablicaassign ADD INDEX (idtablica);");
$DB->Execute("ALTER TABLE tablicaassign ADD INDEX (deleted,iduser); ");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014040602', 'dbvex'));
$DB->CommitTrans();

?>