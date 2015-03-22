<?php

// iNET LMS

$DB->BeginTrans();

$DB->Execute("ALTER TABLE uke ADD vercsv VARCHAR(10) DEFAULT NULL;");

$DB->Execute("DROP TABLE netdevicesassignments;");

$DB->Execute("
CREATE TABLE netdevicesassignments (
	id integer DEFAULT nextval('netdevicesassignments_id_seq'::text) NOT NULL,
	netdevicesid integer NOT NULL REFERENCES netdevices (id) ON DELETE CASCADE ON UPDATE CASCADE,
	netdevicesgroupid integer NOT NULL REFERENCES netdevicesgroups (id) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (id),
	CONSTRAINT netdevicesassignments_netdevicesgroupid_key UNIQUE (netdevicesid, netdevicesgroupid)
);
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015031900', 'dbvex'));

$DB->CommitTrans();

?>
