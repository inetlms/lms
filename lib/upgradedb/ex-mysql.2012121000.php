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

$DB->Execute("INSERT INTO nastypes (name) VALUES ('mikrotik_api')");
$DB->Execute("INSERT INTO nastypes (name) VALUES ('ubiquiti_snmp')");

$DB->Execute("
CREATE TABLE IF NOT EXISTS monitnodes (
    id int NOT NULL default 0,
    test_type varchar(15) default 'icmp' not null,
    test_port int default null,
    active tinyint(1) default 1,
    send_timeout tinyint(1) default null,
    send_ptime tinyint(1) default null,
    maxptime int(11) default NULL,
    PRIMARY KEY (id),
    INDEX active (active)
) ENGINE=InnoDB;
");

$DB->Execute("
    create table monittime (
	id bigint not null auto_increment,
	nodeid int default null,
	ownid int default null,
	cdate int NOT NULL DEFAULT 0,
	ptime decimal(10,3) NOT NULL DEFAULT '0.000' ,
	warn_ptime tinyint(1) default '0' not null,
	warn_timeout tinyint(1) default '0' not null,
	PRIMARY KEY (id)
    ) ENGINE=MyISAM;
");

$DB->Execute("
CREATE TABLE IF NOT EXISTS monitown (
    id int NOT NULL AUTO_INCREMENT,
    ipaddr varchar(100) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
    name varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
    description varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
    test_type varchar(15) default 'icmp' not null,
    test_port int default null,
    active tinyint(1) default 1,
    send_timeout tinyint(1) default null,
    send_ptime tinyint(1) default null,
    maxptime int(11) default null,
    PRIMARY KEY (id),
    INDEX active (active)
) ENGINE=InnoDB;
");

$DB->Execute("
CREATE TABLE IF NOT EXISTS monituser (
    id int(11) DEFAULT '0' NOT NULL,
    sendemail smallint NOT NULL DEFAULT '0',
    sendphone smallint NOT NULL DEFAULT '0',
    sendgg smallint NOT NULL DEFAULT '0',
    active tinyint(1) NOT NULL DEFAULT '1',
    description text COLLATE utf8_polish_ci,
    PRIMARY KEY (id)
) ENGINE=InnoDB;
");

$DB->Execute("
CREATE TABLE IF NOT EXISTS monitwarn (
    id bigint NOT NULL AUTO_INCREMENT,
    nodeid int(11) NOT NULL DEFAULT '0',
    ownid int(11) NOT NULL DEFAULT '0',
    monitid bigint NOT NULL DEFAULT '0',
    cdate int(11) NOT NULL DEFAULT '0',
    backtime int(11) NOT NULL DEFAULT '0',
    sendwarn tinyint(1) NOT NULL DEFAULT '0',
    sendback tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    INDEX monitid (monitid)
) ENGINE=MyISAM;
");

$DB->Execute("ALTER TABLE monittime ADD INDEX ( nodeid ) ;");
$DB->Execute("ALTER TABLE monittime ADD INDEX ( ownid ) ;");

$DB->Execute("DROP VIEW IF EXISTS monit_vnodes;");

$DB->Execute("
 CREATE VIEW monit_vnodes AS SELECT 
 m.id AS id, m.test_type, m.test_port, m.send_timeout, m.send_ptime, inet_ntoa(n.ipaddr) AS ipaddr, m.maxptime, 
 COALESCE((SELECT 1 FROM nodes WHERE nodes.id = m.id AND nodes.netdev != 0),0) AS netdev, n.name 
 FROM monitnodes m 
 JOIN nodes n ON (n.id = m.id) 
 WHERE m.active = 1 AND n.ipaddr !=0 ;
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2012121000', 'dbvex'));

$DB->CommitTrans();

?>