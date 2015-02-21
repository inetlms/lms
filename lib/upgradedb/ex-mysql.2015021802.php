<?php

$DB->BeginTrans();


$DB->Execute("DROP VIEW IF EXISTS vnodes ;");
$DB->Execute("DROP VIEW IF EXISTS vnodes_mac;");
$DB->Execute("DROP VIEW IF EXISTS vmacs;");



if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'nodes','netdevicemodelid'))) 
{
    $DB->Execute("ALTER TABLE nodes ADD COLUMN netdevicemodelid integer DEFAULT NULL");
    $DB->Execute("ALTER TABLE nodes ADD FOREIGN KEY (netdevicemodelid) REFERENCES netdevicemodels (id) ON UPDATE CASCADE ON DELETE SET NULL");
}


$DB->Execute("
CREATE VIEW vnodes_mac AS
SELECT nodeid, GROUP_CONCAT(mac ORDER BY id SEPARATOR ',') AS mac
	FROM macs GROUP BY nodeid
");
$DB->Execute("
CREATE VIEW vnodes AS
SELECT n.*, m.mac
	FROM nodes n
	LEFT JOIN vnodes_mac m ON (n.id = m.nodeid)
");


$DB->Execute("
CREATE VIEW vmacs AS 
	SELECT n.*, m.mac, m.id AS macid 
	FROM nodes n 
	JOIN macs m ON (n.id = m.nodeid);
");


$DB->CommitTrans();

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015021802', 'dbvex'));

?>