<?php

// iNET LMS

$DB->BeginTrans();

$DB->Execute("
CREATE SEQUENCE networknodegroups_id_seq;
");
$DB->Execute("
    CREATE TABLE networknodegroups (
	id INTEGER default nextval('networknodegroups_id_seq'::text) NOT NULL,
	name varchar(128) default null,
	description text default null,
	primary key(id),
	UNIQUE (name));
");

$DB->Execute("
CREATE SEQUENCE networknodeassignments_id_seq;
");
$DB->Execute("
CREATE TABLE networknodeassignments (
	id integer DEFAULT nextval('networknodeassignments_id_seq'::text) NOT NULL,
	networknodeid integer NOT NULL REFERENCES networknode (id) ON DELETE CASCADE ON UPDATE CASCADE,
	networknodegroupid integer NOT NULL REFERENCES networknodegroups (id) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (id),
	CONSTRAINT networknodeassignments_networknodegroupid_key UNIQUE (networknodeid, networknodegroupid)
);
");


$DB->Execute("
CREATE SEQUENCE netdevicesgroups_id_seq;
");
$DB->Execute("
    CREATE TABLE netdevicesgroups (
	id INTEGER default nextval('netdevicesgroups_id_seq'::text) NOT NULL,
	name varchar(128) default null,
	description text default null,
	primary key(id),
	UNIQUE (name));
");


$DB->Execute("
CREATE SEQUENCE netdevicesassignments_id_seq;
");
$DB->Execute("
CREATE TABLE netdevicesassignments (
	id integer DEFAULT nextval('netdevicesassignments_id_seq'::text) NOT NULL,
	netdeviceid integer NOT NULL REFERENCES netdevices (id) ON DELETE CASCADE ON UPDATE CASCADE,
	netdevicegroupid integer NOT NULL REFERENCES netdevicesgroups (id) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (id),
	CONSTRAINT netdevicesassignments_netdevicesgroupid_key UNIQUE (netdeviceid, netdevicegroupid)
);
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015030100', 'dbvex'));

$DB->CommitTrans();

?>
