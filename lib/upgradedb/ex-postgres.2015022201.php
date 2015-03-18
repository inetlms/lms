<?php

// iNET LMS

$DB->BeginTrans();

$DB->Execute("DROP VIEW vnodes;");
$DB->Execute("DROP VIEW vmacs;");

if (!$DB->GetOne('SELECT 1 FROM pg_tables WHERE tablename = ?;',array('invprojects'))
{
    $DB->Execute("
	CREATE SEQUENCE invprojects_id_seq;
	CREATE TABLE invprojects (
		id integer DEFAULT nextval('invprojects_id_seq'::text) NOT NULL,
		name varchar(255) NOT NULL,
		type smallint DEFAULT 0,
		PRIMARY KEY(id)
	);
    ");
    
    $DB->Execute("INSERT INTO invprojects (name,type) VALUES ('inherited',1)");
}

if (!$DB->GetOne('SELECT 1 FROM information_schema.columns WHERE table_name = ?  AND column_name=? ;',array('netdevices','invprojectid')))
    $DB->Execute("ALTER TABLE netdevices ADD COLUMN invprojectid integer DEFAULT NULL");

if (!$DB->GetOne('SELECT 1 FROM information_schema.columns WHERE table_name = ?  AND column_name=? ;',array('netdevices','status')))
    $DB->Execute("ALTER TABLE netdevices ADD COLUMN status smallint DEFAULT 0");

if (!$DB->GetOne('SELECT 1 FROM information_schema.columns WHERE table_name = ?  AND column_name=? ;',array('nodes','invprojectid')))
    $DB->Execute("ALTER TABLE nodes ADD COLUMN invprojectid integer DEFAULT NULL");


$DB->Execute("CREATE VIEW vnodes AS
		SELECT n.*, m.mac
		FROM nodes n
		LEFT JOIN (SELECT nodeid, array_to_string(array_agg(mac), ',') AS mac
			FROM macs GROUP BY nodeid) m ON (n.id = m.nodeid);
");

$DB->Execute("CREATE VIEW vmacs AS
	SELECT n.*, m.mac, m.id AS macid
		FROM nodes n
		JOIN macs m ON (n.id = m.nodeid);");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015022201', 'dbvex'));

$DB->CommitTrans();

?>
