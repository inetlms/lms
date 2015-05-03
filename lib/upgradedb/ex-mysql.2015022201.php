<?php

// iNET LMS

$DB->BeginTrans();

$DB->Execute("DROP VIEW vnodes");
$DB->Execute("DROP VIEW vmacs");


if (!$tmp = $DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE table_name = ? AND table_schema = ? LIMIT 1",array('invprojects',$DB->_dbname))) 
{
    $DB->Execute("
	CREATE TABLE invprojects (
	    id int(11) NOT NULL auto_increment,
	    name varchar(255) NOT NULL, 
	    type tinyint DEFAULT 0, 
	    PRIMARY KEY (id)
	) ENGINE=INNODB;
    ");
    
    $DB->Execute("INSERT INTO invprojects (name,type) VALUES ('inherited',1)");
}

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','invprojectid'))) 
    $DB->Execute("ALTER TABLE netdevices ADD COLUMN invprojectid int(11) DEFAULT NULL");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'netdevices','status'))) 
    $DB->Execute("ALTER TABLE netdevices ADD COLUMN status tinyint(1) DEFAULT '0'");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'nodes','invprojectid'))) 
    $DB->Execute("ALTER TABLE nodes ADD COLUMN invprojectid int(11) DEFAULT NULL");

$DB->Execute("CREATE VIEW vnodes AS
		SELECT n.*, m.mac
		FROM nodes n
		LEFT JOIN vnodes_mac m ON (n.id = m.nodeid)");

$DB->Execute("CREATE VIEW vmacs AS
		SELECT n.*, m.mac, m.id AS macid
		FROM nodes n
		JOIN macs m ON (n.id = m.nodeid)");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015022201', 'dbvex'));

$DB->CommitTrans();

?>
