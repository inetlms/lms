<?php

// iNET LMS

$DB->BeginTrans();

$DB->Execute("DROP VIEW vnodes");
$DB->Execute("DROP VIEW vmacs");



if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'nodes','layer'))) 
    $DB->Execute("ALTER TABLE nodes ADD COLUMN layer int(11) DEFAULT NULL COMMENT 'warstwa sieci';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'nodes','tracttype'))) 
    $DB->Execute("ALTER TABLE nodes ADD COLUMN tracttype int(11) DEFAULT NULL;");

$DB->Execute("CREATE VIEW vnodes AS
		SELECT n.*, m.mac
		FROM nodes n
		LEFT JOIN vnodes_mac m ON (n.id = m.nodeid)");

$DB->Execute("CREATE VIEW vmacs AS
		SELECT n.*, m.mac, m.id AS macid
		FROM nodes n
		JOIN macs m ON (n.id = m.nodeid)");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015030201', 'dbvex'));

$DB->CommitTrans();

?>
