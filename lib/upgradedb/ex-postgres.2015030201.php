<?php

// iNET LMS

$DB->BeginTrans();

$DB->Execute("DROP VIEW vnodes;");
$DB->Execute("DROP VIEW vmacs;");



if (!$DB->GetOne('SELECT 1 FROM information_schema.columns WHERE table_name = ?  AND column_name=? ;',array('nodes','layer'))
    $DB->Execute("ALTER TABLE nodes ADD COLUMN layer integer DEFAULT NULL");

if (!$DB->GetOne('SELECT 1 FROM information_schema.columns WHERE table_name = ?  AND column_name=? ;',array('nodes','tracttype'))
    $DB->Execute("ALTER TABLE nodes ADD COLUMN tracttype integer DEFAULT NULL");


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

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015030201', 'dbvex'));

$DB->CommitTrans();

?>
