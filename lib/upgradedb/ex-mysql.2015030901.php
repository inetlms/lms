<?php

// iNET LMS

$DB->BeginTrans();

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'netdevices','login'))) 
$DB->Execute("ALTER TABLE netdevices ADD login VARCHAR( 128 ) NULL DEFAULT NULL COMMENT 'login do urządzenia';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'netdevices','passwd'))) 
$DB->Execute("ALTER TABLE netdevices ADD passwd VARCHAR( 128 ) NULL DEFAULT NULL ;");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015030901', 'dbvex'));

$DB->CommitTrans();

?>
