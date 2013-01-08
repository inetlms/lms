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

$DB->Execute("DROP VIEW IF EXISTS monit_vnodes;");

$DB->Execute("
    CREATE TABLE monitnodes (
	id integer		DEFAULT '0' NOT NULL,
	test_type varchar(15)	DEFAULT 'icmp' NOT NULL,
	test_port integer	DEFAULT NULL,
	active smallint 	DEFAULT '1' NOT NULL,
	send_timeout smallint	DEFAULT NULL,
	send_ptime smallint 	default null,
	maxptime integer	DEFAULT NULL,
	PRIMARY KEY (id)
    );
    CREATE INDEX monitnodes_active_idx ON monitnodes (active);
");

$DB->Execute("
    CREATE SEQUENCE monittime_id_seq;
    CREATE TABLE monittime (
	id bigint		DEFAULT nextval('monittime_id_seq'::text) NOT NULL,
	nodeid integer		DEFAULT NULL,
	ownid integer		DEFAULT NULL,
	cdate integer 		DEFAULT '0' NOT NULL,
	ptime decimal(10,3) 	DEFAULT '0.000' NOT NULL,
	warn_ptime		smallint default '0' not null,
	warn_timeout		smallint default '0' not null,
	PRIMARY KEY (id)
    );
    CREATE INDEX monittime_nodeid_idx ON monittime (nodeid);
    CREATE INDEX monittime_ownid_idx ON monittime (ownid);
");

$DB->Execute("
    CREATE SEQUENCE monitown_id_seq;
    CREATE TABLE monitown (
	id integer 		DEFAULT nextval('monitown_id_seq'::text) NOT NULL,
	ipaddr varchar(100) 	DEFAULT NULL,
	name varchar(50) 	DEFAULT NULL,
	description varchar(255) DEFAULT NULL,
	test_type varchar(15)	DEFAULT 'icmp' NOT NULL,
	test_port integer	DEFAULT NULL,
	active smallint 	DEFAULT '1' NOT NULL,
	send_timeout smallint	DEFAULT NULL,
	send_ptime smallint	DEFAULT NULL,
	maxptime integer	DEFAULT NULL,
	PRIMARY KEY (id)
    );
    CREATE INDEX monitown_active_idx ON monitown (active);
");



$DB->Execute("
    CREATE TABLE monituser (
	id integer 		DEFAULT '0' NOT NULL,
	sendemail smallint 	DEFAULT '0' NOT NULL,
	sendphone smallint 	DEFAULT '0' NOT NULL,
	sendgg smallint		DEFAULT '0' NOT NULL,
	active smallint 	DEFAULT '1' NOT NULL,
	description text 	DEFAULT NULL,
	PRIMARY KEY (id)
);
");

$DB->Execute("
	CREATE SEQUENCE monitwarn_id_seq;
	CREATE TABLE monitwarn (
	    id bigint 		DEFAULT nextval('monitwarn_id_seq'::text) NOT NULL,
	    nodeid integer 	DEFAULT NULL,
	    ownid integer	DEFAULT NULL,
	    monitid bigint 	DEFAULT NULL,
	    cdate integer 	DEFAULT '0' NOT NULL,
	    backtime integer 	DEFAULT '0' NOT NULL,
	    sendwarn smallint 	DEFAULT '0' NOT NULL,
	    sendback smallint 	DEFAULT '0' NOT NULL,
	    PRIMARY KEY (id)
	);
	CREATE INDEX monitwarn_monitid_idx ON monitwarn (monitid);
");


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