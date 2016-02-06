<?php

$DB->BeginTrans();


if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
    array($DB->_dbname,'netdevices','ibgp'))) 
    $DB->execute("ALTER TABLE netdevices ADD COLUMN ibgp bigint NOT NULL DEFAULT 0;");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
    array($DB->_dbname,'netdevices','ebgp'))) 
    $DB->execute("ALTER TABLE netdevices ADD COLUMN ebgp bigint NOT NULL DEFAULT 0;");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2016020600', 'dbvex'));

$DB->CommitTrans();

?>
